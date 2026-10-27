@extends('layouts.app')
@section('title', app()->getLocale() === 'ar' ? 'خطط الاشتراك' : 'Subscription Plans')

@section('content')
@php $isAr = app()->getLocale() === 'ar'; @endphp

<div class="page-head">
    <h2><x-icon name="settings" /> {{ $isAr ? 'إدارة خطط الاشتراك' : 'Manage Subscription Plans' }}</h2>
    <a href="{{ route('admin.payments.index') }}" class="btn btn-ghost">← {{ __('app.back') }}</a>
</div>

<div class="grid-2" style="gap:20px;align-items:start">

    {{-- Create New Plan --}}
    <div class="card">
        <div class="card-title" style="margin-bottom:18px"><x-icon name="plus" /> {{ $isAr ? 'إنشاء خطة جديدة' : 'Create New Plan' }}</div>
        <form method="POST" action="{{ route('admin.payments.plans.store') }}">
            @csrf
            <div class="form-group">
                <label>{{ $isAr ? 'اسم الخطة' : 'Plan Name' }} *</label>
                <input type="text" name="name" placeholder="{{ $isAr ? 'مثال: اشتراك شهري' : 'e.g. Monthly Plan' }}" required>
            </div>
            <div class="form-group">
                <label>{{ __('app.description') }}</label>
                <textarea name="description" placeholder="{{ $isAr ? 'وصف مختصر للخطة' : 'Short plan description' }}"></textarea>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label>{{ $isAr ? 'السعر (جنيه)' : 'Price (EGP)' }} *</label>
                    <input type="number" name="price" min="0" step="0.01" placeholder="150" required>
                </div>
                <div class="form-group">
                    <label>{{ $isAr ? 'المدة' : 'Duration' }} *</label>
                    <select name="duration" required>
                        <option value="month">{{ $isAr ? 'شهري' : 'Monthly' }}</option>
                        <option value="term">{{ $isAr ? 'فصل دراسي' : 'Term' }}</option>
                        <option value="year">{{ $isAr ? 'سنوي' : 'Yearly' }}</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>{{ $isAr ? 'عدد الأيام' : 'Duration (days)' }} *</label>
                <input type="number" name="duration_days" min="1" placeholder="30" value="30" required>
                <div class="form-hint">{{ $isAr ? 'مثال: شهري = 30، فصل دراسي = 120، سنوي = 365' : 'e.g. Monthly = 30, Term = 120, Yearly = 365' }}</div>
            </div>
            <button type="submit" class="btn btn-primary">{{ $isAr ? 'إنشاء الخطة' : 'Create Plan' }} →</button>
        </form>
    </div>

    {{-- Existing Plans --}}
    <div class="card">
        <div class="card-title" style="margin-bottom:18px"><x-icon name="clipboard" /> {{ $isAr ? 'الخطط الحالية' : 'Existing Plans' }}</div>
        @forelse($plans as $plan)
        <div style="padding:14px 0;border-bottom:1px solid var(--border)">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:10px">
                <div>
                    <div style="font-weight:700;font-size:0.95rem">{{ $plan->name }}</div>
                    <div style="font-size:0.8rem;color:var(--muted);margin-top:2px">{{ $plan->description }}</div>
                    <div style="margin-top:8px;display:flex;gap:8px;align-items:center">
                        <span style="font-weight:800;color:var(--gold)">{{ number_format($plan->price, 0) }} {{ $isAr ? 'ج.م' : 'EGP' }}</span>
                        <span class="badge badge-blue">{{ $plan->duration_label }}</span>
                        <span class="badge {{ $plan->is_active ? 'badge-green' : 'badge-red' }}">
                            {{ $plan->is_active ? ($isAr ? 'مفعّل' : 'Active') : ($isAr ? 'معطّل' : 'Disabled') }}
                        </span>
                    </div>
                </div>
                <div style="display:flex;gap:6px;flex-shrink:0">
                    <form method="POST" action="{{ route('admin.payments.plans.toggle', $plan) }}">
                        @csrf
                        <button type="submit" class="btn btn-ghost btn-sm">
                            {{ $plan->is_active ? ($isAr ? 'تعطيل' : 'Disable') : ($isAr ? 'تفعيل' : 'Enable') }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.payments.plans.destroy', $plan) }}"
                          onsubmit="return confirm('{{ $isAr ? 'حذف هذه الخطة؟' : 'Delete this plan?' }}')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">{{ __('app.delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state"><div class="icon"><x-icon name="card" /></div>{{ __('app.no_data') }}</div>
        @endforelse
    </div>
</div>
@endsection
                            