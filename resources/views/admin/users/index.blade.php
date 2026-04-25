<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Users - Admin Keluh.in</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 30px;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        h1 {
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            background: #2563eb;
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background: #f1f5f9;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            color: #fff;
        }

        .admin {
            background: #ef4444;
        }

        .user {
            background: #10b981;
        }

        .action-btn {
            padding: 6px 10px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 12px;
        }

        .edit {
            background: #f59e0b;
            color: #fff;
        }

        .delete {
            background: #ef4444;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="card">

        <h1>👤 Users Management</h1>

        <a href="#" class="btn">+ Tambah User</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                <tr>
                    <td>1</td>
                    <td>Rizqi Aulia</td>
                    <td>rizqi@example.com</td>
                    <td><span class="badge admin">Admin</span></td>
                    <td>
                        <a href="#" class="action-btn edit">Edit</a>
                        <a href="#" class="action-btn delete">Hapus</a>
                    </td>
                </tr>

                <tr>
                    <td>2</td>
                    <td>Budi Santoso</td>
                    <td>budi@example.com</td>
                    <td><span class="badge user">User</span></td>
                    <td>
                        <a href="#" class="action-btn edit">Edit</a>
                        <a href="#" class="action-btn delete">Hapus</a>
                    </td>
                </tr>

            </tbody>
        </table>

    </div>

</div>

</body>
</html>