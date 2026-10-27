<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_submit_a_payment_request(): void
    {
        [$user, $student] = $this->studentUser();
        $plan = $this->plan();

        $response = $this->actingAs($user)->post(route('portal.subscription.pay', $plan), [
            'method' => 'vodafone_cash',
            'reference_number' => 'REF-1001',
            'phone_number' => '01000000000',
        ]);

        $response->assertRedirect(route('portal.subscription'));
        $response->assertSessionHas('success');

        $this->assertDatabaseCount('subscriptions', 1);
        $this->assertDatabaseCount('payments', 1);
        $this->assertDatabaseHas('subscriptions', [
            'student_id' => $student->id,
            'status' => 'pending',
        ]);
        $this->assertDatabaseHas('payments', [
            'student_id' => $student->id,
            'reference_number' => 'REF-1001',
            'status' => 'pending',
        ]);
    }

    public function test_a_second_pending_payment_request_is_blocked(): void
    {
        [$user, $student] = $this->studentUser();
        $plan = $this->plan();

        $this->actingAs($user)->post(route('portal.subscription.pay', $plan), [
            'method' => 'fawry',
            'reference_number' => 'REF-1002',
        ])->assertSessionHas('success');

        $response = $this->actingAs($user)->post(route('portal.subscription.pay', $plan), [
            'method' => 'fawry',
            'reference_number' => 'REF-1003',
        ]);

        $response->assertRedirect(route('portal.subscription'));
        $response->assertSessionHas('info');
        $this->assertDatabaseCount('subscriptions', 1);
        $this->assertDatabaseCount('payments', 1);
        $this->assertSame(1, Subscription::where('student_id', $student->id)->count());
    }

    public function test_pending_request_blocks_a_payment_for_a_different_plan(): void
    {
        [$user] = $this->studentUser();
        $firstPlan = $this->plan('الخطة الأولى');
        $secondPlan = $this->plan('الخطة الثانية');

        $this->actingAs($user)->post(route('portal.subscription.pay', $firstPlan), [
            'method' => 'fawry',
            'reference_number' => 'REF-1004',
        ])->assertSessionHas('success');

        $this->actingAs($user)->post(route('portal.subscription.pay', $secondPlan), [
            'method' => 'fawry',
            'reference_number' => 'REF-1005',
        ])->assertSessionHas('info');

        $this->assertDatabaseCount('subscriptions', 1);
        $this->assertDatabaseCount('payments', 1);
    }

    private function studentUser(): array
    {
        $student = Student::create([
            'name' => 'طالب تجريبي',
            'email' => fake()->unique()->safeEmail(),
            'phone' => '01000000000',
            'level' => 'first',
            'status' => 'active',
        ]);

        $user = User::factory()->create([
            'role' => 'student',
            'student_id' => $student->id,
        ]);

        return [$user, $student];
    }

    private function plan(string $name = 'الخطة الشهرية'): SubscriptionPlan
    {
        return SubscriptionPlan::create([
            'name' => $name,
            'price' => 150,
            'duration' => 'month',
            'duration_days' => 30,
            'is_active' => true,
        ]);
    }
}
