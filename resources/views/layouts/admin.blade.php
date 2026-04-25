<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - KELUH.IN</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            background: #0d6efd;
            color: white;
        }

        .sidebar a {
            display: block;
            padding: 12px;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 5px;
        }

        .sidebar a:hover {
            background: rgba(255,255,255,0.2);
        }

        .main {
            margin-left: 260px;
            padding: 20px;
        }

        .card {
            border-radius: 12px;
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar p-3">

    <h4 class="mb-4">📢 KELUH.IN</h4>

    <a href="/admin/dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="/admin/complaints"><i class="bi bi-chat-dots"></i> Pengaduan</a>
    <a href="/admin/categories"><i class="bi bi-folder"></i> Kategori</a>
    <a href="/admin/users"><i class="bi bi-people"></i> Users</a>

    <hr>

    <a href="/logout"><i class="bi bi-box-arrow-right"></i> Logout</a>

</div>

<!-- MAIN CONTENT -->
<div class="main">

    <!-- TOP BAR -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Dashboard Admin</h3>

        <span class="badge bg-primary">
            {{ auth()->user()->name ?? 'Admin' }}
        </span>
    </div>

    @yield('content')

</div>

</body>
</html>