<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use App\Models\Payment;
use Illuminate\Http\Request;

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
        $request->validate([
            'method'           => 'required|in:fawry,vodafone_cash',
            'reference_number' => 'required|string|max:100',
            'phone_number'     => 'nullable|string|max:20',
        ]);

        $student = auth()->user()->student;

        // Create pending subscription
        $subscription = Subscription::create([
            'student_id'           => $student->id,
            'subscription_plan_id' => $plan->id,
            'status'               => 'pending',
        ]);

        // Create pending payment record
        Payment::create([
            'student_id'        => $student->id,
            'subscription_id'   => $subscription->id,
            'amount'            => $plan->price,
            'method'            => $request->method,
            'reference_number'  => $request->reference_number,
            'phone_number'      => $request->phone_number,
            'status'            => 'pending',
        ]);

        return redirect()->route('portal.subscription')
            ->with('success', 'تم إرسال طلب الدفع بنجاح! سيتم تفعيل اشتراكك بعد مراجعة الإدارة خلال 24 ساعة.');
    }
}