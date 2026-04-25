<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - KELUH.IN</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(120deg, #20c997, #0d6efd);
            height: 100vh;
        }

        .card {
            border-radius: 15px;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center h-100">

    <div class="col-md-5">

        <div class="card shadow p-4">

            <h3 class="text-center mb-4">📝 Register KELUH.IN</h3>

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="/register">
                @csrf

                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                 <select name="role">
        <option value="">-- Pilih Role --</option>
        <option value="user">User</option>
        <option value="admin">Admin</option>
    </select><br>

                <button class="btn btn-success w-100">Register</button>
            </form>

            <p class="text-center mt-3">
                Sudah punya akun?
                <a href="/login">Login</a>
            </p>

        </div>

    </div>

</div>


</form>
</html>