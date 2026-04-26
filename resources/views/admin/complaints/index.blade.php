@extends('layouts.admin')

@section('title', 'Pengaduan')
@section('subtitle', 'Pantau laporan pengguna dan status penanganannya.')

@section('content')
@php
    $totalComplaints = $complaints->count();
    $waitingComplaints = $complaints->where('status', 'menunggu')->count();
    $activeComplaints = $complaints->where('status', 'diproses')->count();
@endphp

<div class="metric-strip">
    <div class="card mini-metric">
        <div>
            <div class="mini-metric-label">Total laporan</div>
            <div class="mini-metric-value">{{ number_format($totalComplaints) }}</div>
        </div>
        <span class="mini-metric-icon"><i class="bi bi-inbox"></i></span>
    </div>

    <div class="card mini-metric">
        <div>
            <div class="mini-metric-label">Menunggu</div>
            <div class="mini-metric-value">{{ number_format($waitingComplaints) }}</div>
        </div>
        <span class="mini-metric-icon"><i class="bi bi-hourglass-split"></i></span>
    </div>

    <div class="card mini-metric">
        <div>
            <div class="mini-metric-label">Diproses</div>
            <div class="mini-metric-value">{{ number_format($activeComplaints) }}</div>
        </div>
        <span class="mini-metric-icon"><i class="bi bi-arrow-repeat"></i></span>
    </div>
</div>

<section class="card section-card">
    <div class="section-header">
        <div>
            <h2 class="section-title">Daftar Pengaduan</h2>
            <div class="section-subtitle">Laporan terbaru ditampilkan paling atas.</div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th width="80">#</th>
                    <th>Judul</th>
                    <th>User</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($complaints as $complaint)
                    @php
                        $status = $complaint->status ?? 'menunggu';
                        $badgeClass = [
                            'menunggu' => 'badge-menunggu',
                            'diproses' => 'badge-diproses',
                            'selesai' => 'badge-selesai',
                            'ditolak' => 'badge-ditolak',
                        ][$status] ?? 'badge-menunggu';
                    @endphp
                    <tr>
                        <td class="text-muted">{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $complaint->title ?? '-' }}</td>
                        <td>{{ $complaint->is_anonymous ? 'Anonim' : ($complaint->user->name ?? '-') }}</td>
                        <td>
                            <span class="badge-soft {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                        </td>
                        <td>{{ optional($complaint->created_at)->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty-state">Belum ada data pengaduan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
