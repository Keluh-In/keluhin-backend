<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard') - KELUH.IN</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --admin-bg: #f7f9fb;
            --admin-surface: #ffffff;
            --admin-text: #111827;
            --admin-muted: #6b7280;
            --admin-line: #edf1f5;
            --admin-primary: #2563eb;
            --admin-primary-soft: #eff6ff;
            --admin-shadow: 0 16px 40px rgba(15, 23, 42, 0.06);
            --admin-radius: 8px;
        }

        body {
            min-height: 100vh;
            margin: 0;
            background: var(--admin-bg);
            color: var(--admin-text);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .admin-shell {
            display: grid;
            grid-template-columns: 248px minmax(0, 1fr);
            min-height: 100vh;
        }

        .sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            padding: 28px 20px;
            background: var(--admin-surface);
            border-right: 1px solid var(--admin-line);
        }

        .brand-mark {
            display: inline-flex;
            width: 34px;
            height: 34px;
            align-items: center;
            justify-content: center;
            border-radius: var(--admin-radius);
            background: var(--admin-primary-soft);
            color: var(--admin-primary);
        }

        .brand-title {
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 0;
        }

        .brand-subtitle {
            color: var(--admin-muted);
            font-size: .78rem;
        }

        .sidebar-nav {
            display: grid;
            gap: 6px;
            margin-top: 32px;
        }

        .sidebar-link,
        .sidebar-action {
            display: flex;
            gap: 10px;
            align-items: center;
            width: 100%;
            padding: 11px 12px;
            color: #4b5563;
            text-decoration: none;
            border: 0;
            border-radius: var(--admin-radius);
            background: transparent;
            font-size: .94rem;
            font-weight: 500;
            transition: background .18s ease, color .18s ease;
        }

        .sidebar-link i,
        .sidebar-action i {
            color: #94a3b8;
            font-size: 1rem;
        }

        .sidebar-link:hover,
        .sidebar-action:hover,
        .sidebar-link.is-active {
            background: var(--admin-primary-soft);
            color: var(--admin-primary);
        }

        .sidebar-link:hover i,
        .sidebar-action:hover i,
        .sidebar-link.is-active i {
            color: var(--admin-primary);
        }

        .sidebar-footer {
            margin-top: auto;
            padding-top: 24px;
            border-top: 1px solid var(--admin-line);
        }

        .main {
            min-width: 0;
            padding: 28px;
        }

        .content-wrap {
            width: min(100%, 1180px);
            margin: 0 auto;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 28px;
        }

        .page-title {
            margin: 0;
            font-size: clamp(1.35rem, 1vw + 1rem, 1.9rem);
            font-weight: 700;
            letter-spacing: 0;
        }

        .page-kicker {
            margin-top: 4px;
            color: var(--admin-muted);
            font-size: .94rem;
        }

        .user-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 999px;
            background: var(--admin-surface);
            box-shadow: var(--admin-shadow);
            color: #374151;
            font-size: .9rem;
            white-space: nowrap;
        }

        .avatar-dot {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #dbeafe;
            color: var(--admin-primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: .82rem;
        }

        .card {
            border: 0;
            border-radius: var(--admin-radius);
            box-shadow: var(--admin-shadow);
        }

        .table {
            color: #374151;
        }

        .table > :not(caption) > * > * {
            padding: 1rem;
            border-bottom-color: var(--admin-line);
        }

        .table thead th {
            color: var(--admin-muted);
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .02em;
            text-transform: uppercase;
            background: #fbfcfe;
        }

        .badge-soft {
            border-radius: 999px;
            padding: .42rem .65rem;
            font-weight: 600;
            font-size: .76rem;
        }

        .badge-menunggu {
            background: #fff7ed;
            color: #c2410c;
        }

        .badge-diproses {
            background: #eef6ff;
            color: #1d4ed8;
        }

        .badge-selesai {
            background: #ecfdf5;
            color: #047857;
        }

        .badge-ditolak {
            background: #fef2f2;
            color: #b91c1c;
        }

        .btn-primary {
            background: var(--admin-primary);
            border-color: var(--admin-primary);
            border-radius: var(--admin-radius);
            box-shadow: 0 10px 24px rgba(37, 99, 235, 0.18);
        }

        .btn-light {
            border-radius: var(--admin-radius);
        }

        .section-card {
            overflow: hidden;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 22px 24px;
            border-bottom: 1px solid var(--admin-line);
        }

        .section-title {
            margin: 0;
            color: var(--admin-text);
            font-size: 1.02rem;
            font-weight: 700;
            letter-spacing: 0;
        }

        .section-subtitle {
            margin-top: 4px;
            color: var(--admin-muted);
            font-size: .88rem;
        }

        .metric-strip {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }

        .mini-metric {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 18px;
        }

        .mini-metric-label {
            color: var(--admin-muted);
            font-size: .86rem;
            font-weight: 600;
        }

        .mini-metric-value {
            margin-top: 6px;
            color: var(--admin-text);
            font-size: 1.65rem;
            font-weight: 700;
            line-height: 1;
        }

        .mini-metric-icon {
            display: inline-flex;
            width: 38px;
            height: 38px;
            align-items: center;
            justify-content: center;
            border-radius: var(--admin-radius);
            background: var(--admin-primary-soft);
            color: var(--admin-primary);
        }

        .empty-state {
            padding: 44px 24px;
            color: #94a3b8;
            text-align: center;
        }

        @media (max-width: 991.98px) {
            .admin-shell {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: static;
                height: auto;
                padding: 18px;
                border-right: 0;
                border-bottom: 1px solid var(--admin-line);
            }

            .sidebar-nav {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                margin-top: 18px;
            }

            .sidebar-footer {
                margin-top: 16px;
                padding-top: 16px;
            }

            .main {
                padding: 20px 16px 28px;
            }

            .topbar {
                align-items: flex-start;
                flex-direction: column;
            }
        }

        @media (max-width: 575.98px) {
            .metric-strip {
                grid-template-columns: 1fr;
            }

            .section-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .sidebar-nav {
                grid-template-columns: 1fr;
            }

            .user-pill {
                width: 100%;
                justify-content: space-between;
            }
        }
    </style>
</head>
<body>

<div class="admin-shell">
    <aside class="sidebar d-flex flex-column">
        <div class="d-flex align-items-center gap-3">
            <span class="brand-mark"><i class="bi bi-chat-square-text"></i></span>
            <div>
                <div class="brand-title">KELUH.IN</div>
                <div class="brand-subtitle">Admin workspace</div>
            </div>
        </div>

        <nav class="sidebar-nav" aria-label="Admin navigation">
            <a class="sidebar-link {{ request()->is('admin/dashboard') ? 'is-active' : '' }}" href="/admin/dashboard">
                <i class="bi bi-grid-1x2"></i>
                Dashboard
            </a>
            <a class="sidebar-link {{ request()->is('admin/complaints*') ? 'is-active' : '' }}" href="/admin/complaints">
                <i class="bi bi-chat-dots"></i>
                Pengaduan
            </a>
            <a class="sidebar-link {{ request()->is('admin/categories*') ? 'is-active' : '' }}" href="/admin/categories">
                <i class="bi bi-folder2-open"></i>
                Kategori
            </a>
            <a class="sidebar-link {{ request()->is('admin/users*') ? 'is-active' : '' }}" href="/admin/users">
                <i class="bi bi-people"></i>
                Users
            </a>
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="/admin/logout">
                @csrf
                <button type="submit" class="sidebar-action text-start">
                    <i class="bi bi-box-arrow-right"></i>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <main class="main">
        <div class="content-wrap">
            <header class="topbar">
                <div>
                    <h1 class="page-title">@yield('title', 'Dashboard')</h1>
                    <div class="page-kicker">@yield('subtitle', 'Kelola laporan dan aktivitas pengguna dengan ringkas.')</div>
                </div>

                @php
                    $adminName = auth()->user()->name ?? 'Admin';
                @endphp
                <div class="user-pill">
                    <span class="avatar-dot">{{ strtoupper(substr($adminName, 0, 1)) }}</span>
                    <span>{{ $adminName }}</span>
                </div>
            </header>

            @yield('content')
        </div>
    </main>
</div>

</body>
</html>
