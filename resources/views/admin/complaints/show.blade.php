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

    .response-list {
        display: grid;
        gap: 14px;
    }

    .response-item {
        border: 1px solid #edf1f5;
        border-radius: 8px;
        padding: 16px;
        background: #fbfcfe;
    }

    .response-meta {
        color: #6b7280;
        font-size: .84rem;
        margin-top: 8px;
    }

        .attachment-list {
            display: grid;
            gap: 14px;
        }

        .attachment-item {
            border: 1px solid #edf1f5;
            border-radius: 8px;
            padding: 16px;
            background: #fbfcfe;
        }

        .attachment-preview {
            width: 100%;
            max-height: 220px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #edf1f5;
            margin-bottom: 10px;
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

    <section class="card detail-panel">
        <div class="section-header px-0 pt-0">
            <div>
                <h2 class="section-title">Tanggapan Admin</h2>
                <div class="section-subtitle">Kelola seluruh tanggapan untuk pengaduan ini.</div>
            </div>

            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#createResponseModal">
                <i class="bi bi-plus-lg"></i>
                Tambah Tanggapan
            </button>
        </div>

        @if($complaint->responses->isEmpty())
            <div class="empty-state">Belum ada tanggapan.</div>
        @else
            <div class="response-list">
                @foreach($complaint->responses as $response)
                    <article class="response-item">
                        <div class="detail-description mt-0">{{ $response->message }}</div>
                        <div class="response-meta">
                            Oleh {{ $response->admin->name ?? 'Admin' }} pada {{ optional($response->created_at)->format('d M Y H:i') }}
                            @if($response->updated_at && $response->updated_at->ne($response->created_at))
                                · Diedit {{ optional($response->updated_at)->format('d M Y H:i') }}
                            @endif
                        </div>

                        <div class="table-actions mt-3 justify-content-start">
                            <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#editResponseModal-{{ $response->id }}">
                                Update
                            </button>
                            <form method="POST" action="{{ route('admin.complaint-responses.destroy', [$complaint, $response]) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-soft-danger" type="submit">Hapus</button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    <section class="card detail-panel">
        <div class="section-header px-0 pt-0">
            <div>
                <h2 class="section-title">Lampiran Bukti</h2>
                <div class="section-subtitle">Upload, validasi, dan kelola lampiran pengaduan.</div>
            </div>

            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#createAttachmentModal">
                <i class="bi bi-plus-lg"></i>
                Tambah Lampiran
            </button>
        </div>

        @if($complaint->attachments->isEmpty())
            <div class="empty-state">Belum ada lampiran.</div>
        @else
            <div class="attachment-list">
                @foreach($complaint->attachments as $attachment)
                    <article class="attachment-item">
                        @if(str_starts_with($attachment->mime_type, 'image/'))
                            <img class="attachment-preview" src="{{ route('admin.complaint-attachments.file', [$complaint, $attachment]) }}" alt="Lampiran {{ $attachment->original_name }}">
                        @endif

                        <div class="fw-semibold">{{ $attachment->original_name }}</div>
                        <div class="response-meta mt-1">
                            {{ strtoupper($attachment->mime_type) }} · {{ number_format($attachment->file_size / 1024, 1) }} KB
                        </div>
                        <div class="response-meta mt-1">
                            Diunggah oleh {{ $attachment->uploader->name ?? 'Admin' }} pada {{ optional($attachment->created_at)->format('d M Y H:i') }}
                        </div>

                        <div class="mt-2">
                            @if($attachment->is_validated)
                                <span class="badge-soft badge-selesai">Tervalidasi</span>
                            @else
                                <span class="badge-soft badge-ditolak">Belum Validasi</span>
                            @endif
                        </div>

                        @if($attachment->validation_note)
                            <div class="response-meta mt-2">Catatan validasi: {{ $attachment->validation_note }}</div>
                        @endif

                        <div class="table-actions mt-3 justify-content-start">
                            <a class="btn btn-sm btn-light" href="{{ route('admin.complaint-attachments.file', [$complaint, $attachment]) }}" target="_blank" rel="noopener noreferrer">
                                Lihat
                            </a>

                            <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#editAttachmentModal-{{ $attachment->id }}">
                                Update
                            </button>

                            <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#validateAttachmentModal-{{ $attachment->id }}">
                                Validasi
                            </button>

                            <form method="POST" action="{{ route('admin.complaint-attachments.destroy', [$complaint, $attachment]) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-soft-danger" type="submit">Hapus</button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
</div>

<div class="modal fade" id="createAttachmentModal" tabindex="-1" aria-labelledby="createAttachmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" method="POST" action="{{ route('admin.complaint-attachments.store', $complaint) }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h2 class="modal-title" id="createAttachmentModalLabel">Tambah Lampiran</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <label class="form-label" for="create_attachment_file">Pilih Lampiran</label>
                <input id="create_attachment_file" type="file" name="attachment" class="form-control" accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx" required>
                <div class="section-subtitle mt-2">Format: JPG, PNG, WEBP, PDF, DOC, DOCX. Maks 5MB.</div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" type="submit">Tambah Lampiran</button>
            </div>
        </form>
    </div>
</div>

@foreach($complaint->attachments as $attachment)
    <div class="modal fade" id="editAttachmentModal-{{ $attachment->id }}" tabindex="-1" aria-labelledby="editAttachmentModalLabel-{{ $attachment->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" method="POST" action="{{ route('admin.complaint-attachments.update', [$complaint, $attachment]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h2 class="modal-title" id="editAttachmentModalLabel-{{ $attachment->id }}">Ganti Lampiran</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label" for="edit_attachment_file_{{ $attachment->id }}">Pilih File Baru</label>
                    <input id="edit_attachment_file_{{ $attachment->id }}" type="file" name="attachment" class="form-control" accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx" required>
                    <div class="section-subtitle mt-2">Validasi sebelumnya akan direset ketika file diganti.</div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="validateAttachmentModal-{{ $attachment->id }}" tabindex="-1" aria-labelledby="validateAttachmentModalLabel-{{ $attachment->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" method="POST" action="{{ route('admin.complaint-attachments.validate', [$complaint, $attachment]) }}">
                @csrf
                <div class="modal-header">
                    <h2 class="modal-title" id="validateAttachmentModalLabel-{{ $attachment->id }}">Validasi Lampiran</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="is_validated_{{ $attachment->id }}">Status Validasi</label>
                        <select id="is_validated_{{ $attachment->id }}" name="is_validated" class="form-select" required>
                            <option value="1" @selected($attachment->is_validated)>Tervalidasi</option>
                            <option value="0" @selected(! $attachment->is_validated)>Belum Validasi</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label" for="validation_note_{{ $attachment->id }}">Catatan Validasi</label>
                        <textarea id="validation_note_{{ $attachment->id }}" name="validation_note" rows="4" class="form-control" placeholder="Opsional">{{ $attachment->validation_note }}</textarea>
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

<div class="modal fade" id="createResponseModal" tabindex="-1" aria-labelledby="createResponseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" method="POST" action="{{ route('admin.complaint-responses.store', $complaint) }}">
            @csrf
            <div class="modal-header">
                <h2 class="modal-title" id="createResponseModalLabel">Tambah Tanggapan</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <label class="form-label" for="create_response_message">Tanggapan</label>
                <textarea id="create_response_message" name="message" rows="5" class="form-control" required>{{ old('message') }}</textarea>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" type="submit">Tambah Tanggapan</button>
            </div>
        </form>
    </div>
</div>

@foreach($complaint->responses as $response)
    <div class="modal fade" id="editResponseModal-{{ $response->id }}" tabindex="-1" aria-labelledby="editResponseModalLabel-{{ $response->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" method="POST" action="{{ route('admin.complaint-responses.update', [$complaint, $response]) }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h2 class="modal-title" id="editResponseModalLabel-{{ $response->id }}">Update Tanggapan</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label" for="edit_response_message_{{ $response->id }}">Tanggapan</label>
                    <textarea id="edit_response_message_{{ $response->id }}" name="message" rows="5" class="form-control" required>{{ $response->message }}</textarea>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endforeach

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
