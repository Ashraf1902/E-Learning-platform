@extends('layouts.app')
@section('title', 'أكواد الدخول')

@section('content')
<div class="toolbar">
    <span class="toolbar-label">{{ $codes->total() }} {{ app()->getLocale() === 'ar' ? 'كود' : 'codes' }}</span>
    <span class="spacer"></span>
    <a href="{{ route('access-codes.create') }}" class="btn btn-primary btn-sm"><x-icon name="plus" /> {{ app()->getLocale() === 'ar' ? 'إنشاء كود جديد' : 'New code' }}</a>
</div>

<div class="card">
    <div class="table-wrap desktop-table">
        <table>
            <thead>
                <tr>
                    <th>{{ app()->getLocale() === 'ar' ? 'الكود' : 'Code' }}</th>
                    <th>{{ app()->getLocale() === 'ar' ? 'النوع' : 'Type' }}</th>
                    <th>{{ app()->getLocale() === 'ar' ? 'الغرض' : 'Purpose' }}</th>
                    <th>{{ app()->getLocale() === 'ar' ? 'الاستخدام' : 'Usage' }}</th>
                    <th>{{ app()->getLocale() === 'ar' ? 'الصلاحية' : 'Expires' }}</th>
                    <th>{{ app()->getLocale() === 'ar' ? 'الحالة' : 'Status' }}</th>
                    <th>{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($codes as $code)
                <tr>
                    <td>
                        <div class="cell-actions">
                            <span class="code-chip">{{ $code->code }}</span>
                            <button type="button" class="btn btn-ghost btn-sm" onclick="copyCode(this, @js($code->code))">
                                <x-icon name="copy" /> {{ app()->getLocale() === 'ar' ? 'نسخ' : 'Copy' }}
                            </button>
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $code->type === 'registration' ? 'badge-blue' : 'badge-gold' }}">
                            {{ $code->type === 'registration' ? 'تسجيل' : 'فتح محاضرات' }}
                        </span>
                    </td>
                    <td style="max-width:240px">
                        @if($code->type === 'lecture')
                            <div class="cell-sub" style="margin:0">
                                {{ $code->lectures->pluck('title')->take(3)->implode('، ') }}
                                @if($code->lectures->count() > 3) <span style="color:var(--gold)">+{{ $code->lectures->count() - 3 }}</span> @endif
                            </div>
                        @else
                            <div class="cell-sub" style="margin:0">{{ $code->description ?? 'كود تسجيل للطلاب' }}</div>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;min-width:100px">
                            <div class="progress" style="width:56px;flex:none">
                                <div class="progress-fill {{ $code->usages_count >= $code->max_uses ? 'is-fail' : '' }}" style="width:{{ $code->max_uses ? min(100, round($code->usages_count / max(1, $code->max_uses) * 100)) : 0 }}%"></div>
                            </div>
                            <span class="num">{{ $code->usages_count }} / {{ $code->max_uses }}</span>
                        </div>
                    </td>
                    <td class="cell-muted" style="font-size:0.8rem">
                        @if($code->expires_at)
                            {{ $code->expires_at->format('d M Y') }}
                            @if($code->isExpired()) <span class="badge badge-red">منتهي</span> @endif
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $code->is_active ? 'badge-green' : 'badge-yellow' }}">
                            {{ $code->is_active ? 'مفعّل' : 'موقوف' }}
                        </span>
                    </td>
                    <td>
                        <div class="cell-actions">
                            <a href="{{ route('access-codes.show', $code) }}" class="btn btn-ghost btn-sm">الطلاب</a>
                            <a href="{{ route('access-codes.edit', $code) }}" class="btn btn-ghost btn-sm">{{ __('app.edit') }}</a>
                            <form method="POST" action="{{ route('access-codes.toggle', $code) }}" style="display:inline">
                                @csrf
                                <button type="submit" class="btn btn-ghost btn-sm">{{ $code->is_active ? 'إيقاف' : 'تفعيل' }}</button>
                            </form>
                            <button onclick="deleteCode({{ $code->id }})" class="btn btn-danger btn-sm">{{ __('app.delete') }}</button>
                        </div>
                        <form id="delete-code-{{ $code->id }}" method="POST" action="{{ route('access-codes.destroy', $code) }}" style="display:none">
                            @csrf @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <div class="icon"><x-icon name="key" /></div>
                            <div class="empty-title">لا توجد أكواد بعد</div>
                            <div class="empty-hint">أنشئ كود فتح لمحاضرات محددة أو كود تسجيل ليتمكن الطلاب من إنشاء حساباتهم.</div>
                            <a href="{{ route('access-codes.create') }}" class="btn btn-ghost btn-sm"><x-icon name="plus" /> إنشاء كود جديد</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mobile-card-list">
        @forelse($codes as $code)
        <div class="mobile-row-card">
            <div class="mobile-row-top">
                <div class="cell-actions">
                    <span class="code-chip">{{ $code->code }}</span>
                    <button type="button" class="btn btn-ghost btn-sm" onclick="copyCode(this, @js($code->code))"><x-icon name="copy" /> نسخ</button>
                </div>
                <span class="badge {{ $code->is_active ? 'badge-green' : 'badge-yellow' }}">{{ $code->is_active ? 'مفعّل' : 'موقوف' }}</span>
            </div>
            <div class="mobile-row-badges">
                <span class="badge {{ $code->type === 'registration' ? 'badge-blue' : 'badge-gold' }}">{{ $code->type === 'registration' ? 'تسجيل' : 'فتح محاضرات' }}</span>
                <span class="badge {{ $code->usages_count >= $code->max_uses ? 'badge-red' : 'badge-green' }}">{{ $code->usages_count }} / {{ $code->max_uses }}</span>
                @if($code->expires_at)
                <span class="badge badge-blue">حتى {{ $code->expires_at->format('d M Y') }}</span>
                @endif
            </div>
            @if($code->type === 'lecture' && $code->lectures->count())
            <div class="mobile-row-meta">{{ $code->lectures->pluck('title')->take(3)->implode('، ') }}</div>
            @endif
            <div class="mobile-row-actions">
                <a href="{{ route('access-codes.show', $code) }}" class="btn btn-ghost btn-sm">الطلاب</a>
                <a href="{{ route('access-codes.edit', $code) }}" class="btn btn-ghost btn-sm">{{ __('app.edit') }}</a>
                <form method="POST" action="{{ route('access-codes.toggle', $code) }}" style="flex:1">
                    @csrf
                    <button type="submit" class="btn btn-ghost btn-sm" style="width:100%">{{ $code->is_active ? 'إيقاف' : 'تفعيل' }}</button>
                </form>
                <button onclick="deleteCode({{ $code->id }})" class="btn btn-danger btn-sm">{{ __('app.delete') }}</button>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <div class="icon"><x-icon name="key" /></div>
            <div class="empty-title">لا توجد أكواد بعد</div>
        </div>
        @endforelse
    </div>

    <div class="pagination">{{ $codes->links() }}</div>
</div>

@push('scripts')
<script>
function deleteCode(id) {
    if (confirm('هل تريد حذف هذا الكود نهائياً مع كل بيانات استخدامه؟')) {
        document.getElementById('delete-code-' + id).submit();
    }
}

function copyCode(btn, code) {
    var original = btn.dataset.label || btn.innerHTML;
    btn.dataset.label = original;
    function done() {
        btn.textContent = 'تم';
        setTimeout(function () { btn.innerHTML = original; }, 1200);
    }
    function fallback() {
        var ta = document.createElement('textarea');
        ta.value = code;
        ta.style.position = 'fixed';
        ta.style.opacity = '0';
        document.body.appendChild(ta);
        ta.select();
        try { document.execCommand('copy'); } catch (e) {}
        document.body.removeChild(ta);
        done();
    }
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(code).then(done).catch(fallback);
    } else {
        fallback();
    }
}
</script>
@endpush
@endsection
