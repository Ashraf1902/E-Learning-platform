@extends('layouts.app')
@section('title', app()->getLocale() === 'ar' ? 'طلبات الدفع' : 'Payments')

@section('content')
@php
    $isAr = app()->getLocale() === 'ar';
    $avatarColors = ['#e05555','#3ec97a','#4a8cff','#d4a843','#a064f0','#28c8c8','#f07030','#e05599','#70b040','#5088e0'];
@endphp

<div class="toolbar">
    <span class="toolbar-label">{{ __('app.filter') }}:</span>
    <a href="{{ route('admin.payments.index') }}" class="chip {{ !request('status') ? 'active' : '' }}">{{ $isAr ? 'الكل' : 'All' }}</a>
    <a href="{{ route('admin.payments.index', ['status'=>'pending']) }}" class="chip {{ request('status')==='pending' ? 'active' : '' }}">{{ $isAr ? 'قيد المراجعة' : 'Pending' }}</a>
    <a href="{{ route('admin.payments.index', ['status'=>'paid']) }}" class="chip {{ request('status')==='paid' ? 'active' : '' }}">{{ $isAr ? 'مؤكد' : 'Paid' }}</a>
    <a href="{{ route('admin.payments.index', ['status'=>'rejected']) }}" class="chip {{ request('status')==='rejected' ? 'active' : '' }}">{{ $isAr ? 'مرفوض' : 'Rejected' }}</a>
    <span class="spacer"></span>
    <a href="{{ route('admin.payments.plans') }}" class="btn btn-ghost btn-sm"><x-icon name="settings" /> {{ $isAr ? 'إدارة الخطط' : 'Manage Plans' }}</a>
</div>

<div class="card">
    <div class="table-wrap desktop-table">
        <table>
            <thead>
                <tr>
                    <th>{{ $isAr ? 'الطالب' : 'Student' }}</th>
                    <th>{{ $isAr ? 'الخطة' : 'Plan' }}</th>
                    <th>{{ $isAr ? 'المبلغ' : 'Amount' }}</th>
                    <th>{{ $isAr ? 'الطريقة' : 'Method' }}</th>
                    <th>{{ $isAr ? 'رقم المرجع' : 'Reference' }}</th>
                    <th>{{ __('app.status') }}</th>
                    <th>{{ __('app.date') }}</th>
                    <th>{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;min-width:0">
                            <span class="avatar" style="background:{{ $avatarColors[($payment->student->id ?? 0) % count($avatarColors)] }}">{{ strtoupper(mb_substr($payment->student->name ?? '?', 0, 1)) }}</span>
                            <div style="min-width:0">
                                <div class="cell-strong">{{ $payment->student->name ?? '—' }}</div>
                                <div class="cell-sub">{{ $payment->student->email ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="cell-muted">{{ $payment->subscription->plan->name ?? '—' }}</td>
                    <td class="cell-strong">{{ number_format($payment->amount, 0) }} {{ $isAr ? 'ج.م' : 'EGP' }}</td>
                    <td><span class="badge badge-blue">{{ $payment->method_label }}</span></td>
                    <td class="cell-muted" style="font-size:0.8rem">{{ $payment->reference_number }}</td>
                    <td>
                        @if($payment->status === 'paid')
                            <span class="badge badge-green">{{ $isAr ? 'مؤكد' : 'Paid' }}</span>
                        @elseif($payment->status === 'pending')
                            <span class="badge badge-yellow">{{ $isAr ? 'قيد المراجعة' : 'Pending' }}</span>
                        @else
                            <span class="badge badge-red">{{ $isAr ? 'مرفوض' : 'Rejected' }}</span>
                        @endif
                    </td>
                    <td class="cell-muted" style="font-size:0.8rem">{{ $payment->created_at->format('d M Y') }}</td>
                    <td>
                        @if($payment->status === 'pending')
                        <div class="cell-actions">
                            <form method="POST" action="{{ route('admin.payments.approve', $payment) }}"
                                  onsubmit="return confirm('{{ $isAr ? 'تأكيد الدفع وتفعيل الاشتراك؟' : 'Confirm payment and activate subscription?' }}')">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm"><x-icon name="check" /> {{ $isAr ? 'تأكيد' : 'Approve' }}</button>
                            </form>
                            <form method="POST" action="{{ route('admin.payments.reject', $payment) }}"
                                  onsubmit="return confirm('{{ $isAr ? 'رفض الطلب؟' : 'Reject this request?' }}')">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm"><x-icon name="x" /> {{ $isAr ? 'رفض' : 'Reject' }}</button>
                            </form>
                        </div>
                        @else
                        <span class="cell-muted">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <div class="icon"><x-icon name="card" /></div>
                            <div class="empty-title">{{ $isAr ? 'لا توجد طلبات دفع' : 'No payment requests' }}</div>
                            <div class="empty-hint">{{ $isAr ? 'تظهر طلبات الدفع هنا عندما يرسلها الطلاب من صفحة الاشتراك.' : 'Payment requests appear here when students submit them from the subscription page.' }}</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mobile-card-list">
        @forelse($payments as $payment)
        <div class="mobile-row-card">
            <div class="mobile-row-top">
                <div style="display:flex;align-items:center;gap:10px;min-width:0">
                    <span class="avatar" style="background:{{ $avatarColors[($payment->student->id ?? 0) % count($avatarColors)] }}">{{ strtoupper(mb_substr($payment->student->name ?? '?', 0, 1)) }}</span>
                    <div style="min-width:0">
                        <div class="mobile-row-title">{{ $payment->student->name ?? '—' }}</div>
                        <div class="cell-sub">{{ $payment->student->email ?? '' }}</div>
                    </div>
                </div>
                @if($payment->status === 'paid')
                    <span class="badge badge-green">{{ $isAr ? 'مؤكد' : 'Paid' }}</span>
                @elseif($payment->status === 'pending')
                    <span class="badge badge-yellow">{{ $isAr ? 'قيد المراجعة' : 'Pending' }}</span>
                @else
                    <span class="badge badge-red">{{ $isAr ? 'مرفوض' : 'Rejected' }}</span>
                @endif
            </div>
            <div class="mobile-row-badges">
                <span class="badge badge-blue">{{ $payment->method_label }}</span>
                <span class="badge badge-gold">{{ number_format($payment->amount, 0) }} {{ $isAr ? 'ج.م' : 'EGP' }}</span>
            </div>
            <div class="mobile-row-meta">
                {{ $isAr ? 'المرجع:' : 'Ref:' }} {{ $payment->reference_number }}
                · {{ $payment->subscription->plan->name ?? '—' }}
                · {{ $payment->created_at->format('d M Y') }}
            </div>
            @if($payment->status === 'pending')
            <div class="mobile-row-actions">
                <form method="POST" action="{{ route('admin.payments.approve', $payment) }}" style="flex:1"
                      onsubmit="return confirm('{{ $isAr ? 'تأكيد الدفع؟' : 'Confirm payment?' }}')">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm" style="width:100%;justify-content:center">
                        <x-icon name="check" /> {{ $isAr ? 'تأكيد' : 'Approve' }}
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.payments.reject', $payment) }}" style="flex:1"
                      onsubmit="return confirm('{{ $isAr ? 'رفض الطلب؟' : 'Reject?' }}')">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm" style="width:100%;justify-content:center">
                        <x-icon name="x" /> {{ $isAr ? 'رفض' : 'Reject' }}
                    </button>
                </form>
            </div>
            @endif
        </div>
        @empty
        <div class="empty-state">
            <div class="icon"><x-icon name="card" /></div>
            <div class="empty-title">{{ $isAr ? 'لا توجد طلبات دفع' : 'No payment requests' }}</div>
        </div>
        @endforelse
    </div>

    <div class="pagination">{{ $payments->links() }}</div>
</div>
@endsection
