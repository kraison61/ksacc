<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticatedSessionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_renders_for_a_guest(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('เข้าสู่ระบบแอดมิน');
    }

    public function test_authenticated_user_is_redirected_away_from_login(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('login'))
            ->assertRedirectToRoute('admin.dashboard');
    }

    public function test_valid_credentials_start_a_session_and_open_the_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
        ]);

        $this->post(route('login.store'), [
            'email' => 'Admin@Example.com',
            'password' => 'password',
        ])->assertRedirectToRoute('admin.dashboard');

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_returns_to_the_page_the_guest_tried_to_open(): void
    {
        User::factory()->create([
            'email' => 'admin@example.com',
        ]);

        $this->get(route('admin.services.index'))
            ->assertRedirectToRoute('login');

        $this->post(route('login.store'), [
            'email' => 'admin@example.com',
            'password' => 'password',
        ])->assertRedirectToRoute('admin.services.index');
    }

    public function test_wrong_password_does_not_start_a_session(): void
    {
        User::factory()->create([
            'email' => 'admin@example.com',
        ]);

        $this->post(route('login.store'), [
            'email' => 'admin@example.com',
            'password' => 'wrong-password',
        ])->assertInvalid([
            'email' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง',
        ]);

        $this->assertGuest();
    }

    public function test_empty_login_asks_for_email_and_password(): void
    {
        $this->post(route('login.store'), [])
            ->assertInvalid([
                'email' => 'กรุณาระบุอีเมล',
                'password' => 'กรุณาระบุรหัสผ่าน',
            ]);

        $this->assertGuest();
    }

    public function test_invalid_email_format_is_rejected(): void
    {
        $this->post(route('login.store'), [
            'email' => 'not-an-email',
            'password' => 'password',
        ])->assertInvalid([
            'email' => 'รูปแบบอีเมลไม่ถูกต้อง',
        ]);
    }

    public function test_too_many_login_attempts_are_rejected(): void
    {
        User::factory()->create([
            'email' => 'locked@example.com',
        ]);

        foreach (range(1, 5) as $attempt) {
            $this->post(route('login.store'), [
                'email' => 'locked@example.com',
                'password' => 'wrong-password',
            ])->assertInvalid([
                'email' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง',
            ]);
        }

        $this->post(route('login.store'), [
            'email' => 'locked@example.com',
            'password' => 'wrong-password',
        ])->assertInvalid([
            'email' => 'พยายามเข้าสู่ระบบบ่อยเกินไป กรุณารอสักครู่แล้วลองใหม่',
        ]);

        $this->assertGuest();
    }

    public function test_logout_ends_the_session(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.logout'))
            ->assertRedirectToRoute('login');

        $this->assertGuest();
    }
}
