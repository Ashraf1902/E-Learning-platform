<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationCaptchaTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name'                  => 'طالب اختبار',
            'email'                 => 'captcha-student@example.com',
            'phone'                 => '01000000000',
            'address'               => 'القاهرة',
            'parent_phone'          => '01100000000',
            'level'                 => 'first',
            'password'              => 'StrongPass123',
            'password_confirmation' => 'StrongPass123',
            'website_url'           => '',
            'captcha'               => 7,
        ], $overrides);
    }

    public function test_registration_rejects_an_incorrect_captcha(): void
    {
        $this->from('/register')
            ->withSession(['registration_captcha_answer' => 7])
            ->post('/register', $this->payload(['captcha' => 8]))
            ->assertRedirect('/register')
            ->assertSessionHasErrors('captcha');

        $this->assertDatabaseMissing('users', ['email' => 'captcha-student@example.com']);
        $this->assertDatabaseMissing('students', ['email' => 'captcha-student@example.com']);
    }

    public function test_registration_accepts_the_session_captcha_answer(): void
    {
        $this->withSession(['registration_captcha_answer' => 7])
            ->post('/register', $this->payload())
            ->assertRedirect('/login');

        $this->assertDatabaseHas('users', [
            'email' => 'captcha-student@example.com',
            'role'  => 'student',
        ]);
        $this->assertDatabaseHas('students', [
            'email' => 'captcha-student@example.com',
        ]);

        $this->assertNotNull(User::where('email', 'captcha-student@example.com')->first());
        $this->assertNotNull(Student::where('email', 'captcha-student@example.com')->first());
    }

    public function test_registration_rejects_a_filled_honeypot(): void
    {
        $this->from('/register')
            ->withSession(['registration_captcha_answer' => 7])
            ->post('/register', $this->payload(['website_url' => 'https://spam.example']))
            ->assertRedirect('/register')
            ->assertSessionHasErrors('website_url');

        $this->assertDatabaseMissing('users', ['email' => 'captcha-student@example.com']);
    }
}
