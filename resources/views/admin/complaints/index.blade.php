@extends('layouts.admin')

@section('title', 'Pengaduan')
@section('subtitle', 'Pantau laporan pengguna dan status penanganannya.')

@section('content')
@php
    $totalComplaints = $complaints->count();
    $waitingComplaints = $complaints->where('status', 'menunggu')->count();
    $activeComplaints = $complaints->where('status', 'diproses')->count();
    $statuses = ['menunggu', 'diproses', 'selesai', 'ditolak'];
@endphp

<div class="metric-strip">
    <div class="card mini-metric">
        <div>
            <div class="mini-metric-label">Total laporan</div>
            <div class="mini-metric-value">{{ number_format($totalComplaints) }}</div>
        </div>
        <span class="mini-metric-icon"><i class="bi bi-inbox"></i></span>
    </div>

    <div class="card mini-metric">
        <div>
            <div class="mini-metric-label">Menunggu</div>
            <div class="mini-metric-value">{{ number_format($waitingComplaints) }}</div>
        </div>
        <span class="mini-metric-icon"><i class="bi bi-hourglass-split"></i></span>
    </div>

    <div class="card mini-metric">
        <div>
            <div class="mini-metric-label">Diproses</div>
            <div class="mini-metric-value">{{ number_format($activeComplaints) }}</div>
        </div>
        <span class="mini-metric-icon"><i class="bi bi-arrow-repeat"></i></span>
    </div>
</div>

<section class="card section-card">
    <div class="section-header">
        <div>
            <h2 class="section-title">Daftar Pengaduan</h2>
            <div class="section-subtitle">Laporan terbaru ditampilkan paling atas.</div>
        </div>

        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#createComplaintModal">
            <i class="bi bi-plus-lg"></i>
            Tambah Pengaduan
        </button>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th width="80">#</th>
                    <th>Judul</th>
                    <th>Pengguna</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($complaints as $complaint)
                    @php
                        $status = $complaint->status ?? 'menunggu';
                        $badgeClass = [
                            'menunggu' => 'badge-menunggu',
                            'diproses' => 'badge-diproses',
                            'selesai' => 'badge-selesai',
                            'ditolak' => 'badge-ditolak',
                        ][$status] ?? 'badge-menunggu';
                    @endphp
                    <tr>
                        <td class="text-muted">{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $complaint->title ?? '-' }}</td>
                        <td>{{ $complaint->is_anonymous ? 'Anonim' : ($complaint->user->name ?? '-') }}</td>
                        <td>{{ $complaint->category->name ?? '-' }}</td>
                        <td>
                            <span class="badge-soft {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                        </td>
                        <td>{{ optional($complaint->created_at)->format('d M Y') }}</td>
                        <td>
                            <div class="table-actions">
                                <a class="btn btn-sm btn-light" href="{{ route('admin.complaints.show', $complaint) }}">
                                    Detail
                                </a>
                                <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#editComplaintModal-{{ $complaint->id }}">
                                    Update
                                </button>
                                <form method="POST" action="{{ route('admin.complaints.destroy', $complaint) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-soft-danger" type="submit">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-state">Belum ada data pengaduan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<div class="modal fade" id="createComplaintModal" tabindex="-1" aria-labelledby="createComplaintModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form class="modal-content" method="POST" action="{{ route('admin.complaints.store') }}">
            @csrf
            <div class="modal-header">
                <h2 class="modal-title" id="createComplaintModalLabel">Tambah Pengaduan</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="form-grid">
                    <div>
                        <label class="form-label" for="create_user_id">Pengguna</label>
                        <select id="create_user_id" name="user_id" class="form-select" required>
                            <option value="">Pilih pengguna</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label" for="create_category_id">Kategori</label>
                        <select id="create_category_id" name="category_id" class="form-select" required>
                            <option value="">Pilih kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="span-2">
                        <label class="form-label" for="create_title">Judul</label>
                        <input id="create_title" type="text" name="title" value="{{ old('title') }}" class="form-control" required>
                    </div>

                    <div>
                        <label class="form-label" for="create_location">Lokasi</label>
                        <input id="create_location" type="text" name="location" value="{{ old('location') }}" class="form-control">
                    </div>

                    <div>
                        <label class="form-label" for="create_status">Status</label>
                        <select id="create_status" name="status" class="form-select" required>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" @selected(old('status', 'menunggu') === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="span-2 form-check mb-2">
                        <input id="create_is_anonymous" type="checkbox" name="is_anonymous" value="1" class="form-check-input" @checked(old('is_anonymous'))>
                        <label class="form-check-label" for="create_is_anonymous">Tampilkan sebagai anonim</label>
                    </div>

                    <div class="span-4">
                        <label class="form-label" for="create_description">Deskripsi</label>
                        <textarea id="create_description" name="description" rows="4" class="form-control" required>{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" type="submit">Tambah Pengaduan</button>
            </div>
        </form>
    </div>
</div>

@foreach($complaints as $complaint)
    <div class="modal fade" id="editComplaintModal-{{ $complaint->id }}" tabindex="-1" aria-labelledby="editComplaintModalLabel-{{ $complaint->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form class="modal-content" method="POST" action="{{ route('admin.complaints.update', $complaint) }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h2 class="modal-title" id="editComplaintModalLabel-{{ $complaint->id }}">Update Pengaduan</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="form-grid">
                        <div>
                            <label class="form-label" for="edit_user_id_{{ $complaint->id }}">Pengguna</label>
                            <select id="edit_user_id_{{ $complaint->id }}" name="user_id" class="form-select" required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" @selected($complaint->user_id === $user->id)>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="form-label" for="edit_category_id_{{ $complaint->id }}">Kategori</label>
                            <select id="edit_category_id_{{ $complaint->id }}" name="category_id" class="form-select" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected($complaint->category_id === $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="span-2">
                            <label class="form-label" for="edit_title_{{ $complaint->id }}">Judul</label>
                            <input id="edit_title_{{ $complaint->id }}" type="text" name="title" value="{{ $complaint->title }}" class="form-control" required>
                        </div>

                        <div>
                            <label class="form-label" for="edit_location_{{ $complaint->id }}">Lokasi</label>
                            <input id="edit_location_{{ $complaint->id }}" type="text" name="location" value="{{ $complaint->location }}" class="form-control">
                        </div>

                        <div>
                            <label class="form-label" for="edit_status_{{ $complaint->id }}">Status</label>
                            <select id="edit_status_{{ $complaint->id }}" name="status" class="form-select" required>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" @selected($complaint->status === $status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="span-2 form-check mb-2">
                            <input id="edit_is_anonymous_{{ $complaint->id }}" type="checkbox" name="is_anonymous" value="1" class="form-check-input" @checked($complaint->is_anonymous)>
                            <label class="form-check-label" for="edit_is_anonymous_{{ $complaint->id }}">Tampilkan sebagai anonim</label>
                        </div>

                        <div class="span-4">
                            <label class="form-label" for="edit_description_{{ $complaint->id }}">Deskripsi</label>
                            <textarea id="edit_description_{{ $complaint->id }}" name="description" rows="4" class="form-control" required>{{ $complaint->description }}</textarea>
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
@endforeach
@endsection
