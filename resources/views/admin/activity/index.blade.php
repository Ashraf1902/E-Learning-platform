@extends('layouts.app')
@section('title', app()->getLocale() === 'ar' ? 'سجل نشاط الإدارة' : 'Admin Activity')

@section('content')
@php $isAr = app()->getLocale() === 'ar'; @endphp

<div class="page-head">
    <div>
        <h2><x-icon name="shield" /> {{ $isAr ? 'سجل نشاط الإدارة' : 'Admin Activity Log' }}</h2>
        <p>{{ $isAr ? 'آخر العمليات التي أجراها مديرو المنصة.' : 'Recent actions performed by platform administrators.' }}</p>
    </div>
</div>

<div class="card">
    <div class="table-wrap desktop-table">
        <table>
            <thead>
                <tr>
                    <th>{{ $isAr ? 'الوقت' : 'Time' }}</th>
                    <th>{{ $isAr ? 'المدير' : 'Admin' }}</th>
                    <th>{{ $isAr ? 'العملية' : 'Action' }}</th>
                    <th>{{ $isAr ? 'المسار' : 'Path' }}</th>
                    <th>{{ $isAr ? 'الحالة' : 'Status' }}</th>
                    <th>{{ $isAr ? 'IP' : 'IP' }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td class="cell-muted" style="font-size:0.8rem;white-space:nowrap">{{ $log->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <div class="cell-strong">{{ $log->user->name ?? ($isAr ? 'غير معروف' : 'Unknown') }}</div>
                        <div class="cell-sub">{{ $log->user->email ?? '' }}</div>
                    </td>
                    <td>
                        <span class="badge {{ in_array($log->method, ['DELETE'], true) ? 'badge-red' : (in_array($log->method, ['PUT', 'PATCH'], true) ? 'badge-yellow' : 'badge-blue') }}">{{ $log->method }}</span>
                        <span class="cell-muted" style="font-size:0.8rem;display:block;margin-top:5px">{{ $log->route ?: '—' }}</span>
                    </td>
                    <td class="cell-muted" style="font-size:0.8rem;word-break:break-all">{{ $log->path }}</td>
                    <td><span class="badge {{ ($log->status_code ?? 0) >= 400 ? 'badge-red' : 'badge-green' }}">{{ $log->status_code ?? '—' }}</span></td>
                    <td class="cell-muted" style="font-size:0.8rem" title="{{ $log->user_agent }}">{{ $log->ip ?? '—' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <div class="icon"><x-icon name="shield" /></div>
                            <div class="empty-title">{{ $isAr ? 'لا يوجد نشاط بعد' : 'No activity yet' }}</div>
                            <div class="empty-hint">{{ $isAr ? 'ستظهر هنا عمليات الإضافة والتعديل والحذف التي يجريها المديرون.' : 'Admin create, update and delete actions will appear here.' }}</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mobile-card-list">
        @forelse($logs as $log)
        <div class="mobile-row-card">
            <div class="mobile-row-top">
                <div style="min-width:0">
                    <div class="mobile-row-title">{{ $log->user->name ?? ($isAr ? 'غير معروف' : 'Unknown') }}</div>
                    <div class="cell-sub">{{ $log->created_at->format('d M Y H:i') }}</div>
                </div>
                <span class="badge {{ in_array($log->method, ['DELETE'], true) ? 'badge-red' : (in_array($log->method, ['PUT', 'PATCH'], true) ? 'badge-yellow' : 'badge-blue') }}">{{ $log->method }}</span>
            </div>
            <div class="mobile-row-meta" style="word-break:break-all">{{ $log->route ?: $log->path }}</div>
            <div class="mobile-row-meta">{{ $log->ip ?? '—' }} · {{ $log->status_code ?? '—' }}</div>
        </div>
        @empty
        <div class="empty-state">
            <div class="icon"><x-icon name="shield" /></div>
            <div class="empty-title">{{ $isAr ? 'لا يوجد نشاط بعد' : 'No activity yet' }}</div>
        </div>
        @endforelse
    </div>

    <div class="pagination">{{ $logs->links() }}</div>
</div>
@endsection
