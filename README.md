# 🏨 Hotel Booking System

Sistem pemesanan hotel sederhana menggunakan arsitektur **MVC (Model-View-Controller)** dengan PHP Native dan Tailwind CSS. 

---

## 📋 Daftar Isi

- [Tentang Project](#-tentang-project)
- [Fitur](#-fitur)
- [Teknologi](#-teknologi)
- [Arsitektur MVC](#-arsitektur-mvc)
- [Struktur Folder](#-struktur-folder)
- [Penjelasan Detail Folder & File](#-penjelasan-detail-folder--file)
  - [1. app/](#1-app)
  - [2. config/](#2-config)
  - [3.  core/](#3-core)
  - [4. public/](#4-public)
  - [5. routes/](#5-routes)
  - [6. src/](#6-src)
  - [7. storage/](#7-storage)
  - [8.  sql/](#8-sql)
  - [9. Root Files](#9-root-files)
- [Database Schema](#-database-schema)
- [Instalasi](#-instalasi)
- [Penggunaan](#-penggunaan)
- [NPM Scripts](#-npm-scripts)
- [Author](#-author)

---

## 📖 Tentang Project

Project ini adalah sistem booking hotel **MVP (Minimum Viable Product)** yang dibangun dengan:

- **Arsitektur MVC** - Memisahkan logic (Model), tampilan (View), dan kontrol (Controller)
- **PHP Native** - Tanpa framework, cocok untuk pembelajaran
- **Tailwind CSS** - Utility-first CSS framework untuk styling modern
- **MySQL** - Database relasional dengan 3 tabel yang saling terhubung

---

## ✨ Fitur

| Fitur | Deskripsi |
|-------|-----------|
| 🔐 **Autentikasi** | Login, Register, Logout dengan Session |
| 🔒 **Password Hash** | Enkripsi password menggunakan bcrypt |
| 👥 **Multi Role** | Guest (user biasa) dan Admin dengan akses berbeda |
| 📝 **CRUD Lengkap** | Create, Read, Update, Delete untuk semua data |
| 📤 **Upload File** | Upload gambar profil user dan gambar kamar |
| 🔗 **Relasi Data** | 3 tabel yang saling terhubung (users, rooms, bookings) |
| 📊 **Admin Dashboard** | Panel admin dengan statistik dan kelola data |
| 📱 **Responsive** | Tampilan responsif untuk semua ukuran layar |

---

## 🛠 Teknologi

| Teknologi | Versi | Fungsi |
|-----------|-------|--------|
| PHP | 8.0+ | Backend server-side |
| MySQL | 5.7+ | Database |
| PDO | - | Database connection |
| Tailwind CSS | 3.4+ | Styling & UI |
| Node.js | 18+ | Build tools untuk Tailwind |
| NPM | 9+ | Package manager |

---

## 🏗 Arsitektur MVC

```
┌─────────────────────────────────────────────────────────────────┐
│                        ARSITEKTUR MVC                           │
└─────────────────────────────────────────────────────────────────┘

                         ┌─────────────┐
                         │   REQUEST   │
                         │  (Browser)  │
                         └──────┬──────┘
                                │
                                ▼
┌──────────────────────────────────────────────────────────────────┐
│                           ROUTER                                 │
│                        (routes/web.php)                          │
│         Mencocokkan URL dengan Controller yang tepat             │
└──────────────────────────────┬───────────────────────────────────┘
                               │
                               ▼
┌──────────────────────────────────────────────────────────────────┐
│                         CONTROLLER                               │
│                    (app/Controllers/*. php)                       │
│              Menerima request, memproses logic,                  │
│              berkomunikasi dengan Model dan View                 │
└───────────────┬──────────────────────────────────┬───────────────┘
                │                                  │
                ▼                                  ▼
┌───────────────────────────┐      ┌───────────────────────────────┐
│           MODEL           │      │             VIEW              │
│    (app/Models/*. php)     │      │      (app/Views/*. php)        │
│                           │      │                               │
│  • Query ke Database      │      │  • Template HTML              │
│  • Business Logic         │      │  • Menampilkan data           │
│  • Validasi Data          │      │  • User Interface             │
└─────────────┬─────────────┘      └───────────────┬───────────────┘
              │                                    │
              ▼                                    ▼
┌───────────────────────────┐      ┌───────────────────────────────┐
│         DATABASE          │      │          RESPONSE             │
│          (MySQL)          │      │          (HTML/JSON)          │
└───────────────────────────┘      └───────────────────────────────┘
```

---

## 📁 Struktur Folder

```
hotel-booking/
│
├── 📂 app/                          # Application Layer (MVC)
│   ├── 📂 Controllers/              # Controller classes
│   │   ├── 📄 AuthController.php
│   │   ├── 📄 HomeController.php
│   │   ├── 📄 DashboardController.php
│   │   ├── 📄 RoomController.php
│   │   ├── 📄 BookingController. php
│   │   └── 📂 Admin/
│   │       ├── 📄 DashboardController.php
│   │       ├── 📄 RoomController.php
│   │       ├── 📄 BookingController. php
│   │       └── 📄 UserController.php
│   │
│   ├── 📂 Models/                   # Model classes
│   │   ├── 📄 User.php
│   │   ├── 📄 Room.php
│   │   └── 📄 Booking.php
│   │
│   └── 📂 Views/                    # View templates
│       ├── 📂 layouts/
│       │   ├── 📄 app. php
│       │   └── 📄 admin.php
│       ├── 📂 auth/
│       │   ├── 📄 login. php
│       │   └── 📄 register.php
│       ├── 📂 home/
│       │   └── 📄 index.php
│       ├── 📂 dashboard/
│       │   ├── 📄 index.php
│       │   ├── 📄 profile.php
│       │   └── 📄 my-bookings.php
│       ├── 📂 rooms/
│       │   ├── 📄 index.php
│       │   ├── 📄 show.php
│       │   └── 📄 book.php
│       ├── 📂 admin/
│       │   ├── 📂 dashboard/
│       │   │   └── 📄 index.php
│       │   ├── 📂 rooms/
│       │   │   ├── 📄 index.php
│       │   │   ├── 📄 create.php
│       │   │   └── 📄 edit.php
│       │   ├── 📂 bookings/
│       │   │   ├── 📄 index. php
│       │   │   └── 📄 show.php
│       │   └── 📂 users/
│       │       └── 📄 index.php
│       ├── 📂 partials/
│       │   ├── 📄 header.php
│       │   ├── 📄 footer.php
│       │   ├── 📄 navbar.php
│       │   ├── 📄 sidebar.php
│       │   └── 📄 flash-message.php
│       └── 📂 errors/
│           ├── 📄 404.php
│           └── 📄 500. php
│
├── 📂 config/                       # Konfigurasi aplikasi
│   ├── 📄 app.php
│   └── 📄 database.php
│
├── 📂 core/                         # Core/Engine MVC
│   ├── 📄 App.php
│   ├── 📄 Controller.php
│   ├── 📄 Database.php
│   ├── 📄 Model.php
│   ├── 📄 Router.php
│   └── 📄 Middleware.php
│
├── 📂 public/                       # Entry point & assets publik
│   ├── 📄 index.php
│   ├── 📄 . htaccess
│   └── 📂 assets/
│       ├── 📂 css/
│       │   └── 📄 output.css
│       ├── 📂 js/
│       │   └── 📄 script.js
│       └── 📂 images/
│           ├── 📄 logo.png
│           └── 📄 favicon.ico
│
├── 📂 routes/                       # Definisi routing
│   └── 📄 web.php
│
├── 📂 src/                          # Source files
│   └── 📂 css/
│       └── 📄 input.css
│
├── 📂 storage/                      # File storage
│   └── 📂 uploads/
│       ├── 📂 profiles/
│       │   └── 📄 . gitkeep
│       └── 📂 rooms/
│           └── 📄 .gitkeep
│
├── 📂 sql/                          # Database schema
│   └── 📄 schema.sql
│
├── 📂 node_modules/                 # NPM dependencies (auto-generated)
│
├── 📄 . htaccess                     # URL rewrite utama
├── 📄 . gitignore                    # Git ignore rules
├── 📄 package.json                  # NPM configuration
├── 📄 package-lock.json             # NPM lock file
├── 📄 tailwind.config.js            # Tailwind CSS configuration
└── 📄 README.md                     # Dokumentasi project
```

---

## 📚 Penjelasan Detail Folder & File

---

### 1.  `app/`

Folder utama yang berisi **logika aplikasi** mengikuti pola MVC (Model-View-Controller).

---

#### 1.1 `app/Controllers/`

**Fungsi:** Menerima request dari user, memproses logika bisnis, berkomunikasi dengan Model untuk data, dan mengembalikan response melalui View. 

```
app/Controllers/
├── AuthController.php
├── HomeController. php
├── DashboardController.php
├── RoomController.php
├── BookingController. php
└── Admin/
    ├── DashboardController.php
    ├── RoomController.php
    ├── BookingController.php
    └── UserController.php
```

##### Detail File Controllers:

| File | Deskripsi | Methods |
|------|-----------|---------|
| `AuthController. php` | Menangani proses autentikasi user | `loginForm()` - Menampilkan form login |
| | | `login()` - Memproses login, verifikasi password, set session |
| | | `registerForm()` - Menampilkan form registrasi |
| | | `register()` - Memproses registrasi, hash password, simpan ke DB |
| | | `logout()` - Menghapus session, redirect ke login |

| File | Deskripsi | Methods |
|------|-----------|---------|
| `HomeController.php` | Menangani halaman landing/homepage | `index()` - Menampilkan homepage dengan featured rooms |

| File | Deskripsi | Methods |
|------|-----------|---------|
| `DashboardController.php` | Dashboard dan profil user yang login | `index()` - Menampilkan dashboard user |
| | | `profile()` - Menampilkan halaman edit profil |
| | | `updateProfile()` - Memproses update profil & upload foto |

| File | Deskripsi | Methods |
|------|-----------|---------|
| `RoomController. php` | Menampilkan data kamar untuk public | `index()` - Menampilkan daftar semua kamar tersedia |
| | | `show($id)` - Menampilkan detail kamar berdasarkan ID |

| File | Deskripsi | Methods |
|------|-----------|---------|
| `BookingController.php` | Proses booking oleh user | `myBookings()` - Menampilkan daftar booking milik user |
| | | `store()` - Memproses pembuatan booking baru |
| | | `cancel($id)` - Membatalkan booking |

##### Subfolder `Admin/` - Controller khusus untuk Admin:

| File | Deskripsi | Methods |
|------|-----------|---------|
| `Admin/DashboardController.php` | Dashboard statistik untuk admin | `index()` - Menampilkan statistik (total user, room, booking, revenue) |

| File | Deskripsi | Methods |
|------|-----------|---------|
| `Admin/RoomController.php` | CRUD kamar oleh admin | `index()` - Menampilkan daftar semua kamar (tabel) |
| | | `create()` - Menampilkan form tambah kamar |
| | | `store()` - Memproses simpan kamar baru + upload gambar |
| | | `edit($id)` - Menampilkan form edit kamar |
| | | `update($id)` - Memproses update kamar |
| | | `destroy($id)` - Menghapus kamar |

| File | Deskripsi | Methods |
|------|-----------|---------|
| `Admin/BookingController.php` | Kelola booking oleh admin | `index()` - Menampilkan semua booking |
| | | `show($id)` - Menampilkan detail booking |
| | | `updateStatus($id)` - Mengubah status booking (confirm/cancel/complete) |

| File | Deskripsi | Methods |
|------|-----------|---------|
| `Admin/UserController.php` | Kelola user oleh admin | `index()` - Menampilkan daftar semua user |
| | | `show($id)` - Menampilkan detail user |
| | | `destroy($id)` - Menghapus user |

---

#### 1.2 `app/Models/`

**Fungsi:** Berinteraksi dengan database.  Setiap model merepresentasikan satu tabel di database.

```
app/Models/
├── User.php
├── Room. php
└── Booking.php
```

##### Detail File Models:

| File | Tabel | Deskripsi |
|------|-------|-----------|
| `User.php` | `users` | Model untuk data user/admin |

**Methods di `User.php`:**

| Method | Return | Deskripsi |
|--------|--------|-----------|
| `findByEmail($email)` | `array\|null` | Mencari user berdasarkan email |
| `verifyPassword($password, $hash)` | `bool` | Memverifikasi password dengan hash |
| `hashPassword($password)` | `string` | Membuat hash password dengan bcrypt |
| `getAllGuests()` | `array` | Mengambil semua user dengan role 'guest' |
| `countByRole($role)` | `int` | Menghitung jumlah user berdasarkan role |

| File | Tabel | Deskripsi |
|------|-------|-----------|
| `Room. php` | `rooms` | Model untuk data kamar hotel |

**Methods di `Room.php`:**

| Method | Return | Deskripsi |
|--------|--------|-----------|
| `getAvailable()` | `array` | Mengambil semua kamar yang tersedia |
| `checkAvailability($roomId, $checkIn, $checkOut)` | `bool` | Cek ketersediaan kamar pada tanggal tertentu |
| `getByType($type)` | `array` | Mengambil kamar berdasarkan tipe |
| `updateAvailability($id, $status)` | `bool` | Mengubah status ketersediaan kamar |

| File | Tabel | Deskripsi |
|------|-------|-----------|
| `Booking.php` | `bookings` | Model untuk data pemesanan |

**Methods di `Booking.php`:**

| Method | Return | Deskripsi |
|--------|--------|-----------|
| `getByUser($userId)` | `array` | Mengambil semua booking milik user tertentu |
| `getAllWithDetails()` | `array` | Mengambil semua booking dengan data user & room (JOIN) |
| `getByStatus($status)` | `array` | Mengambil booking berdasarkan status |
| `getTotalRevenue()` | `float` | Menghitung total pendapatan dari booking confirmed/completed |
| `getRecentBookings($limit)` | `array` | Mengambil booking terbaru |
| `countByStatus($status)` | `int` | Menghitung jumlah booking berdasarkan status |

---

#### 1. 3 `app/Views/`

**Fungsi:** Menampilkan data ke user dalam bentuk HTML.  Views menerima data dari Controller dan merendernya. 

```
app/Views/
├── layouts/
├── auth/
├── home/
├── dashboard/
├── rooms/
├── admin/
├── partials/
└── errors/
```

##### 1.3.1 `app/Views/layouts/` - Template Layout

Layout adalah template utama yang membungkus konten halaman. 

| File | Deskripsi | Digunakan Oleh |
|------|-----------|----------------|
| `app.php` | Layout untuk halaman user/guest.  Berisi struktur HTML dasar, navbar, dan footer. | Semua halaman public dan user dashboard |
| `admin.php` | Layout untuk halaman admin.  Berisi sidebar navigasi dan header admin. | Semua halaman di folder admin/ |

**Struktur `app.php`:**
```
<! DOCTYPE html>
<html>
<head>
    <!-- Meta, Title, CSS -->
</head>
<body>
    <? php include 'partials/navbar.php'; ?>
    
    <main>
        <? = $content ?>  <!-- Konten halaman dimasukkan di sini -->
    </main>
    
    <?php include 'partials/footer. php'; ?>
    <!-- JavaScript -->
</body>
</html>
```

**Struktur `admin.php`:**
```
<!DOCTYPE html>
<html>
<head>
    <!-- Meta, Title, CSS -->
</head>
<body>
    <div class="flex">
        <?php include 'partials/sidebar.php'; ?>
        
        <main class="flex-1">
            <!-- Header -->
            <?= $content ?>  <!-- Konten halaman dimasukkan di sini -->
        </main>
    </div>
    <!-- JavaScript -->
</body>
</html>
```

##### 1.3.2 `app/Views/auth/` - Halaman Autentikasi

| File | Deskripsi | Route |
|------|-----------|-------|
| `login.php` | Form login dengan input email dan password.  Menampilkan flash message untuk error/success. | `/auth/login` |
| `register. php` | Form registrasi dengan input nama, email, phone, password, konfirmasi password, dan upload foto profil. | `/auth/register` |

##### 1.3. 3 `app/Views/home/` - Halaman Homepage

| File | Deskripsi | Route |
|------|-----------|-------|
| `index. php` | Landing page website.  Menampilkan hero section, featured rooms, dan call-to-action.  | `/` |

##### 1.3.4 `app/Views/dashboard/` - Halaman Dashboard User

| File | Deskripsi | Route |
|------|-----------|-------|
| `index. php` | Dashboard utama user setelah login.  Menampilkan ringkasan booking dan quick actions. | `/dashboard` |
| `profile.php` | Halaman edit profil user. Form untuk update nama, email, phone, password, dan foto profil. | `/dashboard/profile` |
| `my-bookings.php` | Daftar semua booking milik user. Menampilkan status, tanggal, dan aksi (cancel/view). | `/dashboard/bookings` |

##### 1.3. 5 `app/Views/rooms/` - Halaman Kamar (Public)

| File | Deskripsi | Route |
|------|-----------|-------|
| `index.php` | Daftar semua kamar tersedia dalam bentuk grid cards. Filter berdasarkan tipe kamar.  | `/rooms` |
| `show. php` | Detail lengkap satu kamar.  Gambar, deskripsi, harga, fasilitas, dan tombol booking. | `/rooms/{id}` |
| `book.php` | Form pemesanan kamar. Input tanggal check-in, check-out, dan konfirmasi booking. | `/rooms/{id}/book` |

##### 1. 3.6 `app/Views/admin/` - Halaman Admin

**`app/Views/admin/dashboard/`**

| File | Deskripsi | Route |
|------|-----------|-------|
| `index.php` | Dashboard admin dengan statistik.  Menampilkan total users, rooms, bookings, revenue, dan recent bookings. | `/admin/dashboard` |

**`app/Views/admin/rooms/`**

| File | Deskripsi | Route |
|------|-----------|-------|
| `index.php` | Tabel daftar semua kamar dengan aksi edit dan delete. | `/admin/rooms` |
| `create.php` | Form tambah kamar baru. Input nomor kamar, tipe, harga, deskripsi, dan upload gambar.  | `/admin/rooms/create` |
| `edit.php` | Form edit kamar.  Pre-filled dengan data kamar yang dipilih. | `/admin/rooms/{id}/edit` |

**`app/Views/admin/bookings/`**

| File | Deskripsi | Route |
|------|-----------|-------|
| `index.php` | Tabel daftar semua booking.  Filter berdasarkan status.  | `/admin/bookings` |
| `show.php` | Detail booking lengkap. Info guest, room, tanggal, harga, dan form update status. | `/admin/bookings/{id}` |

**`app/Views/admin/users/`**

| File | Deskripsi | Route |
|------|-----------|-------|
| `index.php` | Tabel daftar semua user.  Menampilkan nama, email, role, tanggal registrasi.  | `/admin/users` |

##### 1.3. 7 `app/Views/partials/` - Komponen Reusable

Partial adalah komponen kecil yang digunakan berulang di berbagai halaman.

| File | Deskripsi | Digunakan Di |
|------|-----------|--------------|
| `header.php` | Tag `<head>` berisi meta tags, title, link CSS (Tailwind output), dan font.  | Semua layouts |
| `footer.php` | Footer website berisi copyright, links, dan script JavaScript. | Layout `app.php` |
| `navbar. php` | Navigation bar untuk user.  Logo, menu navigasi, dan user dropdown (jika login). | Layout `app.php` |
| `sidebar.php` | Sidebar menu untuk admin. Logo, menu navigasi admin dengan icons. | Layout `admin.php` |
| `flash-message.php` | Komponen alert untuk menampilkan pesan success/error dari session flash. | Semua halaman |

##### 1.3. 8 `app/Views/errors/` - Halaman Error

| File | Deskripsi | Kapan Ditampilkan |
|------|-----------|-------------------|
| `404.php` | Halaman error 404 - Not Found. Ditampilkan ketika route tidak ditemukan.  | URL tidak valid |
| `500.php` | Halaman error 500 - Server Error. Ditampilkan ketika terjadi error di server. | Exception/error PHP |

---

### 2. `config/`

**Fungsi:** Menyimpan file konfigurasi aplikasi yang berisi konstanta dan pengaturan global. 

```
config/
├── app.php
└── database.php
```

##### Detail File Config:

| File | Deskripsi |
|------|-----------|
| `app.php` | Konfigurasi umum aplikasi |

**Konstanta di `app.php`:**

| Konstanta | Contoh Value | Deskripsi |
|-----------|--------------|-----------|
| `APP_NAME` | `'Hotel Booking'` | Nama aplikasi |
| `APP_URL` | `'http://localhost/hotel-booking'` | Base URL aplikasi |
| `APP_ROOT` | `dirname(__DIR__)` | Path root folder project |
| `CONTROLLERS_PATH` | `APP_ROOT . '/app/Controllers/'` | Path folder controllers |
| `MODELS_PATH` | `APP_ROOT .  '/app/Models/'` | Path folder models |
| `VIEWS_PATH` | `APP_ROOT .  '/app/Views/'` | Path folder views |
| `STORAGE_PATH` | `APP_ROOT . '/storage/'` | Path folder storage |
| `UPLOAD_PATH` | `STORAGE_PATH . 'uploads/'` | Path folder uploads |
| `MAX_FILE_SIZE` | `2 * 1024 * 1024` | Maksimal ukuran file upload (2MB) |
| `ALLOWED_EXTENSIONS` | `['jpg', 'jpeg', 'png', 'gif']` | Ekstensi file yang diizinkan |

| File | Deskripsi |
|------|-----------|
| `database.php` | Konfigurasi koneksi database |

**Konstanta di `database.php`:**

| Konstanta | Contoh Value | Deskripsi |
|-----------|--------------|-----------|
| `DB_HOST` | `'localhost'` | Host database server |
| `DB_NAME` | `'hotel_booking'` | Nama database |
| `DB_USER` | `'root'` | Username database |
| `DB_PASS` | `''` | Password database |
| `DB_CHARSET` | `'utf8mb4'` | Character set database |

---

### 3.  `core/`

**Fungsi:** Berisi class-class inti yang menjadi **fondasi/engine** dari arsitektur MVC.  File-file di sini adalah "mesin" yang menggerakkan aplikasi. 

```
core/
├── App.php
├── Controller. php
├── Database.php
├── Model.php
├── Router.php
└── Middleware. php
```

##### Detail File Core:

| File | Deskripsi |
|------|-----------|
| `App. php` | Bootstrap/entry point aplikasi |

**Fungsi `App.php`:**
- Memulai session PHP
- Memuat file konfigurasi (app.php, database.php)
- Memuat semua class core
- Menginisialisasi Router
- Memuat file routes
- Menjalankan dispatch router

| File | Deskripsi |
|------|-----------|
| `Controller.php` | Base class untuk semua controller |

**Methods di `Controller.php`:**

| Method | Parameter | Return | Deskripsi |
|--------|-----------|--------|-----------|
| `view()` | `$view, $data, $layout` | `void` | Merender view dengan layout dan mengirim data |
| `redirect()` | `$url` | `void` | Redirect ke URL tertentu |
| `back()` | - | `void` | Redirect ke halaman sebelumnya |
| `setFlash()` | `$type, $message` | `void` | Menyimpan flash message ke session |
| `getFlash()` | - | `array\|null` | Mengambil dan menghapus flash message dari session |
| `json()` | `$data, $statusCode` | `void` | Mengembalikan response JSON |
| `uploadFile()` | `$file, $folder` | `string\|null` | Mengupload file dan mengembalikan path |

| File | Deskripsi |
|------|-----------|
| `Database.php` | Koneksi database dengan pattern Singleton |

**Methods di `Database.php`:**

| Method | Return | Deskripsi |
|--------|--------|-----------|
| `getInstance()` | `Database` | Mendapatkan instance Database (Singleton) |
| `getConnection()` | `PDO` | Mendapatkan objek koneksi PDO |

**Mengapa Singleton? **
- Memastikan hanya ada SATU koneksi database sepanjang request
- Menghemat resource server
- Mencegah multiple connection yang tidak perlu

| File | Deskripsi |
|------|-----------|
| `Model.php` | Base class untuk semua model |

**Methods di `Model.php`:**

| Method | Parameter | Return | Deskripsi |
|--------|-----------|--------|-----------|
| `all()` | - | `array` | Mengambil semua record dari tabel |
| `find()` | `$id` | `array\|null` | Mencari record berdasarkan primary key |
| `findBy()` | `$column, $value` | `array\|null` | Mencari record berdasarkan kolom tertentu |
| `where()` | `$column, $value` | `array` | Mengambil semua record yang cocok |
| `create()` | `$data` | `int` | Membuat record baru, return ID |
| `update()` | `$id, $data` | `bool` | Mengupdate record berdasarkan ID |
| `delete()` | `$id` | `bool` | Menghapus record berdasarkan ID |
| `count()` | - | `int` | Menghitung total record |
| `query()` | `$sql, $params` | `array` | Menjalankan raw SQL query |

| File | Deskripsi |
|------|-----------|
| `Router.php` | Menangani URL routing |

**Methods di `Router.php`:**

| Method | Parameter | Return | Deskripsi |
|--------|-----------|--------|-----------|
| `get()` | `$uri, $action` | `void` | Mendaftarkan route GET |
| `post()` | `$uri, $action` | `void` | Mendaftarkan route POST |
| `dispatch()` | - | `void` | Mencocokkan URL dan memanggil controller |

**Cara Kerja Router:**
1. Mengambil URL dari request
2. Mencocokkan dengan daftar routes yang terdaftar
3. Mengekstrak parameter dari URL (jika ada)
4. Memanggil controller dan method yang sesuai
5.  Menampilkan 404 jika route tidak ditemukan

| File | Deskripsi |
|------|-----------|
| `Middleware.php` | Menangani autentikasi dan proteksi halaman |

**Methods di `Middleware. php`:**

| Method | Return | Deskripsi |
|--------|--------|-----------|
| `isLoggedIn()` | `bool` | Cek apakah user sudah login |
| `isAdmin()` | `bool` | Cek apakah user adalah admin |
| `auth()` | `void` | Proteksi halaman - redirect ke login jika belum login |
| `admin()` | `void` | Proteksi halaman admin - redirect jika bukan admin |
| `guest()` | `void` | Redirect ke dashboard jika sudah login (untuk halaman login/register) |
| `user()` | `array\|null` | Mengambil data user yang sedang login dari session |
| `setUser()` | `void` | Menyimpan data user ke session setelah login |
| `logout()` | `void` | Menghapus session dan logout user |

---

### 4.  `public/`

**Fungsi:** Folder yang dapat diakses publik melalui browser.  Semua request masuk melalui `index.php` (Front Controller pattern).

```
public/
├── index.php
├── . htaccess
└── assets/
    ├── css/
    │   └── output.css
    ├── js/
    │   └── script.js
    └── images/
        ├── logo.png
        └── favicon.ico
```

##### Detail File Public:

| File | Deskripsi |
|------|-----------|
| `index.php` | Entry point aplikasi (Front Controller) |

**Fungsi `index.php`:**
- Mendefinisikan `APP_ROOT` constant
- Memuat file config
- Memuat dan menjalankan `App. php`

```php
<?php
define('APP_ROOT', dirname(__DIR__));
require_once APP_ROOT . '/config/app.php';
require_once APP_ROOT . '/core/App.php';
App::run();
```

| File | Deskripsi |
|------|-----------|
| `. htaccess` | Konfigurasi Apache untuk URL rewrite |

**Fungsi `. htaccess`:**
- Mengaktifkan mod_rewrite
- Mengarahkan semua request ke `index.php`
- Kecuali file/folder yang benar-benar ada (CSS, JS, images)

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} ! -d
    RewriteRule ^(. *)$ index.php [QSA,L]
</IfModule>
```

##### Subfolder `public/assets/`:

| Folder/File | Deskripsi |
|-------------|-----------|
| `css/output.css` | File CSS hasil compile dari Tailwind.  Di-generate otomatis oleh `npm run build`.  **Jangan edit manual! ** |
| `js/script.js` | File JavaScript custom untuk interaktivitas (mobile menu, dropdown, dll) |
| `images/logo.png` | Logo website |
| `images/favicon.ico` | Favicon yang tampil di tab browser |

---

### 5. `routes/`

**Fungsi:** Mendefinisikan semua URL routing aplikasi - mapping antara URL dengan Controller. 

```
routes/
└── web.php
```

##### Detail File Routes:

| File | Deskripsi |
|------|-----------|
| `web.php` | Semua definisi route aplikasi |

**Contoh Isi `web.php`:**

```php
<?php
// ==========================================
// PUBLIC ROUTES (Tanpa Login)
// ==========================================
$router->get('/', 'HomeController@index');
$router->get('/rooms', 'RoomController@index');
$router->get('/rooms/{id}', 'RoomController@show');

// ==========================================
// AUTH ROUTES (Untuk Guest)
// ==========================================
$router->get('/auth/login', 'AuthController@loginForm');
$router->post('/auth/login', 'AuthController@login');
$router->get('/auth/register', 'AuthController@registerForm');
$router->post('/auth/register', 'AuthController@register');
$router->get('/auth/logout', 'AuthController@logout');

// ==========================================
// USER ROUTES (Butuh Login)
// ==========================================
$router->get('/dashboard', 'DashboardController@index');
$router->get('/dashboard/profile', 'DashboardController@profile');
$router->post('/dashboard/profile', 'DashboardController@updateProfile');
$router->get('/dashboard/bookings', 'BookingController@myBookings');
$router->post('/bookings', 'BookingController@store');
$router->post('/bookings/{id}/cancel', 'BookingController@cancel');

// ==========================================
// ADMIN ROUTES (Butuh Login + Role Admin)
// ==========================================
$router->get('/admin/dashboard', 'Admin/DashboardController@index');

// Admin - Rooms
$router->get('/admin/rooms', 'Admin/RoomController@index');
$router->get('/admin/rooms/create', 'Admin/RoomController@create');
$router->post('/admin/rooms', 'Admin/RoomController@store');
$router->get('/admin/rooms/{id}/edit', 'Admin/RoomController@edit');
$router->post('/admin/rooms/{id}', 'Admin/RoomController@update');
$router->post('/admin/rooms/{id}/delete', 'Admin/RoomController@destroy');

// Admin - Bookings
$router->get('/admin/bookings', 'Admin/BookingController@index');
$router->get('/admin/bookings/{id}', 'Admin/BookingController@show');
$router->post('/admin/bookings/{id}/status', 'Admin/BookingController@updateStatus');

// Admin - Users
$router->get('/admin/users', 'Admin/UserController@index');
```

**Format Route:**
```php
$router->METHOD('/url/path/{parameter}', 'ControllerName@methodName');
```

- `METHOD`: `get` atau `post`
- `/url/path`: URL yang akan diakses
- `{parameter}`: Parameter dinamis (opsional)
- `ControllerName@methodName`: Controller dan method yang akan dipanggil

---

### 6.  `src/`

**Fungsi:** Menyimpan source files yang perlu di-compile/build, khususnya untuk Tailwind CSS.

```
src/
└── css/
    └── input.css
```

##### Detail File Src:

| File | Deskripsi |
|------|-----------|
| `css/input.css` | Source file Tailwind CSS |

**Isi `input.css`:**

```css
/* Tailwind Directives - WAJIB ADA */
@tailwind base;       /* Reset CSS & base styles */
@tailwind components; /* Component classes */
@tailwind utilities;  /* Utility classes */

/* Custom Components */
@layer components {
  .btn { ...  }
  .btn-primary { ... }
  .form-input { ... }
  .card { ... }
  .alert-success { ... }
  /* dll */
}

/* Custom Utilities */
@layer utilities {
  .text-shadow { ... }
}
```

**Proses Build:**
```
src/css/input.css  ──►  Tailwind CLI  ──►  public/assets/css/output. css
     (source)              (build)              (hasil compile)
```

---

### 7.  `storage/`

**Fungsi:** Menyimpan file yang di-upload oleh user.  Folder ini TIDAK boleh diakses langsung dari browser.

```
storage/
└── uploads/
    ├── profiles/
    │   └── . gitkeep
    └── rooms/
        └── .gitkeep
```

##### Detail Folder Storage:

| Folder | Deskripsi | Contoh File |
|--------|-----------|-------------|
| `uploads/profiles/` | Menyimpan foto profil user | `profile_abc123_1234567890.jpg` |
| `uploads/rooms/` | Menyimpan gambar kamar hotel | `room_xyz789_1234567890.png` |

**File `. gitkeep`:**
- File kosong agar folder ter-track oleh Git
- Git tidak bisa track folder kosong, jadi perlu file ini
- Bisa dihapus setelah ada file lain di folder

**Penamaan File Upload:**
```
{prefix}_{unique_id}_{timestamp}.{extension}

Contoh: profile_5f3a2b1c_1699123456.jpg
```

**Permission Folder:**
```bash
chmod -R 755 storage/uploads/
# atau
chmod -R 775 storage/uploads/
```

---

### 8. `sql/`

**Fungsi:** Menyimpan file SQL untuk struktur database dan data awal. 

```
sql/
└── schema. sql
```

##### Detail File SQL:

| File | Deskripsi |
|------|-----------|
| `schema. sql` | Script SQL lengkap untuk setup database |

**Isi `schema.sql`:**

1. **CREATE DATABASE** - Membuat database `hotel_booking`
2. **CREATE TABLE users** - Tabel untuk data user/admin
3. **CREATE TABLE rooms** - Tabel untuk data kamar
4. **CREATE TABLE bookings** - Tabel untuk data pemesanan
5. **CREATE INDEX** - Index untuk optimasi query
6. **INSERT dummy data** - Data awal untuk testing

**Cara Menggunakan:**
```bash
# Via command line
mysql -u root -p < sql/schema.sql

# Atau import via phpMyAdmin
```

---

### 9. Root Files

File-file yang berada di root folder project. 

```
hotel-booking/
├── . htaccess
├── . gitignore
├── package.json
├── package-lock.json
├── tailwind.config.js
└── README.md
```

##### Detail Root Files:

| File | Deskripsi |
|------|-----------|
| `.htaccess` | Redirect semua request ke folder `public/` |

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

| File | Deskripsi |
|------|-----------|
| `.gitignore` | Daftar file/folder yang diabaikan Git |

```gitignore
# Dependencies
node_modules/

# Environment
.env

# Uploads
storage/uploads/profiles/*
storage/uploads/rooms/*
! storage/uploads/profiles/.gitkeep
!storage/uploads/rooms/.gitkeep

# IDE
.vscode/
.idea/

# OS
.DS_Store
Thumbs.db
```

| File | Deskripsi |
|------|-----------|
| `package.json` | Konfigurasi NPM - dependencies dan scripts |

```json
{
  "name": "hotel-booking",
  "version": "1.0.0",
  "scripts": {
    "dev": "tailwindcss -i ./src/css/input.css -o ./public/assets/css/output. css --watch",
    "build": "tailwindcss -i ./src/css/input.css -o ./public/assets/css/output.css",
    "build:prod": "tailwindcss -i ./src/css/input.css -o ./public/assets/css/output.css --minify"
  },
  "devDependencies": {
    "tailwindcss": "^3.4.0"
  }
}
```

| File | Deskripsi |
|------|-----------|
| `package-lock.json` | Lock file NPM - memastikan versi dependency konsisten |

| File | Deskripsi |
|------|-----------|
| `tailwind. config.js` | Konfigurasi Tailwind CSS |

```javascript
module.exports = {
  content: [
    "./app/Views/**/*.php",  // Scan semua file PHP di Views
  ],
  theme: {
    extend: {
      colors: {
        primary: { ...  }  // Custom colors
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif']
      }
    },
  },
  plugins: [],
}
```

| File | Deskripsi |
|------|-----------|
| `README.md` | Dokumentasi project (file ini) |

---

## 🗄 Database Schema

### Entity Relationship Diagram (ERD)

```
┌─────────────────────┐       ┌─────────────────────┐       ┌─────────────────────┐
│       USERS         │       │      BOOKINGS       │       │       ROOMS         │
├─────────────────────┤       ├─────────────────────┤       ├─────────────────────┤
│ PK  id              │       │ PK  id              │       │ PK  id              │
│     name            │       │ FK  user_id         │       │     room_number     │
│     email (unique)  │──────▶│ FK  room_id         │◀──────│     room_type       │
│     password_hash   │  1:N  │     check_in_date   │  N:1  │     price_per_night │
│     phone           │       │     check_out_date  │       │     description     │
│     profile_image   │       │     total_price     │       │     image           │
│     role            │       │     status          │       │     is_available    │
│     created_at      │       │     created_at      │       │     created_at      │
│     updated_at      │       │     updated_at      │       │     updated_at      │
└─────────────────────┘       └─────────────────────┘       └─────────────────────┘
```

### Tabel Users

| Kolom | Tipe | Constraint | Deskripsi |
|-------|------|------------|-----------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | ID unik user |
| `name` | VARCHAR(100) | NOT NULL | Nama lengkap |
| `email` | VARCHAR(100) | UNIQUE, NOT NULL | Email (untuk login) |
| `password_hash` | VARCHAR(255) | NOT NULL | Password ter-hash (bcrypt) |
| `phone` | VARCHAR(20) | NULL | Nomor telepon |
| `profile_image` | VARCHAR(255) | NULL | Path foto profil |
| `role` | ENUM('guest', 'admin') | DEFAULT 'guest' | Role user |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Waktu dibuat |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Waktu diupdate |

### Tabel Rooms

| Kolom | Tipe | Constraint | Deskripsi |
|-------|------|------------|-----------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | ID unik kamar |
| `room_number` | VARCHAR(10) | UNIQUE, NOT NULL | Nomor kamar |
| `room_type` | ENUM('standard', 'deluxe', 'suite') | NOT NULL | Tipe kamar |
| `price_per_night` | DECIMAL(10,2) | NOT NULL | Harga per malam |
| `description` | TEXT | NULL | Deskripsi kamar |
| `image` | VARCHAR(255) | NULL | Path gambar kamar |
| `is_available` | BOOLEAN | DEFAULT TRUE | Status ketersediaan |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Waktu dibuat |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Waktu diupdate |

### Tabel Bookings

| Kolom | Tipe | Constraint | Deskripsi |
|-------|------|------------|-----------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | ID unik booking |
| `user_id` | INT | FOREIGN KEY → users(id) | Relasi ke user |
| `room_id` | INT | FOREIGN KEY → rooms(id) | Relasi ke room |
| `check_in_date` | DATE | NOT NULL | Tanggal check-in |
| `check_out_date` | DATE | NOT NULL | Tanggal check-out |
| `total_price` | DECIMAL(10,2) | NOT NULL | Total harga |
| `status` | ENUM(... ) | DEFAULT 'pending' | Status booking |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Waktu dibuat |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Waktu diupdate |

**Status Booking:** `pending`, `confirmed`, `cancelled`, `completed`

---

## 🚀 Instalasi

### Prasyarat

- PHP 8.0 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Node.js 18 atau lebih tinggi
- NPM 9 atau lebih tinggi
- Apache dengan mod_rewrite (atau Nginx)

### Langkah Instalasi

#### 1. Clone Repository

```bash
git clone https://github. com/username/hotel-booking. git
cd hotel-booking
```

#### 2. Install Dependencies NPM

```bash
npm install
```

#### 3. Build Tailwind CSS

```bash
npm run build
```

#### 4. Setup Database

**Opsi A - Via Command Line:**
```bash
mysql -u root -p < sql/schema.sql
```

**Opsi B - Via phpMyAdmin:**
1. Buka phpMyAdmin
2.  Import file `sql/schema. sql`

#### 5.  Konfigurasi Database

Edit file `config/database.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'hotel_booking');
define('DB_USER', 'root');
define('DB_PASS', 'your_password'); // Sesuaikan
```

#### 6. Konfigurasi URL

Edit file `config/app.php`:

```php
define('APP_URL', 'http://localhost/hotel-booking');
```

#### 7. Set Permission Folder Upload

```bash
chmod -R 755 storage/uploads/
```

#### 8. Jalankan Aplikasi

**Opsi A - Via XAMPP/WAMP:**
1. Letakkan folder project di `htdocs` (XAMPP) atau `www` (WAMP)
2.  Akses: `http://localhost/hotel-booking/public`

**Opsi B - Via PHP Built-in Server:**
```bash
php -S localhost:8000 -t public
```
Akses: `http://localhost:8000`

---

## 👤 Penggunaan

### Akun Default

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@hotel.com | admin123 |
| Guest | john@example.com | guest123 |
| Guest | jane@example. com | guest123 |

### Alur Penggunaan Guest

```
┌──────────┐     ┌──────────┐     ┌──────────┐     ┌──────────┐
│ Register │────▶│  Login   │────▶│  Lihat   │────▶│  Booking │
│          │     │          │     │  Kamar   │     │  Kamar   │
└──────────┘     └──────────┘     └──────────┘     └──────────┘
                                                         │
                      ┌──────────────────────────────────┘
                      ▼
               ┌──────────┐     ┌──────────┐
               │   Cek    │────▶│  Logout  │
               │ Booking  │     │          │
               └──────────┘     └──────────┘
```

### Alur Penggunaan Admin

```
┌──────────┐     ┌──────────┐     ┌──────────┐
│  Login   │────▶│Dashboard │────▶│  Kelola  │
│  Admin   │     │ Statistik│     │   Data   │
└──────────┘     └──────────┘     └──────────┘
                                       │
            ┌──────────────────────────┼──────────────────────────┐
            ▼                          ▼                          ▼
     ┌──────────┐               ┌──────────┐               ┌──────────┐
     │  Kelola  │               │  Kelola  │               │  Kelola  │
     │  Rooms   │               │ Bookings │               │  Users   │
     └──────────┘               └──────────┘               └──────────┘
```

---

## 📜 NPM Scripts

| Script | Command | Deskripsi |
|--------|---------|-----------|
| `dev` | `npm run dev` | Watch mode - auto compile saat file berubah |
| `build` | `npm run build` | Build CSS sekali |
| `build:prod` | `npm run build:prod` | Build CSS dengan minify untuk production |

### Development Workflow

```bash
# Terminal 1 - PHP Server
php -S localhost:8000 -t public

# Terminal 2 - Tailwind Watch
npm run dev
```

### Production Build

```bash
npm run build:prod
```

---

## 📄 Lisensi

MIT License - Silakan gunakan untuk pembelajaran dan pengembangan.

---

## 👨‍💻 Author

Dibuat dengan ❤️ untuk pembelajaran PHP Native MVC. 