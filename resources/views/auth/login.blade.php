<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - KELUH.IN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --auth-bg: #f7f9fb;
            --auth-surface: #ffffff;
            --auth-text: #111827;
            --auth-muted: #6b7280;
            --auth-line: #edf1f5;
            --auth-primary: #2563eb;
            --auth-primary-soft: #eff6ff;
            --auth-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
            --auth-radius: 8px;
        }

        body {
            min-height: 100vh;
            margin: 0;
            background:
                linear-gradient(180deg, rgba(239, 246, 255, .72), rgba(247, 249, 251, 0) 42%),
                var(--auth-bg);
            color: var(--auth-text);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .auth-shell {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 32px 16px;
        }

        .auth-card {
            width: min(100%, 430px);
            padding: 30px;
            border: 0;
            border-radius: var(--auth-radius);
            background: var(--auth-surface);
            box-shadow: var(--auth-shadow);
        }

        .brand-mark {
            display: inline-flex;
            width: 42px;
            height: 42px;
            align-items: center;
            justify-content: center;
            border-radius: var(--auth-radius);
            background: var(--auth-primary-soft);
            color: var(--auth-primary);
            font-size: 1.2rem;
        }

        .auth-title {
            margin: 18px 0 6px;
            color: var(--auth-text);
            font-size: 1.45rem;
            font-weight: 700;
            letter-spacing: 0;
        }

        .auth-subtitle {
            margin: 0 0 24px;
            color: var(--auth-muted);
            font-size: .94rem;
        }

        .form-label {
            color: #374151;
            font-size: .88rem;
            font-weight: 600;
        }

        .form-control {
            min-height: 46px;
            border-color: var(--auth-line);
            border-radius: var(--auth-radius);
            color: var(--auth-text);
        }

        .form-control:focus {
            border-color: #bfdbfe;
            box-shadow: 0 0 0 .22rem rgba(37, 99, 235, .11);
        }

        .btn-primary {
            min-height: 46px;
            border-color: var(--auth-primary);
            border-radius: var(--auth-radius);
            background: var(--auth-primary);
            box-shadow: 0 12px 28px rgba(37, 99, 235, .18);
            font-weight: 600;
        }

        .hint-box {
            padding: 14px;
            border: 1px solid #dbeafe;
            border-radius: var(--auth-radius);
            background: #f8fbff;
            color: #1e3a8a;
            font-size: .86rem;
        }

        .auth-link {
            color: var(--auth-primary);
            font-weight: 600;
            text-decoration: none;
        }

        .auth-link:hover {
            color: #1d4ed8;
        }

        .alert {
            border: 0;
            border-radius: var(--auth-radius);
        }
    </style>
</head>
<body>
<main class="auth-shell">
    <section class="auth-card">
        <div class="text-center">
            <span class="brand-mark"><i class="bi bi-chat-square-text"></i></span>
            <h1 class="auth-title">Login KELUH.IN</h1>
            <p class="auth-subtitle">Masuk untuk mengelola dashboard pengaduan.</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="hint-box mb-4">
            <div class="fw-semibold mb-1">Contoh akun admin</div>
            <div>Email: <strong>admin@keluhin.com</strong></div>
            <div>Password: <strong>password123</strong></div>
        </div>

        <form method="POST" action="/admin/login">
            @csrf

            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
            </div>

            <div class="mb-4">
                <label class="form-label" for="password">Password</label>
                <input id="password" type="password" name="password" class="form-control" required>
            </div>

            <button class="btn btn-primary w-100" type="submit">Login</button>
        </form>

        <p class="text-center mt-4 mb-0 text-muted">
            Belum punya akun?
            <a class="auth-link" href="/admin/register">Register</a>
        </p>
    </section>
</main>
</body>
</html>
