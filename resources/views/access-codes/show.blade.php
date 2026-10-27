@extends('layouts.app')
@section('title', 'كود: ' . $accessCode->code)

@section('content')
<div class="page-head">
    <h2><x-icon name="key" /> <span style="font-family:monospace;direction:ltr">{{ $accessCode->code }}</span></h2>
    <div style="display:flex;gap:8px">
        <a href="{{ route('access-codes.edit', $accessCode) }}" class="btn btn-ghost"><x-icon name="pencil" /> {{ __('app.edit') }}</a>
        <a href="{{ route('access-codes.index') }}" class="btn btn-ghost">← {{ __('app.back') }}</a>
    </div>
</div>

{{-- Code summary --}}
<div class="card" style="margin-bottom:20px">
    <div style="display:flex;gap:26px;flex-wrap:wrap;align-items:center">
        <div>
            <div style="font-size:0.72rem;color:var(--muted);text-transform:uppercase;letter-spacing:.08em">النوع</div>
            <span class="badge {{ $accessCode->type === 'registration' ? 'badge-blue' : 'badge-gold' }}" style="font-size:0.85rem">
                @if($accessCode->type === 'registration')
                    <x-icon name="clipboard" /> كود تسجيل
                @else
                    <x-icon name="video" /> كود فتح محاضرات
                @endif
            </span>
        </div>
        <div>
            <div style="font-size:0.72rem;color:var(--muted);text-transform:uppercase;letter-spacing:.08em">الطلاب</div>
            <span class="badge {{ $accessCode->usedCount() >= $accessCode->max_uses ? 'badge-red' : 'badge-green' }}" style="font-size:0.85rem">
                {{ $accessCode->usedCount() }} / {{ $accessCode->max_uses }}
            </span>
        </div>
        <div>
            <div style="font-size:0.72rem;color:var(--muted);text-transform:uppercase;letter-spacing:.08em">الحالة</div>
            <span class="badge {{ $accessCode->is_active ? 'badge-green' : 'badge-yellow' }}" style="font-size:0.85rem">
                {{ $accessCode->is_active ? 'مفعّل' : 'موقوف' }}
            </span>
        </div>
        <div>
            <div style="font-size:0.72rem;color:var(--muted);text-transform:uppercase;letter-spacing:.08em">ينتهي</div>
            <div style="font-weight:700;font-size:0.9rem">
                {{ $accessCode->expires_at ? $accessCode->expires_at->format('d M Y') : 'بدون انتهاء' }}
            </div>
        </div>
        @if($accessCode->description)
        <div>
            <div style="font-size:0.72rem;color:var(--muted);text-transform:uppercase;letter-spacing:.08em">ملاحظة</div>
            <div style="font-weight:700;font-size:0.9rem">{{ $accessCode->description }}</div>
        </div>
        @endif
        <div style="margin-inline-start:auto;display:flex;gap:8px">
            <button type="button" class="btn btn-ghost btn-sm" onclick="copyAccessCode(this)">
                <x-icon name="clipboard" /> <span>نسخ الكود</span>
            </button>
            <form method="POST" action="{{ route('access-codes.toggle', $accessCode) }}">
                @csrf
                <button type="submit" class="btn btn-ghost btn-sm">
                    @if ($accessCode->is_active)
                        <x-icon name="pause" /> إيقاف مؤقت
                    @else
                        <x-icon name="play" /> إعادة تفعيل
                    @endif
                </button>
            </form>
        </div>
    </div>

    @if($accessCode->type === 'lecture' && $accessCode->lectures->count())
    <div style="margin-top:16px;padding-top:14px;border-top:1px solid var(--border)">
        <div style="font-size:0.75rem;color:var(--muted);margin-bottom:8px">المحاضرات المفتوحة بالكود:</div>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
            @foreach($accessCode->lectures as $lecture)
            <span class="badge badge-gold"><x-icon name="video" /> {{ $lecture->title }}</span>
            @endforeach
        </div>
    </div>
    @endif
</div>

{{-- Add a student manually --}}
<div class="card" style="margin-bottom:20px">
    <div class="card-title" style="margin-bottom:14px"><x-icon name="plus" /> إضافة طالب للكود يدوياً</div>
    @if($accessCode->unusableReason())
        <div class="alert alert-info" style="margin-bottom:0">{{ $accessCode->unusableReason() }} لا يمكن إضافة طلاب جدد.</div>
    @elseif($availableStudents->isEmpty())
        <div class="alert alert-info" style="margin-bottom:0">كل الطلاب مضافون لهذا الكود بالفعل.</div>
    @else
        <form method="POST" action="{{ route('access-codes.students.store', $accessCode) }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-start">
            @csrf
            <div style="flex:1;min-width:240px">
                <select name="student_id" required style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid var(--border);background:var(--surface2);color:var(--text)">
                    <option value="">— اختر طالباً —</option>
                    @foreach($availableStudents as $student)
                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                    @endforeach
                </select>
                @error('student_id') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <button type="submit" class="btn btn-primary">إضافة</button>
        </form>
    @endif
</div>

{{-- Students who used the code --}}
<div class="card">
    <div class="card-title" style="margin-bottom:14px"><x-icon name="users" /> الطلاب الذين استخدموا الكود ({{ $accessCode->usedCount() }})</div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>الطالب</th>
                    <th>البريد الإلكتروني</th>
                    <th>الصف</th>
                    <th>تاريخ الاستخدام</th>
                    <th>{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($accessCode->usages as $usage)
                <tr>
                    <td style="color:var(--muted);font-family:monospace;font-size:0.78rem">{{ $usage->student?->id }}</td>
                    <td style="font-weight:700">{{ $usage->student?->name ?? '— (طالب محذوف)' }}</td>
                    <td style="color:var(--muted);font-size:0.82rem" dir="ltr">{{ $usage->student?->email }}</td>
                    <td><span class="badge badge-blue">{{ $usage->student?->level_label }}</span></td>
                    <td style="color:var(--muted);font-size:0.8rem">{{ $usage->created_at->format('d M Y — h:i A') }}</td>
                    <td>
                        @if($usage->student)
                        <form method="POST" action="{{ route('access-codes.students.destroy', [$accessCode, $usage->student]) }}"
                              onsubmit="return confirm('سحب الكود من هذا الطالب؟ سيُفقد الوصول للمحاضرات المفتوحة به.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">سحب الكود</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="empty-state"><div class="icon"><x-icon name="users" /></div>لم يستخدم أحد هذا الكود بعد.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function copyAccessCode(btn) {
        var code = @json($accessCode->code);
        var label = btn.querySelector('span');
        var original = label.textContent;
        function done() {
            label.textContent = 'تم النسخ';
            setTimeout(function () { label.textContent = original; }, 1200);
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
@endsection
