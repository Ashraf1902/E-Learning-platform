<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    // ── Student: view plans + their subscription status ──
    public function index()
    {
        $student = auth()->user()->student;
        $plans   = SubscriptionPlan::where('is_active', true)->get();
        $activeSubscription = $student->activeSubscription();
        $payments = $student->payments()->latest()->get();

        return view('portal.subscription', compact('plans', 'activeSubscription', 'payments'));
    }

    // ── Student: submit payment proof for a plan ──
    public function submitPayment(Request $request, SubscriptionPlan $plan)
    {
        abort_unless($plan->is_active, 404);

        $data = $request->validate([
            'method'           => 'required|in:fawry,vodafone_cash',
            'reference_number' => 'required|string|max:100',
            'phone_number'     => 'nullable|string|max:20',
        ]);

        $student = auth()->user()->student;

        $created = DB::transaction(function () use ($data, $plan, $student) {
            // Serialize submissions for this student so two quick requests cannot
            // both create pending subscription/payment rows.
            Student::whereKey($student->getKey())->lockForUpdate()->first();

            $hasPending = Subscription::where('student_id', $student->id)
                ->where('status', 'pending')
                ->exists();

            if ($hasPending) {
                return false;
            }

            $subscription = Subscription::create([
                'student_id'           => $student->id,
                'subscription_plan_id' => $plan->id,
                'status'               => 'pending',
            ]);

            Payment::create([
                'student_id'        => $student->id,
                'subscription_id'   => $subscription->id,
                'amount'            => $plan->price,
                'method'            => $data['method'],
                'reference_number'  => $data['reference_number'],
                'phone_number'      => $data['phone_number'] ?? null,
                'status'            => 'pending',
            ]);

            return true;
        });

        if (! $created) {
            return redirect()->route('portal.subscription')
                ->with('info', 'لديك طلب دفع قيد المراجعة بالفعل. سيتم تفعيل اشتراكك بعد مراجعة الإدارة.');
        }

        return redirect()->route('portal.subscription')
            ->with('success', 'تم إرسال طلب الدفع بنجاح! سيتم تفعيل اشتراكك بعد مراجعة الإدارة خلال 24 ساعة.');
    }
}