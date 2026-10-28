<?php

namespace Tests\Feature;

use App\Models\AdminActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_admin_write_action_is_logged(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.payments.plans.store'), [
                'name'          => 'خطة اختبار',
                'description'   => 'وصف',
                'price'         => 100,
                'duration'      => 'month',
                'duration_days' => 30,
            ])
            ->assertRedirect(route('admin.payments.plans'));

        $this->assertDatabaseHas('admin_activity_logs', [
            'user_id' => $admin->id,
            'method'  => 'POST',
            'route'   => 'admin.payments.plans.store',
        ]);
    }

    public function test_the_activity_page_is_available_to_admins_only(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'student']);

        AdminActivityLog::create([
            'user_id' => $admin->id,
            'method' => 'POST',
            'path' => '/payments/plans',
            'route' => 'admin.payments.plans.store',
            'status_code' => 302,
            'ip' => '127.0.0.1',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.activity.index'))
            ->assertOk()
            ->assertSee('admin.payments.plans.store');

        $this->actingAs($student)
            ->get(route('admin.activity.index'))
            ->assertForbidden();
    }
}
