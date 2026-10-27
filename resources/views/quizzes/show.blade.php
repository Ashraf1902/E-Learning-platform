@extends('layouts.app')
@section('title', $quiz->title)

@section('content')
<div class="page-head">
    <h2>{{ $quiz->title }}</h2>
    <div style="display:flex;gap:8px">
        <a href="{{ route('quizzes.edit', $quiz) }}" class="btn btn-ghost">{{ __('app.edit') }}</a>
        <button onclick="deleteQuiz({{ $quiz->id }})" class="btn btn-danger">{{ __('app.delete') }}</button>
        <a href="{{ route('quizzes.index') }}" class="btn btn-ghost">← {{ __('app.back') }}</a>
    </div>
    <form id="delete-form-{{ $quiz->id }}" method="POST" action="{{ route('quizzes.destroy', $quiz) }}" style="display:none;">
        @csrf @method('DELETE')
    </form>
</div>

<div class="grid-2" style="gap:20px">
    <div>
        <div class="card">
            <div style="display:flex;gap:10px;margin-bottom:16px;flex-wrap:wrap">
                <span class="badge {{ $quiz->status === 'published' ? 'badge-green' : 'badge-yellow' }}">
                    {{ $quiz->status === 'published' ? __('app.published') : __('app.draft') }}
                </span>
                <span class="badge badge-blue">{{ $quiz->questions->count() }} {{ __('app.questions') }}</span>
                <span class="badge badge-gold">{{ __('app.pass_score') }}: {{ $quiz->pass_score }}%</span>
            </div>
            <p style="color:var(--muted);font-size:0.9rem">{{ $quiz->description ?? __('app.no_data') }}</p>
            @if($quiz->lecture)
            <div class="divider"></div>
            <div style="font-size:0.85rem;color:var(--muted)">
                {{ __('app.linked_lecture') }}: <a href="{{ route('lectures.show', $quiz->lecture) }}" style="color:var(--gold)">{{ $quiz->lecture->title }}</a>
            </div>
            @endif
        </div>

        <div class="card mt-4">
            <div class="card-title" style="margin-bottom:16px">{{ __('app.questions') }} ({{ $quiz->questions->count() }})</div>
            @foreach($quiz->questions as $i => $question)
            <div style="padding:12px 0;border-bottom:1px solid var(--border)">
                <div style="font-weight:600;font-size:0.88rem;margin-bottom:8px">
                    {{ $i+1 }}. {{ $question->question_text }}
                    <span class="badge badge-blue" style="font-size:0.72rem;vertical-align:middle">{{ \App\Models\Question::TYPES[$question->type] ?? $question->type }}</span>
                    <span style="color:var(--muted);font-weight:400;font-size:0.78rem">({{ $question->points }} {{ __('app.pts') }})</span>
                </div>
                @if($question->type === 'mcq')
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:4px;font-size:0.82rem">
                    @php
                        $correctOpt = \App\Support\AnswerChecker::canonical($question->correct_answer, $question);
                        $labels     = \App\Models\Question::OPTION_LABELS;
                    @endphp
                    @foreach(($question->option_list ?? []) as $idx => $text)
                    @php $letter = chr(97 + $idx); @endphp
                    <div style="padding:4px 8px;border-radius:6px;{{ $correctOpt === $letter ? 'background:rgba(62,201,122,0.1);color:var(--green)' : 'color:var(--muted)' }}">
                            {{ $labels[$idx] ?? strtoupper($letter) }}) {{ $text }} @if($correctOpt === $letter)<x-icon name="check" />@endif
                    </div>
                    @endforeach
                </div>
                @elseif($question->type === 'essay')
                <div style="font-size:0.82rem;color:var(--muted)"><x-icon name="clipboard" /> {{ app()->getLocale() === 'ar' ? 'مقالي — يُصحَّح يدوياً من صفحة النتائج' : 'Essay — graded manually from the results page' }}</div>
                @elseif($question->type === 'matching')
                <div style="font-size:0.82rem;color:var(--muted)">{{ app()->getLocale() === 'ar' ? 'أزواج المطابقة:' : 'Matching pairs:' }}
                    @foreach($question->pairs() as $pair)
                    <span style="color:var(--text)">{{ $pair['left'] }} ↔ {{ $pair['right'] }}</span>{{ !$loop->last ? ' · ' : '' }}
                    @endforeach
                </div>
                @elseif($question->type === 'ordering')
                <div style="font-size:0.82rem;color:var(--muted)">{{ app()->getLocale() === 'ar' ? 'الترتيب الصحيح:' : 'Correct order:' }}
                    @foreach($question->items() as $idx => $item)
                    <span style="color:var(--text)">{{ $idx + 1 }}. {{ $item }}</span>{{ !$loop->last ? ' · ' : '' }}
                    @endforeach
                </div>
                @else
                <div style="font-size:0.82rem;color:var(--muted)">
                    {{ app()->getLocale() === 'ar' ? 'الإجابة:' : 'Answer:' }}
                    <span style="color:var(--green)">{{ $question->correctLabel() }}</span>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <div class="card">
        <div class="card-title" style="margin-bottom:16px"><x-icon name="chart" /> {{ __('app.attempts') }} ({{ $quiz->attempts->count() }})</div>
        @forelse($quiz->attempts as $attempt)
        <div style="padding:10px 0;border-bottom:1px solid var(--border)">
            <div style="display:flex;justify-content:space-between;align-items:center">
                <div style="font-weight:600;font-size:0.88rem">{{ $attempt->student->name }}</div>
                <div style="display:flex;gap:6px;align-items:center">
                    @if($attempt->isPendingGrading())
                    <span class="badge badge-yellow"><x-icon name="clock" /> {{ app()->getLocale() === 'ar' ? 'ينتظر التصحيح' : 'Needs grading' }}</span>
                    @endif
                    <span class="badge {{ $attempt->isPendingGrading() ? 'badge-yellow' : ($attempt->passed ? 'badge-green' : 'badge-red') }}">
                        {{ $attempt->isPendingGrading() ? '—' : ($attempt->passed ? __('app.passed') : __('app.failed')) }}
                    </span>
                </div>
            </div>
            <div style="color:var(--muted);font-size:0.78rem;margin-top:3px">
                {{ $attempt->score }}/{{ $attempt->total_points }} {{ __('app.pts') }}
                · {{ $attempt->total_points > 0 ? round(($attempt->score/$attempt->total_points)*100) : 0 }}%
                · {{ $attempt->completed_at?->format('d M Y') }}
                · <a href="{{ route('quizzes.results', [$quiz, 'attempt' => $attempt->id]) }}" style="color:var(--gold)">
                    @if ($attempt->isPendingGrading() && $quiz->hasEssayQuestions())
                        <x-icon name="clipboard" /> {{ app()->getLocale() === 'ar' ? 'صحّح المقال' : 'Grade essays' }}
                    @else
                        {{ app()->getLocale() === 'ar' ? 'عرض النتيجة' : 'View result' }}
                    @endif
                </a>
            </div>
        </div>
        @empty
        <div class="text-muted" style="padding:12px 0;font-size:0.85rem">{{ __('app.no_attempts') }}</div>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
function deleteQuiz(id) {
    if (confirm('{{ app()->getLocale() === "ar" ? "هل تريد حذف هذا الاختبار؟" : "Delete this quiz?" }}')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush
@endsection
