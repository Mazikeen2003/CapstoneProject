<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_five_failed_logins_start_a_fifteen_minute_lockout(): void
    {
        Carbon::setTestNow();

        try {
            $email = 'lockout-five@example.com';

            for ($attempt = 0; $attempt < 5; $attempt++) {
                $this->from('/login')->post('/login', [
                    'email' => $email,
                    'password' => 'incorrect-password',
                ]);
            }

            $this->get('/login')->assertSee('data-lockout-seconds="900"', false);
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_ten_failed_logins_within_a_day_start_a_one_hour_lockout(): void
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        try {
            $email = 'lockout-ten@example.com';

            for ($attempt = 0; $attempt < 5; $attempt++) {
                $this->from('/login')->post('/login', [
                    'email' => $email,
                    'password' => 'incorrect-password',
                ]);
            }

            Carbon::setTestNow($now->copy()->addSeconds(901));

            for ($attempt = 0; $attempt < 5; $attempt++) {
                $this->from('/login')->post('/login', [
                    'email' => $email,
                    'password' => 'incorrect-password',
                ]);
            }

            $this->get('/login')->assertSee('data-lockout-seconds="3600"', false);
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
