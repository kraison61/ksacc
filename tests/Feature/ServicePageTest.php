<?php

namespace Tests\Feature;

use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServicePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_service_page_returns_successful_response_with_json_ld(): void
    {
        $service = Service::factory()->create([
            'slug' => 'monthly-bookkeeping',
            'title' => 'ทำบัญชีรายเดือน',
            'description' => 'บันทึกบัญชีทุกเดือน',
            'is_published' => true,
        ]);

        $response = $this->get(route('services.show', $service));

        $response->assertOk();
        $response->assertSee('ทำบัญชีรายเดือน', false);
        $response->assertSee('บันทึกบัญชีทุกเดือน', false);
        $response->assertSee('application/ld+json', false);
        $response->assertSee('"@type":"Service"', false);
        $response->assertSee('ทำบัญชีรายเดือน', false);
    }

    public function test_unpublished_service_page_returns_not_found(): void
    {
        $service = Service::factory()->unpublished()->create([
            'slug' => 'draft-service',
            'title' => 'บริการฉบับร่าง',
        ]);

        $this->get(route('services.show', $service))->assertNotFound();
    }

    public function test_unknown_service_slug_returns_not_found(): void
    {
        $this->get('/services/does-not-exist')->assertNotFound();
    }
}
