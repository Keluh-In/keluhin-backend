@extends('layouts.admin')

@section('title', 'Detail Pengaduan')
@section('subtitle', 'Lihat informasi lengkap laporan dan tindak lanjutnya.')

@section('content')
@php
    $statuses = ['menunggu', 'diproses', 'selesai', 'ditolak'];
    $badgeClass = [
        'menunggu' => 'badge-menunggu',
        'diproses' => 'badge-diproses',
        'selesai' => 'badge-selesai',
        'ditolak' => 'badge-ditolak',
    ][$complaint->status] ?? 'badge-menunggu';
@endphp

<style>
    .detail-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.35fr) minmax(320px, .65fr);
        gap: 20px;
    }

    .detail-panel {
        padding: 24px;
    }

    .detail-title {
        margin: 0;
        color: #111827;
        font-size: 1.35rem;
        font-weight: 700;
    }

    .detail-description {
        margin-top: 18px;
        color: #374151;
        line-height: 1.7;
        white-space: pre-line;
    }

    .info-list {
        display: grid;
        gap: 16px;
    }

    .info-item {
        padding-bottom: 16px;
        border-bottom: 1px solid #edf1f5;
    }

    .info-item:last-child {
        padding-bottom: 0;
        border-bottom: 0;
    }

    .info-label {
        color: #6b7280;
        font-size: .82rem;
        font-weight: 700;
        letter-spacing: .02em;
        text-transform: uppercase;
    }

    .info-value {
        margin-top: 5px;
        color: #111827;
        font-weight: 600;
    }

    .complaint-image {
        width: 100%;
        max-height: 360px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #edf1f5;
    }

    .action-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 20px;
    }

    @media (max-width: 991.98px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }

        .action-row {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>

<div class="action-row">
    <a class="btn btn-light" href="{{ route('admin.complaints.index') }}">
        <i class="bi bi-arrow-left"></i>
        Kembali
    </a>

    <div class="table-actions">
        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#editComplaintModal">
            <i class="bi bi-pencil"></i>
            Update
        </button>
        <form method="POST" action="{{ route('admin.complaints.destroy', $complaint) }}">
            @csrf
            @method('DELETE')
            <button class="btn btn-soft-danger" type="submit">
                <i class="bi bi-trash"></i>
                Hapus
            </button>
        </form>
    </div>
</div>

<div class="detail-grid">
    <section class="card detail-panel">
        <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
            <h2 class="detail-title">{{ $complaint->title }}</h2>
            <span class="badge-soft {{ $badgeClass }}">{{ ucfirst($complaint->status) }}</span>
        </div>

        <div class="detail-description">{{ $complaint->description }}</div>

        @if($complaint->image)
            <div class="mt-4">
                <img class="complaint-image" src="{{ asset('storage/' . $complaint->image) }}" alt="Gambar pengaduan {{ $complaint->title }}">
            </div>
        @endif
    </section>

    <aside class="card detail-panel">
        <h2 class="section-title mb-3">Informasi Laporan</h2>

        <div class="info-list">
            <div class="info-item">
                <div class="info-label">Pelapor</div>
                <div class="info-value">{{ $complaint->is_anonymous ? 'Anonim' : ($complaint->user->name ?? '-') }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $complaint->is_anonymous ? '-' : ($complaint->user->email ?? '-') }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Kategori</div>
                <div class="info-value">{{ $complaint->category->name ?? '-' }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Lokasi</div>
                <div class="info-value">{{ $complaint->location ?: '-' }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Dibuat</div>
                <div class="info-value">{{ optional($complaint->created_at)->format('d M Y H:i') }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Terakhir update</div>
                <div class="info-value">{{ optional($complaint->updated_at)->format('d M Y H:i') }}</div>
            </div>
        </div>
    </aside>

    @if($complaint->response)
        <section class="card detail-panel">
            <h2 class="section-title mb-3">Respon Admin</h2>
            <div class="detail-description">{{ $complaint->response->message }}</div>
            <div class="section-subtitle mt-3">
                Oleh {{ $complaint->response->admin->name ?? 'Admin' }} pada {{ optional($complaint->response->created_at)->format('d M Y H:i') }}
            </div>
        </section>
    @endif
</div>

<div class="modal fade" id="editComplaintModal" tabindex="-1" aria-labelledby="editComplaintModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form class="modal-content" method="POST" action="{{ route('admin.complaints.update', $complaint) }}">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h2 class="modal-title" id="editComplaintModalLabel">Update Pengaduan</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="form-grid">
                    <div>
                        <label class="form-label" for="edit_user_id">Pengguna</label>
                        <select id="edit_user_id" name="user_id" class="form-select" required>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @selected($complaint->user_id === $user->id)>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label" for="edit_category_id">Kategori</label>
                        <select id="edit_category_id" name="category_id" class="form-select" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected($complaint->category_id === $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="span-2">
                        <label class="form-label" for="edit_title">Judul</label>
                        <input id="edit_title" type="text" name="title" value="{{ $complaint->title }}" class="form-control" required>
                    </div>

                    <div>
                        <label class="form-label" for="edit_location">Lokasi</label>
                        <input id="edit_location" type="text" name="location" value="{{ $complaint->location }}" class="form-control">
                    </div>

                    <div>
                        <label class="form-label" for="edit_status">Status</label>
                        <select id="edit_status" name="status" class="form-select" required>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" @selected($complaint->status === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="span-2 form-check mb-2">
                        <input id="edit_is_anonymous" type="checkbox" name="is_anonymous" value="1" class="form-check-input" @checked($complaint->is_anonymous)>
                        <label class="form-check-label" for="edit_is_anonymous">Tampilkan sebagai anonim</label>
                    </div>

                    <div class="span-4">
                        <label class="form-label" for="edit_description">Deskripsi</label>
                        <textarea id="edit_description" name="description" rows="4" class="form-control" required>{{ $complaint->description }}</textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
