<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Enums\ContactClickChannel;
use App\Models\ContactClick;
use App\Models\LineRecipient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactClickControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_open_the_click_list(): void
    {
        $this->get(route('admin.contact-clicks.index'))
            ->assertRedirectToRoute('login');
    }

    public function test_index_lists_newest_clicks_first(): void
    {
        $this->travelTo('2026-10-25 12:00:00');

        ContactClick::factory()->create([
            'channel' => ContactClickChannel::Phone,
            'page_url' => 'http://localhost/older',
            'created_at' => now()->subMinute(),
        ]);
        ContactClick::factory()->notified()->create([
            'channel' => ContactClickChannel::Line,
            'page_url' => 'http://localhost/newer',
            'ip_address' => '203.0.113.20',
            'created_at' => now(),
            'line_notified_at' => now(),
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('admin.contact-clicks.index'));

        $response->assertOk();
        $response->assertSeeInOrder(['/newer', '/older']);
        $response->assertSee('203.0.113.20');
        $response->assertSee('25/10/2026 19:00');
        $response->assertSee('แอด LINE');
        $response->assertSee('โทร');
    }

    public function test_index_filters_by_channel(): void
    {
        ContactClick::factory()->create([
            'channel' => ContactClickChannel::Phone,
            'page_url' => 'http://localhost/from-phone',
        ]);
        ContactClick::factory()->create([
            'channel' => ContactClickChannel::Line,
            'page_url' => 'http://localhost/from-line',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('admin.contact-clicks.index', ['channel' => 'phone']));

        $response->assertOk();
        $response->assertSee('/from-phone');
        $response->assertDontSee('/from-line');
    }

    public function test_invalid_channel_filter_lists_every_click(): void
    {
        ContactClick::factory()->create([
            'channel' => ContactClickChannel::Phone,
            'page_url' => 'http://localhost/from-phone',
        ]);
        ContactClick::factory()->create([
            'channel' => ContactClickChannel::Line,
            'page_url' => 'http://localhost/from-line',
        ]);

        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->get(route('admin.contact-clicks.index', ['channel' => "phone' OR '1'='1"]))
            ->assertOk()
            ->assertSee('/from-phone')
            ->assertSee('/from-line');

        $this->actingAs($admin)
            ->get(route('admin.contact-clicks.index', ['channel' => ['phone']]))
            ->assertOk()
            ->assertSee('/from-phone')
            ->assertSee('/from-line');
    }

    public function test_index_escapes_page_urls_and_user_agents(): void
    {
        $dangerous = "<script>alert('xss')</script>";

        ContactClick::factory()->create([
            'page_url' => 'http://localhost/?q='.$dangerous,
            'user_agent' => $dangerous,
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('admin.contact-clicks.index'));

        $response->assertOk();
        $response->assertSee($dangerous);
        $response->assertDontSee($dangerous, false);
    }

    public function test_index_explains_when_line_oa_is_not_configured(): void
    {
        config([
            'services.line.channel_access_token' => null,
            'services.line.channel_secret' => null,
            'services.line.recipient_id' => null,
        ]);

        $this->actingAs(User::factory()->create())
            ->get(route('admin.contact-clicks.index'))
            ->assertOk()
            ->assertSee('ยังไม่ได้ตั้งค่า LINE OA')
            ->assertSee('ยังไม่มีการคลิกโทรหรือแอด LINE')
            ->assertSee(route('line.webhook'));
    }

    public function test_index_explains_when_line_token_exists_without_recipients(): void
    {
        config([
            'services.line.channel_access_token' => 'test-token',
            'services.line.recipient_id' => null,
        ]);

        $this->actingAs(User::factory()->create())
            ->get(route('admin.contact-clicks.index'))
            ->assertOk()
            ->assertSee('ตั้งค่าโทเค็นแล้ว แต่ยังไม่มีผู้รับแจ้งเตือน')
            ->assertSee(route('line.webhook'));
    }

    public function test_index_shows_when_line_oa_alerts_are_ready(): void
    {
        config([
            'services.line.channel_access_token' => 'test-token',
            'services.line.recipient_id' => 'Uenv123',
        ]);

        LineRecipient::factory()->create([
            'line_id' => 'Uabc123',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('admin.contact-clicks.index'));

        $response->assertOk();
        $response->assertSee('จะแจ้งเตือนผ่าน LINE OA ไปยัง 2 แชท');
        $response->assertSee('Uabc123');
        $response->assertSee('Uenv123');
        $response->assertDontSee('test-token');
    }

    public function test_filtered_empty_channel_says_nothing_was_found(): void
    {
        ContactClick::factory()->create([
            'channel' => ContactClickChannel::Phone,
        ]);

        $this->actingAs(User::factory()->create())
            ->get(route('admin.contact-clicks.index', ['channel' => 'line']))
            ->assertOk()
            ->assertSee('ไม่พบรายการในช่องทางนี้');
    }
}
