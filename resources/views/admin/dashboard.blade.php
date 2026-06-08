@extends('layouts.admin')

@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan pengaduan, status kerja, dan aktivitas terbaru.')

@section('content')
@php
    $totalComplaint = $totalComplaint ?? 0;
    $pending = $pending ?? 0;
    $proses = $proses ?? 0;
    $selesai = $selesai ?? 0;
    $ditolak = $ditolak ?? 0;
    $users = $users ?? 0;
    $activeComplaints = $pending + $proses;
    $completionRate = $totalComplaint > 0 ? round(($selesai / $totalComplaint) * 100) : 0;

    $statCards = [
<<<<<<< HEAD
        ['label' => 'Total Pengaduan', 'value' => $totalComplaint, 'note' => 'Semua laporan masuk', 'icon' => 'bi-inbox', 'tone' => 'blue'],
        ['label' => 'Menunggu', 'value' => $pending, 'note' => 'Perlu ditinjau', 'icon' => 'bi-hourglass-split', 'tone' => 'amber'],
        ['label' => 'Diproses', 'value' => $proses, 'note' => 'Sedang ditangani', 'icon' => 'bi-arrow-repeat', 'tone' => 'cyan'],
        ['label' => 'Selesai', 'value' => $selesai, 'note' => $completionRate . '% penyelesaian', 'icon' => 'bi-check2-circle', 'tone' => 'green'],
=======
        [
            'label' => 'Total Pengaduan',
            'value' => $totalComplaint,
            'note' => 'Semua laporan masuk',
            'icon' => 'bi-inbox',
            'tone' => 'blue',
        ],
        [
            'label' => 'Menunggu',
            'value' => $pending,
            'note' => 'Perlu ditinjau',
            'icon' => 'bi-hourglass-split',
            'tone' => 'amber',
        ],
        [
            'label' => 'Diproses',
            'value' => $proses,
            'note' => 'Sedang ditangani',
            'icon' => 'bi-arrow-repeat',
            'tone' => 'cyan',
        ],
        [
            'label' => 'Selesai',
            'value' => $selesai,
            'note' => $completionRate . '% penyelesaian',
            'icon' => 'bi-check2-circle',
            'tone' => 'green',
        ],
>>>>>>> origin/main
    ];

    $statusRows = [
        ['label' => 'Menunggu', 'value' => $pending, 'class' => 'bar-amber'],
        ['label' => 'Diproses', 'value' => $proses, 'class' => 'bar-blue'],
        ['label' => 'Selesai', 'value' => $selesai, 'class' => 'bar-green'],
        ['label' => 'Ditolak', 'value' => $ditolak, 'class' => 'bar-red'],
    ];
@endphp

<<<<<<< HEAD
{{-- (CSS style Anda tetap sama seperti di atas) --}}
<style>
    .dashboard-stack { display: grid; gap: 24px; }
    .stats-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; }
    .stat-card { padding: 22px; min-height: 150px; }
    .stat-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; }
    .stat-label { color: #6b7280; font-size: .88rem; font-weight: 600; }
    .stat-value { margin-top: 18px; color: #111827; font-size: 2.15rem; font-weight: 700; line-height: 1; }
    .stat-note { margin-top: 9px; color: #94a3b8; font-size: .84rem; }
    .stat-icon { display: inline-flex; width: 42px; height: 42px; align-items: center; justify-content: center; border-radius: 8px; font-size: 1.1rem; }
    .tone-blue { background: #eff6ff; color: #2563eb; }
    .tone-amber { background: #fff7ed; color: #c2410c; }
    .tone-cyan { background: #ecfeff; color: #0e7490; }
    .tone-green { background: #ecfdf5; color: #047857; }
    .analytics-grid { display: grid; grid-template-columns: minmax(0, 1.35fr) minmax(280px, .65fr); gap: 16px; }
    .panel { padding: 24px; }
    .panel-heading { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; margin-bottom: 22px; }
    .panel-title { margin: 0; font-size: 1.02rem; font-weight: 700; color: #111827; }
    .panel-subtitle { margin-top: 4px; color: #6b7280; font-size: .88rem; }
    .completion-ring { display: inline-flex; align-items: center; justify-content: center; width: 70px; height: 70px; border-radius: 50%; background: conic-gradient(#2563eb {{ $completionRate }}%, #eef2f7 0); }
    .completion-ring span { display: inline-flex; align-items: center; justify-content: center; width: 54px; height: 54px; border-radius: 50%; background: #fff; color: #111827; font-size: .95rem; font-weight: 700; }
    .status-list { display: grid; gap: 18px; }
    .status-row { display: grid; grid-template-columns: 96px minmax(0, 1fr) 34px; align-items: center; gap: 14px; color: #4b5563; font-size: .9rem; }
    .status-track { height: 8px; overflow: hidden; border-radius: 999px; background: #f1f5f9; }
    .status-bar { height: 100%; border-radius: inherit; }
    .bar-amber { background: #f59e0b; } .bar-blue { background: #3b82f6; } .bar-green { background: #10b981; } .bar-red { background: #ef4444; }
    .summary-list { display: grid; gap: 14px; }
    .summary-item { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding-bottom: 14px; border-bottom: 1px solid #edf1f5; }
    .summary-item:last-child { padding-bottom: 0; border-bottom: 0; }
    .summary-label { color: #6b7280; font-size: .88rem; }
    .summary-value { color: #111827; font-size: 1.1rem; font-weight: 700; }
    .table-card { overflow: hidden; }
    .table-card-header { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 22px 24px; border-bottom: 1px solid #edf1f5; }
    .table-action { color: #2563eb; font-size: .88rem; font-weight: 600; text-decoration: none; }
    .table-action:hover { color: #1d4ed8; }
    .empty-state { padding: 44px 24px; color: #94a3b8; text-align: center; }
</style>

<div class="dashboard-stack">
    {{-- STATISTIK --}}
    <section class="stats-grid">
=======
<style>
    .dashboard-stack {
        display: grid;
        gap: 24px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }

    .stat-card {
        padding: 22px;
        min-height: 150px;
    }

    .stat-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
    }

    .stat-label {
        color: #6b7280;
        font-size: .88rem;
        font-weight: 600;
    }

    .stat-value {
        margin-top: 18px;
        color: #111827;
        font-size: 2.15rem;
        font-weight: 700;
        letter-spacing: 0;
        line-height: 1;
    }

    .stat-note {
        margin-top: 9px;
        color: #94a3b8;
        font-size: .84rem;
    }

    .stat-icon {
        display: inline-flex;
        width: 42px;
        height: 42px;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-size: 1.1rem;
    }

    .tone-blue {
        background: #eff6ff;
        color: #2563eb;
    }

    .tone-amber {
        background: #fff7ed;
        color: #c2410c;
    }

    .tone-cyan {
        background: #ecfeff;
        color: #0e7490;
    }

    .tone-green {
        background: #ecfdf5;
        color: #047857;
    }

    .analytics-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.35fr) minmax(280px, .65fr);
        gap: 16px;
    }

    .panel {
        padding: 24px;
    }

    .panel-heading {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 22px;
    }

    .panel-title {
        margin: 0;
        font-size: 1.02rem;
        font-weight: 700;
        color: #111827;
    }

    .panel-subtitle {
        margin-top: 4px;
        color: #6b7280;
        font-size: .88rem;
    }

    .completion-ring {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: conic-gradient(#2563eb {{ $completionRate }}%, #eef2f7 0);
    }

    .completion-ring span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: #fff;
        color: #111827;
        font-size: .95rem;
        font-weight: 700;
    }

    .status-list {
        display: grid;
        gap: 18px;
    }

    .status-row {
        display: grid;
        grid-template-columns: 96px minmax(0, 1fr) 34px;
        align-items: center;
        gap: 14px;
        color: #4b5563;
        font-size: .9rem;
    }

    .status-track {
        height: 8px;
        overflow: hidden;
        border-radius: 999px;
        background: #f1f5f9;
    }

    .status-bar {
        height: 100%;
        border-radius: inherit;
    }

    .bar-amber {
        background: #f59e0b;
    }

    .bar-blue {
        background: #3b82f6;
    }

    .bar-green {
        background: #10b981;
    }

    .bar-red {
        background: #ef4444;
    }

    .summary-list {
        display: grid;
        gap: 14px;
    }

    .summary-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding-bottom: 14px;
        border-bottom: 1px solid #edf1f5;
    }

    .summary-item:last-child {
        padding-bottom: 0;
        border-bottom: 0;
    }

    .summary-label {
        color: #6b7280;
        font-size: .88rem;
    }

    .summary-value {
        color: #111827;
        font-size: 1.1rem;
        font-weight: 700;
    }

    .table-card {
        overflow: hidden;
    }

    .table-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 22px 24px;
        border-bottom: 1px solid #edf1f5;
    }

    .table-action {
        color: #2563eb;
        font-size: .88rem;
        font-weight: 600;
        text-decoration: none;
    }

    .table-action:hover {
        color: #1d4ed8;
    }

    .empty-state {
        padding: 44px 24px;
        color: #94a3b8;
        text-align: center;
    }

    @media (max-width: 1199.98px) {
        .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767.98px) {
        .stats-grid,
        .analytics-grid {
            grid-template-columns: 1fr;
        }

        .stat-card,
        .panel {
            padding: 20px;
        }

        .panel-heading,
        .table-card-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .status-row {
            grid-template-columns: 82px minmax(0, 1fr) 30px;
            gap: 10px;
        }
    }
</style>

<div class="dashboard-stack">
    <section class="stats-grid" aria-label="Ringkasan analytics">
>>>>>>> origin/main
        @foreach($statCards as $card)
            <article class="card stat-card">
                <div class="stat-top">
                    <div class="stat-label">{{ $card['label'] }}</div>
<<<<<<< HEAD
                    <span class="stat-icon tone-{{ $card['tone'] }}"><i class="bi {{ $card['icon'] }}"></i></span>
=======
                    <span class="stat-icon tone-{{ $card['tone'] }}">
                        <i class="bi {{ $card['icon'] }}"></i>
                    </span>
>>>>>>> origin/main
                </div>
                <div class="stat-value">{{ number_format($card['value']) }}</div>
                <div class="stat-note">{{ $card['note'] }}</div>
            </article>
        @endforeach
    </section>

<<<<<<< HEAD
    {{-- ANALYTICS GRID --}}
    <section class="analytics-grid">
=======
    <section class="analytics-grid" aria-label="Analitik status">
>>>>>>> origin/main
        <article class="card panel">
            <div class="panel-heading">
                <div>
                    <h2 class="panel-title">Distribusi Status</h2>
                    <div class="panel-subtitle">Perbandingan jumlah laporan berdasarkan tahap penanganan.</div>
                </div>
<<<<<<< HEAD
                <div class="completion-ring"><span>{{ $completionRate }}%</span></div>
            </div>
=======

                <div class="completion-ring" aria-label="Tingkat penyelesaian {{ $completionRate }} persen">
                    <span>{{ $completionRate }}%</span>
                </div>
            </div>

>>>>>>> origin/main
            <div class="status-list">
                @foreach($statusRows as $row)
                    @php
                        $width = $totalComplaint > 0 ? max(4, round(($row['value'] / $totalComplaint) * 100)) : 0;
<<<<<<< HEAD
                    @endphp
                    <div class="status-row">
                        <span>{{ $row['label'] }}</span>
                        <div class="status-track"><div class="status-bar {{ $row['class'] }}" style="width: {{ $width }}%;"></div></div>
=======
                        $barClass = $row['class'];
                    @endphp
                    <div class="status-row">
                        <span>{{ $row['label'] }}</span>
                        <div class="status-track" aria-hidden="true">
                            <div class="status-bar {{ $barClass }}" style="width: {{ $width }}%;"></div>
                        </div>
>>>>>>> origin/main
                        <strong>{{ $row['value'] }}</strong>
                    </div>
                @endforeach
            </div>
        </article>

        <aside class="card panel">
            <div class="panel-heading">
                <div>
                    <h2 class="panel-title">Ringkasan Sistem</h2>
                    <div class="panel-subtitle">Snapshot singkat untuk pemantauan harian.</div>
                </div>
            </div>
<<<<<<< HEAD
            <div class="summary-list">
                <div class="summary-item"><span class="summary-label">Pengaduan aktif</span><span class="summary-value">{{ number_format($activeComplaints) }}</span></div>
                <div class="summary-item"><span class="summary-label">Pengguna terdaftar</span><span class="summary-value">{{ number_format($users) }}</span></div>
                <div class="summary-item"><span class="summary-label">Ditolak</span><span class="summary-value">{{ number_format($ditolak) }}</span></div>
=======

            <div class="summary-list">
                <div class="summary-item">
                    <span class="summary-label">Pengaduan aktif</span>
                    <span class="summary-value">{{ number_format($activeComplaints) }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Pengguna terdaftar</span>
                    <span class="summary-value">{{ number_format($users) }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Ditolak</span>
                    <span class="summary-value">{{ number_format($ditolak) }}</span>
                </div>
>>>>>>> origin/main
            </div>
        </aside>
    </section>

<<<<<<< HEAD
    {{-- TABEL PENGADUAN DENGAN SEARCH --}}
=======
>>>>>>> origin/main
    <section class="card table-card">
        <div class="table-card-header">
            <div>
                <h2 class="panel-title">Pengaduan Terbaru</h2>
                <div class="panel-subtitle">Laporan terbaru yang masuk ke sistem.</div>
            </div>

<<<<<<< HEAD
            <form method="GET" action="{{ route('admin.dashboard') }}" class="d-flex" style="gap: 10px;">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari judul..." style="width: 200px;">
                <button type="submit" class="btn btn-sm btn-primary">Cari</button>
            </form>

            <a class="table-action" href="/admin/complaints">Lihat semua <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="table-responsive">
            <table class="table mb-0 align-middle">
=======
            <a class="table-action" href="/admin/complaints">
                Lihat semua
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
>>>>>>> origin/main
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Pelapor</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($latestComplaints ?? []) as $complaint)
                        @php
                            $status = $complaint->status ?? 'menunggu';
<<<<<<< HEAD
                            $badgeClass = ['menunggu'=>'badge-menunggu','diproses'=>'badge-diproses','selesai'=>'badge-selesai','ditolak'=>'badge-ditolak'][$status] ?? 'badge-menunggu';
=======
                            $badgeClass = [
                                'menunggu' => 'badge-menunggu',
                                'diproses' => 'badge-diproses',
                                'selesai' => 'badge-selesai',
                                'ditolak' => 'badge-ditolak',
                            ][$status] ?? 'badge-menunggu';
>>>>>>> origin/main
                        @endphp
                        <tr>
                            <td class="fw-semibold">{{ $complaint->title ?? '-' }}</td>
                            <td>{{ $complaint->is_anonymous ? 'Anonim' : ($complaint->user->name ?? '-') }}</td>
                            <td>{{ $complaint->category->name ?? '-' }}</td>
<<<<<<< HEAD
                            <td><span class="badge-soft {{ $badgeClass }}">{{ ucfirst($status) }}</span></td>
                            <td>{{ optional($complaint->created_at)->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty-state">Belum ada pengaduan terbaru.</td></tr>
=======
                            <td>
                                <span class="badge-soft {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                            </td>
                            <td>{{ optional($complaint->created_at)->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-state">
                                Belum ada pengaduan terbaru.
                            </td>
                        </tr>
>>>>>>> origin/main
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
