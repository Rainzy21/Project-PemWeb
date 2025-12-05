# 🏨 Hotel Booking System

<div align="center">

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

**Sistem Pemesanan Hotel Berbasis Web dengan Arsitektur MVC Custom**

[Demo](#demo) • [Fitur](#-fitur-utama) • [Instalasi](#-instalasi) • [Dokumentasi](#-dokumentasi-lengkap)

</div>

---

## 📋 Daftar Isi

- [Tentang Project](#-tentang-project)
- [Fitur Utama](#-fitur-utama)
- [Tech Stack](#-tech-stack)
- [Struktur Folder](#-struktur-folder)
- [Instalasi](#-instalasi)
- [Konfigurasi](#-konfigurasi)
- [Dokumentasi Lengkap](#-dokumentasi-lengkap)
  - [Core Framework](#1-core-framework)
  - [Controllers](#2-controllers)
  - [Models](#3-models)
  - [Views](#4-views)
  - [Routes](#5-routes)
- [Database Schema](#-database-schema)
- [API Endpoints](#-api-endpoints)
- [Screenshots](#-screenshots)
- [Kontributor](#-kontributor)

---

## 📖 Tentang Project

**Hotel Booking System** adalah aplikasi web untuk manajemen pemesanan hotel yang dibangun dengan arsitektur **MVC (Model-View-Controller)** custom menggunakan PHP Native.  Project ini dibuat sebagai tugas mata kuliah Pemrograman Web. 

### Highlight:
- 🎯 Custom MVC Framework dari scratch
- 🔐 Sistem autentikasi dengan role-based access
- 📱 Responsive design dengan Tailwind CSS
- 🔄 AJAX untuk interaksi real-time
- 📊 Dashboard admin lengkap dengan analytics

---

## ✨ Fitur Utama

### 👤 User/Guest
| Fitur | Deskripsi |
|-------|-----------|
| 🔐 Autentikasi | Login, Register, Logout, Forgot Password |
| 🛏️ Lihat Kamar | Browse kamar dengan filter dan search |
| 📅 Booking | Pesan kamar dengan cek ketersediaan real-time |
| 📋 My Bookings | Lihat riwayat dan status pemesanan |
| 👤 Profile | Update profil dan password |

### 👨‍💼 Admin
| Fitur | Deskripsi |
|-------|-----------|
| 📊 Dashboard | Overview statistik dan analytics |
| 🛏️ Kelola Kamar | CRUD kamar, toggle availability |
| 📅 Kelola Booking | Confirm, Check-in, Check-out, Cancel |
| 👥 Kelola User | CRUD user, reset password, toggle role |
| 📈 Reports | Export laporan, revenue analytics |

---

## 🛠 Tech Stack

| Kategori | Teknologi |
|----------|-----------|
| **Backend** | PHP 8.x (Native) |
| **Database** | MySQL |
| **Frontend** | HTML5, CSS3, JavaScript |
| **CSS Framework** | Tailwind CSS |
| **Server** | Apache (XAMPP/Laragon) |
| **Pattern** | MVC (Model-View-Controller) |

---

## 📁 Struktur Folder

```
Project-PemWeb/
│
├── 📂 app/                          # Application Layer
│   ├── 📂 Controllers/              # Controller classes
│   │   ├── 📂 Admin/                # Admin controllers
│   │   │   ├── AdminBookingController.php
│   │   │   ├── AdminRoomController.php
│   │   │   ├── AdminUserController.php
│   │   │   └── DashboardController.php
│   │   ├── 📂 Traits/               # Controller traits
│   │   │   ├── FormatsRoomData.php
│   │   │   ├── HandlesAuth.php
│   │   │   ├── HandlesBooking.php
│   │   │   ├── HandlesOldInput.php
│   │   │   ├── HandlesRoom.php
│   │   │   ├── HandlesRoomFilter.php
│   │   │   ├── ValidatesBookingDates.php
│   │   │   └── ValidatesRequest.php
│   │   ├── AuthController.php       # Autentikasi
│   │   ├── BookingController. php    # Pemesanan
│   │   ├── HomeController.php       # Halaman utama
│   │   └── RoomController.php       # Kamar
│   │
│   ├── 📂 Models/                   # Model classes
│   │   ├── 📂 Traits/               # Model traits
│   │   │   ├── Filterable.php
│   │   │   ├── HasImage.php
│   │   │   ├── HasPassword.php
│   │   │   ├── HasRole.php
│   │   │   ├── HasStatus.php
│   │   │   └── Searchable.php
│   │   ├── Booking.php              # Model booking
│   │   ├── PasswordResetRequest.php # Model reset password
│   │   ├── Room.php                 # Model kamar
│   │   └── User.php                 # Model user
│   │
│   ├── 📂 Views/                    # View templates
│   │   ├── 📂 admin/                # Admin views
│   │   ├── 📂 auth/                 # Auth views (login, register)
│   │   ├── 📂 booking/              # Booking views
│   │   ├── 📂 home/                 # Home views
│   │   ├── 📂 layouts/              # Layout templates
│   │   ├── 📂 partials/             # Reusable components
│   │   └── 📂 rooms/                # Room views
│   │
│   └── 📂 storage/                  # App storage
│
├── 📂 config/                       # Konfigurasi
│   └── config.php                   # Config utama
│
├── 📂 core/                         # Core Framework
│   ├── 📂 Traits/                   # Core traits
│   │   ├── ChecksAuth.php
│   │   ├── HandlesErrors.php
│   │   ├── HandlesRequest.php
│   │   ├── HandlesResponse.php
│   │   ├── HasAggregate.php
│   │   ├── HasConnection.php
│   │   ├── HasCRUD.php
│   │   ├── HasFetch.php
│   │   ├── HasQuery.php
│   │   ├── HasRawQuery.php
│   │   ├── HasStatement.php
│   │   ├── HasTransaction.php
│   │   ├── LoadsModels.php
│   │   ├── ParsesRoutes.php
│   │   ├── ResolvesController.php
│   │   └── ValidatesInput.php
│   ├── App.php                      # Application bootstrap
│   ├── Controller.php               # Base controller
│   ├── Database.php                 # Database connection
│   ├── Model.php                    # Base model
│   ├── Router.php                   # Routing system
│   └── View.php                     # View engine
│
├── 📂 public/                       # Public directory (DocumentRoot)
│   ├── 📂 asset/                    # Static assets
│   │   ├── 📂 css/                  # Stylesheets
│   │   ├── 📂 js/                   # JavaScript files
│   │   └── 📂 images/               # Images
│   ├── . htaccess                    # URL rewriting
│   └── index.php                    # Entry point
│
├── 📂 routes/                       # Route definitions
│   └── web.php                      # Web routes
│
├── 📂 sql/                          # Database files
│   └── schema.sql                   # Database schema
│
├── 📂 storage/                      # File storage
│   └── 📂 uploads/                  # Uploaded files
│       ├── 📂 profiles/             # Profile images
│       └── 📂 rooms/                # Room images
│
├── . htaccess                        # Root URL rewriting
├── package.json                     # NPM dependencies
├── tailwind.config.js               # Tailwind configuration
└── README.md                        # Dokumentasi
```

---

## 🚀 Instalasi

### Prasyarat
- PHP >= 8.0
- MySQL >= 5.7
- Apache dengan mod_rewrite
- Node.js (untuk Tailwind CSS)
- Composer (opsional)

### Langkah Instalasi

#### 1. Clone Repository
```bash
git clone https://github.com/Rainzy21/Project-PemWeb. git
cd Project-PemWeb
```

#### 2. Setup Database
```sql
-- Buat database
CREATE DATABASE book_hotel;

-- Import schema
mysql -u root -p book_hotel < sql/schema.sql
```

#### 3. Konfigurasi
Edit file `config/config.php`:
```php
// Sesuaikan dengan environment Anda
define('BASE_URL', 'http://localhost/Project-PemWeb/public/');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'book_hotel');
```

#### 4.  Install Dependencies (Tailwind CSS)
```bash
npm install
npm run build
```

#### 5. Set Permissions
```bash
chmod -R 755 storage/
chmod -R 755 app/storage/
```

#### 6.  Jalankan Aplikasi
Buka browser dan akses:
```
http://localhost/Project-PemWeb/public/
```

---

## ⚙ Konfigurasi

### File: `config/config.php`

```php
<?php

// ============================================
// BASE URL
// ============================================
define('BASE_URL', 'http://localhost/Project-PemWeb/public/');
define('STORAGE_URL', 'http://localhost/Project-PemWeb/storage/');
define('STORAGE_PATH', __DIR__ . '/../storage');

// ============================================
// DATABASE CONFIGURATION
// ============================================
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'book_hotel');

// ============================================
// APP CONFIGURATION
// ============================================
define('APP_NAME', 'Hotel Booking');
define('APP_VERSION', '1.0.0');
define('APP_DEBUG', true);

// ============================================
// SESSION CONFIGURATION
// ============================================
define('SESSION_LIFETIME', 3600); // 1 hour

// ============================================
// UPLOAD CONFIGURATION
// ============================================
define('UPLOAD_PATH', __DIR__ . '/../storage/uploads/');
define('MAX_FILE_SIZE', 2 * 1024 * 1024); // 2MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);
```

### File: `tailwind.config.js`

```javascript
/** @type {import('tailwindcss').Config} */
module. exports = {
  content: [
    "./app/Views/**/*.php",
    "./public/**/*.{html,js,php}",
    "./app/**/*.php",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};
```

---

## 📚 Dokumentasi Lengkap

### 1. Core Framework

#### 📄 `core/App.php` - Application Bootstrap

File ini adalah entry point dari aplikasi yang menginisialisasi router dan mendispatch request. 

```php
<?php

namespace Core;

class App
{
    public function __construct()
    {
        // Load routes dan dispatch via Router
        $router = require dirname(__DIR__) . '/routes/web.php';
        
        $url = trim($_GET['url'] ?? '', '/');
        $router->dispatch($url);
    }
}
```

**Penjelasan:**
- `__construct()` - Constructor yang dipanggil saat aplikasi dimulai
- Memuat file routes dari `routes/web.php`
- Mengambil URL dari query parameter `url`
- Mendispatch URL ke router untuk diproses

---

#### 📄 `core/Router.php` - Routing System

Router menangani mapping URL ke controller dan method yang sesuai.

```php
<? php

namespace Core;

use Core\Traits\ParsesRoutes;
use Core\Traits\ResolvesController;
use Core\Traits\HandlesErrors;

class Router
{
    use ParsesRoutes, ResolvesController, HandlesErrors;

    protected array $routes = [];      // Menyimpan semua route
    protected array $params = [];      // Parameter dari URL
    protected string $controller = ''; // Controller yang akan dipanggil
    protected string $method = '';     // Method yang akan dipanggil

    /**
     * Tambah route GET
     */
    public function get(string $route, string $action): self
    {
        return $this->addRoute('GET', $route, $action);
    }

    /**
     * Tambah route POST
     */
    public function post(string $route, string $action): self
    {
        return $this->addRoute('POST', $route, $action);
    }

    /**
     * Tambah route ke collection
     */
    protected function addRoute(string $method, string $route, string $action): self
    {
        $pattern = $this->convertToRegex($route);
        $this->routes[$method][$pattern] = $action;
        return $this;
    }

    /**
     * Match URL ke route
     */
    public function match(string $url, string $method): bool
    {
        $method = strtoupper($method);

        if (! isset($this->routes[$method])) {
            return false;
        }

        foreach ($this->routes[$method] as $pattern => $action) {
            if (preg_match($pattern, $url, $matches)) {
                $this->params = $this->extractParams($matches);
                $parsed = $this->parseAction($action);
                $this->controller = $parsed['controller'];
                $this->method = $parsed['method'];
                return true;
            }
        }
        return false;
    }

    /**
     * Dispatch route
     */
    public function dispatch(string $url): void
    {
        $url = $this->cleanUrl($url);
        $method = $_SERVER['REQUEST_METHOD'];

        if ($this->match($url, $method)) {
            $this->dispatchToController($this->controller, $this->method, $this->params);
        } else {
            $this->autoRoute($url);
        }
    }

    /**
     * Auto routing: /controller/method/param1/param2
     */
    protected function autoRoute(string $url): void
    {
        $segments = explode('/', $url);
        $controller = ! empty($segments[0]) ? ucfirst($segments[0]) : 'Home';
        $method = $segments[1] ?? 'index';
        $params = array_slice($segments, 2);

        $this->dispatchToController($controller, $method, $params);
    }
}
```

**Fitur Router:**
| Method | Fungsi |
|--------|--------|
| `get()` | Mendaftarkan route GET |
| `post()` | Mendaftarkan route POST |
| `match()` | Mencocokkan URL dengan pattern route |
| `dispatch()` | Menjalankan controller yang sesuai |
| `autoRoute()` | Routing otomatis berdasarkan URL segment |

**Contoh Penggunaan:**
```php
$router->get('rooms/{id}', 'RoomController@detail');
// URL: /rooms/5 → RoomController->detail(5)
```

---

#### 📄 `core/Controller.php` - Base Controller

Controller dasar yang diextend oleh semua controller aplikasi.

```php
<?php

namespace Core;

use Core\Traits\LoadsModels;
use Core\Traits\HandlesRequest;
use Core\Traits\HandlesResponse;
use Core\Traits\ValidatesInput;
use Core\Traits\ChecksAuth;

class Controller
{
    use LoadsModels, HandlesRequest, HandlesResponse, ValidatesInput, ChecksAuth;

    protected View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    /**
     * Render view
     */
    protected function render(string $view, array $data = []): void
    {
        $this->view->render($view, $data);
    }
}
```

**Traits yang digunakan:**
| Trait | Fungsi |
|-------|--------|
| `LoadsModels` | Load model dengan `$this->loadModel('User')` |
| `HandlesRequest` | Method helper untuk request (isPost, isAjax, dll) |
| `HandlesResponse` | Response helper (redirect, json, setFlash) |
| `ValidatesInput` | Validasi input form |
| `ChecksAuth` | Cek autentikasi dan authorization |

---

#### 📄 `core/Database.php` - Database Connection

Singleton class untuk koneksi database menggunakan PDO.

```php
<?php

namespace Core;

use Core\Traits\HasConnection;
use Core\Traits\HasStatement;
use Core\Traits\HasFetch;
use Core\Traits\HasTransaction;

class Database
{
    use HasConnection, HasStatement, HasFetch, HasTransaction;

    private string $host;
    private string $user;
    private string $pass;
    private string $db_name;

    private static ?Database $instance = null;

    public function __construct()
    {
        $this->host = DB_HOST;
        $this->user = DB_USER;
        $this->pass = DB_PASS;
        $this->db_name = DB_NAME;

        $this->connect();
    }

    /**
     * Singleton pattern - one connection for all
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Prevent cloning
     */
    private function __clone() {}
}
```

**Fitur Database:**
- **Singleton Pattern** - Hanya satu instance koneksi
- **PDO** - Prepared statements untuk keamanan
- **Method Chaining** - `$db->query()->bind()->execute()`

---

#### 📄 `core/Model.php` - Base Model

Model dasar dengan CRUD operations.

```php
<? php

namespace Core;

use Core\Traits\HasCRUD;
use Core\Traits\HasQuery;
use Core\Traits\HasAggregate;
use Core\Traits\HasRawQuery;

class Model
{
    use HasCRUD, HasQuery, HasAggregate, HasRawQuery;

    protected $db;
    protected string $table = '';        // Nama tabel
    protected array $fillable = [];      // Kolom yang bisa diisi

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Filter data by fillable fields
     */
    protected function filterFillable(array $data): array
    {
        if (empty($this->fillable)) {
            return $data;
        }
        return array_intersect_key($data, array_flip($this->fillable));
    }

    /**
     * Get all records
     */
    public function all()
    {
        return $this->db->query("SELECT * FROM {$this->table}")->resultSet();
    }

    /**
     * Find by ID
     */
    public function find($id)
    {
        return $this->db->query("SELECT * FROM {$this->table} WHERE id = :id")
                        ->bind(':id', $id)
                        ->single();
    }

    /**
     * Find by any column
     */
    public function findBy($column, $value)
    {
        return $this->db->query("SELECT * FROM {$this->table} WHERE {$column} = :value")
                        ->bind(':value', $value)
                        ->single();
    }

    /**
     * Get all by column (WHERE clause)
     */
    public function where($column, $value)
    {
        return $this->db->query("SELECT * FROM {$this->table} WHERE {$column} = :value")
                        ->bind(':value', $value)
                        ->resultSet();
    }

    /**
     * Create record
     */
    public function create(array $data)
    {
        $data = $this->filterFillable($data);
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $this->db->query("INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})");
        
        foreach ($data as $key => $value) {
            $this->db->bind(":{$key}", $value);
        }
        
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    /**
     * Update record
     */
    public function update($id, array $data)
    {
        $data = $this->filterFillable($data);
        $set = implode(', ', array_map(fn($k) => "{$k} = :{$k}", array_keys($data)));
        
        $this->db->query("UPDATE {$this->table} SET {$set} WHERE id = :id");
        
        foreach ($data as $key => $value) {
            $this->db->bind(":{$key}", $value);
        }
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }

    /**
     * Delete record
     */
    public function delete($id)
    {
        return $this->db->query("DELETE FROM {$this->table} WHERE id = :id")
                        ->bind(':id', $id)
                        ->execute();
    }

    /**
     * Count records
     */
    public function count()
    {
        $result = $this->db->query("SELECT COUNT(*) as total FROM {$this->table}")->single();
        return $result->total ??  0;
    }

    /**
     * Raw query
     */
    public function raw($sql, array $params = [])
    {
        $this->db->query($sql);
        foreach ($params as $key => $value) {
            $this->db->bind($key, $value);
        }
        return $this->db->resultSet();
    }
}
```

**Method CRUD:**
| Method | SQL | Contoh |
|--------|-----|--------|
| `all()` | SELECT * | `$model->all()` |
| `find($id)` | SELECT WHERE id | `$model->find(1)` |
| `findBy($col, $val)` | SELECT WHERE col | `$model->findBy('email', 'test@mail.com')` |
| `where($col, $val)` | SELECT WHERE (array) | `$model->where('status', 'active')` |
| `create($data)` | INSERT | `$model->create(['name' => 'John'])` |
| `update($id, $data)` | UPDATE | `$model->update(1, ['name' => 'Jane'])` |
| `delete($id)` | DELETE | `$model->delete(1)` |
| `count()` | COUNT | `$model->count()` |

---

#### 📄 `core/View.php` - View Engine

Template engine dengan dukungan layout dan partial.

```php
<? php

namespace Core;

class View
{
    protected string $viewPath = '';
    protected array $data = [];
    protected ? string $layout = null;
    protected string $content = '';

    public function __construct()
    {
        $this->viewPath = dirname(__DIR__) . '/app/Views/';
    }

    /**
     * Render view file
     */
    public function render($view, $data = [])
    {
        $this->data = array_merge($this->data, $data);
        
        $filePath = $this->viewPath .  str_replace('.', '/', $view) . '.php';
        
        if (! file_exists($filePath)) {
            throw new \Exception("View file not found: {$filePath}");
        }
        
        extract($this->data);
        ob_start();
        include $filePath;
        $content = ob_get_clean();

        // Jika ada layout, wrap content dengan layout
        if ($this->layout) {
            $this->content = $content;
            $layoutPath = $this->viewPath .  'layouts/' . $this->layout . '.php';
            
            if (file_exists($layoutPath)) {
                ob_start();
                include $layoutPath;
                $content = ob_get_clean();
            }
            $this->layout = null;
        }

        echo $content;
    }

    /**
     * Set layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
        return $this;
    }

    /**
     * Include partial/component
     */
    public function partial($view, $data = [])
    {
        $filePath = $this->viewPath .  'partials/' . str_replace('.', '/', $view) .  '.php';
        extract(array_merge($this->data, $data));
        include $filePath;
    }

    /**
     * Escape HTML (XSS Protection)
     */
    public function e($text)
    {
        return htmlspecialchars($text ??  '', ENT_QUOTES, 'UTF-8');
    }

    /**
     * Generate URL
     */
    public function url($path = '')
    {
        return BASE_URL . ltrim($path, '/');
    }

    /**
     * Format currency (Rupiah)
     */
    public function currency($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    /**
     * Format date
     */
    public function date($date, $format = 'd M Y')
    {
        return date($format, strtotime($date));
    }

    /**
     * Get flash message
     */
    public function flash($type = null)
    {
        if (! isset($_SESSION['flash'])) {
            return null;
        }
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $type ? ($flash['type'] === $type ? $flash['message'] : null) : $flash;
    }

    /**
     * Check if user is logged in
     */
    public function auth()
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Get current user
     */
    public function user()
    {
        return $_SESSION['user'] ?? null;
    }
}
```

**Helper Methods:**
| Method | Fungsi | Contoh |
|--------|--------|--------|
| `e()` | Escape HTML | `<? = $this->e($name) ?>` |
| `url()` | Generate URL | `<? = $this->url('rooms') ?>` |
| `currency()` | Format Rupiah | `<?= $this->currency(500000) ?>` → Rp 500.000 |
| `date()` | Format tanggal | `<?= $this->date('2024-01-15') ?>` → 15 Jan 2024 |
| `flash()` | Flash message | `<?= $this->flash('success') ?>` |
| `auth()` | Cek login | `<?php if($this->auth()): ?>` |
| `user()` | Get user | `<?= $this->user()->name ?>` |

---

### 2. Controllers

#### 📄 `app/Controllers/AuthController.php`

Controller untuk autentikasi (login, register, logout, profile).

```php
<?php

namespace App\Controllers;

use Core\Controller;
use App\Controllers\Traits\HandlesAuth;
use App\Controllers\Traits\HandlesOldInput;
use App\Controllers\Traits\ValidatesRequest;

class AuthController extends Controller
{
    use HandlesAuth, HandlesOldInput, ValidatesRequest;

    /**
     * Tampilkan halaman login
     */
    public function login()
    {
        if ($this->redirectIfAuthenticated()) return;
        $this->render('auth/login', ['title' => 'Login - ' . APP_NAME]);
    }

    /**
     * Proses login
     */
    public function doLogin()
    {
        if (! $this->isPost()) {
            return $this->redirect('auth/login');
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->setFlash('error', 'Email dan password harus diisi');
            return $this->redirect('auth/login');
        }

        $userModel = $this->loadModel('User');

        if (!$user = $this->attemptLogin($userModel, $email, $password)) {
            return $this->redirect('auth/login');
        }

        $this->setUserSession($user);
        $this->setFlash('success', "Selamat datang, {$user->name}!");
        $this->redirectByRole($user);
    }

    /**
     * Proses register
     */
    public function doRegister()
    {
        if (!$this->isPost()) {
            return $this->redirect('auth/register');
        }

        $data = $this->getRegistrationData();
        $this->setOldInput($data);

        if (!$this->validateRegistration($data)) {
            return $this->redirect('auth/register');
        }

        $userModel = $this->loadModel('User');

        if ($userModel->emailExists($data['email'])) {
            $this->setFlash('error', 'Email sudah terdaftar');
            return $this->redirect('auth/register');
        }

        if ($userModel->register($data)) {
            $this->clearOldInput();
            $this->setFlash('success', 'Registrasi berhasil! Silakan login');
            return $this->redirect('auth/login');
        }

        $this->setFlash('error', 'Registrasi gagal.  Silakan coba lagi');
        $this->redirect('auth/register');
    }

    /**
     * Logout
     */
    public function logout()
    {
        $this->destroySession();
        $this->setFlash('success', 'Anda telah logout');
        $this->redirect('auth/login');
    }

    /**
     * Tampilkan profile
     */
    public function profile()
    {
        $this->requireLogin();
        $user = $this->loadModel('User')->find($_SESSION['user_id']);
        $this->view->setLayout('main')->render('home/profile', [
            'title' => 'Profile - ' .  APP_NAME,
            'user' => $user
        ]);
    }
}
```

---

#### 📄 `app/Controllers/RoomController.php`

Controller untuk manajemen kamar (public). 

```php
<?php

namespace App\Controllers;

use Core\Controller;
use App\Controllers\Traits\HandlesRoom;
use App\Controllers\Traits\HandlesRoomFilter;
use App\Controllers\Traits\FormatsRoomData;
use App\Controllers\Traits\ValidatesBookingDates;

class RoomController extends Controller
{
    use HandlesRoom, HandlesRoomFilter, FormatsRoomData, ValidatesBookingDates;

    /**
     * Tampilkan semua kamar dengan filter
     */
    public function index()
    {
        $roomModel = $this->loadModel('Room');
        $params = $this->getFilterParams();
        $rooms = $this->getFilteredRooms($roomModel, $params);

        $this->view->setLayout('main')->render('rooms/index', [
            'title' => 'Kamar Tersedia - ' . APP_NAME,
            'rooms' => $rooms,
            'selectedType' => $params['type'],
            'minPrice' => $params['min_price'],
            'maxPrice' => $params['max_price'],
            'search' => $params['search'] ??  '',
            'checkIn' => $params['check_in'] ?? '',
            'checkOut' => $params['check_out'] ?? ''
        ]);
    }

    /**
     * Tampilkan detail kamar
     */
    public function detail($id)
    {
        $roomModel = $this->loadModel('Room');
        $room = $this->findRoomOrFail($roomModel, $id);

        if (!$room) return;

        $this->view->setLayout('main')->render('rooms/detail', [
            'title' => 'Kamar ' . $room->room_number .  ' - ' . APP_NAME,
            'room' => $room,
            'similarRooms' => $this->getSimilarRooms($roomModel, $room)
        ]);
    }

    /**
     * Check availability (AJAX)
     */
    public function checkAvailability($id)
    {
        $params = $this->getFilterParams();

        if (!$this->validateDatesOrJsonFail($params['check_in'], $params['check_out'])) {
            return;
        }

        $roomModel = $this->loadModel('Room');
        $room = $this->findRoomOrJsonFail($roomModel, $id);

        if (!$room) return;

        $isAvailable = $roomModel->isAvailableForDates($id, $params['check_in'], $params['check_out']);
        $nights = $this->loadModel('Booking')->calculateNights($params['check_in'], $params['check_out']);

        $this->json($this->buildRoomAvailabilityResponse($room, $isAvailable, $nights));
    }
}
```

---

#### 📄 `app/Controllers/BookingController.php`

Controller untuk pemesanan kamar. 

```php
<?php

namespace App\Controllers;

use Core\Controller;
use App\Controllers\Traits\HandlesBooking;
use App\Controllers\Traits\HandlesRoom;
use App\Controllers\Traits\ValidatesBookingDates;

class BookingController extends Controller
{
    use HandlesBooking, HandlesRoom, ValidatesBookingDates;

    /**
     * Tampilkan form booking
     */
    public function create($id)
    {
        $this->requireLogin();

        $roomModel = $this->loadModel('Room');
        $room = $this->findRoomOrFail($roomModel, $id);
        
        if (!$room || !$this->ensureRoomAvailable($room)) {
            return;
        }

        $this->view->setLayout('main')->render('booking/create', [
            'title' => 'Booking ' . $room->room_number .  ' - ' . APP_NAME,
            'room' => $room
        ]);
    }

    /**
     * Proses booking
     */
    public function store()
    {
        $this->requireLogin();

        if (!$this->isPost()) {
            return $this->redirect('rooms');
        }

        $input = $this->getBookingInput();
        $redirectTo = 'booking/create/' . $input['room_id'];

        // Validasi input & tanggal
        if (!$this->validateBookingInput($input, $redirectTo)) return;
        if (!$this->validateBookingDates($input['check_in'], $input['check_out'], $redirectTo)) return;

        // Validasi room
        $roomModel = $this->loadModel('Room');
        $room = $this->findRoomOrFail($roomModel, $input['room_id']);
        if (! $room) return;

        // Cek ketersediaan
        if (!$this->ensureRoomAvailableForDates($roomModel, $input['room_id'], $input['check_in'], $input['check_out'], $redirectTo)) {
            return;
        }

        // Proses booking
        $this->processBooking($input, $room);
    }

    /**
     * Tampilkan booking user yang login
     */
    public function myBookings()
    {
        $this->requireLogin();

        $bookings = $this->loadModel('Booking')->getByUser($_SESSION['user_id']);

        $this->view->setLayout('main')->render('home/my-bookings', [
            'title' => 'Booking Saya - ' . APP_NAME,
            'bookings' => $bookings
        ]);
    }

    /**
     * Cancel booking
     */
    public function cancel($id)
    {
        $this->requireLogin();

        $bookingModel = $this->loadModel('Booking');
        $booking = $this->findBookingOrFail($bookingModel, $id);
        
        if (!$booking) return;

        if (!$this->validateBookingOwnership($booking, false)) {
            $this->setFlash('error', 'Anda tidak memiliki akses ke booking ini');
            return $this->redirect('my-bookings');
        }

        if (! $this->isCancellable($booking)) {
            $this->setFlash('error', 'Booking tidak dapat dibatalkan');
            return $this->redirect('my-bookings');
        }

        $this->setFlash(
            $bookingModel->cancel($id) ? 'success' : 'error',
            $bookingModel->cancel($id) ?  'Booking berhasil dibatalkan' : 'Gagal membatalkan booking'
        );

        $this->redirect('my-bookings');
    }
}
```

---

### 3. Models

#### 📄 `app/Models/User.php`

Model untuk tabel users.

```php
<?php

namespace App\Models;

use Core\Model;
use App\Models\Traits\HasPassword;
use App\Models\Traits\HasImage;
use App\Models\Traits\HasRole;
use App\Models\Traits\Searchable;

class User extends Model
{
    use HasPassword, HasImage, HasRole, Searchable;

    protected string $table = 'users';
    protected array $fillable = ['name', 'email', 'password_hash', 'phone', 'profile_image', 'role'];

    // Trait configurations
    protected string $imageColumn = 'profile_image';
    protected string $uploadDir = 'uploads/profiles';
    protected array $searchable = ['name', 'email', 'phone'];

    /**
     * Register new user
     */
    public function register(array $data): int|false
    {
        if (isset($data['password'])) {
            $data['password_hash'] = $this->hashPassword($data['password']);
            unset($data['password']);
        }
        $data['role'] = $data['role'] ?? 'guest';
        return $this->create($data);
    }

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ? object
    {
        return $this->findBy('email', $email);
    }

    /**
     * Check if email exists
     */
    public function emailExists(string $email): bool
    {
        return $this->findByEmail($email) !== null;
    }
}
```

**Properties:**
| Property | Nilai | Keterangan |
|----------|-------|------------|
| `$table` | `users` | Nama tabel di database |
| `$fillable` | `['name', 'email', ...]` | Kolom yang bisa diisi |
| `$searchable` | `['name', 'email', 'phone']` | Kolom untuk search |

---

#### 📄 `app/Models/Room.php`

Model untuk tabel rooms.

```php
<?php

namespace App\Models;

use Core\Model;
use App\Models\Traits\HasImage;
use App\Models\Traits\Searchable;
use App\Models\Traits\Filterable;

class Room extends Model
{
    use HasImage, Searchable, Filterable;

    protected string $table = 'rooms';
    protected array $fillable = ['room_number', 'room_type', 'price_per_night', 'description', 'image', 'is_available'];

    /**
     * Get available rooms
     */
    public function getAvailable(): array
    {
        return $this->where('is_available', 1);
    }

    /**
     * Get rooms by type
     */
    public function getByType(string $type): array
    {
        return $this->where('room_type', $type);
    }

    /**
     * Toggle availability
     */
    public function toggleAvailability(int $id): bool
    {
        $room = $this->find($id);
        if (!$room) return false;
        $newStatus = $room->is_available ? 0 : 1;
        return $this->update($id, ['is_available' => $newStatus]);
    }

    /**
     * Check if room available for dates
     */
    public function isAvailableForDates(int $roomId, string $checkIn, string $checkOut): bool
    {
        $sql = "SELECT COUNT(*) as total FROM bookings 
                WHERE room_id = :room_id 
                AND status NOT IN ('cancelled', 'checked_out')
                AND (
                    (check_in_date <= :check_in1 AND check_out_date > :check_in2)
                    OR (check_in_date < :check_out1 AND check_out_date >= :check_out2)
                    OR (check_in_date >= :check_in3 AND check_out_date <= :check_out3)
                )";
        
        $result = $this->db->query($sql)
                           ->bind(':room_id', $roomId)
                           ->bind(':check_in1', $checkIn)
                           ->bind(':check_in2', $checkIn)
                           ->bind(':check_out1', $checkOut)
                           ->bind(':check_out2', $checkOut)
                           ->bind(':check_in3', $checkIn)
                           ->bind(':check_out3', $checkOut)
                           ->single();
        
        return ($result->total ??  0) == 0;
    }

    /**
     * Get room statistics
     */
    public function getStats(): array
    {
        return [
            'total' => $this->count(),
            'available' => $this->countWhere('is_available', 1),
            'unavailable' => $this->countWhere('is_available', 0),
            'standard' => $this->countWhere('room_type', 'standard'),
            'deluxe' => $this->countWhere('room_type', 'deluxe'),
            'suite' => $this->countWhere('room_type', 'suite')
        ];
    }
}
```

---

#### 📄 `app/Models/Booking.php`

Model untuk tabel bookings.

```php
<?php

namespace App\Models;

use Core\Model;
use App\Models\Traits\HasStatus;
use App\Models\Traits\Filterable;

class Booking extends Model
{
    use HasStatus, Filterable;

    protected string $table = 'bookings';
    protected array $fillable = ['user_id', 'room_id', 'check_in_date', 'check_out_date', 'total_price', 'status'];

    // Status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_CHECKED_IN = 'checked_in';
    public const STATUS_CHECKED_OUT = 'checked_out';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Get bookings by user
     */
    public function getByUser(int $userId): array
    {
        return $this->where('user_id', $userId);
    }

    /**
     * Get booking with details (JOIN)
     */
    public function getWithDetails(int $id): ? object
    {
        $sql = "SELECT b.*, u.name as guest_name, u.email as guest_email, u.phone as guest_phone,
                       r.room_number, r.room_type, r. price_per_night, r. description as room_description
                FROM {$this->table} b
                JOIN users u ON b.user_id = u.id
                JOIN rooms r ON b.room_id = r.id
                WHERE b.id = :id";
        
        return $this->db->query($sql)->bind(':id', $id)->single();
    }

    /**
     * Confirm booking
     */
    public function confirm(int $id): bool
    {
        return $this->updateStatus($id, self::STATUS_CONFIRMED);
    }

    /**
     * Check in
     */
    public function checkIn(int $id): bool
    {
        return $this->updateStatus($id, self::STATUS_CHECKED_IN);
    }

    /**
     * Check out
     */
    public function checkOut(int $id): bool
    {
        return $this->updateStatus($id, self::STATUS_CHECKED_OUT);
    }

    /**
     * Cancel booking
     */
    public function cancel(int $id): bool
    {
        return $this->updateStatus($id, self::STATUS_CANCELLED);
    }

    /**
     * Calculate total price
     */
    public function calculateTotalPrice(float $pricePerNight, string $checkIn, string $checkOut): float
    {
        $nights = $this->calculateNights($checkIn, $checkOut);
        return $pricePerNight * $nights;
    }

    /**
     * Calculate nights
     */
    public function calculateNights(string $checkIn, string $checkOut): int
    {
        $checkInDate = new \DateTime($checkIn);
        $checkOutDate = new \DateTime($checkOut);
        return $checkOutDate->diff($checkInDate)->days;
    }

    /**
     * Get total revenue
     */
    public function getTotalRevenue(): float
    {
        $sql = "SELECT COALESCE(SUM(total_price), 0) as total FROM {$this->table} 
                WHERE status IN ('confirmed', 'checked_in', 'checked_out')";
        $result = $this->db->query($sql)->single();
        return (float) ($result->total ?? 0);
    }

    /**
     * Get booking statistics
     */
    public function getStats(): array
    {
        return [
            'total' => $this->count(),
            'pending' => $this->countByStatus(self::STATUS_PENDING),
            'confirmed' => $this->countByStatus(self::STATUS_CONFIRMED),
            'checked_in' => $this->countByStatus(self::STATUS_CHECKED_IN),
            'checked_out' => $this->countByStatus(self::STATUS_CHECKED_OUT),
            'cancelled' => $this->countByStatus(self::STATUS_CANCELLED),
            'today_check_ins' => count($this->getTodayCheckIns()),
            'today_check_outs' => count($this->getTodayCheckOuts()),
            'total_revenue' => $this->getTotalRevenue()
        ];
    }
}
```

**Status Flow:**
```
pending → confirmed → checked_in → checked_out
    ↓         ↓            ↓
cancelled  cancelled   cancelled
```

---

### 4. Views

#### Struktur Views

```
app/Views/
├── admin/           # Admin panel views
│   ├── dashboard. php
│   ├── users/
│   ├── rooms/
│   └── bookings/
├── auth/            # Authentication views
│   ├── login.php
│   ├── register.php
│   └── forgot-password.php
├── booking/         # Booking views
│   ├── create.php
│   └── detail.php
├── home/            # Home & profile views
│   ├── index.php
│   ├── profile.php
│   └── my-bookings.php
├── layouts/         # Layout templates
│   ├── main.php     # Main layout untuk user
│   └── admin.php    # Layout untuk admin
├── partials/        # Reusable components
│   ├── header. php
│   ├── footer.php
│   ├── navbar.php
│   └── flash.php
└── rooms/           # Room views
    ├── index.php
    ├── detail.php
    └── search.php
```

#### Contoh Penggunaan View dengan Layout

```php
// Di Controller
$this->view->setLayout('main')->render('rooms/index', [
    'title' => 'Kamar Tersedia',
    'rooms' => $rooms
]);
```

```php
// Di layouts/main.php
<! DOCTYPE html>
<html>
<head>
    <title><? = $this->e($title) ?></title>
</head>
<body>
    <? php $this->partial('navbar') ?>
    
    <main>
        <?= $this->content() ? > <!-- Content dari view -->
    </main>
    
    <?php $this->partial('footer') ?>
</body>
</html>
```

---

### 5. Routes

#### 📄 `routes/web.php`

```php
<?php

use Core\Router;

$router = new Router();

// ============================================
// PUBLIC ROUTES
// ============================================

// Home
$router->get('', 'HomeController@index');
$router->get('home', 'HomeController@index');

// Auth - Guest
$router->get('login', 'AuthController@login');
$router->post('login', 'AuthController@doLogin');
$router->get('register', 'AuthController@register');
$router->post('register', 'AuthController@doRegister');
$router->post('logout', 'AuthController@logout');
$router->get('forgot-password', 'AuthController@forgotPassword');
$router->post('forgot-password', 'AuthController@doForgotPassword');

// Auth - Profile (requires login)
$router->get('profile', 'AuthController@profile');
$router->post('profile/update', 'AuthController@updateProfile');
$router->post('profile/password', 'AuthController@updatePassword');

// ============================================
// ROOMS (Public)
// ============================================

$router->get('rooms', 'RoomController@index');
$router->get('rooms/search', 'RoomController@search');
$router->get('rooms/types', 'RoomController@types');
$router->get('rooms/filter', 'RoomController@filterByType');
$router->get('rooms/info/{id}', 'RoomController@getInfo');
$router->get('rooms/availability/{id}', 'RoomController@checkAvailability');
$router->get('rooms/{id}', 'RoomController@detail');

// ============================================
// BOOKING (Requires Login)
// ============================================

$router->get('my-bookings', 'BookingController@myBookings');
$router->post('booking/check-availability', 'BookingController@checkAvailability');
$router->get('booking/create/{id}', 'BookingController@create');
$router->post('booking/store', 'BookingController@store');
$router->get('booking/detail/{id}', 'BookingController@detail');
$router->post('booking/cancel/{id}', 'BookingController@cancel');
$router->get('booking/invoice/{id}', 'BookingController@invoice');

// ============================================
// ADMIN ROUTES
// ============================================

// Dashboard
$router->get('admin', 'Admin\\DashboardController@index');
$router->get('admin/dashboard', 'Admin\\DashboardController@index');
$router->get('admin/analytics', 'Admin\\DashboardController@analytics');
$router->get('admin/reports', 'Admin\\DashboardController@reports');

// Admin - Users Management
$router->get('admin/users', 'Admin\\AdminUserController@index');
$router->get('admin/users/create', 'Admin\\AdminUserController@create');
$router->post('admin/users/store', 'Admin\\AdminUserController@store');
$router->get('admin/users/{id}/edit', 'Admin\\AdminUserController@edit');
$router->post('admin/users/{id}/update', 'Admin\\AdminUserController@update');
$router->post('admin/users/{id}/delete', 'Admin\\AdminUserController@delete');

// Admin - Rooms Management
$router->get('admin/rooms', 'Admin\\AdminRoomController@index');
$router->get('admin/rooms/create', 'Admin\\AdminRoomController@create');
$router->post('admin/rooms/store', 'Admin\\AdminRoomController@store');
$router->get('admin/rooms/{id}/edit', 'Admin\\AdminRoomController@edit');
$router->post('admin/rooms/{id}/update', 'Admin\\AdminRoomController@update');
$router->post('admin/rooms/{id}/delete', 'Admin\\AdminRoomController@delete');
$router->post('admin/rooms/{id}/toggle', 'Admin\\AdminRoomController@toggleAvailability');

// Admin - Bookings Management
$router->get('admin/bookings', 'Admin\\AdminBookingController@index');
$router->post('admin/bookings/{id}/confirm', 'Admin\\AdminBookingController@confirm');
$router->post('admin/bookings/{id}/checkin', 'Admin\\AdminBookingController@checkIn');
$router->post('admin/bookings/{id}/checkout', 'Admin\\AdminBookingController@checkOut');
$router->post('admin/bookings/{id}/cancel', 'Admin\\AdminBookingController@cancel');

return $router;
```

**Route Parameters:**
- `{id}` - Parameter dinamis yang akan diteruskan ke method controller
- Contoh: `rooms/{id}` dengan URL `/rooms/5` → `RoomController@detail(5)`

---

## 🗄 Database Schema

### Tabel `users`

```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    profile_image VARCHAR(255),
    role ENUM('guest', 'admin') DEFAULT 'guest',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Tabel `rooms`

```sql
CREATE TABLE rooms (
    id INT PRIMARY KEY AUTO_INCREMENT,
    room_number VARCHAR(10) UNIQUE NOT NULL,
    room_type ENUM('standard', 'deluxe', 'suite') NOT NULL,
    price_per_night DECIMAL(10,2) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    is_available TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Tabel `bookings`

```sql
CREATE TABLE bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    check_in_date DATE NOT NULL,
    check_out_date DATE NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
);
```

### Tabel `password_reset_requests`

```sql
CREATE TABLE password_reset_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    email VARCHAR(100) NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### ERD (Entity Relationship Diagram)

```
┌──────────────┐       ┌──────────────┐       ┌──────────────┐
│    users     │       │   bookings   │       │    rooms     │
├──────────────┤       ├──────────────┤       ├──────────────┤
│ id (PK)      │───┐   │ id (PK)      │   ┌───│ id (PK)      │
│ name         │   │   │ user_id (FK) │───┘   │ room_number  │
│ email        │   └───│ room_id (FK) │       │ room_type    │
│ password_hash│       │ check_in_date│       │ price/night  │
│ phone        │       │ check_out    │       │ description  │
│ profile_image│       │ total_price  │       │ image        │
│ role         │       │ status       │       │ is_available │
│ created_at   │       │ created_at   │       │ created_at   │
└──────────────┘       └──────────────┘       └──────────────┘
```

---

## 🔌 API Endpoints

### Public Endpoints

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/rooms` | List semua kamar |
| GET | `/rooms/{id}` | Detail kamar |
| GET | `/rooms/search` | Search kamar |
| GET | `/rooms/filter? type=deluxe` | Filter kamar by type |
| GET | `/rooms/availability/{id}? check_in=... &check_out=...` | Cek availability (AJAX) |

### Auth Endpoints

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| POST | `/login` | Login |
| POST | `/register` | Register |
| POST | `/logout` | Logout |
| POST | `/forgot-password` | Request reset password |

### Protected Endpoints (Requires Login)

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/profile` | View profile |
| POST | `/profile/update` | Update profile |
| POST | `/profile/password` | Update password |
| GET | `/my-bookings` | List user bookings |
| GET | `/booking/create/{room_id}` | Form booking |
| POST | `/booking/store` | Create booking |
| POST | `/booking/cancel/{id}` | Cancel booking |

### Admin Endpoints (Requires Admin Role)

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/admin/dashboard` | Admin dashboard |
| GET | `/admin/users` | List users |
| POST | `/admin/users/store` | Create user |
| POST | `/admin/users/{id}/update` | Update user |
| POST | `/admin/users/{id}/delete` | Delete user |
| GET | `/admin/rooms` | List rooms |
| POST | `/admin/rooms/store` | Create room |
| POST | `/admin/rooms/{id}/toggle` | Toggle availability |
| GET | `/admin/bookings` | List bookings |
| POST | `/admin/bookings/{id}/confirm` | Confirm booking |
| POST | `/admin/bookings/{id}/checkin` | Check-in |
| POST | `/admin/bookings/{id}/checkout` | Check-out |

---

## 🔐 Keamanan

### Implementasi Keamanan

| Fitur | Implementasi |
|-------|--------------|
| **SQL Injection** | PDO Prepared Statements |
| **XSS** | `htmlspecialchars()` via `$this->e()` |
| **CSRF** | Form token validation |
| **Password** | `password_hash()` dengan BCRYPT |
| **Session** | Session fixation protection |
| **File Upload** | Validasi extension & size |
| **Access Control** | Role-based authorization |

---

## 👥 Kontributor

<table>
  <tr>
    <td align="center">
      <a href="https://github.com/Rainzy21">
        <img src="https://github.com/Rainzy21.png" width="100px;" alt=""/><br />
        <sub><b>Rainzy21</b></sub>
      </a>
    </td>
  </tr>
</table>

---

## 📄 Lisensi

Project ini dibuat untuk keperluan akademik - Tugas Pemrograman Web. 

---

<div align="center">

**⭐ Jangan lupa berikan star jika project ini membantu!  ⭐**

Made with ❤️ for Pemrograman Web Course

</div>