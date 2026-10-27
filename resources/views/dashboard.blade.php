@extends('layouts.app')
@section('title', __('app.dashboard'))

@section('content')
@php
    $isAr = app()->getLocale() === 'ar';
    $maxLevel = max(1, collect($levels)->max('count'));
    $avatarColors = ['#e05555','#3ec97a','#4a8cff','#d4a843','#a064f0','#28c8c8','#f07030','#e05599','#70b040','#5088e0'];
@endphp

<div class="stat-strip">
    <div class="stat-cell">
        <div class="stat-cell-icon"><x-icon name="video" /></div>
        <div>
            <div class="stat-cell-label">{{ __('app.total_lectures') }}</div>
            <div class="stat-cell-value">{{ $stats['lectures'] }}</div>
        </div>
    </div>
    <div class="stat-cell">
        <div class="stat-cell-icon"><x-icon name="users" /></div>
        <div>
            <div class="stat-cell-label">{{ __('app.total_students') }}</div>
            <div class="stat-cell-value">{{ $stats['students'] }}</div>
        </div>
    </div>
    <div class="stat-cell">
        <div class="stat-cell-icon"><x-icon name="clipboard" /></div>
        <div>
            <div class="stat-cell-label">{{ __('app.total_quizzes') }}</div>
            <div class="stat-cell-value">{{ $stats['quizzes'] }}</div>
        </div>
    </div>
    <div class="stat-cell">
        <div class="stat-cell-icon"><x-icon name="check-circle" /></div>
        <div>
            <div class="stat-cell-label">{{ __('app.published') }}</div>
            <div class="stat-cell-value">{{ $stats['published'] }}<span class="stat-cell-note"> / {{ $stats['lectures'] }}</span></div>
        </div>
    </div>
</div>

<div class="dash-grid">
    <div style="display:flex;flex-direction:column;gap:18px;min-width:0">
        <div class="card">
            <div class="section-head">
                <div class="section-title"><x-icon name="users" /> {{ $isAr ? 'الطلاب حسب المستوى' : 'Students by Level' }}</div>
                <a href="{{ route('students.create') }}" class="btn btn-ghost btn-sm"><x-icon name="plus" /> {{ __('app.add_student') }}</a>
            </div>

            @foreach($levels as $key => $level)
            <a href="{{ route('students.index', ['level' => $key]) }}" class="level-row">
                <div class="level-head">
                    <span class="level-name">{{ $level['label'] }}</span>
                    <span class="level-count">{{ $level['count'] }} {{ $isAr ? 'طالب' : 'students' }}</span>
                </div>
                <div class="level-track">
                    <div class="level-fill" style="width:{{ $level['count'] ? max(2, round($level['count'] / $maxLevel * 100)) : 0 }}%"></div>
                </div>
            </a>
            @endforeach
        </div>

        <div class="card">
            <div class="section-head">
                <div class="section-title"><x-icon name="video" /> {{ __('app.recent_lectures') }}</div>
                <a href="{{ route('lectures.index') }}" class="btn-link">{{ __('app.view_all') }}</a>
            </div>
            <div class="data-list">
                @forelse($recentLectures as $lecture)
                <a href="{{ route('lectures.show', $lecture) }}" class="data-row">
                    <div class="data-icon"><x-icon name="video" /></div>
                    <div class="data-main">
                        <span class="data-title">{{ $lecture->title }}</span>
                        <span class="data-meta">{{ $lecture->category }} · {{ $lecture->level_label }}</span>
                    </div>
                    <div class="data-side">
                        <span class="badge {{ $lecture->status === 'published' ? 'badge-green' : 'badge-yellow' }}">
                            {{ $lecture->status === 'published' ? __('app.published') : __('app.draft') }}
                        </span>
                    </div>
                </a>
                @empty
                <div class="empty-state">
                    <div class="icon"><x-icon name="video" /></div>
                    <div class="empty-title">{{ __('app.no_data') }}</div>
                    <div class="empty-hint">{{ $isAr ? 'أضف أول محاضرة لتبدأ بناء المحتوى.' : 'Add your first lecture to start building content.' }}</div>
                    <a href="{{ route('lectures.create') }}" class="btn btn-ghost btn-sm"><x-icon name="plus" /> {{ __('app.add_lecture') }}</a>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <div style="display:flex;flex-direction:column;gap:18px;min-width:0">
        <div class="card">
            <div class="section-head">
                <div class="section-title"><x-icon name="user" /> {{ __('app.recent_students') }}</div>
                <a href="{{ route('students.index') }}" class="btn-link">{{ __('app.view_all') }}</a>
            </div>
            <div class="data-list">
                @forelse($recentStudents as $student)
                <a href="{{ route('students.show', $student) }}" class="data-row">
                    <div class="avatar" style="background:{{ $avatarColors[$student->id % count($avatarColors)] }}">{{ strtoupper(mb_substr($student->name, 0, 1)) }}</div>
                    <div class="data-main">
                        <span class="data-title">{{ $student->name }}</span>
                        <span class="data-meta">{{ $student->email }}</span>
                    </div>
                    <div class="data-side">
                        <span class="badge badge-gold">{{ $student->level_label }}</span>
                    </div>
                </a>
                @empty
                <div class="empty-state">
                    <div class="icon"><x-icon name="users" /></div>
                    <div class="empty-title">{{ $isAr ? 'لا يوجد طلاب بعد' : 'No students yet' }}</div>
                    <div class="empty-hint">{{ $isAr ? 'أضف طالباً أو شارك كود تسجيل.' : 'Add a student or share a registration code.' }}</div>
                    <a href="{{ route('students.create') }}" class="btn btn-ghost btn-sm"><x-icon name="plus" /> {{ __('app.add_student') }}</a>
                </div>
                @endforelse
            </div>
        </div>

        <div class="card">
            <div class="section-head">
                <div class="section-title"><x-icon name="clipboard" /> {{ $isAr ? 'أحدث الاختبارات' : 'Latest Quizzes' }}</div>
                <a href="{{ route('quizzes.index') }}" class="btn-link">{{ __('app.view_all') }}</a>
            </div>
            <div class="data-list">
                @forelse($recentQuizzes as $quiz)
                <a href="{{ route('quizzes.show', $quiz) }}" class="data-row">
                    <div class="data-icon"><x-icon name="clipboard" /></div>
                    <div class="data-main">
                        <span class="data-title">{{ $quiz->title }}</span>
                        <span class="data-meta">{{ $quiz->questions_count }} {{ __('app.questions') }} · {{ $quiz->lecture->title ?? '—' }}</span>
                    </div>
                    <div class="data-side">
                        <span class="badge {{ $quiz->status === 'published' ? 'badge-green' : 'badge-yellow' }}">
                            {{ $quiz->status === 'published' ? __('app.published') : __('app.draft') }}
                        </span>
                    </div>
                </a>
                @empty
                <div class="empty-state">
                    <div class="icon"><x-icon name="clipboard" /></div>
                    <div class="empty-title">{{ __('app.no_data') }}</div>
                    <div class="empty-hint">{{ $isAr ? 'أنشئ اختباراً لمحاضرة لقياس تقدم الطلاب.' : 'Create a quiz for a lecture to measure progress.' }}</div>
                    <a href="{{ route('quizzes.create') }}" class="btn btn-ghost btn-sm"><x-icon name="plus" /> {{ __('app.add_quiz') }}</a>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
