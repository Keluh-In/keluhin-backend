@extends('layouts.admin')

@section('title', 'Audit Log')
@section('subtitle', 'Pantau aksi penting yang dilakukan dari Admin Dashboard.')

@section('content')
<style>
    .audit-json {
        max-width: 360px;
        max-height: 180px;
        overflow: auto;
        margin: 0;
        padding: 12px;
        background: #f8fafc;
        border: 1px solid #edf1f5;
        border-radius: 8px;
        color: #374151;
        font-size: .78rem;
        white-space: pre-wrap;
    }
</style>

<section class="card section-card">
    <div class="section-header">
        <div>
            <h2 class="section-title">Riwayat Audit</h2>
            <div class="section-subtitle">Aksi terbaru ditampilkan paling atas.</div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Aktor</th>
                    <th>Aksi</th>
                    <th>Resource</th>
                    <th>Sebelum</th>
                    <th>Sesudah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="text-muted">{{ optional($log->created_at)->format('d M Y H:i') }}</td>
                        <td>
                            <div class="fw-semibold">{{ $log->actor->name ?? 'Sistem' }}</div>
                            <div class="section-subtitle">{{ $log->actor->email ?? '-' }}</div>
                        </td>
                        <td><span class="badge-soft badge-diproses">{{ $log->action }}</span></td>
                        <td>
                            <div class="fw-semibold">{{ $log->auditable_type ? class_basename($log->auditable_type) : '-' }}</div>
                            <div class="section-subtitle">ID: {{ $log->auditable_id ?? '-' }}</div>
                        </td>
                        <td>
                            @if($log->old_values)
                                <pre class="audit-json">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($log->new_values)
                                <pre class="audit-json">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-state">Belum ada audit log.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<div class="mt-4">
    {{ $logs->links() }}
</div>
@endsection
