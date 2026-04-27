@extends('layouts.admin')

@section('title', 'Pengguna')
@section('subtitle', 'Kelola akun dan lihat aktivitas pengaduan pengguna.')

@section('content')
@php
    $totalUsers = $users->count();
    $adminUsers = $users->where('role', 'admin')->count();
    $superAdminUsers = $users->where('role', 'super_admin')->count();
    $bannedUsers = $users->whereNotNull('banned_at')->count();
@endphp

<div class="metric-strip">
    <div class="card mini-metric">
        <div>
            <div class="mini-metric-label">Total pengguna</div>
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
            <h2 class="section-title">Daftar Pengguna</h2>
            <div class="section-subtitle">Akun terdaftar dan jumlah pengaduan yang dibuat.</div>
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
                    <th>Role</th>
                    <th>Status</th>
                    <th>Pengaduan</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    @php
                        $canManageUser = $user->role === 'user' || auth()->user()->isSuperAdmin();
                        $roleBadge = match ($user->role) {
                            'super_admin' => 'badge-diproses',
                            'admin' => 'badge-ditolak',
                            default => 'badge-selesai',
                        };
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
                        <td>{{ number_format($user->complaints_count ?? 0) }}</td>
                        <td>
                            <div class="table-actions">
                                <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#editUserModal-{{ $user->id }}" @disabled(! $canManageUser)>
                                    Update
                                </button>

                                @if($user->isBanned())
                                    <form method="POST" action="{{ route('admin.users.unban', $user) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-primary" type="submit" @disabled(! $canManageUser)>Unban</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.users.ban', $user) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-soft-danger" type="submit" @disabled(auth()->id() === $user->id || ! $canManageUser)>Ban</button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-soft-danger" type="submit" @disabled(auth()->id() === $user->id || ! $canManageUser)>Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-state">Belum ada pengguna.</td>
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

                <div>
                    <label class="form-label" for="create_user_role">Role</label>
                    <select id="create_user_role" name="role" class="form-select" required>
                        @foreach($roleOptions as $roleValue => $roleLabel)
                            <option value="{{ $roleValue }}" @selected(old('role', 'user') === $roleValue)>{{ $roleLabel }}</option>
                        @endforeach
                    </select>
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
    @continue($user->role !== 'user' && ! auth()->user()->isSuperAdmin())

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

                    <div>
                        <label class="form-label" for="edit_user_role_{{ $user->id }}">Role</label>
                        <select id="edit_user_role_{{ $user->id }}" name="role" class="form-select" required>
                            @foreach($roleOptions as $roleValue => $roleLabel)
                                <option value="{{ $roleValue }}" @selected($user->role === $roleValue)>{{ $roleLabel }}</option>
                            @endforeach
                        </select>
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
