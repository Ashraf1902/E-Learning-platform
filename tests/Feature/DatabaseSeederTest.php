<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    private array $envKeys = ['ADMIN_NAME', 'ADMIN_EMAIL', 'ADMIN_PASSWORD', 'DEMO_PASSWORD'];

    protected function setUp(): void
    {
        parent::setUp();

        $this->clearSeederEnv();
    }

    protected function tearDown(): void
    {
        $this->clearSeederEnv();

        parent::tearDown();
    }

    public function test_seeder_does_not_create_a_default_admin(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('users', 0);
    }

    public function test_seeder_creates_admin_from_environment_and_uses_demo_password(): void
    {
        $this->setSeederEnv('ADMIN_EMAIL', 'owner@example.com');
        $this->setSeederEnv('ADMIN_PASSWORD', 'StrongAdminPass123');
        $this->setSeederEnv('DEMO_PASSWORD', 'DemoStudentPass123');

        $this->seed(DatabaseSeeder::class);

        $admin = User::where('email', 'owner@example.com')->firstOrFail();

        $this->assertSame('admin', $admin->role);
        $this->assertTrue(Hash::check('StrongAdminPass123', $admin->password));

        $student = User::where('email', 'ahmed@example.com')->firstOrFail();

        $this->assertSame('student', $student->role);
        $this->assertTrue(Hash::check('DemoStudentPass123', $student->password));
    }

    private function setSeederEnv(string $key, string $value): void
    {
        putenv("{$key}={$value}");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }

    private function clearSeederEnv(): void
    {
        foreach ($this->envKeys as $key) {
            putenv($key);
            unset($_ENV[$key], $_SERVER[$key]);
        }
    }
}
