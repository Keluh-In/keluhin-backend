<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - KELUH.IN</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        html,
        body {
            min-height: 100%;
        }

        body {
            background: linear-gradient(120deg, #0d6efd, #4dabf7);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .card {
            border-radius: 15px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-11 col-sm-8 col-md-6 col-lg-4">
            <div class="card shadow p-4">

            <h3 class="text-center mb-4">🔐 Login KELUH.IN</h3>

            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="alert alert-info small">
                <div class="fw-semibold mb-1">Contoh akun admin</div>
                <div>Email: <strong>admin@keluhin.com</strong></div>
                <div>Password: <strong>password123</strong></div>
            </div>

            <form method="POST" action="/admin/login">
                @csrf

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button class="btn btn-primary w-100">Login</button>
            </form>

            <p class="text-center mt-3">
                Belum punya akun?
                <a href="/admin/register">Register</a>
            </p>

            </div>
        </div>
    </div>
</div>

</body>
</html>
