# PRD Admin Dashboard KELUH.IN

## 1. Ringkasan

Admin Dashboard KELUH.IN adalah panel internal untuk mengelola data pengaduan, kategori, dan akun pengguna. Panel ini hanya boleh diakses oleh akun dengan role `admin` atau `super_admin`.

Dokumen ini menjadi acuan saat menambah fitur baru agar alur data, permission, tampilan UI, validasi, routing, dan pola CRUD tetap seragam.

## 2. Tujuan Produk

- Menyediakan panel admin yang jelas, rapi, dan mudah dipakai untuk operasional pengaduan.
- Menjaga data pengaduan, kategori, dan pengguna agar tidak bentrok antarfitur.
- Membuat pola pengembangan fitur admin konsisten dan mudah dipelihara.
- Memisahkan pengalaman admin dashboard dari pengalaman pengguna biasa.

## 3. Scope

Dokumen ini hanya berlaku untuk Admin Dashboard web.

Termasuk:
- Login admin.
- Dashboard analytics.
- CRUD pengaduan.
- CRUD kategori.
- CRUD pengguna.
- Ban/unban pengguna.
- Detail pengaduan.
- Fitur admin baru yang ditambahkan di masa depan.

Tidak termasuk:
- API pengguna biasa.
- Aplikasi mobile/frontend pengguna.
- Public landing page.

## 4. Role dan Akses

### Role Target

- `admin`: boleh login ke Admin Dashboard dan mengelola data admin.
- `super_admin`: boleh login ke Admin Dashboard dan mengelola aksi administratif sensitif.
- `user`: tidak boleh login ke Admin Dashboard. Pengguna biasa hanya menggunakan API.

### Aturan Role Administratif

Permission `admin` dan `super_admin` harus didefinisikan eksplisit. Jangan hanya mengecek `role !== user`. Gunakan helper atau policy yang jelas, misalnya:

- `isAdmin()`
- `isSuperAdmin()`
- `canAccessAdminPanel()`

### Aturan Login Admin

- Hanya akun admin aktif yang boleh login ke panel admin.
- Akun banned tidak boleh login.
- Jika session lama milik pengguna biasa masih aktif, middleware admin harus memaksa logout dan redirect ke login.

## 5. Data Utama

### Pengaduan

Admin boleh:
- Membuat pengaduan manual.
- Mengedit semua field pengaduan.
- Menghapus pengaduan.
- Melihat detail pengaduan.

Field utama:
- `user_id`
- `category_id`
- `title`
- `description`
- `location`
- `image`
- `is_anonymous`
- `status`

Status pengaduan saat ini dianggap final:
- `menunggu`
- `diproses`
- `selesai`
- `ditolak`

Catatan: status ini sudah cukup untuk MVP dan workflow dasar. Jangan menambah status baru tanpa alasan operasional yang kuat, karena akan berdampak ke validasi, filter, badge UI, analytics, dan API.

### Kategori

Kategori digunakan untuk mengelompokkan pengaduan.

Aturan:
- Nama kategori harus unik.
- Kategori digunakan sebagai relasi pengaduan.
- Jika kategori sudah dipakai pengaduan, gunakan soft delete atau mekanisme archive.

### Pengguna

Pengguna dikelola dari Admin Dashboard.

Aturan:
- Admin dapat membuat pengguna baru.
- Admin dapat mengubah nama, email, password, dan role.
- Admin dapat ban/unban pengguna.
- Admin tidak boleh menghapus atau ban akun yang sedang digunakan sendiri.
- Pengguna biasa tidak login ke Admin Dashboard.

## 6. Soft Delete

Standar keamanan data adalah soft delete.

Keputusan implementasi:
- Tambahkan `SoftDeletes` pada model utama yang dikelola admin:
  - `Complaint`
  - `Category`
  - `User`
- Tambahkan kolom `deleted_at` pada tabel terkait.
- Tombol `Hapus` di UI melakukan soft delete, bukan hard delete.
- Fitur restore/permanent delete hanya boleh tersedia untuk `super_admin`.

Catatan migrasi: fitur yang saat ini masih hard delete harus dimigrasikan bertahap ke soft delete agar histori data tidak hilang.

## 7. Pola CRUD Admin

Semua fitur CRUD admin baru harus memakai pola:

- Halaman list berbasis table.
- Tombol `Tambah` di kanan header section.
- Form tambah menggunakan modal.
- Tombol `Update` per row membuka modal update.
- Tombol `Hapus` per row sebagai aksi kecil di table.
- Detail page dibuat jika data memiliki banyak informasi atau membutuhkan konteks penuh.

Pola tombol:
- `Tambah [Nama Data]`
- `Update`
- `Hapus`
- `Detail`
- `Simpan Perubahan`
- `Batal`

Jangan menaruh form besar langsung di atas table kecuali fitur tersebut benar-benar membutuhkan input cepat.

## 8. Routing Convention

Gunakan prefix admin:

- `GET /admin/{resource}` untuk list.
- `POST /admin/{resource}` untuk store.
- `GET /admin/{resource}/{id}` untuk detail jika dibutuhkan.
- `PUT /admin/{resource}/{id}` untuk update.
- `DELETE /admin/{resource}/{id}` untuk delete.
- Action khusus boleh memakai nested route:
  - `POST /admin/users/{user}/ban`
  - `POST /admin/users/{user}/unban`

Catatan istilah: label UI memakai `Pengguna`. Nama route teknis seperti `/admin/users` boleh tetap dipakai selama belum ada kebutuhan migrasi route.

Semua route admin harus memakai middleware:

- `auth`
- `admin`

Gunakan route name:

- `admin.{resource}.index`
- `admin.{resource}.store`
- `admin.{resource}.show`
- `admin.{resource}.update`
- `admin.{resource}.destroy`

## 9. Controller Convention

Controller admin harus:

- Menggunakan route model binding untuk update/delete/show jika memungkinkan.
- Melakukan validasi di controller atau FormRequest.
- Mengembalikan redirect dengan `success` setelah aksi berhasil.
- Mengembalikan `withErrors()` untuk aksi yang ditolak.
- Tidak mencampur logika API pengguna dengan logika admin dashboard.
- Menggunakan controller terpisah untuk halaman admin dan fitur pengguna biasa.

Contoh respons sukses:

```php
return back()->with('success', 'Data berhasil diperbarui.');
```

Untuk delete dari halaman detail, redirect ke halaman index agar pengguna tidak kembali ke resource yang sudah dihapus.

## 10. Pemisahan Admin dan Pengguna

Halaman admin dan halaman pengguna harus dipisahkan.

Aturan:
- Admin Dashboard memakai prefix `/admin`.
- Admin Dashboard memakai middleware `auth` dan `admin`.
- Admin Dashboard memakai controller, view, layout, dan navigation sendiri.
- Pengguna biasa memakai API atau frontend pengguna yang terpisah.
- Jika halaman web pengguna dibuat di masa depan, gunakan prefix, controller, dan layout yang berbeda dari Admin Dashboard.

Tujuan pemisahan ini adalah menjaga permission tetap jelas, mencegah kebocoran fitur admin ke pengguna biasa, dan membuat UI admin bisa berkembang tanpa mengganggu pengalaman pengguna.

## 11. Validasi Data

Validasi wajib dilakukan sebelum create/update.

Aturan umum:
- String diberi `max:255` jika masuk akal.
- Email harus valid dan unik.
- Password minimal 6 karakter sesuai aturan saat ini.
- Enum/status harus memakai `Rule::in([...])`.
- Foreign key harus memakai `exists:table,id`.

Jangan percaya input dari hidden field sebagai data final tanpa validasi.

## 12. Design System Admin

Style admin saat ini menjadi standar.

### Prinsip Visual

- Minimalis.
- Banyak white space.
- Tipografi sederhana dan mudah dibaca.
- Warna subtle.
- Border ringan.
- Soft shadow.
- Radius card maksimal 8px.
- Hindari gradient besar dan elemen dekoratif berlebihan.

### Layout

- Sidebar kiri untuk navigasi.
- Content area centered dengan max width.
- Topbar berisi title, subtitle, dan pill akun.
- Dashboard dan halaman list menggunakan grid/card.

### Komponen

Gunakan komponen berikut secara konsisten:

- `card`
- `section-card`
- `section-header`
- `section-title`
- `section-subtitle`
- `metric-strip`
- `mini-metric`
- `badge-soft`
- `table-actions`
- Bootstrap modal untuk create/update.

### Badge Status

Gunakan badge soft:

- `menunggu`: amber/orange soft.
- `diproses`: blue soft.
- `selesai`: green soft.
- `ditolak`: red soft.
- `aktif`: green soft.
- `banned`: red soft.

### Modal

Modal digunakan untuk:

- Tambah data.
- Update data.
- Aksi form pendek sampai sedang.

Halaman detail digunakan untuk:

- Data yang memiliki deskripsi panjang.
- Gambar/file.
- Banyak metadata.
- Riwayat atau response.

## 13. Bahasa UI

Semua UI admin harus menggunakan Bahasa Indonesia.

Standar istilah:

- `Tambah`
- `Update` boleh dipakai, tetapi jika ingin lebih formal gunakan `Perbarui`.
- `Hapus`
- `Detail`
- `Simpan Perubahan`
- `Batal`
- `Pengaduan`
- `Kategori`
- `Pengguna`, bukan `Users`.
- `Diban` atau `Banned` sebaiknya diseragamkan. Rekomendasi: gunakan `Diban`.

Saat menambah fitur baru, hindari campuran istilah Indonesia/English dalam satu halaman.

## 14. Admin Dashboard Metrics

Metric harus berasal dari query yang sesuai dengan data sebenarnya.

Contoh:
- Total pengaduan: count semua pengaduan aktif.
- Menunggu: count status `menunggu`.
- Diproses: count status `diproses`.
- Selesai: count status `selesai`.
- Ditolak: count status `ditolak`.
- Diban: count pengguna dengan `banned_at` tidak null.

Jangan hardcode angka di view.

## 15. Permission Rules

Aturan saat ini:

- Admin boleh mengelola pengaduan, kategori, dan pengguna.
- Admin tidak boleh ban diri sendiri.
- Admin tidak boleh hapus diri sendiri.
- Pengguna diban tidak boleh login.
- Pengguna biasa tidak boleh akses admin panel.

Aturan untuk `super_admin`:

- Permanent delete hanya untuk `super_admin`.
- Restore soft-deleted data hanya untuk `super_admin`.
- Mengubah role admin lain sebaiknya hanya untuk `super_admin`.

## 16. Audit Log Admin

Admin Dashboard harus punya audit log untuk aksi penting.

Aksi minimal yang dicatat:
- Create, update, soft delete, restore, dan permanent delete data admin.
- Ban dan unban pengguna.
- Perubahan status pengaduan.
- Perubahan role pengguna.

Data audit log minimal:
- Aktor admin.
- Aksi yang dilakukan.
- Resource yang terdampak.
- Nilai sebelum dan sesudah jika relevan.
- Waktu aksi.

Audit log hanya boleh dilihat oleh `super_admin`, kecuali ada kebutuhan operasional yang disetujui.

## 17. Error Handling dan Feedback

Setiap aksi create/update/delete/ban/unban harus memberi feedback:

- Success alert untuk aksi berhasil.
- Error alert untuk validasi atau aksi ditolak.

Pesan harus singkat dan jelas:

- `Pengaduan berhasil ditambahkan.`
- `Kategori berhasil diperbarui.`
- `Pengguna berhasil diban.`
- `Akun yang sedang digunakan tidak bisa dihapus.`

## 18. Checklist Sebelum Menambah Fitur Admin

Sebelum menambah fitur baru, pastikan:

- Route memakai prefix `/admin`.
- Route memakai middleware `auth` dan `admin`.
- Controller tidak mencampur logika API pengguna.
- Ada validasi request.
- UI mengikuti pola table + modal.
- Semua label memakai Bahasa Indonesia.
- Delete mempertimbangkan soft delete.
- Data relasi memakai foreign key yang valid.
- Status/enum tidak dibuat bebas tanpa dokumentasi.
- View berhasil dikompilasi dengan `php artisan view:cache`.
- Test minimal berjalan dengan `php artisan test`.

## 19. Keputusan Final

- Role `super_admin` dibuat sebagai role resmi untuk aksi administratif sensitif.
- Model utama yang dikelola admin dimigrasikan ke soft delete.
- Istilah UI `Users` diganti menjadi `Pengguna`.
- Admin Dashboard punya audit log untuk aksi penting seperti delete, ban, perubahan status, dan perubahan role.
- Halaman admin dan halaman pengguna dipisahkan dari sisi route, middleware, controller, view, layout, dan navigation.
