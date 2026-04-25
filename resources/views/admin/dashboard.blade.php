@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Dashboard Admin</h3>
        <span class="text-muted">Keluh.in MVP System</span>
    </div>

    <!-- STAT CARDS -->
    <div class="row g-3">

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-primary text-white">
                <div class="card-body">
                    <div>Total Pengaduan</div>
                    <h2 class="fw-bold">{{ $data['total_complaints'] ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-warning text-dark">
                <div class="card-body">
                    <div>Menunggu</div>
                    <h2 class="fw-bold">{{ $data['menunggu'] ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-info text-white">
                <div class="card-body">
                    <div>Diproses</div>
                    <h2 class="fw-bold">{{ $data['diproses'] ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-success text-white">
                <div class="card-body">
                    <div>Selesai</div>
                    <h2 class="fw-bold">{{ $data['selesai'] ?? 0 }}</h2>
                </div>
            </div>
        </div>

    </div>

    <!-- TABLE -->
    <div class="card mt-4 shadow-sm border-0">

        <div class="card-header bg-white fw-bold">
            Pengaduan Terbaru
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>Judul</th>
                            <th>Status</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse(($data['latest_complaints'] ?? []) as $c)

                        <tr>
                            <td class="fw-semibold">{{ $c->title }}</td>

                            <td>
                                @if($c->status == 'menunggu')
                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                @elseif($c->status == 'diproses')
                                    <span class="badge bg-info">Diproses</span>
                                @elseif($c->status == 'selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @else
                                    <span class="badge bg-danger">{{ $c->status }}</span>
                                @endif
                            </td>

                            <td>
                                <a href="/admin/complaints/{{ $c->id }}"
                                   class="btn btn-sm btn-primary">
                                    Detail
                                </a>
                            </td>
                        </tr>

                        @empty

                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">
                                Belum ada pengaduan
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