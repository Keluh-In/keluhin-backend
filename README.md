# Keluhin Backend

Backend Laravel untuk aplikasi Keluhin. Project ini menyediakan API auth, kategori, pengaduan, dan halaman admin dashboard sederhana.

## Requirement

Pastikan sudah terinstall:

- PHP 8.3 atau lebih baru
- Composer
- Node.js dan npm
- SQLite

Environment yang dipakai:

- macOS: Laravel Herd
- Windows: Laragon

## Catatan Environment

### macOS + Laravel Herd

Laravel Herd biasanya sudah menyediakan PHP dan Composer.

Jika `php` atau `composer` belum terbaca di terminal, jalankan:

```bash
source ~/.zshrc
```

Lalu cek:

```bash
php -v
composer --version
```

### Windows + Laragon

Jalankan command dari terminal Laragon, PowerShell, Command Prompt, Git Bash, atau terminal bawaan editor.

Pastikan PHP dan Composer dari Laragon sudah terbaca:

```bash
php -v
composer --version
```

Jika belum terbaca, buka Laragon lalu cek menu:

```text
Menu > Tools > Path > Add Laragon to Path
```

Setelah itu tutup terminal lama, buka terminal baru, lalu cek ulang `php -v` dan `composer --version`.

## Setup Dari Awal

Clone repository:

```bash
git clone <url-repository>
cd keluhin-backend
```

Install dependency PHP:

```bash
composer install
```

Install dependency frontend:

```bash
npm install
```

Copy file environment.

macOS / Linux / Git Bash:

```bash
cp .env.example .env
```

Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Windows Command Prompt:

```bat
copy .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

## Konfigurasi Database

Project ini memakai SQLite untuk development lokal.

Disarankan pakai file SQLite di folder `database`.

macOS / Linux / Git Bash:

```bash
touch database/database.sqlite
```

Windows PowerShell:

```powershell
New-Item -ItemType File database/database.sqlite
```

Windows Command Prompt:

```bat
type nul > database\database.sqlite
```

Lalu ubah `.env` menjadi salah satu opsi berikut.

Opsi paling universal:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Jika SQLite tidak terbaca dengan path relatif, gunakan absolute path sesuai OS.

macOS / Linux:

```env
DB_CONNECTION=sqlite
DB_DATABASE=/path/ke/project/keluhin-backend/database/database.sqlite
```

Windows:

```env
DB_CONNECTION=sqlite
DB_DATABASE=C:\path\ke\project\keluhin-backend\database\database.sqlite
```

Contoh jika project ada di Laragon:

```env
DB_CONNECTION=sqlite
DB_DATABASE=C:\laragon\www\keluhin-backend\database\database.sqlite
```

Bagian berikut tidak dipakai oleh SQLite, jadi boleh dibiarkan atau dikomentari:

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_USERNAME=root
DB_PASSWORD=
```

Catatan: jika `.env` memakai `DB_DATABASE=keluhin`, Laravel akan membuat file SQLite bernama `keluhin` di root project.

## Migrasi dan Seeder

Untuk membuat ulang database dari nol dan mengisi data contoh:

```bash
php artisan migrate:fresh --seed
```

Seeder akan membuat:

- akun admin
- user dummy
- kategori pengaduan
- data pengaduan dummy

Contoh akun admin:

```text
Email: admin@keluhin.com
Password: password123
```

## Menjalankan Project

Jalankan server Laravel:

```bash
php artisan serve
```

Buka:

```text
http://127.0.0.1:8000
```

Admin login:

```text
http://127.0.0.1:8000/admin/login
```

Admin dashboard:

```text
http://127.0.0.1:8000/admin/dashboard
```

Jika belum login, dashboard akan redirect ke halaman login admin.

## Menjalankan Asset Frontend

Untuk development Vite:

```bash
npm run dev
```

Untuk build asset:

```bash
npm run build
```

## Endpoint API Utama

Auth:

```text
POST /api/register
POST /api/login
POST /api/logout
```

Profile:

```text
GET /api/profile
PUT /api/profile
```

Complaints:

```text
GET    /api/complaints
POST   /api/complaints
GET    /api/complaints/{id}
PUT    /api/complaints/{id}
DELETE /api/complaints/{id}
```

Categories:

```text
GET /api/categories
```

Endpoint yang berada di dalam middleware `auth:sanctum` membutuhkan token dari `/api/login`.

## Membuka Database di TablePlus

Jika memakai konfigurasi yang disarankan, pilih file:

```text
database/database.sqlite
```

Jika masih memakai konfigurasi lama `DB_DATABASE=keluhin`, pilih file bernama `keluhin` di root project.

```text
keluhin
```

Di TablePlus:

1. Buat connection baru.
2. Pilih SQLite.
3. Pilih file database.
4. Klik Connect.

Tabel utama:

- users
- categories
- complaints
- responses
- sessions
- jobs
- personal_access_tokens

## Command Berguna

Cek route:

```bash
php artisan route:list
```

Cek status migration:

```bash
php artisan migrate:status
```

Clear config cache:

```bash
php artisan config:clear
```

Reset database dan isi ulang data contoh:

```bash
php artisan migrate:fresh --seed
```

## Troubleshooting

Jika muncul error `vendor/autoload.php not found`, jalankan:

```bash
composer install
```

Jika muncul error `No application encryption key has been specified`, jalankan:

```bash
php artisan key:generate
php artisan config:clear
```

Jika muncul error `no such table: sessions`, pastikan migration sudah dijalankan:

```bash
php artisan migrate
```

Jika `php` atau `composer` tidak ditemukan tetapi sudah install lewat Laravel Herd:

```bash
source ~/.zshrc
php -v
composer --version
```
