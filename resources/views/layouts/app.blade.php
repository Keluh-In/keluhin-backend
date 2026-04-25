<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>KELUH.IN</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        .navbar {
            background: #0d6efd;
        }

        .navbar a {
            color: white !important;
        }

        .card {
            border-radius: 12px;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark px-3">
    <a class="navbar-brand fw-bold" href="#">📢 KELUH.IN</a>

    <div class="ms-auto">
        @auth
            <a href="/profile" class="btn btn-light btn-sm">Profile</a>
            <a href="/logout" class="btn btn-danger btn-sm">Logout</a>
        @else
            <a href="/login" class="btn btn-light btn-sm">Login</a>
        @endauth
    </div>
</nav>

<!-- CONTENT -->
<div class="container py-4">
    @yield('content')
</div>

<!-- FOOTER -->
<footer class="text-center py-3 text-muted">
    © {{ date('Y') }} KELUH.IN - Sistem Pengaduan
</footer>

</body>
</html>