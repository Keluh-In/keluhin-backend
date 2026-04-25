@extends('admin.layout')

@section('content')

<h3>Users</h3>

<table class="table table-striped">
    <tr>
        <th>Nama</th>
        <th>Email</th>
        <th>Total Pengaduan</th>
    </tr>

    @foreach($users as $u)
    <tr>
        <td>{{ $u->name }}</td>
        <td>{{ $u->email }}</td>
        <td>{{ $u->complaints->count() }}</td>
    </tr>
    @endforeach
</table>

@endsection