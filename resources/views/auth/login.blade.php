<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - KELUH.IN</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(120deg, #0d6efd, #4dabf7);
            height: 100vh;
        }

        .card {
            border-radius: 15px;
        }
    </style>
</head>
<body>
<form method="POST" action="/login">
    @csrf
<div class="container d-flex justify-content-center align-items-center h-100">

    <div class="col-md-5">

        <div class="card shadow p-4">

            <h3 class="text-center mb-4">🔐 Login KELUH.IN</h3>

            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="/login">
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
                <a href="/register">Register</a>
            </p>

        </div>

    </div>

</div>

</body>
</html>