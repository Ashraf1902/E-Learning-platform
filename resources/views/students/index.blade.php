@extends('layouts.app')
@section('title', __('app.students'))

@section('content')
@php
    $avatarColors = ['#e05555','#3ec97a','#4a8cff','#d4a843','#a064f0','#28c8c8','#f07030','#e05599','#70b040','#5088e0'];
@endphp

<div class="toolbar">
    <span class="toolbar-label">{{ __('app.filter') }}:</span>
    <a href="{{ route('students.index') }}" class="chip {{ !request('level') ? 'active' : '' }}">{{ __('app.all_levels') }}</a>
    <a href="{{ route('students.index', ['level' => 'first']) }}" class="chip {{ request('level') === 'first' ? 'active' : '' }}">{{ __('app.first') }}</a>
    <a href="{{ route('students.index', ['level' => 'second']) }}" class="chip {{ request('level') === 'second' ? 'active' : '' }}">{{ __('app.second') }}</a>
    <a href="{{ route('students.index', ['level' => 'third']) }}" class="chip {{ request('level') === 'third' ? 'active' : '' }}">{{ __('app.third') }}</a>
    <span class="spacer"></span>
    <a href="{{ route('students.create') }}" class="btn btn-primary btn-sm"><x-icon name="plus" /> {{ __('app.add_student') }}</a>
</div>

<div class="card">
    <div class="table-wrap desktop-table">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('app.student_name') }}</th>
                    <th>{{ __('app.email') }}</th>
                    <th>{{ __('app.level') }}</th>
                    <th>{{ __('app.lectures') }}</th>
                    <th>{{ __('app.status') }}</th>
                    <th>{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                <tr>
                    <td class="num">{{ $student->id }}</td>
                    <td>
                        <a href="{{ route('students.show', $student) }}" style="display:flex;align-items:center;gap:10px;color:inherit;text-decoration:none">
                            <span class="avatar" style="background:{{ $avatarColors[$student->id % count($avatarColors)] }}">{{ strtoupper(mb_substr($student->name, 0, 1)) }}</span>
                            <span class="cell-strong">{{ $student->name }}</span>
                        </a>
                    </td>
                    <td class="cell-muted">{{ $student->email }}</td>
                    <td><span class="badge badge-gold">{{ $student->level_label }}</span></td>
                    <td><span class="badge badge-blue">{{ $student->lectures_count }}</span></td>
                    <td>
                        <span class="badge {{ $student->status === 'active' ? 'badge-green' : 'badge-red' }}">
                            {{ __('app.' . $student->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="cell-actions">
                            <a href="{{ route('students.show', $student) }}" class="btn btn-ghost btn-sm">{{ __('app.view') }}</a>
                            <a href="{{ route('students.edit', $student) }}" class="btn btn-ghost btn-sm">{{ __('app.edit') }}</a>
                            <button onclick="deleteItem({{ $student->id }})" class="btn btn-danger btn-sm">{{ __('app.delete') }}</button>
                        </div>
                        <form id="delete-form-{{ $student->id }}" method="POST" action="{{ route('students.destroy', $student) }}" style="display:none;">
                            @csrf @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <div class="icon"><x-icon name="users" /></div>
                            <div class="empty-title">{{ __('app.no_data') }}</div>
                            <div class="empty-hint">{{ app()->getLocale() === 'ar' ? 'أضف طالباً يدوياً أو شارك كود تسجيل ليُسجّل بنفسه.' : 'Add a student manually or share a registration code for self sign-up.' }}</div>
                            <a href="{{ route('students.create') }}" class="btn btn-ghost btn-sm"><x-icon name="plus" /> {{ __('app.add_student') }}</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mobile-card-list">
        @forelse($students as $student)
        <div class="mobile-row-card">
            <div class="mobile-row-top">
                <a href="{{ route('students.show', $student) }}" class="mobile-row-title">{{ $student->name }}</a>
                <span class="badge {{ $student->status === 'active' ? 'badge-green' : 'badge-red' }}">
                    {{ __('app.' . $student->status) }}
                </span>
            </div>
            <div class="mobile-row-badges">
                <span class="badge badge-gold">{{ $student->level_label }}</span>
                <span class="badge badge-blue">{{ $student->lectures_count }} {{ __('app.lectures') }}</span>
            </div>
            <div class="mobile-row-meta">{{ $student->email }}</div>
            <div class="mobile-row-actions">
                <a href="{{ route('students.show', $student) }}" class="btn btn-ghost btn-sm">{{ __('app.view') }}</a>
                <a href="{{ route('students.edit', $student) }}" class="btn btn-ghost btn-sm">{{ __('app.edit') }}</a>
                <button onclick="deleteItem({{ $student->id }})" class="btn btn-danger btn-sm">{{ __('app.delete') }}</button>
            </div>
            <form id="delete-form-mobile-{{ $student->id }}" method="POST" action="{{ route('students.destroy', $student) }}" style="display:none;">
                @csrf @method('DELETE')
            </form>
        </div>
        @empty
        <div class="empty-state">
            <div class="icon"><x-icon name="users" /></div>
            <div class="empty-title">{{ __('app.no_data') }}</div>
        </div>
        @endforelse
    </div>

    <div class="pagination">{{ $students->links() }}</div>
</div>

@push('scripts')
<script>
function deleteItem(id) {
    if (confirm('{{ app()->getLocale() === 'ar' ? 'هل تريد حذف هذا الطالب؟' : 'Delete this student?' }}')) {
        const desktopForm = document.getElementById('delete-form-' + id);
        const mobileForm = document.getElementById('delete-form-mobile-' + id);
        (desktopForm || mobileForm).submit();
    }
}
</script>
@endpush
@endsection
