@extends(auth()->user()->isAdmin() ? 'layouts.app' : 'portal.layout')
@php $isAr = app()->getLocale() === 'ar'; @endphp

@section('title', $isAr ? 'الملف الشخصي' : 'My Profile')

@section('content')
<div class="page-head">
    <div>
        <h2><x-icon name="user" /> {{ $isAr ? 'الملف الشخصي' : 'My Profile' }}</h2>
        <p>{{ $isAr ? 'راجع بيانات حسابك وغيّر كلمة المرور في أي وقت.' : 'Review your account details and change your password at any time.' }}</p>
    </div>
</div>

@if(session('status') === 'password-updated')
<div class="alert alert-success">
    <x-icon name="check-circle" />
    <div>{{ $isAr ? 'تم تحديث كلمة المرور بنجاح.' : 'Your password has been updated successfully.' }}</div>
</div>
@endif

<div class="grid-2" style="align-items:start;max-width:1000px">

    <div class="card">
        <div class="card-title" style="margin-bottom:16px">
            <x-icon name="user" /> {{ $isAr ? 'بيانات الحساب' : 'Account details' }}
        </div>

        <div class="form-group">
            <label>{{ $isAr ? 'الاسم' : 'Name' }}</label>
            <input type="text" value="{{ auth()->user()->name }}" readonly>
        </div>

        <div class="form-group">
            <label>{{ $isAr ? 'البريد الإلكتروني' : 'Email address' }}</label>
            <input type="text" value="{{ auth()->user()->email }}" readonly dir="ltr">
        </div>

        <div class="form-group" style="margin-bottom:0">
            <label>{{ $isAr ? 'نوع الحساب' : 'Account type' }}</label>
            <div>
                <span class="badge {{ auth()->user()->isAdmin() ? 'badge-gold' : 'badge-blue' }}">
                    {{ auth()->user()->isAdmin() ? ($isAr ? 'مدير' : 'Admin') : ($isAr ? 'طالب' : 'Student') }}
                </span>
            </div>
        </div>

        <p class="form-hint" style="margin-top:18px">
            {{ $isAr ? 'لتغيير الاسم أو البريد الإلكتروني، تواصل مع إدارة المنصة.' : 'To change your name or email address, contact the platform administration.' }}
        </p>
    </div>

    <div class="card">
        <div class="card-title" style="margin-bottom:16px">
            <x-icon name="lock" /> {{ $isAr ? 'تغيير كلمة المرور' : 'Change password' }}
        </div>

        <p class="form-hint" style="margin-top:0;margin-bottom:18px">
            {{ $isAr ? 'استخدم كلمة مرور طويلة وعشوائية (8 أحرف على الأقل) للحفاظ على أمان حسابك.' : 'Use a long, random password (at least 8 characters) to keep your account secure.' }}
        </p>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <div class="form-group">
                <label for="current_password">{{ $isAr ? 'كلمة المرور الحالية' : 'Current password' }}</label>
                <input
                    type="password"
                    id="current_password"
                    name="current_password"
                    autocomplete="current-password"
                    required
                >
                @error('current_password', 'updatePassword')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">{{ $isAr ? 'كلمة المرور الجديدة' : 'New password' }}</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    autocomplete="new-password"
                    required
                >
                @error('password', 'updatePassword')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">{{ $isAr ? 'تأكيد كلمة المرور الجديدة' : 'Confirm new password' }}</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    autocomplete="new-password"
                    required
                >
                @error('password_confirmation', 'updatePassword')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <x-icon name="shield" /> {{ $isAr ? 'حفظ كلمة المرور' : 'Save password' }}
            </button>
        </form>
    </div>

</div>
@endsection
