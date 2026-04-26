@extends('layouts.admin')

@section('title', 'Kategori')
@section('subtitle', 'Kelola kelompok laporan agar pengaduan mudah dipantau.')

@section('content')
@php
    $totalCategories = $categories->count();
@endphp

<div class="metric-strip">
    <div class="card mini-metric">
        <div>
            <div class="mini-metric-label">Total kategori</div>
            <div class="mini-metric-value">{{ number_format($totalCategories) }}</div>
        </div>
        <span class="mini-metric-icon"><i class="bi bi-folder2-open"></i></span>
    </div>

    <div class="card mini-metric">
        <div>
            <div class="mini-metric-label">Status aktif</div>
            <div class="mini-metric-value">{{ number_format($totalCategories) }}</div>
        </div>
        <span class="mini-metric-icon"><i class="bi bi-check2-circle"></i></span>
    </div>

    <div class="card mini-metric">
        <div>
            <div class="mini-metric-label">Terbaru</div>
            <div class="mini-metric-value">{{ optional($categories->first())->id ?? 0 }}</div>
        </div>
        <span class="mini-metric-icon"><i class="bi bi-clock-history"></i></span>
    </div>
</div>

<section class="card section-card">
    <div class="section-header">
        <div>
            <h2 class="section-title">Daftar Kategori</h2>
            <div class="section-subtitle">Kategori yang tersedia untuk laporan pengguna.</div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th width="90">ID</th>
                    <th>Nama Kategori</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td class="text-muted">{{ $category->id }}</td>
                        <td class="fw-semibold">{{ $category->name }}</td>
                        <td>
                            <span class="badge-soft badge-selesai">Aktif</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="empty-state">Belum ada kategori.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
