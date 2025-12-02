# 🏨 Hotel Booking System - Project PemWeb

Sistem Pemesanan Hotel berbasis PHP dengan arsitektur MVC (Model-View-Controller) custom framework.

![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4? style=flat-square&logo=php&logoColor=white)
![MySQL](https://img. shields.io/badge/MySQL-5.7+-4479A1?style=flat-square&logo=mysql&logoColor=white)
![TailwindCSS](https://img.shields. io/badge/TailwindCSS-3.x-06B6D4? style=flat-square&logo=tailwindcss&logoColor=white)

---

## 📑 Daftar Isi

- [Tentang Proyek](#-tentang-proyek)
- [Fitur](#-fitur)
- [Persyaratan Sistem](#-persyaratan-sistem)
- [Instalasi](#-instalasi)
- [Struktur Folder](#-struktur-folder)
- [Penjelasan File](#-penjelasan-file)
  - [Root Files](#root-files)
  - [Config](#config)
  - [Core](#core)
  - [App/Controllers](#appcontrollers)
  - [App/Models](#appmodels)
  - [App/Views](#appviews)
  - [Routes](#routes)
  - [Public](#public)
  - [Storage](#storage)
  - [SQL](#sql)
- [Cara Penggunaan](#-cara-penggunaan)
- [API Routes](#-api-routes)
- [Kontributor](#-kontributor)

---

## 📖 Tentang Proyek

**Hotel Booking System** adalah aplikasi web untuk manajemen pemesanan hotel yang dibangun menggunakan PHP native dengan arsitektur MVC custom.  Proyek ini dibuat untuk memenuhi tugas Pemrograman Web dengan fitur lengkap untuk tamu dan admin. 

---

## ✨ Fitur

### 👤 Fitur Tamu (Guest)
- ✅ Registrasi dan Login
- ✅ Lihat daftar kamar
- ✅ Pencarian dan filter kamar
- ✅ Booking kamar
- ✅ Lihat riwayat pemesanan
- ✅ Cetak invoice
- ✅ Update profil

### 🔐 Fitur Admin
- ✅ Dashboard dengan analytics
- ✅ Manajemen pengguna (CRUD)
- ✅ Manajemen kamar (CRUD)
- ✅ Manajemen booking
- ✅ Check-in / Check-out
- ✅ Export laporan
- ✅ Activity log

---

## 💻 Persyaratan Sistem

| Komponen | Versi Minimum |
|----------|---------------|
| PHP | 8.0+ |
| MySQL | 5.7+ |
| Apache | 2.4+ |
| Node.js | 16+ (untuk Tailwind CSS) |
| Composer | Tidak diperlukan |

### Ekstensi PHP yang Dibutuhkan
- PDO
- PDO_MySQL
- mbstring
- session

---

## 🚀 Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/Rainzy21/Project-PemWeb. git
cd Project-PemWeb
```

### 2.  Konfigurasi Database
```sql
-- Buat database
CREATE DATABASE book_hotel;

-- Import schema (jika ada)
mysql -u root -p book_hotel < sql/schema.sql
```

### 3. Konfigurasi Aplikasi
Edit file `config/config.php`:
```php
define('BASE_URL', 'http://localhost/Project-PemWeb/public/');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'book_hotel');
```

### 4.  Install Dependencies (Tailwind CSS)
```bash
npm install
```

### 5. Jalankan Tailwind (Development)
```bash
npm run dev
```

### 6.  Akses Aplikasi
Buka browser dan akses:
```
http://localhost/Project-PemWeb/public/
```

---

## 📁 Struktur Folder

```
Project-PemWeb/
│
├── 📄 . htaccess                    # Konfigurasi Apache (URL rewriting)
├── 📄 README.md                    # Dokumentasi proyek
├── 📄 package. json                 # Dependencies Node.js
├── 📄 package-lock.json            # Lock file dependencies
├── 📄 tailwind.config.js           # Konfigurasi Tailwind CSS
│
├── 📁 app/                         # Aplikasi utama (MVC)
│   ├── 📁 Controllers/             # Controller aplikasi
│   │   ├── 📁 Admin/               # Controller admin
│   │   ├── 📁 Traits/              # Traits untuk controller
│   │   ├── 📄 AuthController.php   # Autentikasi user
│   │   ├── 📄 BookingController. php # Manajemen booking
│   │   └── 📄 RoomController.php   # Manajemen kamar
│   │
│   ├── 📁 Models/                  # Model database
│   │   ├── 📁 Traits/              # Traits untuk model
│   │   ├── 📄 Booking.php          # Model booking
│   │   ├── 📄 Room.php             # Model kamar
│   │   └── 📄 User.php             # Model user
│   │
│   └── 📁 Views/                   # Template view
│       ├── 📁 admin/               # View halaman admin
│       ├── 📁 auth/                # View login/register
│       ├── 📁 books/               # View booking
│       ├── 📁 home/                # View halaman utama
│       ├── 📁 layout/              # Layout template
│       ├── 📁 partials/            # Komponen partial
│       └── 📁 rooms/               # View kamar
│
├── 📁 config/                      # Konfigurasi aplikasi
│   └── 📄 config.php               # File konfigurasi utama
│
├── 📁 core/                        # Framework core
│   ├── 📄 App.php                  # Bootstrap aplikasi
│   ├── 📄 Controller.php           # Base controller
│   ├── 📄 Database.php             # Koneksi database
│   ├── 📄 Model.php                # Base model
│   ├── 📄 Router.php               # Sistem routing
│   ├── 📄 View.php                 # View renderer
│   └── 📁 Traits/                  # Traits framework
│
├── 📁 public/                      # Document root (web accessible)
│   ├── 📄 . htaccess                # Rewrite rules
│   ├── 📄 index.php                # Entry point aplikasi
│   └── 📁 asset/                   # Assets (CSS, JS, images)
│
├── 📁 routes/                      # Definisi routing
│   └── 📄 web.php                  # Routes aplikasi
│
├── 📁 sql/                         # Database files
│   └── 📄 schema.sql               # Struktur database
│
├── 📁 storage/                     # File storage
│   └── 📁 uploads/                 # Upload files
│
└── 📁 node_modules/                # Dependencies Node.js
```

---

## 📝 Penjelasan File

### Root Files

#### 📄 `.htaccess`
```apache
# Konfigurasi Apache untuk URL rewriting
# Mengarahkan semua request ke public/index.php
```
**Fungsi:** Mengatur URL rewriting agar semua request diarahkan ke folder `public/`. 

#### 📄 `package.json`
**Fungsi:** Mendefinisikan dependencies Node.js untuk Tailwind CSS. 
```json
{
  "scripts": {
    "dev": "tailwindcss -i ./public/asset/css/input.css -o ./public/asset/css/output.css --watch",
    "build": "tailwindcss -i ./public/asset/css/input.css -o ./public/asset/css/output. css --minify"
  }
}
```

#### 📄 `tailwind.config.js`
**Fungsi:** Konfigurasi Tailwind CSS untuk styling aplikasi.
```javascript
module.exports = {
  content: ["./app/Views/**/*.php"],
  theme: { extend: {} },
  plugins: [],
}
```

---

### Config

#### 📄 `config/config.php`
**Fungsi:** File konfigurasi utama aplikasi. 

| Konstanta | Deskripsi | Contoh |
|-----------|-----------|--------|
| `BASE_URL` | URL dasar aplikasi | `http://localhost/Project-PemWeb/public/` |
| `STORAGE_PATH` | Path folder storage | `__DIR__ . '/../storage'` |
| `DB_HOST` | Host database | `localhost` |
| `DB_USER` | Username database | `root` |
| `DB_PASS` | Password database | `''` |
| `DB_NAME` | Nama database | `book_hotel` |
| `APP_NAME` | Nama aplikasi | `Hotel Booking` |
| `APP_VERSION` | Versi aplikasi | `1.0.0` |
| `APP_DEBUG` | Mode debug | `true` |
| `SESSION_LIFETIME` | Durasi session (detik) | `3600` |
| `UPLOAD_PATH` | Path upload file | `__DIR__ . '/../public/uploads/'` |
| `MAX_FILE_SIZE` | Maksimal ukuran file | `2MB` |
| `ALLOWED_EXTENSIONS` | Ekstensi file yang diizinkan | `['jpg', 'jpeg', 'png', 'gif']` |

---

### Core

#### 📄 `core/App.php`
**Fungsi:** Bootstrap aplikasi dan dispatcher utama. 

```php
namespace Core;

class App
{
    use ParsesUrl, ResolvesApp;
    
    protected string $defaultController = 'Home';
    protected string $defaultMethod = 'index';
}
```

**Method:**
| Method | Deskripsi |
|--------|-----------|
| `__construct()` | Inisialisasi aplikasi dan dispatch request |
| `dispatch()` | Mengarahkan request ke controller yang sesuai |
| `getController()` | Mendapatkan nama controller saat ini |
| `getMethod()` | Mendapatkan nama method saat ini |
| `getCurrentParams()` | Mendapatkan parameter URL |

---

#### 📄 `core/Controller.php`
**Fungsi:** Base controller yang di-extend oleh semua controller. 

```php
namespace Core;

class Controller
{
    use LoadsModels, HandlesRequest, HandlesResponse, ValidatesInput, ChecksAuth;
    
    protected View $view;
    
    protected function render(string $view, array $data = []): void;
}
```

**Traits yang digunakan:**
| Trait | Deskripsi |
|-------|-----------|
| `LoadsModels` | Memuat model |
| `HandlesRequest` | Menangani HTTP request |
| `HandlesResponse` | Menangani HTTP response |
| `ValidatesInput` | Validasi input |
| `ChecksAuth` | Cek autentikasi |

---

#### 📄 `core/Database.php`
**Fungsi:** Koneksi database menggunakan PDO dengan Singleton Pattern.

```php
namespace Core;

class Database
{
    use HasConnection, HasStatement, HasFetch, HasTransaction;
    
    private static ?Database $instance = null;
    
    public static function getInstance(): self;
}
```

**Traits yang digunakan:**
| Trait | Deskripsi |
|-------|-----------|
| `HasConnection` | Koneksi database |
| `HasStatement` | Prepared statements |
| `HasFetch` | Fetch data |
| `HasTransaction` | Transaction handling |

---

#### 📄 `core/Model.php`
**Fungsi:** Base model dengan operasi CRUD dasar.

```php
namespace Core;

class Model
{
    use HasCRUD, HasQuery, HasAggregate, HasRawQuery;
    
    protected $db;
    protected string $table = '';
    protected array $fillable = [];
}
```

**Method Bawaan:**
| Method | Deskripsi | Return |
|--------|-----------|--------|
| `all()` | Ambil semua record | `array` |
| `find($id)` | Cari berdasarkan ID | `object\|null` |
| `findBy($column, $value)` | Cari berdasarkan kolom | `object\|null` |
| `where($column, $value)` | Ambil semua dengan kondisi | `array` |
| `create(array $data)` | Buat record baru | `int` (ID) |
| `update($id, array $data)` | Update record | `bool` |
| `delete($id)` | Hapus record | `bool` |
| `count()` | Hitung jumlah record | `int` |
| `raw($sql, array $params)` | Query mentah | `array` |

---

#### 📄 `core/Router. php`
**Fungsi:** Sistem routing untuk mengarahkan URL ke controller.

```php
namespace Core;

class Router
{
    use ParsesRoutes, ResolvesController, HandlesErrors;
    
    public function get(string $route, string $action): self;
    public function post(string $route, string $action): self;
    public function dispatch(string $url): void;
}
```

**Fitur:**
- ✅ GET dan POST routes
- ✅ Parameter dinamis `{id}`
- ✅ Auto routing (fallback)
- ✅ Regex pattern matching

---

#### 📄 `core/View.php`
**Fungsi:** Render template view dengan fitur lengkap. 

```php
namespace Core;

class View
{
    public function render($view, $data = []);
    public function setLayout($layout);
    public function partial($view, $data = []);
}
```

**Helper Methods:**
| Method | Deskripsi | Contoh |
|--------|-----------|--------|
| `e($text)` | Escape HTML | `$this->e($name)` |
| `url($path)` | Generate URL | `$this->url('rooms')` |
| `asset($path)` | Asset URL | `$this->asset('css/style.css')` |
| `flash($type)` | Flash message | `$this->flash('success')` |
| `auth()` | Cek login | `$this->auth()` |
| `user()` | Data user login | `$this->user()` |
| `currency($amount)` | Format Rupiah | `$this->currency(500000)` |
| `date($date, $format)` | Format tanggal | `$this->date('2024-01-01')` |
| `old($key, $default)` | Old input value | `$this->old('email')` |

---

### Core/Traits

#### 📁 `core/Traits/`

| File | Deskripsi |
|------|-----------|
| `ChecksAuth. php` | Trait untuk cek autentikasi user |
| `FormatsOutput.php` | Trait untuk format output (currency, date) |
| `GeneratesUrls.php` | Trait untuk generate URL |
| `HandlesErrors.php` | Trait untuk handle error (404, 500) |
| `HandlesRequest.php` | Trait untuk handle HTTP request |
| `HandlesResponse.php` | Trait untuk handle HTTP response (redirect, json) |
| `HandlesSession.php` | Trait untuk handle session |
| `HasAggregate.php` | Trait untuk fungsi agregat (count, sum, avg) |
| `HasCRUD. php` | Trait untuk operasi CRUD |
| `HasConnection.php` | Trait untuk koneksi database PDO |
| `HasFetch.php` | Trait untuk fetch data dari database |
| `HasQuery.php` | Trait untuk query builder |
| `HasRawQuery.php` | Trait untuk raw SQL query |
| `HasStatement.php` | Trait untuk prepared statements |
| `HasTransaction.php` | Trait untuk database transaction |
| `LoadsModels. php` | Trait untuk memuat model |
| `ParsesRoutes.php` | Trait untuk parsing routes |
| `ParsesUrl.php` | Trait untuk parsing URL |
| `RendersViews.php` | Trait untuk render views |
| `ResolvesApp.php` | Trait untuk resolve controller dan method |
| `ResolvesController.php` | Trait untuk resolve controller class |
| `ValidatesInput.php` | Trait untuk validasi input |

---

### App/Controllers

#### 📄 `app/Controllers/AuthController.php`
**Fungsi:** Menangani autentikasi user (login, register, logout, profile).

**Methods:**
| Method | Route | Deskripsi |
|--------|-------|-----------|
| `login()` | GET `/login` | Tampilkan form login |
| `doLogin()` | POST `/login` | Proses login |
| `register()` | GET `/register` | Tampilkan form register |
| `doRegister()` | POST `/register` | Proses registrasi |
| `logout()` | GET `/logout` | Logout user |
| `profile()` | GET `/profile` | Tampilkan profil |
| `updateProfile()` | POST `/profile/update` | Update profil |
| `updatePassword()` | POST `/profile/password` | Ganti password |
| `forgotPassword()` | GET `/forgot-password` | Form lupa password |
| `doForgotPassword()` | POST `/forgot-password` | Proses reset password |

---

#### 📄 `app/Controllers/BookingController.php`
**Fungsi:** Menangani pemesanan kamar oleh user.

**Methods:**
| Method | Route | Deskripsi |
|--------|-------|-----------|
| `myBookings()` | GET `/my-bookings` | Daftar booking user |
| `create($id)` | GET `/booking/create/{id}` | Form booking |
| `store()` | POST `/booking/store` | Simpan booking |
| `detail($id)` | GET `/booking/detail/{id}` | Detail booking |
| `cancel($id)` | GET `/booking/cancel/{id}` | Batalkan booking |
| `invoice($id)` | GET `/booking/invoice/{id}` | Cetak invoice |
| `checkAvailability()` | POST `/booking/check-availability` | Cek ketersediaan |

---

#### 📄 `app/Controllers/RoomController.php`
**Fungsi:** Menampilkan daftar dan detail kamar. 

**Methods:**
| Method | Route | Deskripsi |
|--------|-------|-----------|
| `index()` | GET `/rooms` | Daftar semua kamar |
| `detail($id)` | GET `/rooms/{id}` | Detail kamar |
| `search()` | GET `/rooms/search` | Pencarian kamar |
| `types()` | GET `/rooms/types` | Daftar tipe kamar |
| `filterByType()` | GET `/rooms/filter` | Filter berdasarkan tipe |
| `getInfo($id)` | GET `/rooms/info/{id}` | Info kamar (AJAX) |
| `checkAvailability($id)` | GET `/rooms/availability/{id}` | Cek ketersediaan |

---

#### 📁 `app/Controllers/Admin/`
**Fungsi:** Controller untuk panel admin.

| Controller | Deskripsi |
|------------|-----------|
| `AdminDashboardController. php` | Dashboard, analytics, reports |
| `AdminUserController.php` | Manajemen user |
| `AdminRoomController.php` | Manajemen kamar |
| `AdminBookingController.php` | Manajemen booking |

---

### App/Models

#### 📄 `app/Models/User.php`
**Fungsi:** Model untuk tabel users.

```php
namespace App\Models;

class User extends \Core\Model
{
    protected string $table = 'users';
    protected array $fillable = ['name', 'email', 'password', 'phone', 'role'];
}
```

---

#### 📄 `app/Models/Room.php`
**Fungsi:** Model untuk tabel rooms/kamar.

```php
namespace App\Models;

class Room extends \Core\Model
{
    protected string $table = 'rooms';
    protected array $fillable = ['name', 'type', 'price', 'capacity', 'description', 'image', 'is_available'];
}
```

---

#### 📄 `app/Models/Booking.php`
**Fungsi:** Model untuk tabel bookings/pemesanan.

```php
namespace App\Models;

class Booking extends \Core\Model
{
    protected string $table = 'bookings';
    protected array $fillable = ['user_id', 'room_id', 'check_in', 'check_out', 'total_price', 'status'];
}
```

---

### App/Views

#### 📁 `app/Views/`

| Folder | Deskripsi |
|--------|-----------|
| `admin/` | View untuk halaman admin (dashboard, users, rooms, bookings) |
| `auth/` | View untuk login, register, forgot password |
| `books/` | View untuk booking (create, detail, my-bookings, invoice) |
| `home/` | View untuk halaman utama |
| `layout/` | Template layout (header, footer, sidebar) |
| `partials/` | Komponen reusable (alerts, pagination, modal) |
| `rooms/` | View untuk daftar dan detail kamar |

---

### Routes

#### 📄 `routes/web. php`
**Fungsi:** Mendefinisikan semua route aplikasi. 

**Public Routes:**
```php
$router->get('', 'HomeController@index');
$router->get('login', 'AuthController@login');
$router->post('login', 'AuthController@doLogin');
$router->get('register', 'AuthController@register');
$router->get('rooms', 'RoomController@index');
$router->get('rooms/{id}', 'RoomController@detail');
```

**Protected Routes (Requires Login):**
```php
$router->get('my-bookings', 'BookingController@myBookings');
$router->get('booking/create/{id}', 'BookingController@create');
$router->get('profile', 'AuthController@profile');
```

**Admin Routes:**
```php
$router->get('admin', 'AdminDashboardController@index');
$router->get('admin/users', 'AdminUserController@index');
$router->get('admin/rooms', 'AdminRoomController@index');
$router->get('admin/bookings', 'AdminBookingController@index');
```

---

### Public

#### 📄 `public/index.php`
**Fungsi:** Entry point aplikasi (front controller).

```php
<? php
session_start();

// Load config
require_once __DIR__ . '/../config/config.php';

// Autoload dengan namespace
spl_autoload_register(function ($class) {
    // Mapping namespace ke direktori
    $namespaceMap = [
        'Core\\Traits\\' => '/core/Traits/',
        'Core\\' => '/core/',
        'App\\Controllers\\' => '/app/controllers/',
        'App\\Models\\Traits\\' => '/app/models/Traits/',
        'App\\Models\\' => '/app/models/'
    ];
    // ... 
});

// Initialize App
$app = new Core\App();
```

#### 📄 `public/. htaccess`
**Fungsi:** URL rewriting untuk clean URL.

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(. *)$ index.php?url=$1 [QSA,L]
```

#### 📁 `public/asset/`
**Fungsi:** Folder untuk assets publik (CSS, JavaScript, Images).

---

### Storage

#### 📁 `storage/uploads/`
**Fungsi:** Menyimpan file yang diupload user (foto profil, gambar kamar). 

---

### SQL

#### 📄 `sql/schema.sql`
**Fungsi:** File SQL untuk struktur database.

---

## 🔧 Cara Penggunaan

### Membuat Controller Baru
```php
<? php
// app/Controllers/ExampleController.php

namespace App\Controllers;

use Core\Controller;

class ExampleController extends Controller
{
    public function index()
    {
        $data = ['title' => 'Example Page'];
        $this->render('example/index', $data);
    }
}
```

### Membuat Model Baru
```php
<?php
// app/Models/Example.php

namespace App\Models;

use Core\Model;

class Example extends Model
{
    protected string $table = 'examples';
    protected array $fillable = ['name', 'description'];
}
```

### Membuat View Baru
```php
<!-- app/Views/example/index. php -->
<! DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
</head>
<body>
    <h1><?= $title ?></h1>
</body>
</html>
```

### Menambah Route Baru
```php
// routes/web.php
$router->get('example', 'ExampleController@index');
$router->post('example/store', 'ExampleController@store');
$router->get('example/{id}', 'ExampleController@show');
```

---

## 🛣️ API Routes

### Public Routes
| Method | URL | Controller | Deskripsi |
|--------|-----|------------|-----------|
| GET | `/` | HomeController@index | Halaman utama |
| GET | `/login` | AuthController@login | Form login |
| POST | `/login` | AuthController@doLogin | Proses login |
| GET | `/register` | AuthController@register | Form register |
| POST | `/register` | AuthController@doRegister | Proses register |
| GET | `/logout` | AuthController@logout | Logout |
| GET | `/rooms` | RoomController@index | Daftar kamar |
| GET | `/rooms/{id}` | RoomController@detail | Detail kamar |

### User Routes (Requires Login)
| Method | URL | Controller | Deskripsi |
|--------|-----|------------|-----------|
| GET | `/profile` | AuthController@profile | Profil user |
| POST | `/profile/update` | AuthController@updateProfile | Update profil |
| GET | `/my-bookings` | BookingController@myBookings | Daftar booking |
| GET | `/booking/create/{id}` | BookingController@create | Form booking |
| POST | `/booking/store` | BookingController@store | Simpan booking |
| GET | `/booking/detail/{id}` | BookingController@detail | Detail booking |
| GET | `/booking/cancel/{id}` | BookingController@cancel | Batalkan booking |
| GET | `/booking/invoice/{id}` | BookingController@invoice | Cetak invoice |

### Admin Routes
| Method | URL | Controller | Deskripsi |
|--------|-----|------------|-----------|
| GET | `/admin` | AdminDashboardController@index | Dashboard |
| GET | `/admin/users` | AdminUserController@index | Daftar user |
| GET | `/admin/users/create` | AdminUserController@create | Form tambah user |
| GET | `/admin/rooms` | AdminRoomController@index | Daftar kamar |
| GET | `/admin/rooms/create` | AdminRoomController@create | Form tambah kamar |
| GET | `/admin/bookings` | AdminBookingController@index | Daftar booking |
| GET | `/admin/bookings/{id}/confirm` | AdminBookingController@confirm | Konfirmasi booking |
| GET | `/admin/bookings/{id}/checkin` | AdminBookingController@checkIn | Check-in |
| GET | `/admin/bookings/{id}/checkout` | AdminBookingController@checkOut | Check-out |

---

## 👥 Kontributor

| Nama | Role |
|------|------|
| Rainzy21 | Developer |

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan akademik - Tugas Pemrograman Web. 

---

<p align="center">
  Made with ❤️ by <a href="https://github.com/Rainzy21">Rainzy21</a>
</p>