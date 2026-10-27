@extends('layouts.app')
@section('title', __('app.quizzes'))

@section('content')
<div class="toolbar">
    <span class="toolbar-label">{{ __('app.quizzes') }}: {{ $quizzes->total() }}</span>
    <span class="spacer"></span>
    <a href="{{ route('quizzes.create') }}" class="btn btn-primary btn-sm"><x-icon name="plus" /> {{ __('app.add_quiz') }}</a>
</div>

<div class="card">
    <div class="table-wrap desktop-table">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('app.quiz_title') }}</th>
                    <th>{{ __('app.linked_lecture') }}</th>
                    <th>{{ __('app.questions') }}</th>
                    <th>{{ __('app.pass_score') }}</th>
                    <th>{{ __('app.status') }}</th>
                    <th>{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quizzes as $quiz)
                <tr>
                    <td class="num">{{ $quiz->id }}</td>
                    <td><a href="{{ route('quizzes.show', $quiz) }}" class="cell-strong" style="color:inherit;text-decoration:none">{{ $quiz->title }}</a></td>
                    <td class="cell-muted">{{ $quiz->lecture->title ?? '—' }}</td>
                    <td><span class="badge badge-blue">{{ $quiz->questions_count }}</span></td>
                    <td class="num">{{ $quiz->pass_score }}%</td>
                    <td>
                        <span class="badge {{ $quiz->status === 'published' ? 'badge-green' : 'badge-yellow' }}">
                            {{ $quiz->status === 'published' ? __('app.published') : __('app.draft') }}
                        </span>
                    </td>
                    <td>
                        <div class="cell-actions">
                            <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-ghost btn-sm">{{ __('app.view') }}</a>
                            <a href="{{ route('quizzes.edit', $quiz) }}" class="btn btn-ghost btn-sm">{{ __('app.edit') }}</a>
                            <button onclick="deleteItem({{ $quiz->id }})" class="btn btn-danger btn-sm">{{ __('app.delete') }}</button>
                        </div>
                        <form id="delete-form-{{ $quiz->id }}" method="POST" action="{{ route('quizzes.destroy', $quiz) }}" style="display:none;">
                            @csrf @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <div class="icon"><x-icon name="clipboard" /></div>
                            <div class="empty-title">{{ __('app.no_data') }}</div>
                            <div class="empty-hint">{{ app()->getLocale() === 'ar' ? 'أنشئ اختباراً وربطه بمحاضرة لتظهر نتائجه في لوحة المتصدرين.' : 'Create a quiz linked to a lecture so results appear on the leaderboard.' }}</div>
                            <a href="{{ route('quizzes.create') }}" class="btn btn-ghost btn-sm"><x-icon name="plus" /> {{ __('app.add_quiz') }}</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mobile-card-list">
        @forelse($quizzes as $quiz)
        <div class="mobile-row-card">
            <div class="mobile-row-top">
                <a href="{{ route('quizzes.show', $quiz) }}" class="mobile-row-title">{{ $quiz->title }}</a>
                <span class="badge {{ $quiz->status === 'published' ? 'badge-green' : 'badge-yellow' }}">
                    {{ $quiz->status === 'published' ? __('app.published') : __('app.draft') }}
                </span>
            </div>
            <div class="mobile-row-badges">
                <span class="badge badge-blue">{{ $quiz->questions_count }} {{ __('app.questions') }}</span>
                <span class="badge badge-blue">{{ __('app.pass_score') }}: {{ $quiz->pass_score }}%</span>
            </div>
            <div class="mobile-row-meta">{{ $quiz->lecture->title ?? '—' }}</div>
            <div class="mobile-row-actions">
                <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-ghost btn-sm">{{ __('app.view') }}</a>
                <a href="{{ route('quizzes.edit', $quiz) }}" class="btn btn-ghost btn-sm">{{ __('app.edit') }}</a>
                <button onclick="deleteItem({{ $quiz->id }})" class="btn btn-danger btn-sm">{{ __('app.delete') }}</button>
            </div>
            <form id="delete-form-mobile-{{ $quiz->id }}" method="POST" action="{{ route('quizzes.destroy', $quiz) }}" style="display:none;">
                @csrf @method('DELETE')
            </form>
        </div>
        @empty
        <div class="empty-state">
            <div class="icon"><x-icon name="clipboard" /></div>
            <div class="empty-title">{{ __('app.no_data') }}</div>
        </div>
        @endforelse
    </div>

    <div class="pagination">{{ $quizzes->links() }}</div>
</div>

@push('scripts')
<script>
function deleteItem(id) {
    if (confirm('{{ app()->getLocale() === 'ar' ? 'هل تريد حذف هذا الاختبار؟' : 'Delete this quiz?' }}')) {
        const desktopForm = document.getElementById('delete-form-' + id);
        const mobileForm = document.getElementById('delete-form-mobile-' + id);
        (desktopForm || mobileForm).submit();
    }
}
</script>
@endpush
@endsection
