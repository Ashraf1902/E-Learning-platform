@extends('layouts.app')
@section('title', $lecture->title)

@section('content')
<div class="page-head">
    <h2>{{ $lecture->title }}</h2>
    <div style="display:flex;gap:8px">
        <a href="{{ route('lectures.edit', $lecture) }}" class="btn btn-ghost">{{ __('app.edit') }}</a>
        <button onclick="deleteLecture({{ $lecture->id }})" class="btn btn-danger">{{ __('app.delete') }}</button>
        <a href="{{ route('lectures.index') }}" class="btn btn-ghost">← {{ __('app.back') }}</a>
    </div>
    <form id="delete-form-{{ $lecture->id }}" method="POST" action="{{ route('lectures.destroy', $lecture) }}" style="display:none;">
        @csrf @method('DELETE')
    </form>
</div>

<div class="grid-2" style="gap:20px">
    <div>
        <div class="card">
            <div style="display:flex;gap:10px;margin-bottom:16px;flex-wrap:wrap">
                <span class="badge badge-blue">{{ $lecture->category }}</span>
                <span class="badge badge-gold">{{ $lecture->level_label }}</span>
                <span class="badge {{ $lecture->status === 'published' ? 'badge-green' : 'badge-yellow' }}">
                    {{ $lecture->status === 'published' ? __('app.published') : __('app.draft') }}
                </span>
                @if($lecture->duration_minutes)
                <span class="badge badge-blue">⏱ {{ $lecture->duration_minutes }} {{ __('app.min') }}</span>
                @endif
            </div>

            {{-- Secure Video Player --}}
            @if($lecture->hasVideo())
            <div style="margin-bottom:16px">
                <x-secure-video-player :lecture="$lecture" />
            </div>
            @endif

            <p style="color:var(--muted);font-size:0.9rem;line-height:1.8">{{ $lecture->description ?? __('app.no_data') }}</p>
        </div>

        {{-- Materials --}}
        @if($lecture->materials->count())
        <div class="card mt-4">
            <div class="card-title" style="margin-bottom:16px"><x-icon name="paperclip" /> {{ app()->getLocale() === 'ar' ? 'المرفقات' : 'Materials' }} ({{ $lecture->materials->count() }})</div>
            @foreach($lecture->materials as $material)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border)">
                <div style="display:flex;align-items:center;gap:10px">
                    <span style="font-size:1.3rem">{{ $material->file_icon }}</span>
                    <div>
                        <div style="font-size:0.88rem;font-weight:600">{{ $material->title }}</div>
                        <div style="font-size:0.74rem;color:var(--muted)">{{ $material->file_size_label }}</div>
                    </div>
                </div>
                <a href="{{ $material->file_url }}" target="_blank" class="btn btn-ghost btn-sm">
                    {{ app()->getLocale() === 'ar' ? 'تحميل' : 'Download' }}
                </a>
            </div>
            @endforeach
        </div>
        @endif

        <div class="card mt-4">
            <div class="card-header">
                <div class="card-title"><x-icon name="clipboard" /> {{ __('app.quizzes') }} ({{ $lecture->quizzes->count() }})</div>
                <a href="{{ route('quizzes.create') }}" class="btn btn-primary btn-sm">+ {{ __('app.add_quiz') }}</a>
            </div>
            @forelse($lecture->quizzes as $quiz)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid var(--border)">
                <span style="font-size:0.9rem;font-weight:600">{{ $quiz->title }}</span>
                <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-ghost btn-sm">{{ __('app.view') }}</a>
            </div>
            @empty
            <div class="text-muted" style="padding:12px 0;font-size:0.85rem">{{ __('app.no_data') }}</div>
            @endforelse
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><x-icon name="users" /> {{ __('app.students') }} ({{ $lecture->students->count() }})</div>
        </div>
        @forelse($lecture->students as $student)
        <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid var(--border)">
            <div>
                <div style="font-weight:600;font-size:0.88rem">{{ $student->name }}</div>
                <div style="color:var(--muted);font-size:0.78rem">{{ $student->email }}</div>
            </div>
            <div style="display:flex;gap:6px;align-items:center">
                <span class="badge badge-gold">{{ $student->level_label }}</span>
                <span class="badge {{ $student->pivot->completed ? 'badge-green' : 'badge-yellow' }}">
                    @if($student->pivot->completed)<x-icon name="check" />@else ... @endif
                </span>
            </div>
        </div>
        @empty
        <div class="text-muted" style="padding:12px 0;font-size:0.85rem">{{ __('app.no_data') }}</div>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
function deleteLecture(id) {
    if (confirm('{{ app()->getLocale() === "ar" ? "هل تريد حذف هذه المحاضرة؟" : "Delete this lecture?" }}')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush
@endsection