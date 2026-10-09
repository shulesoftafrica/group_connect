<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

/**
 * Public registration is closed (group accounts are created through onboarding). The stock Breeze screen used to
 * 500 with "View [auth.register] not found" (72 log entries) and its store() could not create a valid user.
 */
class RegistrationTest extends TestCase
{
    public function test_registration_screen_redirects_to_login_instead_of_erroring(): void
    {
        $response = $this->get('/register');

        $response->assertRedirect(route('login'));
    }

    public function test_posting_to_register_does_not_create_or_log_in_a_user(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }
}
