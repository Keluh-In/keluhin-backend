@extends('layouts.admin')

@section('title', 'Users')
@section('subtitle', 'Kelola akun dan lihat aktivitas pengaduan pengguna.')

@section('content')
@php
    $totalUsers = $users->count();
    $adminUsers = $users->where('role', 'admin')->count();
    $regularUsers = $users->where('role', 'user')->count();
@endphp

<div class="metric-strip">
    <div class="card mini-metric">
        <div>
            <div class="mini-metric-label">Total users</div>
            <div class="mini-metric-value">{{ number_format($totalUsers) }}</div>
        </div>
        <span class="mini-metric-icon"><i class="bi bi-people"></i></span>
    </div>

    <div class="card mini-metric">
        <div>
            <div class="mini-metric-label">Admin</div>
            <div class="mini-metric-value">{{ number_format($adminUsers) }}</div>
        </div>
        <span class="mini-metric-icon"><i class="bi bi-shield-check"></i></span>
    </div>

    <div class="card mini-metric">
        <div>
            <div class="mini-metric-label">Pengguna</div>
            <div class="mini-metric-value">{{ number_format($regularUsers) }}</div>
        </div>
        <span class="mini-metric-icon"><i class="bi bi-person"></i></span>
    </div>
</div>

<section class="card section-card">
    <div class="section-header">
        <div>
            <h2 class="section-title">Daftar Users</h2>
            <div class="section-subtitle">Akun terdaftar dan jumlah pengaduan yang dibuat.</div>
        </div>

        <a href="/admin/register" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            Tambah User
        </a>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th width="90">ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Pengaduan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="text-muted">{{ $user->id }}</td>
                        <td class="fw-semibold">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge-soft {{ $user->role === 'admin' ? 'badge-ditolak' : 'badge-selesai' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>{{ number_format($user->complaints_count ?? 0) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty-state">Belum ada user.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
