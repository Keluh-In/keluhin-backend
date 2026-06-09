@extends('layouts.admin')

@section('title', 'Manajemen Admin')
@section('subtitle', 'Kelola akun admin internal dan akses super admin.')

@section('content')
@php
    $totalAdmins = $users->count();
    $adminUsers = $users->where('role', 'admin')->count();
    $superAdminUsers = $users->where('role', 'super_admin')->count();
    $bannedUsers = $users->whereNotNull('banned_at')->count();
@endphp
<style>
    .mini-metric {
        text-align: center !important; /* Memastikan isi kartu rata tengah */
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .mini-metric-value {
        font-style: normal !important;
        font-family: sans-serif !important;
        font-weight: 600;
        margin: 5px 0;
    }
</style>

<div class="metric-strip">
    <div class="card mini-metric">
        <div>
            <div class="mini-metric-label">Total akun admin</div>
            <div class="mini-metric-value">{{ number_format($totalAdmins) }}</div>
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
            <div class="mini-metric-label">Super Admin</div>
            <div class="mini-metric-value">{{ number_format($superAdminUsers) }}</div>
        </div>
        <span class="mini-metric-icon"><i class="bi bi-shield-lock"></i></span>
    </div>

    <div class="card mini-metric">
        <div>
            <div class="mini-metric-label">Diban</div>
            <div class="mini-metric-value">{{ number_format($bannedUsers) }}</div>
        </div>
        <span class="mini-metric-icon"><i class="bi bi-slash-circle"></i></span>
    </div>
</div>

<section class="card section-card">
    <div class="section-header">
        <div>
            <h2 class="section-title">Daftar Admin</h2>
            <div class="section-subtitle">Akun internal untuk operasional dashboard.</div>
        </div>

        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#createAdminModal">
            <i class="bi bi-plus-lg"></i>
            Tambah Admin
        </button>
    </div>

    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th width="90">ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    @php
                        $roleBadge = $user->role === 'super_admin' ? 'badge-diproses' : 'badge-ditolak';
                    @endphp
                    <tr>
                        <td class="text-muted">{{ $user->id }}</td>
                        <td class="fw-semibold">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge-soft {{ $roleBadge }}">
                                {{ $user->roleLabel() }}
                            </span>
                        </td>
                        <td>
                            @if($user->isBanned())
                                <span class="badge-soft badge-ditolak">Diban</span>
                            @else
                                <span class="badge-soft badge-selesai">Aktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="table-actions">
                                <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#editAdminModal-{{ $user->id }}">
                                    Update
                                </button>

                                @if($user->isBanned())
                                    <form method="POST" action="{{ route('admin.admin-users.unban', $user) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-primary" type="submit">Unban</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.admin-users.ban', $user) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-soft-danger" type="submit" @disabled(auth()->id() === $user->id)>Ban</button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('admin.admin-users.destroy', $user) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-soft-danger" type="submit" @disabled(auth()->id() === $user->id)>Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-state">Belum ada akun admin.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

@endsection
