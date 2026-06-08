# Keluh In — Design System

> Sistem desain terpadu untuk aplikasi pengaduan mahasiswa.
> Dipakai bersama oleh aplikasi **mobile (Android)** dan **dashboard web admin** agar tampilan konsisten dan pengembangan lebih cepat.

**Versi:** 1.0 · **Font:** Poppins · **Warna utama:** `#2B63F0` · **Grid:** 4px

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

---

## 1. Prinsip

- **Tenang & terpercaya.** Aplikasi pengaduan harus terasa aman, bukan agresif. Biru sebagai warna dominan, merah hanya untuk aksi destruktif/penolakan.
- **Satu sistem, dua platform.** Spesifikasi sama untuk mobile dan web; yang berbeda hanya ukuran sentuh (mobile minimal 44px) dan tata letak.
- **Jelas di atas dekoratif.** Hierarki dibangun lewat tipografi dan spacing, bukan banyak warna atau bayangan tebal.
- **Konsisten lewat token.** Semua nilai (warna, ukuran, radius) berasal dari token bernama, bukan angka acak.

---

## 2. Warna

### Primary — Blue

| Token | Hex | Penggunaan |
|---|---|---|
| `blue-50` | `#EEF3FE` | Background lembut, highlight |
| `blue-100` | `#D8E3FD` | Background hover halus |
| `blue-200` | `#B3C7FB` | Border aksen |
| `blue-300` | `#84A3F8` | — |
| `blue-400` | `#527CF4` | — |
| **`blue-500`** | **`#2B63F0`** | **Primary** — tombol, link, ikon aktif |
| `blue-600` | `#1E4FD4` | Hover / pressed |
| `blue-700` | `#1A41AB` | Teks di atas background biru muda |
| `blue-800` | `#173681` | — |
| `blue-900` | `#142B5E` | — |

### Neutral — Ink

| Token | Hex | Penggunaan |
|---|---|---|
| `ink-0` | `#FFFFFF` | Permukaan / kartu |
| `ink-50` | `#F6F8FB` | Background halaman |
| `ink-100` | `#EDF1F6` | Background sekunder, divider |
| `ink-200` | `#DCE3EC` | Border default |
| `ink-300` | `#C2CCD9` | Border kuat / outline input |
| `ink-400` | `#9AA7B8` | Teks subtle, placeholder |
| `ink-500` | `#708096` | Caption |
| `ink-600` | `#4E5C70` | Teks sekunder |
| `ink-700` | `#344256` | — |
| `ink-800` | `#20293B` | — |
| `ink-900` | `#18233D` | Teks utama |
| `ink-950` | `#0E1626` | Sidebar gelap, dark mode |

### Semantic — Status

Setiap status punya tiga nilai: warna inti, background lembut, dan teks gelap (untuk dipakai di atas background lembut).

| Status | Inti | Background | Teks |
|---|---|---|---|
| Info / **Baru** | `#2B63F0` | `#EEF3FE` | `#1A41AB` |
| Warning / **Diproses** | `#F59E0B` | `#FEF3E2` | `#B45309` |
| Success / **Selesai** | `#16A34A` | `#E7F6EC` | `#0F7A37` |
| Danger / **Ditolak** | `#E23D3D` | `#FCEAEA` | `#B42318` |

---

## 3. Tipografi

Satu typeface — **Poppins** — untuk mobile & web. Ukuran `px` (web) setara `sp` (Android).

| Peran | Ukuran / Line-height | Weight | Catatan |
|---|---|---|---|
| Display | 40 / 48 | 600 | Judul halaman utama, onboarding |
| Heading 1 | 32 / 40 | 600 | Judul layar |
| Heading 2 | 24 / 32 | 600 | Judul section |
| Heading 3 | 20 / 28 | 600 | Judul kartu |
| Body Large | 18 / 28 | 400 | Intro, paragraf penting |
| Body | 16 / 26 | 400 | Teks isi standar |
| Body Small | 14 / 22 | 400 | Deskripsi, metadata |
| Caption | 12 / 18 | 500 | Label, timestamp |
| Button | 16 / 24 | 600 | Teks tombol |

**Weight yang dipakai:** Regular (400), Medium (500), SemiBold (600). Hindari Bold (700) kecuali untuk wordmark logo.

---

## 4. Spacing, Radius & Elevation

### Spacing (base 4px)

| Token | Nilai |
|---|---|
| `space-1` | 4px |
| `space-2` | 8px |
| `space-3` | 12px |
| `space-4` | 16px |
| `space-5` | 20px |
| `space-6` | 24px |
| `space-8` | 32px |
| `space-10` | 40px |
| `space-12` | 48px |
| `space-16` | 64px |

Padding kartu standar `space-5` (20px). Jarak antar elemen list `space-3` (12px). Margin antar section `space-12`–`space-16`.

### Radius

| Token | Nilai | Untuk |
|---|---|---|
| `radius-sm` | 8px | Chip kecil, badge persegi |
| `radius-md` | 12px | Tombol, input |
| `radius-lg` | 16px | Kartu |
| `radius-xl` | 20px | Sheet, modal |
| `radius-2xl` | 28px | Container besar / frame |
| `radius-full` | 999px | Pill, avatar, badge |

### Elevation

| Token | Shadow | Untuk |
|---|---|---|
| `shadow-1` | `0 1px 2px rgba(16,22,38,.06), 0 1px 3px rgba(16,22,38,.08)` | Kartu, list item |
| `shadow-2` | `0 4px 14px rgba(16,22,38,.08)` | Dropdown, popover |
| `shadow-3` | `0 14px 32px rgba(16,22,38,.12)` | Modal, sheet |

---

## 5. Komponen

### Buttons

| Varian | Background | Teks | Border |
|---|---|---|---|
| Primary | `blue-500` (hover `blue-600`) | putih | — |
| Secondary | `blue-50` | `blue-700` | — |
| Outline | putih | `ink-800` | `ink-300` |
| Ghost | transparan | `ink-700` | — |
| Danger | `#E23D3D` (hover `#B42318`) | putih | — |

- **Ukuran:** Small `7×14px` / 13px · Medium `11×20px` / 15px · Large `14×26px` / 16px.
- **Radius** `radius-md`. **Weight** 600. **Disabled** opacity 45%.
- Di mobile, tinggi minimal 44px untuk area sentuh.

### Input & Form

- Border `1.5px ink-300`, radius `radius-md`, padding `11×14px`, font 15px.
- **Focus:** border `blue-500` + ring `0 0 0 4px blue-50`.
- **Error:** border `#E23D3D` + ring `0 0 0 4px #FCEAEA`, plus hint teks merah di bawah.
- **Disabled:** background `ink-100`, teks `ink-500`.
- Label di atas field, 13px / weight 600 / warna `ink-800`. Hint 12px / `ink-500`.
- Textarea (deskripsi keluhan): tinggi minimal 96px, resize vertikal.

### Chip Kategori

Pill, radius `radius-full`, padding `7×14px`, font 13px / weight 500.
- **Default:** background `ink-100`, teks `ink-700`.
- **Aktif:** background `blue-50`, teks `blue-700`, border `1.5px blue-300`.

### Badge / Status

Pill dengan titik berwarna + label (lihat [bagian 6](#6-status-pengaduan-domain)). Font 12.5px / weight 600.

### Card

Background `ink-0`, border `1px ink-200`, radius `radius-lg`, shadow `shadow-1`, padding `space-5`.
Kartu pengaduan: judul (H3) + badge status di kanan atas, deskripsi (Body Small `ink-600`), footer metadata (avatar + nama + kategori + waktu).

### Navigasi

- **Mobile — Bottom nav:** 3 item (Beranda, Riwayat, Profil). Ikon 22px + label 10px. Aktif `blue-500`, nonaktif `ink-400`.
- **Web — Sidebar:** lebar 180–240px, background `ink-950`, teks `#9AA7B8`. Item aktif background `blue-500` + teks putih. Topbar berisi judul halaman + aksi utama.

---

## 6. Status Pengaduan (Domain)

Empat status inti, konsisten di mobile & web.

| Status | Arti | Warna |
|---|---|---|
| **Baru** | Pengaduan baru masuk, belum ditangani | Info / Blue |
| **Diproses** | Sedang ditindaklanjuti | Warning / Amber |
| **Selesai** | Sudah ditangani / ditutup | Success / Green |
| **Ditolak** | Tidak valid / di luar wewenang | Danger / Red |

**Aturan:** selalu tampilkan **titik berwarna + label teks**, jangan mengandalkan warna saja — agar tetap terbaca bagi pengguna buta warna.

---

## 7. Pola Layar

### Mobile — Beranda mahasiswa

- Header biru (`blue-500`) berisi sapaan + judul, dengan body melengkung naik menutupi tepi header (radius atas `18px`).
- Daftar kartu pengaduan ringkas: judul + badge status mini + metadata.
- Tombol primary lebar penuh "+ Buat Pengaduan".
- Bottom navigation tetap di bawah.

### Web — Dashboard admin

- Sidebar gelap di kiri (navigasi utama) + area konten terang.
- Baris **stat cards** di atas (Total, Diproses, Selesai) — angka besar (H2) dengan warna status.
- **Tabel** pengaduan: kolom Judul / Kategori / Status (badge). Header tabel background `ink-100`.
- Tombol aksi (mis. Ekspor) di kanan atas header.

---

## 8. Aksesibilitas

- **Kontras:** teks utama `ink-900` di atas putih dan teks putih di atas `blue-500` memenuhi WCAG AA.
- **Status bukan hanya warna:** selalu pasangkan dengan label/ikon.
- **Area sentuh mobile:** minimal 44×44px.
- **Focus terlihat:** semua elemen interaktif punya focus ring (`0 0 0 4px blue-50`).
- **Hierarki:** gunakan ukuran tipografi, jangan hanya tebal/warna.

---

## 9. Design Tokens

### CSS Variables (web / Laravel)

```css
:root {
  /* primary */
  --color-primary:       #2B63F0;
  --color-primary-hover: #1E4FD4;
  --color-primary-soft:  #EEF3FE;
  /* text & surface */
  --color-text:          #18233D;
  --color-text-muted:    #4E5C70;
  --color-text-subtle:   #9AA7B8;
  --color-border:        #DCE3EC;
  --color-bg:            #F6F8FB;
  --color-surface:       #FFFFFF;
  --color-sidebar:       #0E1626;
  /* status */
  --color-baru:    #2B63F0; --color-baru-bg:    #EEF3FE; --color-baru-text:    #1A41AB;
  --color-proses:  #F59E0B; --color-proses-bg:  #FEF3E2; --color-proses-text:  #B45309;
  --color-selesai: #16A34A; --color-selesai-bg: #E7F6EC; --color-selesai-text: #0F7A37;
  --color-tolak:   #E23D3D; --color-tolak-bg:   #FCEAEA; --color-tolak-text:   #B42318;
  /* typography */
  --font-sans: 'Poppins', system-ui, sans-serif;
  /* radius */
  --radius-sm: 8px;  --radius-md: 12px;  --radius-lg: 16px;
  --radius-xl: 20px; --radius-2xl: 28px; --radius-full: 999px;
  /* elevation */
  --shadow-1: 0 1px 2px rgba(16,22,38,.06), 0 1px 3px rgba(16,22,38,.08);
  --shadow-2: 0 4px 14px rgba(16,22,38,.08);
  --shadow-3: 0 14px 32px rgba(16,22,38,.12);
}
```

### Tailwind (`tailwind.config.js` → `theme.extend`)

```js
theme: {
  extend: {
    colors: {
      primary: {
        DEFAULT: '#2B63F0', hover: '#1E4FD4', soft: '#EEF3FE',
        50:'#EEF3FE',100:'#D8E3FD',200:'#B3C7FB',300:'#84A3F8',400:'#527CF4',
        500:'#2B63F0',600:'#1E4FD4',700:'#1A41AB',800:'#173681',900:'#142B5E',
      },
      ink: {
        50:'#F6F8FB',100:'#EDF1F6',200:'#DCE3EC',300:'#C2CCD9',400:'#9AA7B8',
        500:'#708096',600:'#4E5C70',700:'#344256',800:'#20293B',900:'#18233D',950:'#0E1626',
      },
      baru:'#2B63F0', proses:'#F59E0B', selesai:'#16A34A', tolak:'#E23D3D',
    },
    fontFamily: { sans: ['Poppins','system-ui','sans-serif'] },
    borderRadius: { md:'12px', lg:'16px', xl:'20px', '2xl':'28px' },
    boxShadow: {
      e1:'0 1px 2px rgba(16,22,38,.06),0 1px 3px rgba(16,22,38,.08)',
      e2:'0 4px 14px rgba(16,22,38,.08)',
      e3:'0 14px 32px rgba(16,22,38,.12)',
    },
  },
}
```

### Jetpack Compose (Android)

```kotlin
object KeluhInColors {
    val Primary      = Color(0xFF2B63F0)
    val PrimaryHover = Color(0xFF1E4FD4)
    val PrimarySoft  = Color(0xFFEEF3FE)
    val TextPrimary  = Color(0xFF18233D)
    val TextMuted    = Color(0xFF4E5C70)
    val Border       = Color(0xFFDCE3EC)
    val Surface      = Color(0xFFFFFFFF)
    val Background   = Color(0xFFF6F8FB)
    // status
    val Baru    = Color(0xFF2B63F0)
    val Proses  = Color(0xFFF59E0B)
    val Selesai = Color(0xFF16A34A)
    val Ditolak = Color(0xFFE23D3D)
}

val KeluhInShapes = Shapes(
    extraSmall = RoundedCornerShape(8.dp),
    small      = RoundedCornerShape(12.dp),
    medium     = RoundedCornerShape(16.dp),
    large      = RoundedCornerShape(20.dp),
    extraLarge = RoundedCornerShape(28.dp),
)
```

### Flutter (mobile app ini)

Token di-port ke Dart di `lib/core/constants/` dan dipakai lewat `AppTheme`.
Sumber tunggal — dilarang hardcode hex/ukuran di luar file token.

| File | Isi |
|---|---|
| `app_colors.dart` | skala `blue-50..900`, `ink-0..950`, status `core/bg/text`, alias semantik (`primary`, `background`, `textPrimary`, dll) |
| `app_spacing.dart` | `space1..space16` (4–64px) |
| `app_radius.dart` | `sm 8 / md 12 / lg 16 / xl 20 / xxl 28 / full 999` |
| `app_elevation.dart` | `shadow1/2/3` (`List<BoxShadow>`) + `e1/e2/e3` numerik |
| `app_typography.dart` | Poppins via `google_fonts`: `display / heading1..3 / bodyLarge / body / bodySmall / caption / button` + `textTheme` |

```dart
// Pemakaian
Text('Judul', style: AppTypography.heading3);
Container(
  padding: const EdgeInsets.all(AppSpacing.space5),
  decoration: BoxDecoration(
    color: AppColors.surface,
    borderRadius: BorderRadius.circular(AppRadius.lg),
    boxShadow: AppElevation.shadow1,
  ),
);
```

**Komponen reusable:** `CustomButton`, `CustomTextField` (label di atas + border token),
`ComplaintCard`, `StatCard`, dan `StatusBadge` (pill titik+label).

**Catatan status (mobile):** label status dirender apa adanya dari backend
(mis. masih memakai "Menunggu"). Mapping warna saat ini dipertahankan:
Menunggu=amber, Diproses=biru, Selesai=hijau, Ditolak=merah — lihat
`Helper.statusColor`. Bila label "Menunggu" diganti "Baru", samakan juga di sini.

---

*Keluh In Design System · v1.0 · Poppins · `#2B63F0` — dibuat untuk konsistensi mobile & web.*
