@extends('layouts.admin')

@section('title', 'Kategori')
@section('subtitle', 'Kelola kelompok laporan agar pengaduan mudah dipantau.')

@section('content')
@php
    $totalCategories = $categories->count();
@endphp

<style>
    /* CSS untuk memastikan angka tegak dan rapi di tengah */
    .mini-metric-value {
        font-style: normal !important;
        font-family: sans-serif !important;
        font-weight: 700;
        font-size: 1.5rem;
        margin-top: 5px;
    }
    .metric-card-custom {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 20px;
    }
</style>

<div class="gap-3 mb-4 metric-strip d-flex">
    <div class="card mini-metric metric-card-custom flex-fill">
        <div class="mini-metric-label">Total kategori</div>
        <div class="mini-metric-value">{{ number_format($totalCategories) }}</div>
        <span class="mt-2 mini-metric-icon"><i class="bi bi-folder2-open"></i></span>
    </div>

    <div class="card mini-metric metric-card-custom flex-fill">
        <div class="mini-metric-label">Status aktif</div>
        <div class="mini-metric-value">{{ number_format($totalCategories) }}</div>
        <span class="mt-2 mini-metric-icon"><i class="bi bi-check2-circle"></i></span>
    </div>

    <div class="card mini-metric metric-card-custom flex-fill">
        <div class="mini-metric-label">Terbaru</div>
        <div class="mini-metric-value">{{ optional($categories->first())->id ?? 0 }}</div>
        <span class="mt-2 mini-metric-icon"><i class="bi bi-clock-history"></i></span>
    </div>
</div>

<section class="card section-card">
    <div class="p-4 section-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="section-title">Daftar Kategori</h2>
            <div class="section-subtitle">Kategori yang tersedia untuk laporan pengguna.</div>
        </div>

        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
            <i class="bi bi-plus-lg"></i> Tambah Kategori
        </button>
    </div>

    <div class="table-responsive">
        <table class="table mb-0 align-middle">
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
                            <div class="gap-2 table-actions d-flex justify-content-end">
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

<div class="modal fade" id="createCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <div class="modal-header">
                <h2 class="modal-title">Tambah Kategori</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label">Nama Kategori</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" type="submit">Tambah Kategori</button>
            </div>
        </form>
    </div>
</div>

@foreach($categories as $category)
    <div class="modal fade" id="editCategoryModal-{{ $category->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" method="POST" action="{{ route('admin.categories.update', $category) }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h2 class="modal-title">Update Kategori</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" name="name" value="{{ $category->name }}" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endforeach
@endsection
