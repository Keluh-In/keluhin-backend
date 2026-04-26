<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex">

    <!-- SIDEBAR -->
    <div class="w-64 bg-gray-900 text-white min-h-screen p-5">
        <h2 class="text-2xl font-bold mb-6">Keluh.in</h2>

        <ul>
            <li class="mb-3"><a href="/admin/dashboard">Dashboard</a></li>
            <li class="mb-3"><a href="#">Complaints</a></li>
            <li class="mb-3"><a href="#">Users</a></li>
            <li class="mt-10">
                <form method="POST" action="/admin/logout">
                    @csrf
                    <button class="bg-red-500 px-3 py-1 rounded">Logout</button>
                </form>
            </li>
        </ul>
    </div>

    <!-- CONTENT -->
    <div class="flex-1 p-6">

        <h1 class="text-3xl font-bold mb-6">Dashboard Admin</h1>

        <!-- CARDS -->
        <div class="grid grid-cols-4 gap-4">

            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-gray-500">Total Keluhan</h2>
                <p class="text-2xl font-bold">{{ $total }}</p>
            </div>

            <div class="bg-yellow-100 p-4 rounded shadow">
                <h2 class="text-gray-500">Menunggu</h2>
                <p class="text-2xl font-bold">{{ $menunggu }}</p>
            </div>

            <div class="bg-blue-100 p-4 rounded shadow">
                <h2 class="text-gray-500">Diproses</h2>
                <p class="text-2xl font-bold">{{ $proses }}</p>
            </div>

            <div class="bg-green-100 p-4 rounded shadow">
                <h2 class="text-gray-500">Selesai</h2>
                <p class="text-2xl font-bold">{{ $selesai }}</p>
            </div>

        </div>

        <!-- TABLE -->
        <div class="mt-8 bg-white p-4 rounded shadow">
            <h2 class="text-xl font-bold mb-4">Recent Complaints</h2>

            <table class="w-full border">
                <tr class="bg-gray-200">
                    <th class="p-2">Title</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Tanggal</th>
                </tr>

                @foreach(\App\Models\Complaint::latest()->limit(5)->get() as $c)
                <tr>
                    <td class="p-2">{{ $c->title }}</td>
                    <td class="p-2">{{ $c->status }}</td>
                    <td class="p-2">{{ $c->created_at }}</td>
                </tr>
                @endforeach

            </table>
        </div>

    </div>

</div>

</body>
</html>
