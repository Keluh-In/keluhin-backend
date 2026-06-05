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

<div class="metric-strip">
    <div class="card mini-metric">
        <div>
            <div class="mini-metric-label">Total pengguna aplikasi</div>
            <div class="mini-metric-value">{{ number_format($totalUsers) }}</div>
        </div>
        <span class="mini-metric-icon"><i class="bi bi-people"></i></span>
    </div>

    <div class="card mini-metric">
        <div>
            <div class="mini-metric-label">Aktif</div>
            <div class="mini-metric-value">{{ number_format($activeUsers) }}</div>
        </div>
        <span class="mini-metric-icon"><i class="bi bi-check-circle"></i></span>
    </div>

    <div class="card mini-metric">
        <div>
            <div class="mini-metric-label">Total pengaduan</div>
            <div class="mini-metric-value">{{ number_format($totalComplaints) }}</div>
        </div>
        <span class="mini-metric-icon"><i class="bi bi-chat-dots"></i></span>
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
            <h2 class="section-title">Daftar Pengguna</h2>
            <div class="section-subtitle">Akun end-user mobile app dan jumlah pengaduan yang dibuat.</div>
        </div>

        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#createUserModal">
            <i class="bi bi-plus-lg"></i>
            Tambah Pengguna
        </button>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
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
                            @if($user->isBanned())
                                <span class="badge-soft badge-ditolak">Diban</span>
                            @else
                                <span class="badge-soft badge-selesai">Aktif</span>
                            @endif
                        </td>
                        <td>{{ number_format($user->complaints_count ?? 0) }}</td>
                        <td>
                            <div class="table-actions">
                                <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#editUserModal-{{ $user->id }}">
                                    Update
                                </button>

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
                    <tr>
                        <td colspan="6" class="empty-state">Belum ada pengguna aplikasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="modal-header">
                <h2 class="modal-title" id="createUserModalLabel">Tambah Pengguna</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label" for="create_user_name">Nama</label>
                    <input id="create_user_name" type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="create_user_email">Email</label>
                    <input id="create_user_email" type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="create_user_password">Password</label>
                    <input id="create_user_password" type="password" name="password" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" type="submit">Tambah Pengguna</button>
            </div>
        </form>
    </div>
</div>

@foreach($users as $user)
    <div class="modal fade" id="editUserModal-{{ $user->id }}" tabindex="-1" aria-labelledby="editUserModalLabel-{{ $user->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h2 class="modal-title" id="editUserModalLabel-{{ $user->id }}">Update Pengguna</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="edit_user_name_{{ $user->id }}">Nama</label>
                        <input id="edit_user_name_{{ $user->id }}" type="text" name="name" value="{{ $user->name }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="edit_user_email_{{ $user->id }}">Email</label>
                        <input id="edit_user_email_{{ $user->id }}" type="email" name="email" value="{{ $user->email }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="edit_user_password_{{ $user->id }}">Password Baru</label>
                        <input id="edit_user_password_{{ $user->id }}" type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endforeach
@endsection
