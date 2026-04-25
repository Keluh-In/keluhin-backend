@extends('admin.layout')

@section('content')

<h3>Kategori</h3>

<form method="POST" action="/admin/categories">
    @csrf
    <input type="text" name="name" class="form-control" placeholder="Nama kategori">
    <button class="btn btn-primary mt-2">Tambah</button>
</form>

<table class="table mt-4">
    <tr>
        <th>Nama</th>
    </tr>

    @foreach($categories as $cat)
    <tr>
        <td>{{ $cat->name }}</td>
    </tr>
    @endforeach
</table>

@endsection