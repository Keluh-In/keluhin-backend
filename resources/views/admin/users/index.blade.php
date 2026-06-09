@extends('layouts.admin')

@section('title', 'Pengguna Aplikasi')
@section('subtitle', 'Kelola akun end-user mobile app dan lihat aktivitas pengaduan.')

@section('content')
@php
    $totalUsers = $users->count();
    $bannedUsers = $users->whereNotNull('banned_at')->count();
    $activeUsers = $totalUsers - $bannedUsers;
    $totalComplaints = $users->sum('complaints_count');
@endphp

<style>
    /* CSS untuk memastikan angka tegak lurus dan rata tengah */
    .mini-metric {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        text-align: center !important;
    }
    .mini-metric-value {
        font-style: normal !important;
        font-family: sans-serif !important;
        font-weight: 700;
        font-size: 1.5rem;
    }
</style>

<div class="metric-strip">
    <div class="card mini-metric">
        <div class="mini-metric-label">Total pengguna aplikasi</div>
        <div class="mini-metric-value">{{ number_format($totalUsers) }}</div>
        <span class="mt-2 mini-metric-icon"><i class="bi bi-people"></i></span>
    </div>

    <div class="card mini-metric">
        <div class="mini-metric-label">Aktif</div>
        <div class="mini-metric-value">{{ number_format($activeUsers) }}</div>
        <span class="mt-2 mini-metric-icon"><i class="bi bi-check-circle"></i></span>
    </div>

    <div class="card mini-metric">
        <div class="mini-metric-label">Total pengaduan</div>
        <div class="mini-metric-value">{{ number_format($totalComplaints) }}</div>
        <span class="mt-2 mini-metric-icon"><i class="bi bi-chat-dots"></i></span>
    </div>

    <div class="card mini-metric">
        <div class="mini-metric-label">Diban</div>
        <div class="mini-metric-value">{{ number_format($bannedUsers) }}</div>
        <span class="mt-2 mini-metric-icon"><i class="bi bi-slash-circle"></i></span>
    </div>
</div>

<section class="card section-card">
    <div class="p-4 section-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="section-title">Daftar Pengguna</h2>
            <div class="section-subtitle">Akun end-user mobile app dan jumlah pengaduan yang dibuat.</div>
        </div>

        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#createUserModal">
            <i class="bi bi-plus-lg"></i> Tambah Pengguna
        </button>
    </div>

    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th width="90">ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Pengaduan</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="text-muted">{{ $user->id }}</td>
                        <td class="fw-semibold">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge-soft {{ $user->isBanned() ? 'badge-ditolak' : 'badge-selesai' }}">
                                {{ $user->isBanned() ? 'Diban' : 'Aktif' }}
                            </span>
                        </td>
                        <td>{{ number_format($user->complaints_count ?? 0) }}</td>
                        <td class="text-end">
                            <div class="gap-1 d-flex justify-content-end">
                                <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#editUserModal-{{ $user->id }}">Update</button>

                                @if($user->isBanned())
                                    <form method="POST" action="{{ route('admin.users.unban', $user) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-primary" type="submit">Unban</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.users.ban', $user) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-soft-danger" type="submit" @disabled(auth()->id() === $user->id)>Ban</button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-soft-danger" type="submit" @disabled(auth()->id() === $user->id)>Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty-state">Belum ada pengguna aplikasi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

{{-- Bagian Modal tetap sama --}}

@endsection
