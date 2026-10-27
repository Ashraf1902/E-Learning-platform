@extends('portal.layout')
@section('title', app()->getLocale() === 'ar' ? 'الاشتراك' : 'Subscription')

@section('content')
@php
    $isAr = app()->getLocale() === 'ar';
    $currency = $isAr ? 'جنيه' : 'EGP';
@endphp

@if($activeSubscription)
<div class="cta-card is-ok" style="margin-bottom:24px">
    <div class="cta-icon"><x-icon name="check-circle" /></div>
    <div class="cta-body">
        <div class="cta-title">{{ $isAr ? 'اشتراكك نشط' : 'Your subscription is active' }}</div>
        <div class="cta-text">
            {{ $activeSubscription->plan->name ?? '' }} ·
            {{ $isAr ? 'ينتهي في' : 'Expires on' }} {{ $activeSubscription->ends_at->format('d M Y') }}
            ({{ $activeSubscription->ends_at->diffInDays(now()) }} {{ $isAr ? 'يوم متبقي' : 'days left' }})
        </div>
    </div>
    <span class="badge badge-green">{{ $activeSubscription->plan->name ?? '' }}</span>
</div>
@else
<div class="cta-card is-gold" style="margin-bottom:24px">
    <div class="cta-icon"><x-icon name="lock" /></div>
    <div class="cta-body">
        <div class="cta-title">{{ $isAr ? 'لا يوجد اشتراك نشط' : 'No active subscription' }}</div>
        <div class="cta-text">{{ $isAr ? 'اختر خطة من الأسفل للوصول الكامل للمحاضرات والاختبارات، أو استخدم كود فتح من صفحة أكواد الفتح.' : 'Choose a plan below for full access to lectures and quizzes, or redeem an unlock code.' }}</div>
    </div>
    <div class="cta-actions">
        <a href="{{ route('portal.codes') }}" class="btn btn-ghost btn-sm"><x-icon name="ticket" /> {{ $isAr ? 'كود فتح' : 'Unlock code' }}</a>
    </div>
</div>
@endif

<div class="section-head">
    <div class="section-title"><x-icon name="card" /> {{ $isAr ? 'خطط الاشتراك المتاحة' : 'Available Plans' }}</div>
</div>

@if($plans->count())
<div class="plan-grid" style="margin-bottom:28px">
    @foreach($plans as $plan)
    @php $isCurrent = $activeSubscription && $activeSubscription->subscription_plan_id === $plan->id; @endphp
    <div class="plan-card {{ $isCurrent ? 'is-current' : '' }}">
        <div class="plan-head">
            <div>
                <div class="plan-name">{{ $plan->name }}</div>
                @if($plan->description)
                <div class="plan-desc" style="margin-top:4px">{{ $plan->description }}</div>
                @endif
            </div>
            @if($isCurrent)
            <span class="badge badge-green">{{ $isAr ? 'خطتك الحالية' : 'Current' }}</span>
            @endif
        </div>
        <div class="plan-price">
            <span class="plan-amount">{{ number_format($plan->price, 0) }}</span>
            <span class="plan-currency">{{ $currency }}</span>
            <span class="plan-period">/ {{ $plan->duration_label }}</span>
        </div>
        @if($isCurrent)
        <button type="button" class="btn btn-ghost" style="width:100%;justify-content:center" disabled>
            <x-icon name="check-circle" /> {{ $isAr ? 'اشتراكك الحالي' : 'Current plan' }}
        </button>
        @else
        <button type="button"
                class="btn btn-primary"
                style="width:100%;justify-content:center"
                data-plan-id="{{ $plan->id }}"
                data-plan-name="{{ $plan->name }}"
                data-plan-price="{{ number_format($plan->price, 0, '.', '') }}"
                data-plan-period="{{ $plan->duration_label }}"
                onclick="openPayModal(this)">
            {{ $isAr ? 'اشترك الآن' : 'Subscribe now' }}
        </button>
        @endif
    </div>
    @endforeach
</div>
@else
<div class="card" style="margin-bottom:28px">
    <div class="empty-state">
        <div class="icon"><x-icon name="card" /></div>
        <div class="empty-title">{{ $isAr ? 'لا توجد خطط اشتراك متاحة' : 'No subscription plans available' }}</div>
        <div class="empty-hint">{{ $isAr ? 'يمكنك استخدام كود فتح للوصول إلى محاضرات محددة في الوقت الحالي.' : 'You can redeem an unlock code to access specific lectures in the meantime.' }}</div>
        <a href="{{ route('portal.codes') }}" class="btn btn-ghost btn-sm"><x-icon name="ticket" /> {{ $isAr ? 'أكواد الفتح' : 'Access codes' }}</a>
    </div>
</div>
@endif

@if($payments->count())
<div class="card">
    <div class="section-head">
        <div class="section-title"><x-icon name="clipboard" /> {{ $isAr ? 'سجل طلبات الدفع' : 'Payment History' }}</div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>{{ $isAr ? 'المبلغ' : 'Amount' }}</th>
                    <th>{{ $isAr ? 'طريقة الدفع' : 'Method' }}</th>
                    <th>{{ $isAr ? 'رقم المرجع' : 'Reference' }}</th>
                    <th>{{ __('app.status') }}</th>
                    <th>{{ __('app.date') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                <tr>
                    <td class="cell-strong">{{ number_format($payment->amount, 0) }} {{ $currency }}</td>
                    <td class="cell-muted">{{ $payment->method_label }}</td>
                    <td class="cell-muted" style="font-size:0.8rem">{{ $payment->reference_number }}</td>
                    <td>
                        @if($payment->status === 'paid')
                            <span class="badge badge-green">{{ $isAr ? 'مؤكد' : 'Confirmed' }}</span>
                        @elseif($payment->status === 'pending')
                            <span class="badge badge-yellow">{{ $isAr ? 'قيد المراجعة' : 'Pending review' }}</span>
                        @else
                            <span class="badge badge-red">{{ $isAr ? 'مرفوض' : 'Rejected' }}</span>
                        @endif
                    </td>
                    <td class="cell-muted" style="font-size:0.8rem">{{ $payment->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div id="payModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-card">
        <div class="modal-head">
            <div class="modal-title" id="modalTitle"></div>
            <button type="button" class="modal-close" aria-label="{{ $isAr ? 'إغلاق' : 'Close' }}" onclick="closePayModal()">
                <x-icon name="x" />
            </button>
        </div>

        <div class="pay-box">
            <div class="pay-label">{{ $isAr ? 'المطلوب دفعه' : 'Amount to pay' }}</div>
            <div class="pay-amount" id="modalPrice"></div>
            <div class="pay-label" id="modalPeriod" style="margin-top:2px"></div>
        </div>

        <div class="pay-box">
            <div class="pay-label">{{ $isAr ? 'حوّل عبر فوري أو فودافون كاش إلى' : 'Transfer via Fawry or Vodafone Cash to' }}</div>
            <div class="pay-number" style="font-size:1.1rem;margin-top:2px">01140398484</div>
        </div>

        <ol class="pay-steps">
            <li>{{ $isAr ? 'حوّل المبلغ المطلوب إلى الرقم أعلاه.' : 'Transfer the amount to the number above.' }}</li>
            <li>{{ $isAr ? 'احفظ رقم العملية أو الإيصال.' : 'Keep the transaction number / receipt.' }}</li>
            <li>{{ $isAr ? 'أدخل رقم المرجع وطريقة الدفع أدناه.' : 'Enter the reference and payment method below.' }}</li>
        </ol>

        <form method="POST" id="payForm" action="">
            @csrf
            <div class="form-group">
                <label>{{ $isAr ? 'طريقة الدفع' : 'Payment Method' }} *</label>
                <select name="method" required>
                    <option value="vodafone_cash">{{ $isAr ? 'فودافون كاش' : 'Vodafone Cash' }}</option>
                    <option value="fawry">{{ $isAr ? 'فوري' : 'Fawry' }}</option>
                </select>
            </div>
            <div class="form-group">
                <label>{{ $isAr ? 'رقم المرجع / رقم العملية' : 'Reference / Transaction Number' }} *</label>
                <input type="text" name="reference_number" id="payReference" required placeholder="{{ $isAr ? 'مثال: 123456789' : 'e.g. 123456789' }}">
            </div>
            <div class="form-group">
                <label>{{ $isAr ? 'رقم الهاتف المستخدم في الدفع' : 'Phone number used' }}</label>
                <input type="text" name="phone_number" placeholder="01XXXXXXXXX" inputmode="tel">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:4px">
                {{ $isAr ? 'إرسال طلب الدفع' : 'Submit Payment Request' }}
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
(function () {
    var modal = document.getElementById('payModal');
    var lastTrigger = null;

    window.openPayModal = function (btn) {
        lastTrigger = btn;
        document.getElementById('modalTitle').textContent = btn.dataset.planName;
        document.getElementById('modalPrice').textContent = Number(btn.dataset.planPrice).toLocaleString('en-US') + ' {{ $currency }}';
        document.getElementById('modalPeriod').textContent = '/ ' + btn.dataset.planPeriod;
        document.getElementById('payForm').action = '{{ url('/portal/subscription') }}/' + btn.dataset.planId + '/pay';
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        document.getElementById('payReference').focus();
    };

    window.closePayModal = function () {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        if (lastTrigger) lastTrigger.focus();
    };

    modal.addEventListener('click', function (e) {
        if (e.target === modal) window.closePayModal();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.classList.contains('is-open')) window.closePayModal();
    });
})();
</script>
@endpush
@endsection
