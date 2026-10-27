@extends('layouts.app')

@section('title', app()->getLocale() === 'ar' ? 'وضع الصيانة' : 'Maintenance')

@section('content')
@php $isAr = app()->getLocale() === 'ar'; @endphp

<div class="page-head">
    <div>
        <h2><x-icon name="wrench" /> {{ $isAr ? 'وضع الصيانة' : 'Maintenance Mode' }}</h2>
        <p>{{ $isAr ? 'إيقاف الموقع مؤقتاً عن الزوار مع إبقاء لوحة التحكم متاحة لك' : 'Temporarily close the site to visitors while keeping the admin panel available' }}</p>
    </div>
</div>

@if(session('status'))
<div class="alert alert-success"><x-icon name="check-circle" /><span>{{ session('status') }}</span></div>
@endif
@if(session('error'))
<div class="alert alert-error"><x-icon name="alert" /><span>{{ session('error') }}</span></div>
@endif

<div class="grid-2" style="gap:20px;align-items:start">

    <div class="card">
        <div class="card-title" style="margin-bottom:16px;display:flex;align-items:center;justify-content:space-between;gap:10px">
            <span><x-icon name="info" /> {{ $isAr ? 'الحالة الحالية' : 'Current Status' }}</span>
            <span class="badge {{ $down ? 'badge-yellow' : 'badge-green' }}">
                {{ $down ? ($isAr ? 'قيد الصيانة' : 'Under maintenance') : ($isAr ? 'الموقع يعمل' : 'Live') }}
            </span>
        </div>

        <p style="color:var(--muted);line-height:1.9;font-size:0.88rem">
            @if($down)
                {{ $isAr ? 'الموقع مغلق أمام الجمهور الآن ويظهر للزوار صفحة الصيانة. لوحة التحكم تبقى متاحة لك.' : 'The site is closed to the public right now and visitors see the maintenance page. The admin panel stays available to you.' }}
            @else
                {{ $isAr ? 'الموقع متاح للجميع الآن. عند تفعيل الصيانة سيرى الزوار صفحة إعلامية فقط.' : 'The site is live for everyone right now. When enabled, visitors will only see an informational page.' }}
            @endif
        </p>

        @if($down && $secretUrl)
        <div class="form-group" style="margin-top:16px;margin-bottom:0">
            <label>{{ $isAr ? 'رابط التجاوز' : 'Bypass URL' }}</label>
            <input type="text" value="{{ $secretUrl }}" readonly onclick="this.select()">
            <div class="form-hint">{{ $isAr ? 'افتح هذا الرابط لتعيين كوكي يسمح لك بتصفح الموقع أثناء الصيانة.' : 'Open this URL to set a cookie that lets you browse the site during maintenance.' }}</div>
        </div>
        @endif
    </div>

    <div class="card">
        <div class="card-title" style="margin-bottom:16px"><x-icon name="settings" /> {{ $isAr ? 'التحكم' : 'Controls' }}</div>

        @if(!$down)
        <form method="POST" action="{{ route('admin.maintenance.down') }}">
            @csrf
            <div class="form-group">
                <label for="secret">{{ $isAr ? 'كلمة سر التجاوز (اختياري)' : 'Bypass secret (optional)' }}</label>
                <input type="text" id="secret" name="secret" value="{{ old('secret') }}" placeholder="{{ $isAr ? 'مثال: admin-2026' : 'e.g. admin-2026' }}">
                <div class="form-hint">{{ $isAr ? 'حروف وأرقام إنجليزية وشرطات فقط. يُنشئ رابط /{secret} للدخول أثناء الصيانة.' : 'Latin letters, numbers and dashes only. Creates a /{secret} URL to get in during maintenance.' }}</div>
            </div>
            <button type="submit" class="btn btn-primary">
                <x-icon name="wrench" /> {{ $isAr ? 'تفعيل وضع الصيانة' : 'Enable maintenance mode' }}
            </button>
        </form>
        @else
        <p style="color:var(--muted);line-height:1.9;font-size:0.88rem">
            {{ $isAr ? 'أوقف الصيانة لإعادة فتح الموقع للجميع فوراً.' : 'Turn maintenance off to reopen the site to everyone immediately.' }}
        </p>
        <form method="POST" action="{{ route('admin.maintenance.up') }}">
            @csrf
            <button type="submit" class="btn btn-success">
                <x-icon name="check" /> {{ $isAr ? 'إيقاف وضع الصيانة' : 'Disable maintenance mode' }}
            </button>
        </form>
        @endif
    </div>
</div>
@endsection
