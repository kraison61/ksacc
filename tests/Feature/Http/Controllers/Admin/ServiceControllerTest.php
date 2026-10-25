<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_open_the_service_list(): void
    {
        $this->get(route('admin.services.index'))
            ->assertRedirectToRoute('login');
    }

    public function test_guest_cannot_create_a_service(): void
    {
        $this->post(route('admin.services.store'), $this->servicePayload())
            ->assertRedirectToRoute('login');

        $this->assertDatabaseCount('services', 0);
    }

    public function test_guest_cannot_open_the_service_editor(): void
    {
        $service = Service::factory()->unpublished()->create();

        $this->get(route('admin.services.edit', $service))
            ->assertRedirectToRoute('login');
    }

    public function test_guest_cannot_update_a_service(): void
    {
        $service = Service::factory()->create([
            'title' => 'ชื่อเดิม',
        ]);

        $this->put(route('admin.services.update', $service), $this->servicePayload([
            'title' => 'ชื่อใหม่',
        ]))->assertRedirectToRoute('login');

        $this->assertSame('ชื่อเดิม', $service->refresh()->title);
    }

    public function test_guest_cannot_delete_a_service(): void
    {
        $service = Service::factory()->create();

        $this->delete(route('admin.services.destroy', $service))
            ->assertRedirectToRoute('login');

        $this->assertModelExists($service);
    }

    public function test_index_lists_published_and_draft_services(): void
    {
        Service::factory()->create([
            'title' => 'บริการที่เผยแพร่',
            'sort_order' => 2,
        ]);
        Service::factory()->unpublished()->create([
            'title' => 'บริการฉบับร่าง',
            'sort_order' => 1,
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('admin.services.index'));

        $response->assertOk();
        $response->assertSeeInOrder(['บริการฉบับร่าง', 'บริการที่เผยแพร่']);
        $response->assertSee('ฉบับร่าง');
        $response->assertSee('เผยแพร่');
    }

    public function test_index_escapes_service_titles(): void
    {
        $title = "<script>alert('xss')</script>";

        Service::factory()->create([
            'title' => $title,
            'slug' => 'xss-service',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('admin.services.index'));

        $response->assertOk();
        $response->assertSee($title);
        $response->assertDontSee($title, false);
    }

    public function test_create_form_renders(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.services.create'))
            ->assertOk()
            ->assertSee('เพิ่มบริการ')
            ->assertSee('สมุดบัญชี');
    }

    public function test_valid_payload_creates_a_published_service(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.services.store'), $this->servicePayload())
            ->assertRedirectToRoute('admin.services.index')
            ->assertSessionHas('status', 'บันทึกบริการแล้ว');

        $service = Service::query()->where('slug', 'monthly-bookkeeping')->first();

        $this->assertNotNull($service);
        $this->assertSame('ทำบัญชีรายเดือน', $service->title);
        $this->assertTrue($service->is_published);
        $this->assertSame(2, $service->sort_order);

        $this->get(route('services.show', $service))->assertOk();
    }

    public function test_draft_service_stays_off_the_public_page_and_can_be_edited(): void
    {
        $admin = User::factory()->create();
        $payload = $this->servicePayload();
        unset($payload['is_published']);

        $this->actingAs($admin)
            ->post(route('admin.services.store'), $payload)
            ->assertRedirectToRoute('admin.services.index');

        $service = Service::query()->where('slug', 'monthly-bookkeeping')->firstOrFail();

        $this->assertFalse($service->is_published);
        $this->get(route('services.show', $service))->assertNotFound();
        $this->actingAs($admin)
            ->get(route('admin.services.edit', $service))
            ->assertOk()
            ->assertSee('ทำบัญชีรายเดือน');
    }

    public function test_empty_payload_does_not_create_a_service(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.services.store'), [])
            ->assertInvalid([
                'title' => 'กรุณาระบุชื่อบริการ',
                'slug' => 'กรุณาระบุ slug',
                'description' => 'กรุณาระบุคำอธิบาย',
                'icon' => 'กรุณาเลือกไอคอน',
                'sort_order' => 'กรุณาระบุลำดับการแสดงผล',
            ]);

        $this->assertDatabaseCount('services', 0);
    }

    public function test_duplicate_slug_is_rejected(): void
    {
        Service::factory()->create([
            'slug' => 'monthly-bookkeeping',
        ]);

        $this->actingAs(User::factory()->create())
            ->post(route('admin.services.store'), $this->servicePayload())
            ->assertInvalid([
                'slug' => 'slug นี้ถูกใช้แล้ว',
            ]);

        $this->assertDatabaseCount('services', 1);
    }

    public function test_non_latin_slug_is_rejected(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.services.store'), $this->servicePayload([
                'slug' => 'ทำบัญชี',
            ]))
            ->assertInvalid([
                'slug' => 'slug ใช้ได้เฉพาะตัวอักษรภาษาอังกฤษตัวพิมพ์เล็ก ตัวเลข และขีดกลาง',
            ]);

        $this->assertDatabaseCount('services', 0);
    }

    public function test_unknown_icon_is_rejected(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.services.store'), $this->servicePayload([
                'icon' => 'not-an-icon',
            ]))
            ->assertInvalid([
                'icon' => 'ไอคอนที่เลือกไม่ถูกต้อง',
            ]);

        $this->assertDatabaseCount('services', 0);
    }

    public function test_negative_sort_order_is_rejected(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.services.store'), $this->servicePayload([
                'sort_order' => -1,
            ]))
            ->assertInvalid([
                'sort_order' => 'ลำดับการแสดงผลต้องไม่ต่ำกว่า 0',
            ]);

        $this->assertDatabaseCount('services', 0);
    }

    public function test_update_changes_the_service_and_keeps_the_same_record(): void
    {
        $service = Service::factory()->create([
            'slug' => 'monthly-bookkeeping',
            'title' => 'ชื่อเดิม',
        ]);

        $this->actingAs(User::factory()->create())
            ->put(route('admin.services.update', $service), $this->servicePayload([
                'title' => 'ชื่อใหม่',
                'slug' => 'Updated-Slug',
                'created_at' => '2000-01-01 00:00:00',
            ]))
            ->assertRedirectToRoute('admin.services.edit', ['service' => 'updated-slug'])
            ->assertSessionHas('status', 'บันทึกการแก้ไขแล้ว');

        $service->refresh();

        $this->assertSame('ชื่อใหม่', $service->title);
        $this->assertSame('updated-slug', $service->slug);
        $this->assertNotSame(2000, $service->created_at->year);
        $this->assertDatabaseCount('services', 1);
    }

    public function test_edit_form_escapes_the_description(): void
    {
        $description = "<script>alert('xss')</script>";

        $service = Service::factory()->create([
            'description' => $description,
            'slug' => 'xss-service',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('admin.services.edit', $service));

        $response->assertOk();
        $response->assertSee($description);
        $response->assertDontSee($description, false);
    }

    public function test_destroy_deletes_the_service(): void
    {
        $service = Service::factory()->create();

        $this->actingAs(User::factory()->create())
            ->delete(route('admin.services.destroy', $service))
            ->assertRedirectToRoute('admin.services.index')
            ->assertSessionHas('status', 'ลบบริการแล้ว');

        $this->assertModelMissing($service);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function servicePayload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'ทำบัญชีรายเดือน',
            'slug' => 'monthly-bookkeeping',
            'description' => 'บันทึกบัญชีทุกเดือน',
            'icon' => 'book',
            'sort_order' => 2,
            'is_published' => '1',
        ], $overrides);
    }
}
