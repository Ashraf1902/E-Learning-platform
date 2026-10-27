@extends('layouts.app')
@section('title', __('app.lectures'))

@section('content')
<div class="toolbar">
    <span class="toolbar-label">{{ __('app.filter') }}:</span>
    <a href="{{ route('lectures.index') }}" class="chip {{ !request('level') ? 'active' : '' }}">{{ __('app.all_levels') }}</a>
    <a href="{{ route('lectures.index', ['level' => 'first']) }}" class="chip {{ request('level') === 'first' ? 'active' : '' }}">{{ __('app.first') }}</a>
    <a href="{{ route('lectures.index', ['level' => 'second']) }}" class="chip {{ request('level') === 'second' ? 'active' : '' }}">{{ __('app.second') }}</a>
    <a href="{{ route('lectures.index', ['level' => 'third']) }}" class="chip {{ request('level') === 'third' ? 'active' : '' }}">{{ __('app.third') }}</a>
    <span class="spacer"></span>
    <a href="{{ route('lectures.create') }}" class="btn btn-primary btn-sm"><x-icon name="plus" /> {{ __('app.add_lecture') }}</a>
</div>

<div class="card">
    <div class="table-wrap desktop-table">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('app.lecture_title') }}</th>
                    <th>{{ __('app.category') }}</th>
                    <th>{{ __('app.level') }}</th>
                    <th>{{ __('app.duration') }}</th>
                    <th>{{ __('app.status') }}</th>
                    <th>{{ __('app.created_at') }}</th>
                    <th>{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lectures as $lecture)
                <tr>
                    <td class="num">{{ $lecture->id }}</td>
                    <td>
                        <a href="{{ route('lectures.show', $lecture) }}" class="cell-strong" style="color:inherit;text-decoration:none">{{ $lecture->title }}</a>
                    </td>
                    <td><span class="badge badge-blue">{{ $lecture->category }}</span></td>
                    <td><span class="badge badge-gold">{{ $lecture->level_label }}</span></td>
                    <td class="cell-muted">{{ $lecture->duration_minutes ? $lecture->duration_minutes . ' ' . __('app.min') : '—' }}</td>
                    <td>
                        <span class="badge {{ $lecture->status === 'published' ? 'badge-green' : 'badge-yellow' }}">
                            {{ $lecture->status === 'published' ? __('app.published') : __('app.draft') }}
                        </span>
                    </td>
                    <td class="cell-muted" style="font-size:0.8rem">{{ $lecture->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="cell-actions">
                            <a href="{{ route('lectures.show', $lecture) }}" class="btn btn-ghost btn-sm">{{ __('app.view') }}</a>
                            <a href="{{ route('lectures.edit', $lecture) }}" class="btn btn-ghost btn-sm">{{ __('app.edit') }}</a>
                            <button onclick="deleteItem({{ $lecture->id }})" class="btn btn-danger btn-sm">{{ __('app.delete') }}</button>
                        </div>
                        <form id="delete-form-{{ $lecture->id }}" method="POST" action="{{ route('lectures.destroy', $lecture) }}" style="display:none;">
                            @csrf @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <div class="icon"><x-icon name="video" /></div>
                            <div class="empty-title">{{ __('app.no_data') }}</div>
                            <div class="empty-hint">{{ app()->getLocale() === 'ar' ? 'أضف أول محاضرة وارفع ملفاتها ليبدأ الطلاب المشاهدة.' : 'Add the first lecture and upload its files so students can start watching.' }}</div>
                            <a href="{{ route('lectures.create') }}" class="btn btn-ghost btn-sm"><x-icon name="plus" /> {{ __('app.add_lecture') }}</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mobile-card-list">
        @forelse($lectures as $lecture)
        <div class="mobile-row-card">
            <div class="mobile-row-top">
                <a href="{{ route('lectures.show', $lecture) }}" class="mobile-row-title">{{ $lecture->title }}</a>
                <span class="badge {{ $lecture->status === 'published' ? 'badge-green' : 'badge-yellow' }}">
                    {{ $lecture->status === 'published' ? __('app.published') : __('app.draft') }}
                </span>
            </div>
            <div class="mobile-row-badges">
                <span class="badge badge-blue">{{ $lecture->category }}</span>
                <span class="badge badge-gold">{{ $lecture->level_label }}</span>
                @if($lecture->duration_minutes)
                <span class="badge badge-blue"><x-icon name="clock" /> {{ $lecture->duration_minutes }} {{ __('app.min') }}</span>
                @endif
            </div>
            <div class="mobile-row-meta">{{ $lecture->created_at->format('d M Y') }}</div>
            <div class="mobile-row-actions">
                <a href="{{ route('lectures.show', $lecture) }}" class="btn btn-ghost btn-sm">{{ __('app.view') }}</a>
                <a href="{{ route('lectures.edit', $lecture) }}" class="btn btn-ghost btn-sm">{{ __('app.edit') }}</a>
                <button onclick="deleteItem({{ $lecture->id }})" class="btn btn-danger btn-sm">{{ __('app.delete') }}</button>
            </div>
            <form id="delete-form-mobile-{{ $lecture->id }}" method="POST" action="{{ route('lectures.destroy', $lecture) }}" style="display:none;">
                @csrf @method('DELETE')
            </form>
        </div>
        @empty
        <div class="empty-state">
            <div class="icon"><x-icon name="video" /></div>
            <div class="empty-title">{{ __('app.no_data') }}</div>
        </div>
        @endforelse
    </div>

    <div class="pagination">{{ $lectures->links() }}</div>
</div>

@push('scripts')
<script>
function deleteItem(id) {
    if (confirm('{{ app()->getLocale() === "ar" ? "هل تريد حذف هذه المحاضرة؟" : "Delete this lecture?" }}')) {
        const desktopForm = document.getElementById('delete-form-' + id);
        const mobileForm = document.getElementById('delete-form-mobile-' + id);
        (desktopForm || mobileForm).submit();
    }
}
</script>
@endpush
@endsection
