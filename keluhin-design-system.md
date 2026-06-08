# Keluh In — Design System (Backend / Admin Dashboard)

> Sistem desain untuk **dashboard web admin** aplikasi pengaduan mahasiswa.
> Repo ini adalah **backend Laravel** yang menyajikan REST API (dikonsumsi aplikasi mobile, repo terpisah) **dan** dashboard admin berbasis Blade.
> Dokumen ini mengikat nilai-nilai visual yang benar-benar dipakai di dashboard admin, bukan target aspiratif.

**Stack web:** Laravel 13 · Blade · **Bootstrap 5.3.3** (CDN) · Bootstrap Icons 1.11.3
**Font:** Inter (fallback `system-ui`) · **Warna utama:** `#2563eb` · **Radius dasar:** `8px`
**Sumber tunggal token:** blok `<style>` + `:root` di `resources/views/layouts/admin.blade.php`

> **Catatan stack.** Tailwind v4 (`@tailwindcss/vite`) terpasang dan dikompilasi via Vite (`resources/css/app.css`), tetapi layout aktif (`layouts/admin.blade.php`, `layouts/app.blade.php`) memakai **Bootstrap via CDN**, bukan Tailwind. Token di bawah berasal dari CSS variables Bootstrap-layer, bukan dari config Tailwind.

---

## Daftar Isi

1. [Prinsip](#1-prinsip)
2. [Warna](#2-warna)
3. [Tipografi](#3-tipografi)
4. [Spacing, Radius & Elevation](#4-spacing-radius--elevation)
5. [Komponen](#5-komponen)
6. [Status Pengaduan](#6-status-pengaduan-domain)
7. [Pola Layar](#7-pola-layar)
8. [Aksesibilitas](#8-aksesibilitas)
9. [Design Tokens](#9-design-tokens)
10. [Kontrak API untuk Mobile](#10-kontrak-api-untuk-mobile)

---

## 1. Prinsip

- **Tenang & terpercaya.** Biru sebagai warna dominan; merah hanya untuk aksi destruktif/penolakan.
- **Permukaan terang.** Dashboard memakai background `#f7f9fb` dengan kartu putih dan sidebar **terang** — bukan sidebar gelap.
- **Jelas di atas dekoratif.** Hierarki lewat tipografi dan spacing, bukan banyak warna.
- **Konsisten lewat variabel.** Warna, radius, dan shadow berasal dari CSS variables `--admin-*`, bukan angka acak.

---

## 2. Warna

### Brand & Permukaan (CSS `:root` aktual)

| Variabel | Hex | Penggunaan |
|---|---|---|
| `--admin-primary` | `#2563eb` | Primary — tombol, link aktif, ikon |
| `--admin-primary-soft` | `#eff6ff` | Background hover sidebar, soft badge, icon chip |
| `--admin-bg` | `#f7f9fb` | Background halaman |
| `--admin-surface` | `#ffffff` | Permukaan / kartu / sidebar |
| `--admin-text` | `#111827` | Teks utama |
| `--admin-muted` | `#6b7280` | Teks sekunder, caption, label metrik |
| `--admin-line` | `#edf1f5` | Border, divider, garis tabel |

### Warna pendukung (literal di stylesheet)

| Hex | Dipakai untuk |
|---|---|
| `#4b5563` | Teks link sidebar (default) |
| `#94a3b8` | Ikon sidebar nonaktif, empty-state |
| `#374151` | Teks tabel, label form, user-pill |
| `#dbeafe` | Background avatar-dot |
| `#bfdbfe` | Border input saat focus |

### Semantic — Status (soft badge)

Tiap status: background lembut + teks gelap. Diambil dari class `.badge-*`.

| Status | Background | Teks |
|---|---|---|
| **Menunggu** | `#fff7ed` | `#c2410c` |
| **Diproses** | `#eef6ff` | `#1d4ed8` |
| **Selesai** | `#ecfdf5` | `#047857` |
| **Ditolak** | `#fef2f2` | `#b91c1c` |

---

## 3. Tipografi

Font stack: `Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif`.
Inter **dideklarasikan** di `font-family` tetapi belum dimuat via `<link>` — saat ini efektif jatuh ke `system-ui`. Untuk benar memakai Inter, tambahkan link font di `<head>`.

Ukuran memakai `rem` (relatif `16px`). Nilai aktual dari stylesheet admin:

| Peran | Ukuran | Weight | Sumber |
|---|---|---|---|
| Page title | `clamp(1.35rem, 1vw + 1rem, 1.9rem)` | 700 | `.page-title` |
| Section title | `1.02rem` | 700 | `.section-title` |
| Modal title | `1.08rem` | 700 | `.modal-title` |
| Metric value | `1.65rem` | 700 | `.mini-metric-value` |
| Brand title | `1rem` | 700 | `.brand-title` |
| Body / link sidebar | `0.94rem` | 500 | `.sidebar-link` |
| Section subtitle | `0.88rem` | — | `.section-subtitle` |
| Metric label | `0.86rem` | 600 | `.mini-metric-label` |
| Form label | `0.84rem` | 600 | `.form-label` |
| Brand subtitle / caption | `0.78rem` | — | `.brand-subtitle` |
| Badge | `0.76rem` | 600 | `.badge-soft` |

**Weight dipakai:** 500, 600, 700. Heading/title memakai **700** (berbeda dari spec lama yang 600).

---

## 4. Spacing, Radius & Elevation

### Spacing

Tidak ada skala token bernama; jarak ditulis langsung (`px`/`rem`). Nilai yang sering muncul:

| Konteks | Nilai |
|---|---|
| Padding `.main` | `28px` |
| Padding sidebar | `28px 20px` |
| Padding `.section-header` | `22px 24px` |
| Padding `.modal-body` | `24px` |
| Gap grid metrik / form | `16px` / `14px` |
| Padding link sidebar | `11px 12px` |

### Radius

| Token / nilai | Untuk |
|---|---|
| `--admin-radius` = **`8px`** | Kartu, tombol, input, modal, badge persegi, icon chip |
| `999px` | Pill: `.badge-soft`, `.user-pill` |
| `50%` | `.avatar-dot` |

> Spec lama memakai skala radius 8/12/16/20/28. Implementasi nyata memakai **satu radius `8px`** untuk hampir semua permukaan.

### Elevation

| Token / nilai | Shadow | Untuk |
|---|---|---|
| `--admin-shadow` | `0 16px 40px rgba(15,23,42,.06)` | Kartu, user-pill, alert |
| (btn-primary) | `0 10px 24px rgba(37,99,235,.18)` | Tombol primary |
| (modal) | `0 28px 80px rgba(15,23,42,.18)` | Modal |

---

## 5. Komponen

Class kustom didefinisikan di `<style>` `layouts/admin.blade.php`, di atas Bootstrap.

### Buttons

| Varian | Class | Background | Teks |
|---|---|---|---|
| Primary | `.btn.btn-primary` | `--admin-primary` (+ shadow biru) | putih |
| Soft danger | `.btn-soft-danger` | `#fef2f2` (hover `#fee2e2`) | `#b91c1c` |
| Light | `.btn.btn-light` | Bootstrap default + radius `8px` | — |

Semua radius `--admin-radius` (`8px`).

### Input & Form

- `.form-control` / `.form-select`: tinggi minimal `42px`, border `--admin-line`, radius `8px`, teks `--admin-text`.
- **Focus:** border `#bfdbfe` + ring `0 0 0 .2rem rgba(37,99,235,.1)`.
- **Label:** `.form-label`, `0.84rem` / weight 600 / warna `#374151`.
- **Layout:** `.form-grid` = 4 kolom (`repeat(4, 1fr)`), helper `.span-2` / `.span-4`. Turun ke 2 kolom di ≤991px.

### Badge / Status

`.badge-soft` — pill (`999px`), padding `.42rem .65rem`, weight 600, `0.76rem`. Modifier warna `.badge-menunggu` / `.badge-diproses` / `.badge-selesai` / `.badge-ditolak` (lihat [bagian 6](#6-status-pengaduan-domain)). Render: `ucfirst($status)`.

### Card

`.card`: border 0, radius `8px`, shadow `--admin-shadow`. Varian `.section-card` (overflow hidden) + `.section-header` (header dengan border bawah) + `.section-title` / `.section-subtitle`.

### Mini-metric (stat card)

`.metric-strip` = grid 3 kolom. Tiap `.mini-metric`: label (`.mini-metric-label`) + nilai besar (`.mini-metric-value`, `1.65rem`/700) + `.mini-metric-icon` (chip `38px`, bg `--admin-primary-soft`). Turun ke 1 kolom di ≤575px.

### Navigasi

- **Sidebar (`.sidebar`):** **terang** (`--admin-surface`), sticky, lebar **248px** (`grid-template-columns: 248px minmax(0,1fr)`), border kanan `--admin-line`. Item `.sidebar-link`: teks `#4b5563`, ikon `#94a3b8`; **aktif/hover** → background `--admin-primary-soft` + teks/ikon `--admin-primary`. Di ≤991px sidebar jadi statis + nav grid.
- **Topbar (`.topbar`):** judul halaman (`.page-title` + `.page-kicker`) di kiri, `.user-pill` (pill + `.avatar-dot`) di kanan.

### Modal

`.modal-content`: radius `8px`, shadow `0 28px 80px rgba(15,23,42,.18)`. Header/footer padding `20px 24px`, body `24px`.

---

## 6. Status Pengaduan (Domain)

Empat status — **nilai enum di database** (`enum('status', [...])` pada migrasi `create_complaints_table`), default `menunggu`.

| Nilai enum | Label tampil | Arti | Warna badge |
|---|---|---|---|
| `menunggu` | Menunggu | Baru masuk, belum ditangani | Amber (`#fff7ed`/`#c2410c`) |
| `diproses` | Diproses | Sedang ditindaklanjuti | Blue (`#eef6ff`/`#1d4ed8`) |
| `selesai` | Selesai | Sudah ditangani / ditutup | Green (`#ecfdf5`/`#047857`) |
| `ditolak` | Ditolak | Tidak valid / di luar wewenang | Red (`#fef2f2`/`#b91c1c`) |

> **Penting:** status awal adalah **`menunggu`**, bukan "Baru". Bila label hendak diubah ke "Baru", ubah nilai enum migrasi, mapping `$badgeClass` di view, dan kontrak API sekaligus.

**Aturan a11y:** badge memakai warna + label teks (`ucfirst`), tidak bergantung warna saja.

---

## 7. Pola Layar

### Dashboard admin (Blade + Bootstrap)

- **Shell** `.admin-shell`: sidebar terang `248px` + area konten terang.
- **Topbar:** judul halaman + user-pill.
- **Stat cards:** `.metric-strip` 3 kolom (mis. Total / Menunggu / Diproses) — angka besar `1.65rem`/700.
- **Tabel** pengaduan: kolom Judul / Kategori / Status (`.badge-soft`). Aksi (edit/hapus) via modal Bootstrap, `.table-actions` rata kanan.
- **Form panel** `.form-panel` + `.form-grid` untuk create/edit inline.

Layar lain mengikuti pola sama: `audit-logs`, `users` (+ `admins`), `categories`, `complaints` (`index` / `show`).

---

## 8. Aksesibilitas

- **Kontras:** teks `--admin-text` (`#111827`) di atas putih dan teks putih di atas `--admin-primary` memenuhi WCAG AA.
- **Status bukan hanya warna:** selalu pasangkan badge dengan label teks.
- **Focus terlihat:** input punya ring `0 0 0 .2rem rgba(37,99,235,.1)`.
- **Hierarki:** lewat ukuran/weight tipografi, bukan hanya warna.
- **Bahasa:** `<html lang="id">`.

---

## 9. Design Tokens

### CSS Variables aktual (`layouts/admin.blade.php`)

```css
:root {
  --admin-bg:           #f7f9fb;
  --admin-surface:      #ffffff;
  --admin-text:         #111827;
  --admin-muted:        #6b7280;
  --admin-line:         #edf1f5;
  --admin-primary:      #2563eb;
  --admin-primary-soft: #eff6ff;
  --admin-shadow: 0 16px 40px rgba(15, 23, 42, 0.06);
  --admin-radius: 8px;
}

/* status badges */
.badge-menunggu { background:#fff7ed; color:#c2410c; }
.badge-diproses { background:#eef6ff; color:#1d4ed8; }
.badge-selesai  { background:#ecfdf5; color:#047857; }
.badge-ditolak  { background:#fef2f2; color:#b91c1c; }
```

### Tailwind v4 (opsional, untuk halaman non-Bootstrap)

Tailwind dikonfigurasi via CSS-first (`resources/css/app.css`), bukan `tailwind.config.js` (file itu legacy/kosong di v4). Untuk menyelaraskan utility Tailwind dengan token di atas, tambahkan blok `@theme`:

```css
/* resources/css/app.css */
@import "tailwindcss";

@theme {
  --color-primary:      #2563eb;
  --color-primary-soft: #eff6ff;
  --color-bg:           #f7f9fb;
  --color-surface:      #ffffff;
  --color-text:         #111827;
  --color-muted:        #6b7280;
  --color-line:         #edf1f5;

  --color-menunggu: #c2410c;
  --color-diproses: #1d4ed8;
  --color-selesai:  #047857;
  --color-ditolak:  #b91c1c;

  --radius-admin: 8px;
}
```

> Jika kelak admin dashboard dimigrasikan dari Bootstrap ke Tailwind, blok `@theme` ini menjadi sumber token tunggal dan `<style>` inline bisa dibuang.

---

## 10. Kontrak API untuk Mobile

Aplikasi mobile (repo terpisah, Flutter/Compose) **mengonsumsi REST API** repo ini — bukan berbagi file token. Yang harus konsisten lintas platform hanyalah **kontrak data**, bukan CSS:

- **Auth:** Laravel Sanctum (bearer token).
- **Status pengaduan:** API mengirim nilai enum apa adanya (`menunggu` / `diproses` / `selesai` / `ditolak`). Sisi mobile memetakan ke warna sendiri; samakan mapping dengan [bagian 6](#6-status-pengaduan-domain).
- Token visual (warna/font/radius) mobile didefinisikan di repo mobile dan **tidak** diatur dokumen ini.

---

*Keluh In Design System (Backend/Admin) · Laravel 13 + Bootstrap 5.3.3 · `#2563eb` · Inter — selaras dengan implementasi nyata `layouts/admin.blade.php`.*
