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
- [Database Schema](#-database-schema)
- [Routes](#-routes)
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

| Fitur                  | Deskripsi                                              |
| ---------------------- | ------------------------------------------------------ |
| 🔐 **Autentikasi**     | Login, Register, Logout dengan Session                 |
| 🔒 **Password Hash**   | Enkripsi password menggunakan bcrypt                   |
| 👥 **Multi Role**      | Guest (user biasa) dan Admin dengan akses berbeda      |
| 📝 **CRUD Lengkap**    | Create, Read, Update, Delete untuk semua data          |
| 📤 **Upload File**     | Upload gambar profil user dan gambar kamar             |
| 🔗 **Relasi Data**     | 3 tabel yang saling terhubung (users, rooms, bookings) |
| 📊 **Admin Dashboard** | Panel admin dengan statistik, analytics, dan reports   |
| 📈 **Analytics**       | Grafik revenue, booking trends, room popularity        |
| 📋 **Reports**         | Laporan revenue, occupancy rate, export CSV            |
| 📱 **Responsive**      | Tampilan responsif untuk semua ukuran layar            |

---

## 🛠 Teknologi

| Teknologi    | Versi | Fungsi                      |
| ------------ | ----- | --------------------------- |
| PHP          | 8.0+  | Backend server-side         |
| MySQL        | 5.7+  | Database                    |
| PDO          | -     | Database connection         |
| Tailwind CSS | 3.4+  | Styling & UI                |
| Chart.js     | 4.0+  | Grafik dan visualisasi data |
| Node.js      | 18+   | Build tools untuk Tailwind  |
| NPM          | 9+    | Package manager             |

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
│                    (app/Controllers/*.php)                       │
│              Menerima request, memproses logic,                  │
│              berkomunikasi dengan Model dan View                 │
└───────────────┬──────────────────────────────────┬───────────────┘
                │                                  │
                ▼                                  ▼
┌───────────────────────────┐      ┌───────────────────────────────┐
│           MODEL           │      │             VIEW              │
│    (app/Models/*.php)     │      │      (app/Views/*.php)        │
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
│   │   ├── 📄 AuthController.php    # Login, Register, Profile
│   │   ├── 📄 HomeController.php    # Homepage
│   │   ├── 📄 RoomController.php    # Public room listing
│   │   ├── 📄 BookingController.php # User booking
│   │   └── 📂 Admin/                # Admin controllers
│   │       ├── 📄 DashboardController.php
│   │       ├── 📄 RoomController.php
│   │       ├── 📄 BookingController.php
│   │       └── 📄 UserController.php
│   │
│   ├── 📂 Models/                   # Model classes
│   │   ├── 📄 User.php
│   │   ├── 📄 Room.php
│   │   └── 📄 Booking.php
│   │
│   └── 📂 Views/                    # View templates
│       ├── 📂 layouts/
│       │   ├── 📄 app.php           # Layout untuk user
│       │   └── 📄 admin.php         # Layout untuk admin
│       ├── 📂 auth/
│       │   ├── 📄 login.php
│       │   ├── 📄 register.php
│       │   └── 📄 profile.php
│       ├── 📂 home/
│       │   └── 📄 index.php
│       ├── 📂 rooms/
│       │   ├── 📄 index.php
│       │   └── 📄 detail.php
│       ├── 📂 bookings/
│       │   ├── 📄 my-bookings.php
│       │   ├── 📄 create.php
│       │   ├── 📄 detail.php
│       │   └── 📄 invoice.php
│       ├── 📂 admin/
│       │   ├── 📂 dashboard/
│       │   │   ├── 📄 index.php
│       │   │   ├── 📄 analytics.php
│       │   │   ├── 📄 reports.php
│       │   │   ├── 📄 activity-log.php
│       │   │   └── 📄 settings.php
│       │   ├── 📂 rooms/
│       │   │   ├── 📄 index.php
│       │   │   ├── 📄 form.php
│       │   │   └── 📄 detail.php
│       │   ├── 📂 bookings/
│       │   │   ├── 📄 index.php
│       │   │   ├── 📄 detail.php
│       │   │   ├── 📄 create.php
│       │   │   ├── 📄 today-checkins.php
│       │   │   ├── 📄 today-checkouts.php
│       │   │   └── 📄 invoice.php
│       │   └── 📂 users/
│       │       ├── 📄 index.php
│       │       ├── 📄 form.php
│       │       └── 📄 detail.php
│       ├── 📂 partials/
│       │   ├── 📄 header.php
│       │   ├── 📄 footer.php
│       │   ├── 📄 navbar.php
│       │   ├── 📄 sidebar.php
│       │   └── 📄 flash-message.php
│       └── 📂 errors/
│           ├── 📄 404.php
│           └── 📄 500.php
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
│   ├── 📄 View.php
│   ├── 📄 Router.php
│   └── 📄 Middleware.php
│
├── 📂 public/                       # Entry point & assets publik
│   ├── 📄 index.php
│   ├── 📄 .htaccess
│   └── 📂 assets/
│       ├── 📂 css/
│       │   └── 📄 output.css
│       ├── 📂 js/
│       │   └── 📄 script.js
│       └── 📂 images/
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
│       └── 📂 rooms/
│
├── 📂 sql/                          # Database schema
│   └── 📄 schema.sql
│
├── 📄 .htaccess
├── 📄 .gitignore
├── 📄 package.json
├── 📄 tailwind.config.js
└── 📄 README.md
```

---

## 📚 Penjelasan Detail Folder & File

### 1. `app/Controllers/`

#### Public Controllers

| File                    | Deskripsi           | Methods                                                                                                                                  |
| ----------------------- | ------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- |
| `AuthController.php`    | Autentikasi user    | `login()`, `doLogin()`, `register()`, `doRegister()`, `logout()`, `profile()`, `updateProfile()`, `updatePassword()`, `forgotPassword()` |
| `HomeController.php`    | Landing page        | `index()`                                                                                                                                |
| `RoomController.php`    | Daftar kamar public | `index()`, `detail()`, `search()`, `types()`, `filterByType()`, `checkAvailability()`, `getInfo()`                                       |
| `BookingController.php` | Booking oleh user   | `myBookings()`, `create()`, `store()`, `detail()`, `cancel()`, `invoice()`, `checkAvailability()`                                        |

#### Admin Controllers (`app/Controllers/Admin/`)

| File                      | Deskripsi             | Methods                                                                                                                                                                                        |
| ------------------------- | --------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `DashboardController.php` | Dashboard & statistik | `index()`, `analytics()`, `reports()`, `exportReport()`, `settings()`, `activityLog()`                                                                                                         |
| `UserController.php`      | CRUD user             | `index()`, `detail()`, `create()`, `store()`, `edit()`, `update()`, `delete()`, `toggleRole()`, `resetPassword()`, `bulkAction()`, `export()`, `stats()`                                       |
| `RoomController.php`      | CRUD kamar            | `index()`, `detail()`, `create()`, `store()`, `edit()`, `update()`, `delete()`, `toggleAvailability()`, `bulkUpdate()`, `checkAvailability()`, `stats()`                                       |
| `BookingController.php`   | Kelola booking        | `index()`, `detail()`, `create()`, `store()`, `updateStatus()`, `confirm()`, `checkIn()`, `checkOut()`, `cancel()`, `delete()`, `todayCheckIns()`, `todayCheckOuts()`, `invoice()`, `export()` |

---

### 2. `app/Models/`

| File          | Tabel      | Methods Utama                                                                                                                                          |
| ------------- | ---------- | ------------------------------------------------------------------------------------------------------------------------------------------------------ |
| `User.php`    | `users`    | `findByEmail()`, `getGuests()`, `getAdmins()`, `hashPassword()`, `verifyPassword()`                                                                    |
| `Room.php`    | `rooms`    | `getAvailable()`, `getStandard()`, `getDeluxe()`, `getSuite()`, `findByRoomNumber()`, `isAvailableForDates()`, `setAvailability()`                     |
| `Booking.php` | `bookings` | `getByUser()`, `getByStatus()`, `getAllWithDetails()`, `getPending()`, `getConfirmed()`, `getTodayCheckIns()`, `getTodayCheckOuts()`, `updateStatus()` |

---

### 3. `core/`

| File             | Deskripsi                                                |
| ---------------- | -------------------------------------------------------- |
| `App.php`        | Bootstrap aplikasi - memuat config, routes, dan dispatch |
| `Controller.php` | Base controller dengan helper methods                    |
| `Database.php`   | Koneksi database dengan Singleton pattern                |
| `Model.php`      | Base model dengan CRUD methods                           |
| `View.php`       | Render view dengan layout support                        |
| `Router.php`     | URL routing dengan parameter support                     |
| `Middleware.php` | Autentikasi dan proteksi halaman                         |

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
│     password        │  1:N  │     check_in_date   │  N:1  │     price_per_night │
│     phone           │       │     check_out_date  │       │     description     │
│     profile_image   │       │     total_price     │       │     image           │
│     role            │       │     status          │       │     is_available    │
│     created_at      │       │     created_at      │       │     created_at      │
│     updated_at      │       │     updated_at      │       │     updated_at      │
└─────────────────────┘       └─────────────────────┘       └─────────────────────┘
```

### Tabel Users

| Kolom           | Tipe                   | Constraint                  | Deskripsi                  |
| --------------- | ---------------------- | --------------------------- | -------------------------- |
| `id`            | INT                    | PK, AUTO_INCREMENT          | ID unik user               |
| `name`          | VARCHAR(100)           | NOT NULL                    | Nama lengkap               |
| `email`         | VARCHAR(100)           | UNIQUE, NOT NULL            | Email (untuk login)        |
| `password`      | VARCHAR(255)           | NOT NULL                    | Password ter-hash (bcrypt) |
| `phone`         | VARCHAR(20)            | NULL                        | Nomor telepon              |
| `profile_image` | VARCHAR(255)           | NULL                        | Path foto profil           |
| `role`          | ENUM('guest', 'admin') | DEFAULT 'guest'             | Role user                  |
| `created_at`    | TIMESTAMP              | DEFAULT CURRENT_TIMESTAMP   | Waktu dibuat               |
| `updated_at`    | TIMESTAMP              | ON UPDATE CURRENT_TIMESTAMP | Waktu diupdate             |

### Tabel Rooms

| Kolom             | Tipe                                | Constraint                  | Deskripsi           |
| ----------------- | ----------------------------------- | --------------------------- | ------------------- |
| `id`              | INT                                 | PK, AUTO_INCREMENT          | ID unik kamar       |
| `room_number`     | VARCHAR(10)                         | UNIQUE, NOT NULL            | Nomor kamar         |
| `room_type`       | ENUM('standard', 'deluxe', 'suite') | NOT NULL                    | Tipe kamar          |
| `price_per_night` | DECIMAL(10,2)                       | NOT NULL                    | Harga per malam     |
| `description`     | TEXT                                | NULL                        | Deskripsi kamar     |
| `image`           | VARCHAR(255)                        | NULL                        | Path gambar kamar   |
| `is_available`    | BOOLEAN                             | DEFAULT TRUE                | Status ketersediaan |
| `created_at`      | TIMESTAMP                           | DEFAULT CURRENT_TIMESTAMP   | Waktu dibuat        |
| `updated_at`      | TIMESTAMP                           | ON UPDATE CURRENT_TIMESTAMP | Waktu diupdate      |

### Tabel Bookings

| Kolom            | Tipe          | Constraint                  | Deskripsi         |
| ---------------- | ------------- | --------------------------- | ----------------- |
| `id`             | INT           | PK, AUTO_INCREMENT          | ID unik booking   |
| `user_id`        | INT           | FK → users(id)              | Relasi ke user    |
| `room_id`        | INT           | FK → rooms(id)              | Relasi ke room    |
| `check_in_date`  | DATE          | NOT NULL                    | Tanggal check-in  |
| `check_out_date` | DATE          | NOT NULL                    | Tanggal check-out |
| `total_price`    | DECIMAL(10,2) | NOT NULL                    | Total harga       |
| `status`         | ENUM(...)     | DEFAULT 'pending'           | Status booking    |
| `created_at`     | TIMESTAMP     | DEFAULT CURRENT_TIMESTAMP   | Waktu dibuat      |
| `updated_at`     | TIMESTAMP     | ON UPDATE CURRENT_TIMESTAMP | Waktu diupdate    |

**Status Booking:** `pending`, `confirmed`, `checked_in`, `checked_out`, `cancelled`

---

## 🛣 Routes

### Public Routes

| Method | URL                | Controller@Method               | Deskripsi            |
| ------ | ------------------ | ------------------------------- | -------------------- |
| GET    | `/`                | HomeController@index            | Homepage             |
| GET    | `/home`            | HomeController@index            | Homepage             |
| GET    | `/login`           | AuthController@login            | Form login           |
| POST   | `/login`           | AuthController@doLogin          | Proses login         |
| GET    | `/register`        | AuthController@register         | Form register        |
| POST   | `/register`        | AuthController@doRegister       | Proses register      |
| GET    | `/logout`          | AuthController@logout           | Logout               |
| GET    | `/forgot-password` | AuthController@forgotPassword   | Form lupa password   |
| POST   | `/forgot-password` | AuthController@doForgotPassword | Proses lupa password |

### Profile Routes (Requires Login)

| Method | URL                 | Controller@Method             | Deskripsi       |
| ------ | ------------------- | ----------------------------- | --------------- |
| GET    | `/profile`          | AuthController@profile        | Halaman profil  |
| POST   | `/profile/update`   | AuthController@updateProfile  | Update profil   |
| POST   | `/profile/password` | AuthController@updatePassword | Update password |

### Room Routes (Public)

| Method | URL                        | Controller@Method                | Deskripsi         |
| ------ | -------------------------- | -------------------------------- | ----------------- |
| GET    | `/rooms`                   | RoomController@index             | Daftar kamar      |
| GET    | `/rooms/search`            | RoomController@search            | Cari kamar        |
| GET    | `/rooms/types`             | RoomController@types             | Daftar tipe kamar |
| GET    | `/rooms/filter`            | RoomController@filterByType      | Filter by tipe    |
| GET    | `/rooms/{id}`              | RoomController@detail            | Detail kamar      |
| GET    | `/rooms/info/{id}`         | RoomController@getInfo           | Info kamar (AJAX) |
| GET    | `/rooms/availability/{id}` | RoomController@checkAvailability | Cek ketersediaan  |

### Booking Routes (Requires Login)

| Method | URL                           | Controller@Method                   | Deskripsi           |
| ------ | ----------------------------- | ----------------------------------- | ------------------- |
| GET    | `/my-bookings`                | BookingController@myBookings        | Daftar booking user |
| POST   | `/booking/check-availability` | BookingController@checkAvailability | Cek ketersediaan    |
| GET    | `/booking/create/{id}`        | BookingController@create            | Form booking        |
| POST   | `/booking/store`              | BookingController@store             | Simpan booking      |
| GET    | `/booking/detail/{id}`        | BookingController@detail            | Detail booking      |
| GET    | `/booking/cancel/{id}`        | BookingController@cancel            | Batalkan booking    |
| GET    | `/booking/invoice/{id}`       | BookingController@invoice           | Print invoice       |

### Admin Dashboard Routes

| Method | URL                     | Controller@Method                      | Deskripsi    |
| ------ | ----------------------- | -------------------------------------- | ------------ |
| GET    | `/admin`                | Admin\DashboardController@index        | Dashboard    |
| GET    | `/admin/dashboard`      | Admin\DashboardController@index        | Dashboard    |
| GET    | `/admin/analytics`      | Admin\DashboardController@analytics    | Analytics    |
| GET    | `/admin/reports`        | Admin\DashboardController@reports      | Reports      |
| GET    | `/admin/reports/export` | Admin\DashboardController@exportReport | Export CSV   |
| GET    | `/admin/settings`       | Admin\DashboardController@settings     | Settings     |
| GET    | `/admin/activity-log`   | Admin\DashboardController@activityLog  | Activity log |

### Admin User Routes

| Method | URL                                | Controller@Method                  | Deskripsi        |
| ------ | ---------------------------------- | ---------------------------------- | ---------------- |
| GET    | `/admin/users`                     | Admin\UserController@index         | Daftar user      |
| GET    | `/admin/users/create`              | Admin\UserController@create        | Form tambah      |
| POST   | `/admin/users/store`               | Admin\UserController@store         | Simpan user      |
| GET    | `/admin/users/export`              | Admin\UserController@export        | Export CSV       |
| GET    | `/admin/users/stats`               | Admin\UserController@stats         | Statistik (AJAX) |
| POST   | `/admin/users/bulk-action`         | Admin\UserController@bulkAction    | Bulk action      |
| GET    | `/admin/users/{id}`                | Admin\UserController@detail        | Detail user      |
| GET    | `/admin/users/{id}/edit`           | Admin\UserController@edit          | Form edit        |
| POST   | `/admin/users/{id}/update`         | Admin\UserController@update        | Update user      |
| POST   | `/admin/users/{id}/reset-password` | Admin\UserController@resetPassword | Reset password   |
| GET    | `/admin/users/{id}/delete`         | Admin\UserController@delete        | Hapus user       |
| GET    | `/admin/users/{id}/toggle-role`    | Admin\UserController@toggleRole    | Toggle role      |

### Admin Room Routes

| Method | URL                        | Controller@Method                       | Deskripsi        |
| ------ | -------------------------- | --------------------------------------- | ---------------- |
| GET    | `/admin/rooms`             | Admin\RoomController@index              | Daftar kamar     |
| GET    | `/admin/rooms/create`      | Admin\RoomController@create             | Form tambah      |
| POST   | `/admin/rooms/store`       | Admin\RoomController@store              | Simpan kamar     |
| GET    | `/admin/rooms/stats`       | Admin\RoomController@stats              | Statistik (AJAX) |
| POST   | `/admin/rooms/bulk-update` | Admin\RoomController@bulkUpdate         | Bulk update      |
| GET    | `/admin/rooms/{id}`        | Admin\RoomController@detail             | Detail kamar     |
| GET    | `/admin/rooms/{id}/edit`   | Admin\RoomController@edit               | Form edit        |
| POST   | `/admin/rooms/{id}/update` | Admin\RoomController@update             | Update kamar     |
| GET    | `/admin/rooms/{id}/delete` | Admin\RoomController@delete             | Hapus kamar      |
| GET    | `/admin/rooms/{id}/toggle` | Admin\RoomController@toggleAvailability | Toggle status    |
| GET    | `/admin/rooms/{id}/check`  | Admin\RoomController@checkAvailability  | Cek ketersediaan |

### Admin Booking Routes

| Method | URL                               | Controller@Method                      | Deskripsi          |
| ------ | --------------------------------- | -------------------------------------- | ------------------ |
| GET    | `/admin/bookings`                 | Admin\BookingController@index          | Daftar booking     |
| GET    | `/admin/bookings/create`          | Admin\BookingController@create         | Form tambah        |
| POST   | `/admin/bookings/store`           | Admin\BookingController@store          | Simpan booking     |
| GET    | `/admin/bookings/export`          | Admin\BookingController@export         | Export CSV         |
| GET    | `/admin/bookings/today-checkins`  | Admin\BookingController@todayCheckIns  | Check-in hari ini  |
| GET    | `/admin/bookings/today-checkouts` | Admin\BookingController@todayCheckOuts | Check-out hari ini |
| GET    | `/admin/bookings/{id}`            | Admin\BookingController@detail         | Detail booking     |
| POST   | `/admin/bookings/{id}/status`     | Admin\BookingController@updateStatus   | Update status      |
| GET    | `/admin/bookings/{id}/confirm`    | Admin\BookingController@confirm        | Konfirmasi         |
| GET    | `/admin/bookings/{id}/checkin`    | Admin\BookingController@checkIn        | Check-in           |
| GET    | `/admin/bookings/{id}/checkout`   | Admin\BookingController@checkOut       | Check-out          |
| GET    | `/admin/bookings/{id}/cancel`     | Admin\BookingController@cancel         | Batalkan           |
| GET    | `/admin/bookings/{id}/delete`     | Admin\BookingController@delete         | Hapus              |
| GET    | `/admin/bookings/{id}/invoice`    | Admin\BookingController@invoice        | Print invoice      |

---

## 🚀 Instalasi

### Prasyarat

- PHP 8.0+
- MySQL 5.7+
- Node.js 18+
- NPM 9+
- Apache dengan mod_rewrite

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/username/hotel-booking.git
cd hotel-booking

# 2. Install dependencies NPM
npm install

# 3. Build Tailwind CSS
npm run build

# 4. Import database
mysql -u root -p < sql/schema.sql

# 5. Konfigurasi database (edit config/database.php)
# 6. Konfigurasi URL (edit config/app.php)

# 7. Set permission folder upload
chmod -R 755 storage/uploads/

# 8. Jalankan aplikasi
# Via XAMPP: akses http://localhost/hotel-booking
# Via PHP server: php -S localhost:8000 -t public
```

---

## 👤 Penggunaan

### Akun Default

| Role  | Email           | Password |
| ----- | --------------- | -------- |
| Admin | admin@hotel.com | admin123 |
| Guest | guest@hotel.com | guest123 |

### Alur Guest

```
Register → Login → Lihat Kamar → Booking → Cek Booking → Logout
```

### Alur Admin

```
Login → Dashboard → Kelola Users/Rooms/Bookings → Analytics/Reports → Logout
```

---

## 📜 NPM Scripts

| Script       | Command              | Deskripsi                 |
| ------------ | -------------------- | ------------------------- |
| `dev`        | `npm run dev`        | Watch mode - auto compile |
| `build`      | `npm run build`      | Build CSS sekali          |
| `build:prod` | `npm run build:prod` | Build CSS minified        |

---

## 👨‍💻 Author

Dibuat dengan ❤️ untuk pembelajaran PHP Native MVC.
