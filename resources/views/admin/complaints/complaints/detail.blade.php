@extends('admin.layout')

@section('content')

<h3>Detail Pengaduan</h3>

<div class="card p-3">

    <h5>{{ $complaint->title }}</h5>

    <p>{{ $complaint->description }}</p>

    <p><b>Status:</b> {{ $complaint->status }}</p>

    <hr>

    <form method="POST" action="/admin/complaints/{{ $complaint->id }}/status">
        @csrf

        <label>Update Status</label>
        <select class="form-control" name="status">
            <option>menunggu</option>
            <option>diproses</option>
            <option>selesai</option>
            <option>ditolak</option>
        </select>

        <button class="btn btn-primary mt-2">Update</button>
    </form>

    <hr>

    <form method="POST" action="/admin/complaints/{{ $complaint->id }}/response">
        @csrf

        <textarea name="message" class="form-control" placeholder="Balasan admin"></textarea>

        <button class="btn btn-success mt-2">Kirim Respon</button>
    </form>

</div>

@endsection