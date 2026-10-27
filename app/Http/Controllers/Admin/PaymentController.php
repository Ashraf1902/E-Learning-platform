<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['student', 'subscription.plan'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->paginate(15)->withQueryString();

        return view('admin.payments.index', compact('payments'));
    }

    public function approve(Payment $payment)
    {
        $payment->load('subscription.plan');
        $plan = $payment->subscription->plan;

        $startsAt = now();
        $endsAt   = now()->addDays($plan->duration_days);

        $payment->subscription->update([
            'status'    => 'active',
            'starts_at' => $startsAt,
            'ends_at'   => $endsAt,
        ]);

        $payment->update([
            'status'  => 'paid',
            'paid_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'تم تأكيد الدفع وتفعيل الاشتراك للطالب بنجاح.');
    }

    public function reject(Request $request, Payment $payment)
    {
        $request->validate(['admin_note' => 'nullable|string|max:255']);

        $payment->update([
            'status'     => 'rejected',
            'admin_note' => $request->admin_note,
        ]);

        if ($payment->subscription) {
            $payment->subscription->update(['status' => 'cancelled']);
        }

        return redirect()->back()
            ->with('success', 'تم رفض طلب الدفع.');
    }

    public function plans()
    {
        $plans = SubscriptionPlan::latest()->get();
        return view('admin.payments.plans', compact('plans'));
    }

    public function storePlan(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'duration'       => 'required|in:month,term,year',
            'duration_days'  => 'required|integer|min:1',
        ]);

        SubscriptionPlan::create($request->only('name', 'description', 'price', 'duration', 'duration_days'));

        return redirect()->route('admin.payments.plans')
            ->with('success', 'تم إنشاء خطة الاشتراك بنجاح.');
    }

    public function togglePlan(SubscriptionPlan $plan)
    {
        $plan->update(['is_active' => !$plan->is_active]);
        return redirect()->back()->with('success', 'تم تحديث حالة الخطة.');
    }

    public function destroyPlan(SubscriptionPlan $plan)
    {
        $plan->delete();
        return redirect()->back()->with('success', 'تم حذف الخطة.');
    }
}