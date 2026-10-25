<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\PricingPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PricingPlanControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_open_the_price_list(): void
    {
        $this->get(route('admin.pricing-plans.index'))
            ->assertRedirectToRoute('login');
    }

    public function test_guest_cannot_create_a_pricing_plan(): void
    {
        $this->post(route('admin.pricing-plans.store'), $this->planPayload())
            ->assertRedirectToRoute('login');

        $this->assertDatabaseCount('pricing_plans', 0);
    }

    public function test_guest_cannot_update_a_pricing_plan(): void
    {
        $plan = PricingPlan::factory()->create([
            'title' => 'ชื่อเดิม',
        ]);

        $this->put(route('admin.pricing-plans.update', $plan), $this->planPayload([
            'title' => 'ชื่อใหม่',
            'slug' => $plan->slug,
        ]))->assertRedirectToRoute('login');

        $this->assertSame('ชื่อเดิม', $plan->refresh()->title);
    }

    public function test_guest_cannot_delete_a_pricing_plan(): void
    {
        $plan = PricingPlan::factory()->create();

        $this->delete(route('admin.pricing-plans.destroy', $plan))
            ->assertRedirectToRoute('login');

        $this->assertModelExists($plan);
    }

    public function test_index_lists_packages_and_a_la_carte_plans(): void
    {
        PricingPlan::factory()->create([
            'title' => 'แพ็กเกจรายเดือน',
            'type' => PricingPlan::TYPE_PACKAGE,
        ]);
        PricingPlan::factory()->aLaCarte()->unpublished()->create([
            'title' => 'จดทะเบียนแยกชิ้น',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('admin.pricing-plans.index'));

        $response->assertOk();
        $response->assertSee('แพ็กเกจรายเดือน');
        $response->assertSee('จดทะเบียนแยกชิ้น');
        $response->assertSee('ฉบับร่าง');
    }

    public function test_type_filter_shows_only_the_selected_section(): void
    {
        PricingPlan::factory()->create([
            'title' => 'แพ็กเกจรายเดือน',
            'type' => PricingPlan::TYPE_PACKAGE,
        ]);
        PricingPlan::factory()->aLaCarte()->create([
            'title' => 'จดทะเบียนแยกชิ้น',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('admin.pricing-plans.index', ['type' => PricingPlan::TYPE_PACKAGE]));

        $response->assertOk();
        $response->assertSee('แพ็กเกจรายเดือน');
        $response->assertDontSee('จดทะเบียนแยกชิ้น');
    }

    public function test_unknown_type_filter_does_not_hide_plans(): void
    {
        PricingPlan::factory()->create([
            'title' => 'แพ็กเกจรายเดือน',
        ]);
        PricingPlan::factory()->aLaCarte()->create([
            'title' => 'จดทะเบียนแยกชิ้น',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('admin.pricing-plans.index', ['type' => "package' OR '1'='1"]));

        $response->assertOk();
        $response->assertSee('แพ็กเกจรายเดือน');
        $response->assertSee('จดทะเบียนแยกชิ้น');
    }

    public function test_index_escapes_plan_titles(): void
    {
        $title = "<script>alert('xss')</script>";

        PricingPlan::factory()->create([
            'title' => $title,
            'slug' => 'xss-plan',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('admin.pricing-plans.index'));

        $response->assertOk();
        $response->assertSee($title);
        $response->assertDontSee($title, false);
    }

    public function test_valid_package_stores_feature_lines(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.pricing-plans.store'), $this->planPayload([
                'features' => "ทำบัญชีรายเดือน\n\nยื่นภาษีรายเดือน",
                'is_featured' => '1',
            ]))
            ->assertRedirectToRoute('admin.pricing-plans.index')
            ->assertSessionHas('status', 'บันทึกแพ็กเกจราคาแล้ว');

        $plan = PricingPlan::query()->where('slug', 'monthly-basic')->first();

        $this->assertNotNull($plan);
        $this->assertSame(PricingPlan::TYPE_PACKAGE, $plan->type);
        $this->assertSame(['ทำบัญชีรายเดือน', 'ยื่นภาษีรายเดือน'], $plan->features);
        $this->assertTrue($plan->is_featured);
        $this->assertSame('1,500', $plan->price_amount);
    }

    public function test_blank_features_are_stored_as_null(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.pricing-plans.store'), $this->planPayload([
                'type' => PricingPlan::TYPE_A_LA_CARTE,
                'features' => "  \n",
                'subtitle' => '',
                'cta_label' => '',
            ]))
            ->assertRedirectToRoute('admin.pricing-plans.index');

        $plan = PricingPlan::query()->where('slug', 'monthly-basic')->firstOrFail();

        $this->assertNull($plan->features);
        $this->assertNull($plan->subtitle);
        $this->assertNull($plan->cta_label);
    }

    public function test_empty_payload_does_not_create_a_pricing_plan(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.pricing-plans.store'), [])
            ->assertInvalid([
                'type' => 'กรุณาเลือกประเภท',
                'title' => 'กรุณาระบุชื่อ',
                'slug' => 'กรุณาระบุ slug',
                'price_amount' => 'กรุณาระบุราคา',
                'sort_order' => 'กรุณาระบุลำดับการแสดงผล',
            ]);

        $this->assertDatabaseCount('pricing_plans', 0);
    }

    public function test_duplicate_slug_is_rejected(): void
    {
        PricingPlan::factory()->create([
            'slug' => 'monthly-basic',
        ]);

        $this->actingAs(User::factory()->create())
            ->post(route('admin.pricing-plans.store'), $this->planPayload())
            ->assertInvalid([
                'slug' => 'slug นี้ถูกใช้แล้ว',
            ]);

        $this->assertDatabaseCount('pricing_plans', 1);
    }

    public function test_unknown_type_is_rejected(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.pricing-plans.store'), $this->planPayload([
                'type' => 'subscription',
            ]))
            ->assertInvalid([
                'type' => 'ประเภทที่เลือกไม่ถูกต้อง',
            ]);

        $this->assertDatabaseCount('pricing_plans', 0);
    }

    public function test_feature_line_longer_than_255_characters_is_rejected(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.pricing-plans.store'), $this->planPayload([
                'features' => str_repeat('ก', 256),
            ]))
            ->assertInvalid([
                'features.0' => 'แต่ละคุณสมบัติต้องไม่เกิน 255 ตัวอักษร',
            ]);

        $this->assertDatabaseCount('pricing_plans', 0);
    }

    public function test_update_keeps_the_same_slug_and_replaces_features(): void
    {
        $plan = PricingPlan::factory()->create([
            'slug' => 'monthly-basic',
            'title' => 'ชื่อเดิม',
            'features' => ['ของเก่า'],
        ]);

        $this->actingAs(User::factory()->create())
            ->put(route('admin.pricing-plans.update', $plan), $this->planPayload([
                'title' => 'ชื่อใหม่',
                'features' => 'ของใหม่',
            ]))
            ->assertRedirectToRoute('admin.pricing-plans.edit', $plan)
            ->assertSessionHas('status', 'บันทึกการแก้ไขแล้ว');

        $plan->refresh();

        $this->assertSame('ชื่อใหม่', $plan->title);
        $this->assertSame('monthly-basic', $plan->slug);
        $this->assertSame(['ของใหม่'], $plan->features);
    }

    public function test_destroy_deletes_the_pricing_plan(): void
    {
        $plan = PricingPlan::factory()->create();

        $this->actingAs(User::factory()->create())
            ->delete(route('admin.pricing-plans.destroy', $plan))
            ->assertRedirectToRoute('admin.pricing-plans.index')
            ->assertSessionHas('status', 'ลบแพ็กเกจราคาแล้ว');

        $this->assertModelMissing($plan);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function planPayload(array $overrides = []): array
    {
        return array_merge([
            'type' => PricingPlan::TYPE_PACKAGE,
            'title' => 'บัญชีและภาษีรายเดือน',
            'slug' => 'monthly-basic',
            'subtitle' => 'สำหรับกิจการขนาดเล็ก',
            'price_prefix' => 'เริ่มต้น',
            'price_amount' => '1,500',
            'price_suffix' => 'บาท/เดือน',
            'features' => "ทำบัญชีรายเดือน\nยื่นภาษีรายเดือน",
            'cta_label' => 'สอบถามราคา',
            'is_featured' => '0',
            'sort_order' => 1,
            'is_published' => '1',
        ], $overrides);
    }
}
