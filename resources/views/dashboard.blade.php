<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keluh.in Dashboard</title>

    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite('resources/css/app.css')
</head>

<body class="transition-colors duration-300 bg-slate-100 dark:bg-slate-900">

<div class="flex min-h-screen">

    <aside class="transition-colors duration-300 bg-white shadow-2xl w-72 dark:bg-slate-800">
        <div class="p-6 border-b dark:border-slate-700">
            <h1 class="text-3xl font-bold text-blue-600">KELUH.IN</h1>
            <p class="mt-1 text-sm text-slate-500">Admin Workspace</p>
        </div>

        <nav class="p-5 space-y-3">
            <a href="/admin/dashboard" class="flex items-center gap-3 p-4 font-medium text-white bg-blue-500 shadow-lg rounded-2xl">
                📊 Dashboard
            </a>
            <a href="#" class="flex items-center gap-3 p-4 transition rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-700 dark:text-white">
                📢 Pengaduan
            </a>
            <a href="#" class="flex items-center gap-3 p-4 transition rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-700 dark:text-white">
                👥 Pengguna
            </a>
            <div class="pt-10">
                <form method="POST" action="/admin/logout">
                    @csrf
                    <button class="w-full py-4 text-white transition bg-red-500 shadow-lg hover:bg-red-600 rounded-2xl">
                        Logout
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <main class="flex-1 p-8">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-4xl font-bold dark:text-white">Dashboard</h1>
                <p class="mt-2 text-slate-500">Selamat datang kembali di panel admin.</p>
            </div>

            <div class="flex items-center gap-5">
                <button
                    onclick="toggleDarkMode()"
                    class="px-5 py-3 transition-all duration-300 bg-white border shadow-lg rounded-2xl dark:bg-slate-800 border-slate-200 dark:border-slate-700 hover:scale-105 active:scale-95"
                >
                    <span id="theme-icon" class="text-xl">🌙</span>
                </button>

                <div class="flex items-center gap-4 px-5 py-3 transition-colors bg-white shadow-lg dark:bg-slate-800 rounded-2xl">
                    <img src="https://i.pravatar.cc/150" class="object-cover w-12 h-12 border-2 border-blue-500 rounded-full">
                    <div>
                        <h3 class="font-bold dark:text-white">{{ auth()->user()->name }}</h3>
                        <p class="text-sm text-slate-500">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-4">
            <div class="p-6 transition-colors bg-white shadow-xl dark:bg-slate-800 rounded-3xl">
                <p class="text-slate-500">Total Pengaduan</p>
                <h1 class="mt-4 text-5xl font-bold dark:text-white">{{ $total }}</h1>
            </div>
            <div class="p-6 bg-yellow-100 shadow-xl dark:bg-yellow-500/20 rounded-3xl">
                <p class="text-yellow-700 dark:text-yellow-300">Menunggu</p>
                <h1 class="mt-4 text-5xl font-bold text-yellow-600 dark:text-yellow-300">{{ $menunggu }}</h1>
            </div>
            <div class="p-6 bg-blue-100 shadow-xl dark:bg-blue-500/20 rounded-3xl">
                <p class="text-blue-700 dark:text-blue-300">Diproses</p>
                <h1 class="mt-4 text-5xl font-bold text-blue-600 dark:text-blue-300">{{ $proses }}</h1>
            </div>
            <div class="p-6 bg-green-100 shadow-xl dark:bg-green-500/20 rounded-3xl">
                <p class="text-green-700 dark:text-green-300">Selesai</p>
                <h1 class="mt-4 text-5xl font-bold text-green-600 dark:text-green-300">{{ $selesai }}</h1>
            </div>
        </div>

        <div class="overflow-hidden transition-colors bg-white shadow-xl dark:bg-slate-800 rounded-3xl">
            <div class="p-6 border-b dark:border-slate-700">
                <h2 class="text-2xl font-bold dark:text-white">Pengaduan Terbaru</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 dark:bg-slate-700/50">
                        <tr>
                            <th class="p-5 font-semibold text-left dark:text-white">Judul</th>
                            <th class="p-5 font-semibold text-left dark:text-white">Status</th>
                            <th class="p-5 font-semibold text-left dark:text-white">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Complaint::latest()->limit(5)->get() as $c)
                        <tr class="transition-colors border-t dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/30">
                            <td class="p-5 dark:text-slate-300">{{ $c->title }}</td>
                            <td class="p-5">
                                @php
                                    $statusClass = match($c->status) {
                                        'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/20 dark:text-yellow-300',
                                        'process' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300',
                                        'rejected' => 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-300',
                                        default => 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-300'
                                    };
                                    $statusText = match($c->status) {
                                        'pending' => 'Menunggu',
                                        'process' => 'Diproses',
                                        'rejected' => 'Ditolak',
                                        default => 'Selesai'
                                    };
                                @endphp
                                <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="p-5 dark:text-slate-400">{{ $c->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script>
    const html = document.documentElement;
    const icon = document.getElementById('theme-icon');

    // Inisialisasi Ikon saat load
    function updateIcon() {
        if (html.classList.contains('dark')) {
            icon.innerHTML = '☀️';
        } else {
            icon.innerHTML = '🌙';
        }
    }

    // Jalankan saat pertama kali buka halaman
    updateIcon();

    function toggleDarkMode() {
        html.classList.toggle('dark');

        if (html.classList.contains('dark')) {
            localStorage.theme = 'dark';
        } else {
            localStorage.theme = 'light';
        }

        updateIcon();
    }
</script>

</body>
</html>
