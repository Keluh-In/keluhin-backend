<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>KELUH.IN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* Gaya Default (Light Mode) */
        body { background: #f4f6f9; transition: all 0.3s; }
        .navbar { background: #0d6efd; }

        /* Gaya Khusus Dark Mode */
        html[data-bs-theme='dark'] body {
            background: #121212;
            color: white;
        }
        html[data-bs-theme='dark'] .card {
            background: #1e1e1e;
            border-color: #333;
            color: white;
        }
        html[data-bs-theme='dark'] .navbar {
            background: #212529 !important;
        }
    </style>

    <script>
        const storedTheme = localStorage.getItem('theme');
        if (storedTheme === 'dark' || (!storedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.setAttribute('data-bs-theme', 'dark');
        }
    </script>
</head>
<body>

<nav class="px-3 shadow-sm navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand fw-bold" href="#">📢 KELUH.IN</a>

    <div class="gap-3 ms-auto d-flex align-items-center">
        <button id="theme-toggle" class="border-0 btn btn-outline-light">
            <i id="theme-icon" class="bi bi-moon-stars-fill"></i>
        </button>

        @auth
            <a href="/profile" class="btn btn-light btn-sm">Profile</a>
            <form method="POST" action="/admin/logout" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">Logout</button>
            </form>
        @else
            <a href="/admin/login" class="btn btn-light btn-sm">Login</a>
        @endauth
    </div>
</nav>

<div class="container py-4">
    @yield('content')
</div>

<footer class="py-3 text-center text-muted">
    © {{ date('Y') }} KELUH.IN - Sistem Pengaduan
</footer>

<script>
    const toggleBtn = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');
    const htmlEl = document.documentElement;

    // Fungsi update ikon
    function updateIcon(theme) {
        if (theme === 'dark') {
            themeIcon.classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
        } else {
            themeIcon.classList.replace('bi-sun-fill', 'bi-moon-stars-fill');
        }
    }

    // Set ikon awal sesuai tema saat ini
    updateIcon(htmlEl.getAttribute('data-bs-theme'));

    toggleBtn.addEventListener('click', () => {
        const currentTheme = htmlEl.getAttribute('data-bs-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

        htmlEl.setAttribute('data-bs-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateIcon(newTheme);
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
