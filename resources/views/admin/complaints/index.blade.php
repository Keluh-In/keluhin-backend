<!DOCTYPE html>
<html>
<head>
    <title>Data Complaints</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

<div class="p-6">

    <!-- HEADER -->
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold">Manajemen Pengaduan</h1>

        <a href="#" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
            + Tambah
        </a>
    </div>

    <!-- CARD -->
    <div class="bg-white p-6 rounded-2xl shadow">

        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">#</th>
                        <th class="p-3">Judul</th>
                        <th class="p-3">User</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
  @forelse ($complaints as $item)
    <tr>
        <td>{{ $item->id }}</td>
        <td>{{ $item->title ?? '-' }}</td>
        <td>{{ $item->status ?? '-' }}</td>
    </tr>
@empty
    <tr>
        <td colspan="3">Belum ada data</td>
    </tr>
@endforelse
                <tbody>
                    @forelse ($complaints as $c)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">{{ $loop->iteration }}</td>

                        <td class="p-3 font-medium">
                            {{ $c->title ?? '-' }}
                        </td>

                        <td class="p-3">
                            {{ $c->user->name ?? '-' }}
                        </td>

                        <td class="p-3">
                            @if($c->status == 'menunggu')
                                <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">
                                    Menunggu
                                </span>
                            @elseif($c->status == 'diproses')
                                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">
                                    Diproses
                                </span>
                            @elseif($c->status == 'selesai')
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">
                                    Selesai
                                </span>
                            @else
                                <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">
                                    Ditolak
                                </span>
                            @endif
                        </td>

                        <td class="p-3">
                            {{ $c->created_at->format('d M Y') }}
                        </td>

                        <td class="p-3 space-x-2">
                            <a href="#" class="text-blue-500 hover:underline">Detail</a>
                            <a href="#" class="text-green-500 hover:underline">Edit</a>
                            <a href="#" class="text-red-500 hover:underline">Hapus</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center p-4 text-gray-500">
                            Belum ada data pengaduan
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>

</div>

</body>
</html>