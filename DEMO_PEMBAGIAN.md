# Pembagian Demo UAS — Keluhin Backend (3 Orang)

> Dokumen ini cuma buat panduan presentasi/video, tidak ada kode yang diubah.

**Stack:** Laravel 13 (PHP 8.3) + Sanctum (token API) + Blade (admin panel) + SQLite.
**Alur besar app:** User pakai API (register/login → bikin keluhan → pantau status) → Admin pakai web panel `/admin` buat handle keluhan masuk → Admin/super admin atur data master (kategori, user) & lihat audit log.

---

## Orang 1 — Sisi User / API Mobile

**Fitur yang didemo:**
1. Register & Login (dapat token)
2. Kelola Keluhan milik sendiri (buat, lihat list/detail, edit, hapus, lihat balasan admin)

**Urutan demo (karena ini pure API, demo lewat Bruno/Postman — repo udah ada collection siap pakai di `bruno/keluhin/`):**
1. `POST /api/register` → kasih lihat response berisi data user + token
2. `POST /api/login` → tunjukin token dipakai sebagai Bearer Auth buat request selanjutnya
3. `GET /api/categories` → lihat daftar kategori keluhan
4. `POST /api/complaints` → submit keluhan baru (judul, kategori, deskripsi, opsional foto) — tunjukin response ada `image_url` siap dipakai langsung di frontend
5. `GET /api/complaints` → lihat list keluhan sendiri
6. `GET /api/complaints/stats` → lihat ringkasan jumlah keluhan per status (total/menunggu/diproses/selesai/ditolak)
7. `GET /api/complaints/{id}` → lihat detail satu keluhan
8. `PUT /api/complaints/{id}` → coba edit (tunjukin masih bisa karena status masih "menunggu")
9. `GET /api/complaints/{id}/responses` → cek apakah ada balasan dari admin
10. `DELETE /api/complaints/{id}` → hapus keluhan

**File & fungsi yang dijelaskan:**
- [app/Http/Controllers/Api/AuthController.php](app/Http/Controllers/Api/AuthController.php) — `register()` (baris 18-37), `login()` (baris 42-62)
- [app/Http/Controllers/Api/ComplaintController.php](app/Http/Controllers/Api/ComplaintController.php) — `index()`, `stats()`, `store()`, `show()`, `update()`, `destroy()` (baris 24-135)
- [app/Http/Controllers/Api/ResponseController.php](app/Http/Controllers/Api/ResponseController.php) — `index()` (baris 16-27)
- [app/Services/FileUploadService.php](app/Services/FileUploadService.php) — `upload()` (baris 12-21)
- [app/Models/Complaint.php](app/Models/Complaint.php) — `$fillable` (baris 17-19) & accessor `getImageUrlAttribute()` (baris 29-32)

**Poin alur logic yang perlu diomongin:**
1. Pas register, password di-hash dulu pakai `Hash::make()` sebelum disimpan, terus langsung dibuatin token pakai Sanctum (`createToken()`) — jadi user langsung login tanpa perlu request kedua.
2. Pas login, cek password-nya pakai `Hash::check()` (bandingin plain text vs hash), kalau salah ya return error 401.
3. Pas bikin keluhan baru, status-nya otomatis di-set `'menunggu'` — user nggak bisa milih status sendiri pas create.
4. Ada aturan: keluhan cuma bisa di-edit/dihapus selama status-nya masih `'menunggu'`. Begitu admin udah proses (status berubah), endpoint update/delete langsung nolak. Ini dicek manual lewat if-check `$complaint->status !== 'menunggu'`, bukan lewat policy/gate.
5. Upload foto keluhan dilempar ke `FileUploadService`, fungsinya simple: simpan file ke folder `storage/app/public/uploads/complaints` dan balikin path-nya buat disimpan ke kolom `image`. Sempat ada bug di mana kolom `image` ketinggalan dari `$fillable` model, jadi foto-nya kesimpen tapi nggak ke-assign — udah dibenerin, sekarang model juga punya accessor `image_url` yang otomatis bikin URL publik penuh, jadi frontend nggak perlu nyusun path manual.
6. Endpoint `stats()` itu satu query doang: `selectRaw('status, COUNT(*) as total')` + `groupBy('status')`, terus di-`pluck` jadi map status→jumlah. Yang perlu disebut: route-nya `/complaints/stats` harus didaftarkan **sebelum** `/complaints/{id}` di `routes/api.php`, karena kalau kebalik, `{id}` bakal "nyalip" dan nganggep `stats` itu nilai `id`.

---

## Orang 2 — Admin: Penanganan Keluhan

**Fitur yang didemo:**
1. Dashboard admin (statistik)
2. Kelola Keluhan & Balasan (lihat detail, ubah status, kasih tanggapan)
3. Validasi Lampiran/Bukti

**Urutan klik di website:**
1. Buka `/admin/login` → login pakai akun admin
2. Otomatis masuk `/admin/dashboard` → tunjukin kartu statistik (total, menunggu, diproses, selesai, ditolak) + tabel 6 keluhan terbaru
3. Klik menu "Keluhan" (`/admin/complaints`) → lihat semua keluhan dari semua user
4. Klik salah satu keluhan → masuk halaman detail (`/admin/complaints/{id}`)
5. Tunjukin form ubah status (dropdown menunggu/diproses/selesai/ditolak) → submit
6. Tunjukin form tambah tanggapan/balasan → submit
7. Tunjukin upload lampiran bukti → lalu klik validasi (approve/reject + catatan)

**File & fungsi yang dijelaskan:**
- [app/Http/Controllers/Admin/DashboardController.php](app/Http/Controllers/Admin/DashboardController.php) — `index()` (baris 11-34)
- [app/Http/Controllers/Admin/ComplaintController.php](app/Http/Controllers/Admin/ComplaintController.php) — `index()`, `show()`, `update()`, `destroy()` (baris 15-87)
- [app/Http/Controllers/Admin/ComplaintResponseController.php](app/Http/Controllers/Admin/ComplaintResponseController.php) — `store()`, `update()`, `destroy()` (baris 13-78)
- [app/Http/Controllers/Admin/ComplaintAttachmentController.php](app/Http/Controllers/Admin/ComplaintAttachmentController.php) — `store()`, `validateAttachment()`, `file()` (baris 19-154)

**Poin alur logic yang perlu diomongin:**
1. Dashboard itu cuma 4 query `count()` terpisah berdasarkan kolom status (`menunggu`, `diproses`, `selesai`, `ditolak`) — nggak ada perhitungan rumit, murni hitung jumlah baris per status.
2. Pas admin update keluhan, kode ambil snapshot data "sebelum" dan "sesudah" (`AdminAuditLog::snapshot()`), terus dibandingin — kalau yang berubah itu kolom `status`, action audit-nya dicatat beda (`complaint.status_changed`), kalau bukan ya `complaint.updated`. Jadi audit log-nya cukup pintar buat ngebedain jenis perubahan.
3. Nambah tanggapan itu simpel: bikin record baru di tabel `responses` terus langsung dicatat ke audit log — tapi perlu disebutin: nambah tanggapan ini **nggak otomatis** mengubah status keluhan jadi "diproses", itu harus diubah manual lewat dropdown status (terpisah).
4. Validasi lampiran (`validateAttachment()`) cuma update 4 kolom: `is_validated` (boolean), `validated_by` (siapa yang validasi), `validated_at` (kapan), `validation_note` (alasan/catatan) — semacam approval flow sederhana.
5. Upload lampiran dibatasi: maksimal 5MB, tipe file cuma jpg/jpeg/png/webp/pdf/doc/docx — validasinya pakai Laravel validation rule `max:5120` + `mimes:...`, jadi kalau upload di luar itu langsung ditolak sebelum masuk logic.

---

## Orang 3 — Admin: Manajemen Data & Tata Kelola

**Fitur yang didemo:**
1. Kelola Kategori
2. Kelola User (app user & akun admin, termasuk ban/unban)
3. Audit Log

**Urutan klik di website:**
1. Login admin → `/admin/categories` → tambah kategori baru, edit nama, hapus kategori
2. Klik menu "Users" (`/admin/users`) → tambah app user, edit, ban, unban, hapus
3. (login sebagai super admin) klik menu "Admin Users" (`/admin/admin-users`) → tambah akun admin/super admin, coba ubah role, coba ban
4. Coba aksi yang **gagal** dengan sengaja buat demo guard rail: ban diri sendiri, atau demote satu-satunya super admin → tunjukin pesan error
5. Klik menu "Audit Log" (`/admin/audit-logs`) → tunjukin histori semua aksi di atas tercatat siapa-ngapain-kapan

**File & fungsi yang dijelaskan:**
- [app/Http/Controllers/Admin/CategoryController.php](app/Http/Controllers/Admin/CategoryController.php) — `store()`, `update()`, `destroy()` (baris 20-80)
- [app/Http/Controllers/Admin/UserController.php](app/Http/Controllers/Admin/UserController.php) — `storeAppUser()`, `updateAdminUser()`, `banAppUser()`/`banAdminUser()`, `destroyAdminUser()`, `canModifyCurrentSuperAdminCount()` (baris 38-359)
- [app/Http/Controllers/Admin/AuditLogController.php](app/Http/Controllers/Admin/AuditLogController.php) — `index()` (baris 10-17)
- [app/Models/AdminAuditLog.php](app/Models/AdminAuditLog.php) — `record()`, `snapshot()` (baris 35-59)
- [app/Http/Middleware/AdminMiddleware.php](app/Http/Middleware/AdminMiddleware.php) & [app/Http/Middleware/SuperAdminMiddleware.php](app/Http/Middleware/SuperAdminMiddleware.php)

**Poin alur logic yang perlu diomongin:**
1. Nama kategori wajib unik. Pas tambah pakai rule `unique:categories,name`, tapi pas edit dia pakai `Rule::unique(...)->ignore($category->id)` — supaya kategori bisa di-update tanpa nabrak validasi unique ke nama dirinya sendiri.
2. Ada beberapa "pagar pengaman" di `UserController`: admin nggak bisa ngubah role akun yang dia pake sendiri, dan sistem nggak bolehin demote/ban/hapus super admin kalau itu satu-satunya super admin aktif yang tersisa — dicek lewat helper `canModifyCurrentSuperAdminCount()` yang ngitung berapa super admin aktif (bukan banned, bukan soft-deleted).
3. Ban itu konsepnya simpel: cuma isi kolom `banned_at` dengan timestamp sekarang. Nanti pas user/admin yang dibanned coba akses, `AdminMiddleware` bakal cek status ini dan langsung logout + redirect ke login.
4. Tiap aksi penting (create/update/delete/ban) di seluruh panel admin manggil `AdminAuditLog::record()` yang nyimpen siapa pelakunya (`actor_id`), aksi apa, dan data sebelum-sesudah dalam format JSON (`old_values`/`new_values`) — jadi semua perubahan bisa di-trace di halaman Audit Log.
5. Fitur "Admin Users" dan "Audit Log" itu dibatasi cuma buat super admin lewat `SuperAdminMiddleware` (beda dari `AdminMiddleware` yang cuma cek admin biasa) — jadi ada 2 layer middleware: layer 1 cek "ini admin apa bukan", layer 2 cek "ini super admin apa cuma admin biasa".

---

## Catatan Pembagian

- Beban ketiga orang seimbang: masing-masing 2-3 fitur inti, demo bisa diselesaikan dalam ~3-5 menit per orang.
- Pengelompokan ngikutin alur pakai aplikasi: User (API) → Admin handle keluhan → Admin atur data/governance.
- File config, migration, seeder, dan boilerplate Laravel lainnya nggak perlu dijelaskan di video — fokus ke controller/model/logic yang disebut di atas aja.
