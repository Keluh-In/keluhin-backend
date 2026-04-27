# Keluhin Backend

Backend Laravel untuk aplikasi Keluhin. Project ini menyediakan REST API untuk auth, profil, kategori, pengaduan, tanggapan admin, serta dashboard admin berbasis Blade.

## Requirement

- PHP 8.3 atau lebih baru
- Composer
- Node.js dan npm
- SQLite untuk development lokal

Project ini nyaman dijalankan dengan Laravel Herd di macOS atau Laragon di Windows.

## Setup

Clone repository dan masuk ke folder project:

```bash
git clone <url-repository>
cd keluhin-backend
```

Install dependency:

```bash
composer install
npm install
```

Siapkan file environment:

```bash
cp .env.example .env
php artisan key:generate
```

Untuk Windows PowerShell, gunakan:

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

## Database

Project ini memakai SQLite untuk development lokal. Buat file database:

```bash
touch database/database.sqlite
```

Untuk Windows PowerShell:

```powershell
New-Item -ItemType File database/database.sqlite
```

Atur konfigurasi database di `.env`:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Jika path relatif tidak terbaca, gunakan absolute path ke file `database/database.sqlite`.

Jalankan migration dan seeder:

```bash
php artisan migrate:fresh --seed
```

Seeder membuat data awal seperti admin, user contoh, kategori, dan pengaduan dummy.

Contoh akun admin:

```text
Email: admin@keluhin.com
Password: password123
```

## Menjalankan Project

Jalankan Laravel server:

```bash
php artisan serve
```

Buka aplikasi:

```text
http://127.0.0.1:8000
```

Admin panel:

```text
http://127.0.0.1:8000/admin/login
http://127.0.0.1:8000/admin/dashboard
```

Untuk asset frontend:

```bash
npm run dev
```

Build asset production:

```bash
npm run build
```

## Dokumentasi API

Dokumentasi API tersedia dalam format Swagger/OpenAPI.

Swagger UI:

```text
http://127.0.0.1:8000/api/documentation
```

OpenAPI JSON:

```text
http://127.0.0.1:8000/api/documentation/openapi.json
```

File spesifikasi berada di:

```text
docs/openapi.json
```

Endpoint yang dilindungi memakai Laravel Sanctum bearer token. Ambil token dari endpoint login, lalu gunakan header:

```http
Authorization: Bearer <token>
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
GET    /api/complaints/{id}/responses
```

Categories:

```text
GET /api/categories
```

## Testing

Jalankan test:

```bash
php artisan test
```

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

Reset database dan isi ulang data:

```bash
php artisan migrate:fresh --seed
```

## Troubleshooting

Jika muncul error `vendor/autoload.php not found`:

```bash
composer install
```

Jika muncul error `No application encryption key has been specified`:

```bash
php artisan key:generate
php artisan config:clear
```

Jika muncul error `no such table: sessions` atau tabel lain belum ada:

```bash
php artisan migrate
```

Jika `php` atau `composer` tidak ditemukan di macOS dengan Laravel Herd:

```bash
source ~/.zshrc
php -v
composer --version
```

Jika memakai Laragon di Windows dan `php` belum terbaca, aktifkan path dari menu:

```text
Menu > Tools > Path > Add Laragon to Path
```
