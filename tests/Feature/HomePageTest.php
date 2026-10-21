<?php

namespace Tests\Feature;

use App\Models\PricingPlan;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_returns_successful_response(): void
    {
        Service::factory()->create([
            'title' => 'ทำบัญชีรายเดือน',
            'is_published' => true,
        ]);

        PricingPlan::factory()->create([
            'title' => 'แพ็กเกจทดสอบ',
            'type' => PricingPlan::TYPE_PACKAGE,
            'is_published' => true,
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('หจก. เกษมสุขวัฒนกิจ', false);
        $response->assertSee('แอด LINE', false);
        $response->assertSee('เปิดเมนู', false);
        $response->assertSee('data-nav-toggle', false);
        $response->assertSee('data-nav-backdrop', false);
        $response->assertSee('ทำบัญชีรายเดือน', false);
        $response->assertSee('แพ็กเกจทดสอบ', false);
        $response->assertSee('application/ld+json', false);
        $response->assertSee('"@type":"AccountingService"', false);
        $response->assertSee('"@type":"ItemList"', false);
        $response->assertSee('ทำบัญชีรายเดือน', false);
    }

    public function test_home_page_hides_unpublished_services_and_plans(): void
    {
        Service::factory()->create([
            'title' => 'บริการที่เผยแพร่',
            'is_published' => true,
        ]);

        Service::factory()->unpublished()->create([
            'title' => 'บริการฉบับร่าง',
        ]);

        PricingPlan::factory()->create([
            'title' => 'แพ็กเกจที่เผยแพร่',
            'is_published' => true,
        ]);

        PricingPlan::factory()->unpublished()->create([
            'title' => 'แพ็กเกจฉบับร่าง',
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('บริการที่เผยแพร่', false);
        $response->assertDontSee('บริการฉบับร่าง', false);
        $response->assertSee('แพ็กเกจที่เผยแพร่', false);
        $response->assertDontSee('แพ็กเกจฉบับร่าง', false);
    }
}
