<?php

namespace Tests\Feature\Http\Controllers\Frontend;

use App\Models\ContactClick;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ContactClickControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_phone_click_is_stored_and_pushed_to_line_oa(): void
    {
        $this->travelTo('2026-10-25 12:00:00');
        $this->configureLine();

        $this->postClick('phone', 'http://localhost/services/monthly-bookkeeping?utm=hero')
            ->assertNoContent();

        $click = ContactClick::query()->first();

        $this->assertNotNull($click);
        $this->assertSame('phone', $click->channel->value);
        $this->assertSame('http://localhost/services/monthly-bookkeeping?utm=hero', $click->page_url);
        $this->assertSame('127.0.0.1', $click->ip_address);
        $this->assertNotNull($click->line_notified_at);
        $this->assertTrue($click->line_notified_at->equalTo('2026-10-25 12:00:00'));

        Http::assertSent(function (Request $request): bool {
            $text = $request['messages'][0]['text'] ?? null;

            return $request->url() === 'https://api.line.me/v2/bot/message/push'
                && $request->hasHeader('Authorization', 'Bearer test-token')
                && $request['to'] === 'Uabc123'
                && $text === <<<'TEXT'
มีผู้เข้าชมคลิกจากเว็บไซต์
ช่องทาง: โทร
หน้า: /services/monthly-bookkeeping?utm=hero
เวลา: 25/10/2026 19:00
IP: 127.0.0.1
TEXT;
        });
    }

    public function test_line_click_from_the_same_ip_is_alerted_separately_from_phone(): void
    {
        $this->configureLine();

        $this->postClick('phone')->assertNoContent();
        $this->postClick('line')->assertNoContent();

        $this->assertSame(2, ContactClick::query()->whereNotNull('line_notified_at')->count());
        Http::assertSentCount(2);
    }

    public function test_repeated_phone_click_waits_two_minutes_before_another_line_alert(): void
    {
        $this->freezeTime();
        $this->configureLine();

        $this->postClick('phone')->assertNoContent();
        $this->postClick('phone')->assertNoContent();
        Http::assertSentCount(1);

        $this->travel(119)->seconds();
        $this->postClick('phone')->assertNoContent();
        Http::assertSentCount(1);

        $this->travel(1)->seconds();
        $this->postClick('phone')->assertNoContent();

        $this->assertSame(4, ContactClick::query()->count());
        $this->assertSame(2, ContactClick::query()->whereNotNull('line_notified_at')->count());
        Http::assertSentCount(2);
    }

    public function test_a_different_ip_still_receives_a_line_alert_during_the_cooldown(): void
    {
        $this->freezeTime();
        $this->configureLine();

        $this->postClick('phone')->assertNoContent();

        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.10'])
            ->post(route('contact-clicks.store'), [
                'channel' => 'phone',
                'page_url' => 'http://localhost/',
            ])
            ->assertNoContent();

        $this->assertDatabaseHas('contact_clicks', [
            'ip_address' => '203.0.113.10',
        ]);
        $this->assertSame(2, ContactClick::query()->whereNotNull('line_notified_at')->count());
        Http::assertSentCount(2);
    }

    public function test_click_is_stored_without_calling_line_when_oa_is_not_configured(): void
    {
        Http::preventStrayRequests();

        $this->postClick('line', 'http://localhost/#contact')->assertNoContent();

        $this->assertDatabaseHas('contact_clicks', [
            'channel' => 'line',
            'line_notified_at' => null,
        ]);
        Http::assertNothingSent();
    }

    public function test_line_push_failure_keeps_the_click_and_leaves_it_unnotified(): void
    {
        $this->configureLine(Http::response(['message' => 'error'], 500));

        $this->postClick('phone')->assertNoContent();

        $this->assertSame(1, ContactClick::query()->count());
        $this->assertSame(0, ContactClick::query()->whereNotNull('line_notified_at')->count());
    }

    public function test_line_connection_failure_keeps_the_click_and_leaves_it_unnotified(): void
    {
        $this->configureLine(Http::failedConnection());

        $this->postClick('phone')->assertNoContent();

        $this->assertSame(1, ContactClick::query()->count());
        $this->assertSame(0, ContactClick::query()->whereNotNull('line_notified_at')->count());
    }

    public function test_empty_payload_asks_for_the_channel_and_page(): void
    {
        $this->post(route('contact-clicks.store'), [])
            ->assertInvalid([
                'channel' => 'กรุณาระบุช่องทาง',
                'page_url' => 'กรุณาระบุหน้าเว็บ',
            ]);

        $this->assertDatabaseCount('contact_clicks', 0);
    }

    public function test_unknown_channel_is_rejected(): void
    {
        $this->postClick('email')
            ->assertInvalid(['channel' => 'ช่องทางไม่ถูกต้อง']);

        $this->assertDatabaseCount('contact_clicks', 0);
    }

    #[DataProvider('invalidPageUrls')]
    public function test_page_url_outside_this_site_is_rejected(string $pageUrl): void
    {
        $this->postClick('phone', $pageUrl)
            ->assertInvalid(['page_url' => 'หน้าเว็บไม่ถูกต้อง']);

        $this->assertDatabaseCount('contact_clicks', 0);
    }

    public function test_contact_clicks_are_rate_limited(): void
    {
        Http::preventStrayRequests();

        for ($attempt = 0; $attempt < 30; $attempt++) {
            $this->postClick('phone')->assertNoContent();
        }

        $this->postClick('phone')->assertTooManyRequests();
        $this->assertDatabaseCount('contact_clicks', 30);
    }

    public function test_public_pages_mark_phone_and_line_links_for_tracking(): void
    {
        $service = Service::factory()->create([
            'slug' => 'monthly-bookkeeping',
            'is_published' => true,
        ]);

        $home = $this->get(route('home'));
        $servicePage = $this->get(route('services.show', $service));

        $home->assertOk();
        $home->assertSee('data-contact-track="phone"', false);
        $home->assertSee('data-contact-track="line"', false);
        $home->assertSee('name="contact-clicks-url"', false);
        $home->assertSee('name="csrf-token"', false);

        $servicePage->assertOk();
        $servicePage->assertSee('data-contact-track="phone"', false);
        $servicePage->assertSee('data-contact-track="line"', false);
    }

    public function test_public_pages_skip_tracking_when_contact_links_are_not_configured(): void
    {
        config([
            'company.line_url' => null,
            'company.phone' => null,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('data-contact-track', false);
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function invalidPageUrls(): array
    {
        return [
            'other host' => ['https://example.com/'],
            'userinfo' => ['http://user:secret@localhost/'],
            'not a url' => ['javascript:alert(1)'],
            'too long' => ['http://localhost/'.str_repeat('a', 2048)],
        ];
    }

    private function postClick(string $channel, string $pageUrl = 'http://localhost/'): TestResponse
    {
        return $this->post(route('contact-clicks.store'), [
            'channel' => $channel,
            'page_url' => $pageUrl,
        ]);
    }

    private function configureLine(mixed $response = null): void
    {
        config([
            'services.line.channel_access_token' => 'test-token',
            'services.line.recipient_id' => 'Uabc123',
        ]);

        Http::preventStrayRequests();
        Http::fake([
            'https://api.line.me/*' => $response ?? Http::response([], 200),
        ]);
    }
}
