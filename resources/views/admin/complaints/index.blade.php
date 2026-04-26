@extends('layouts.admin')

@section('title', 'Pengaduan')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Pengaduan</h3>
            <div class="text-muted">Pantau dan kelola laporan pengaduan terbaru.</div>
        </div>

        <a href="#" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Pengaduan
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="80">#</th>
                            <th>Judul</th>
                            <th>User</th>
                            <th width="140">Status</th>
                            <th width="160">Tanggal</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($complaints as $complaint)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $complaint->title ?? '-' }}</td>
                                <td>{{ $complaint->user->name ?? '-' }}</td>
                                <td>
                                    @if($complaint->status === 'menunggu')
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @elseif($complaint->status === 'diproses')
                                        <span class="badge bg-info">Diproses</span>
                                    @elseif($complaint->status === 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-danger">{{ ucfirst($complaint->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ optional($complaint->created_at)->format('d M Y') }}</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-primary">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    Belum ada data pengaduan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
