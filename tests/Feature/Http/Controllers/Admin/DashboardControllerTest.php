<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Enums\ContactClickChannel;
use App\Models\ContactClick;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirectToRoute('login');
    }

    public function test_dashboard_shows_service_and_pricing_counts(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('บริการ')
            ->assertSee('แพ็กเกจราคา')
            ->assertSee('เผยแพร่ 0 รายการ');
    }

    public function test_dashboard_counts_contact_clicks_for_the_bangkok_day(): void
    {
        $this->travelTo('2026-10-25 18:00:00');

        ContactClick::factory()->create([
            'channel' => ContactClickChannel::Phone,
            'created_at' => '2026-10-25 16:00:00',
        ]);
        ContactClick::factory()->create([
            'channel' => ContactClickChannel::Line,
            'created_at' => '2026-10-25 17:30:00',
        ]);

        $this->actingAs(User::factory()->create())
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('โทร 0')
            ->assertSee('แอด LINE 1');
    }
}
