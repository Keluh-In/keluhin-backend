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

        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
            <i class="bi bi-plus-lg"></i>
            Tambah Kategori
        </button>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th width="90">ID</th>
                    <th>Nama Kategori</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
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
                        <td>
                            <div class="table-actions">
                                <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#editCategoryModal-{{ $category->id }}">
                                    Update
                                </button>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-soft-danger" type="submit">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="empty-state">Belum ada kategori.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <div class="modal-header">
                <h2 class="modal-title" id="createCategoryModalLabel">Tambah Kategori</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <label class="form-label" for="create_category_name">Nama Kategori</label>
                <input id="create_category_name" type="text" name="name" value="{{ old('name') }}" class="form-control" required>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" type="submit">Tambah Kategori</button>
            </div>
        </form>
    </div>
</div>

@foreach($categories as $category)
    <div class="modal fade" id="editCategoryModal-{{ $category->id }}" tabindex="-1" aria-labelledby="editCategoryModalLabel-{{ $category->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" method="POST" action="{{ route('admin.categories.update', $category) }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h2 class="modal-title" id="editCategoryModalLabel-{{ $category->id }}">Update Kategori</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label" for="edit_category_name_{{ $category->id }}">Nama Kategori</label>
                    <input id="edit_category_name_{{ $category->id }}" type="text" name="name" value="{{ $category->name }}" class="form-control" required>
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
