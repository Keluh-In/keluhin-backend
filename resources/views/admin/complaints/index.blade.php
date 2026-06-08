@extends('layouts.admin')

@section('title', 'Pengaduan')
@section('subtitle', 'Pantau laporan pengguna dan status penanganannya.')

@section('content')
@php
    $groupedComplaints = [
        'menunggu' => $complaints->where('status', 'menunggu'),
        'diproses' => $complaints->where('status', 'diproses'),
        'selesai'  => $complaints->where('status', 'selesai'),
        'ditolak'  => $complaints->where('status', 'ditolak'),
    ];

    $statusConfig = [
        'menunggu' => [
            'title' => 'Menunggu',
            'color' => 'warning',
            'icon' => 'bi-hourglass-split',
            'emoji' => '⌛',
            'textColor' => '#f59e0b',
        ],
        'diproses' => [
            'title' => 'Diproses',
            'color' => 'primary',
            'icon' => 'bi-arrow-repeat',
            'emoji' => '🔄',
            'textColor' => '#2563eb',
        ],
        'selesai' => [
            'title' => 'Selesai',
            'color' => 'success',
            'icon' => 'bi-check-circle-fill',
            'emoji' => '✅',
            'textColor' => '#16a34a',
        ],
        'ditolak' => [
            'title' => 'Ditolak',
            'color' => 'danger',
            'icon' => 'bi-x-circle-fill',
            'emoji' => '🚫',
            'textColor' => '#dc2626',
        ],
    ];
@endphp

<!-- Gaya Kustom CSS untuk Presisi Desain Mirip Gambar -->
<style>
    .stat-card-container {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    @media (min-width: 768px) {
        .stat-card-container {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }
    .stat-card {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .stat-label {
        font-size: 0.75rem;
        font-weight: 500;
        color: #64748b;
        letter-spacing: 0.5px;
    }
    .stat-value {
        font-size: 2.25rem;
        font-weight: 700;
        color: #0f172a;
        margin-top: 4px;
        margin-bottom: 12px;
    }
    .stat-icon-wrapper {
        width: 36px;
        height: 36px;
        background-color: #eff6ff;
        color: #2563eb;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .main-table-card {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        margin-bottom: 3rem;
    }
    .status-badge-title {
        font-size: 0.875rem;
        font-weight: 600;
    }
    .custom-table thead {
        background-color: #f8fafc;
    }
    .custom-table th {
        font-size: 0.7rem;
        font-weight: 600;
        color: #64748b;
        letter-spacing: 0.5px;
        padding: 12px 16px;
        border-bottom: 1px solid #f1f5f9;
    }
    .custom-table td {
        font-size: 0.8rem;
        color: #334155;
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
    }
    .btn-action-detail {
        background-color: #ffffff;
        color: #475569;
        border: 1px solid #e2e8f0;
        font-size: 0.75rem;
        font-weight: 500;
        padding: 4px 12px;
        border-radius: 6px;
        transition: all 0.15s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }
    .btn-action-detail:hover {
        background-color: #f8fafc;
        color: #1e293b;
    }
    .btn-action-update {
        background-color: #2563eb;
        color: #ffffff;
        border: none;
        font-size: 0.75rem;
        font-weight: 500;
        padding: 5px 12px;
        border-radius: 6px;
        transition: all 0.15s ease;
    }
    .btn-action-update:hover {
        background-color: #1d4ed8;
    }
    .btn-action-delete {
        background-color: #fff5f5;
        color: #e53e3e;
        border: 1px solid #fed7d7;
        font-size: 0.75rem;
        font-weight: 500;
        padding: 4px 12px;
        border-radius: 6px;
        transition: all 0.15s ease;
    }
    .btn-action-delete:hover {
        background-color: #fff0f0;
        color: #c53030;
    }
    .btn-add-complaint {
        background-color: #2563eb;
        color: #ffffff;
        border: none;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.15s ease;
        text-decoration: none;
    }
    .btn-add-complaint:hover {
        background-color: #1d4ed8;
        color: #ffffff;
    }
</style>

<!-- 1. BARIS STATISTIK UTAMA (Persis seperti gambar) -->
<div class="stat-card-container">
    <!-- Total Laporan -->
    <div class="stat-card">
        <span class="stat-label">Total laporan</span>
        <span class="stat-value">{{ $complaints->count() }}</span>
        <div class="stat-icon-wrapper">
            <svg class="w-5 h-5" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
        </div>
    </div>
    <!-- Menunggu -->
    <div class="stat-card">
        <span class="stat-label">Menunggu</span>
        <span class="stat-value">{{ $groupedComplaints['menunggu']->count() }}</span>
        <div class="stat-icon-wrapper" style="background-color: #fffbeb; color: #d97706;">
            <svg class="w-5 h-5" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>
    <!-- Diproses -->
    <div class="stat-card">
        <span class="stat-label">Diproses</span>
        <span class="stat-value">{{ $groupedComplaints['diproses']->count() }}</span>
        <div class="stat-icon-wrapper" style="background-color: #f0fdf4; color: #16a34a;">
            <svg class="w-5 h-5" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.213 6H16"></path></svg>
        </div>
    </div>
</div>

<!-- 2. DAFTAR PENGADUAN KONTEN UTAMA -->
<div class="main-table-card">
    <!-- Header Tabel & Tombol Tambah Pengaduan -->
    <div class="gap-3 p-4 border-bottom border-light d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
        <div>
            <h5 class="mb-1 fw-bold text-dark" style="font-size: 1.1rem; letter-spacing: -0.2px;">Daftar Pengaduan</h5>
            <p class="mb-0 text-muted small" style="font-size: 0.8rem;">Laporan dikelompokkan berdasarkan status.</p>
        </div>
        <div>
            <button type="button" class="btn-add-complaint" data-bs-toggle="modal" data-bs-target="#createComplaintModal">
                <i class="bi bi-plus-lg"></i> Tambah Pengaduan
            </button>
        </div>
    </div>

    <!-- Body Pengaduan per Kategori Status -->
    <div class="p-4">
        <div class="gap-4 d-flex flex-column">
            @foreach($groupedComplaints as $status => $items)
                <div class="status-group">
                    <!-- Judul Status dengan Emoji dan Angka Jumlah -->
                    <div class="gap-2 mb-3 d-flex align-items-center">
                        <span class="status-badge-title" style="color: {{ $statusConfig[$status]['textColor'] }};">
                            {{ $statusConfig[$status]['emoji'] }} {{ $statusConfig[$status]['title'] }} ({{ $items->count() }})
                        </span>
                    </div>

                    <!-- Tabel Elemen -->
                    <div class="overflow-hidden border table-responsive rounded-3">
                        <table class="table mb-0 align-middle custom-table table-hover">
                            <thead>
                                <tr>
                                    <th width="60" class="text-center">#</th>
                                    <th style="width: 45%;">JUDUL</th>
                                    <th>PENGGUNA</th>
                                    <th>KATEGORI</th>
                                    <th>TANGGAL</th>
                                    <th class="text-center" width="240">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $complaint)
                                    <tr>
                                        <td class="text-center text-muted fw-medium">{{ $loop->iteration }}</td>
                                        <td class="fw-medium text-dark">
                                            {{ $complaint->title ?? '-' }}
                                        </td>
                                        <td>
                                            {{ $complaint->is_anonymous ? 'Anonim' : ($complaint->user->name ?? '-') }}
                                        </td>
                                        <td>
                                            {{ $complaint->category->name ?? '-' }}
                                        </td>
                                        <td class="text-muted">
                                            {{ optional($complaint->created_at)->format('d M Y') }}
                                        </td>
                                        <td>
                                            <div class="gap-1 d-flex align-items-center justify-content-center">
                                                <a class="transition btn-action-detail" href="{{ route('admin.complaints.show', $complaint) }}">
                                                    Detail
                                                </a>

                                                <button class="transition btn-action-update" type="button" data-bs-toggle="modal" data-bs-target="#editComplaintModal-{{ $complaint->id }}">
                                                    Update
                                                </button>

                                                <form method="POST" class="d-inline" action="{{ route('admin.complaints.destroy', $complaint) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="transition btn-action-delete" type="submit">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-4 text-center text-muted">
                                            Tidak ada data pengaduan dengan status <strong>{{ $statusConfig[$status]['title'] }}</strong>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- 3. MODAL: TAMBAH PENGADUAN -->
<div class="modal fade" id="createComplaintModal" tabindex="-1" aria-labelledby="createComplaintModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form class="border-0 shadow modal-content rounded-4" method="POST" action="{{ route('admin.complaints.store') }}">
            @csrf
            <div class="p-4 modal-header border-bottom border-light">
                <h5 class="modal-title fw-bold" id="createComplaintModalLabel">Tambah Pengaduan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="p-4 modal-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold small text-muted" for="create_user_id">Pengguna</label>
                        <select id="create_user_id" name="user_id" class="py-2 form-select rounded-3" required>
                            <option value="">Pilih pengguna</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold small text-muted" for="create_category_id">Kategori</label>
                        <select id="create_category_id" name="category_id" class="py-2 form-select rounded-3" required>
                            <option value="">Pilih kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold small text-muted" for="create_title">Judul Pengaduan</label>
                        <input id="create_title" type="text" name="title" value="{{ old('title') }}" class="py-2 form-control rounded-3" required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold small text-muted" for="create_location">Lokasi Kejadian</label>
                        <input id="create_location" type="text" name="location" value="{{ old('location') }}" class="py-2 form-control rounded-3">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold small text-muted" for="create_status">Status Awal</label>
                        <select id="create_status" name="status" class="py-2 form-select rounded-3" required>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" @selected(old('status', 'menunggu') === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="my-2 col-12">
                        <div class="form-check form-switch">
                            <input id="create_is_anonymous" type="checkbox" name="is_anonymous" value="1" class="form-check-input" @checked(old('is_anonymous'))>
                            <label class="form-check-label fw-medium text-secondary" for="create_is_anonymous">Laporkan sebagai Anonim</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold small text-muted" for="create_description">Deskripsi Lengkap</label>
                        <textarea id="create_description" name="description" rows="4" class="form-control rounded-3" required>{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="p-4 modal-footer border-top border-light">
                <button class="px-4 py-2 btn btn-light rounded-3" type="button" data-bs-dismiss="modal">Batal</button>
                <button class="px-4 py-2 btn btn-primary rounded-3 fw-semibold" type="submit">Tambah Pengaduan</button>
            </div>
        </form>
    </div>
</div>

<!-- 4. MODAL: EDIT PENGADUAN -->
@foreach($complaints as $complaint)
    <div class="modal fade" id="editComplaintModal-{{ $complaint->id }}" tabindex="-1" aria-labelledby="editComplaintModalLabel-{{ $complaint->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form class="border-0 shadow modal-content rounded-4" method="POST" action="{{ route('admin.complaints.update', $complaint) }}">
                @csrf
                @method('PUT')
                <div class="p-4 modal-header border-bottom border-light">
                    <h5 class="modal-title fw-bold" id="editComplaintModalLabel-{{ $complaint->id }}">Update Pengaduan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="p-4 modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-muted" for="edit_user_id_{{ $complaint->id }}">Pengguna</label>
                            <select id="edit_user_id_{{ $complaint->id }}" name="user_id" class="py-2 form-select rounded-3" required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" @selected($complaint->user_id === $user->id)>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-muted" for="edit_category_id_{{ $complaint->id }}">Kategori</label>
                            <select id="edit_category_id_{{ $complaint->id }}" name="category_id" class="py-2 form-select rounded-3" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected($complaint->category_id === $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-muted" for="edit_title_{{ $complaint->id }}">Judul</label>
                            <input id="edit_title_{{ $complaint->id }}" type="text" name="title" value="{{ $complaint->title }}" class="py-2 form-control rounded-3" required>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-muted" for="edit_location_{{ $complaint->id }}">Lokasi</label>
                            <input id="edit_location_{{ $complaint->id }}" type="text" name="location" value="{{ $complaint->location }}" class="py-2 form-control rounded-3">
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-muted" for="edit_status_{{ $complaint->id }}">Status</label>
                            <select id="edit_status_{{ $complaint->id }}" name="status" class="py-2 form-select rounded-3" required>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" @selected($complaint->status === $status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="my-2 col-12">
                            <div class="form-check form-switch">
                                <input id="edit_is_anonymous_{{ $complaint->id }}" type="checkbox" name="is_anonymous" value="1" class="form-check-input" @checked($complaint->is_anonymous)>
                                <label class="form-check-label fw-medium text-secondary" for="edit_is_anonymous_{{ $complaint->id }}">Tampilkan sebagai anonim</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-muted" for="edit_description_{{ $complaint->id }}">Deskripsi</label>
                            <textarea id="edit_description_{{ $complaint->id }}" name="description" rows="4" class="form-control rounded-3" required>{{ $complaint->description }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="p-4 modal-footer border-top border-light">
                    <button class="px-4 py-2 btn btn-light rounded-3" type="button" data-bs-dismiss="modal">Batal</button>
                    <button class="px-4 py-2 btn btn-primary rounded-3 fw-semibold" type="submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endforeach
@endsection
