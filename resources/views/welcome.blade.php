<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keluh.in - Sistem Pengaduan</title>

    @vite('resources/css/app.css')
</head>

<body class="bg-gray-50 text-gray-800">

    <!-- NAVBAR -->
    <header class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-indigo-600">
                Keluh.in
            </h1>

            <nav class="space-x-6 text-sm font-medium">
                <a href="#" class="hover:text-indigo-600">Beranda</a>
                <a href="#" class="hover:text-indigo-600">Fitur</a>
                <a href="/login" class="text-indigo-600 hover:underline">Login</a>
            </nav>
        </div>
    </header>

    <!-- HERO -->
    <section class="max-w-6xl mx-auto px-6 py-20 grid md:grid-cols-2 gap-10 items-center">

        <div>
            <h2 class="text-4xl font-bold leading-tight">
                Sistem Pengaduan Online <br>
                Lebih Cepat & Transparan
            </h2>

            <p class="mt-4 text-gray-600">
                Keluh.in membantu masyarakat menyampaikan keluhan dengan mudah,
                dan diproses secara transparan oleh admin.
            </p>

            <div class="mt-6 flex gap-4">
                <a href="/register"
                   class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                    Mulai Sekarang
                </a>

                <a href="/login"
                   class="border border-gray-300 px-6 py-3 rounded-lg hover:bg-gray-100">
                    Login
                </a>
            </div>
        </div>

        <div class="flex justify-center">
            <div class="bg-indigo-100 w-72 h-72 rounded-2xl flex items-center justify-center">
                <span class="text-indigo-600 font-semibold">
                    Ilustrasi Sistem
                </span>
            </div>
        </div>

    </section>

    <!-- FITUR -->
    <section class="bg-white py-16">
        <div class="max-w-6xl mx-auto px-6">

            <h3 class="text-2xl font-bold text-center mb-10">
                Fitur Utama
            </h3>

            <div class="grid md:grid-cols-3 gap-6">

                <div class="p-6 bg-gray-50 rounded-xl">
                    <h4 class="font-semibold mb-2">Laporan Cepat</h4>
                    <p class="text-sm text-gray-600">
                        Kirim pengaduan hanya dalam beberapa klik.
                    </p>
                </div>

                <div class="p-6 bg-gray-50 rounded-xl">
                    <h4 class="font-semibold mb-2">Tracking Status</h4>
                    <p class="text-sm text-gray-600">
                        Pantau perkembangan laporan secara real-time.
                    </p>
                </div>

                <div class="p-6 bg-gray-50 rounded-xl">
                    <h4 class="font-semibold mb-2">Respon Admin</h4>
                    <p class="text-sm text-gray-600">
                        Admin menindaklanjuti laporan dengan cepat.
                    </p>
                </div>

            </div>

        </div>
    </section>

    <!-- FOOTER -->
    <footer class="text-center py-8 text-sm text-gray-500">
        © {{ date('Y') }} Keluh.in. All rights reserved.
    </footer>

</body>
</html>