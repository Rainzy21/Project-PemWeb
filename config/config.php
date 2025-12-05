<?php

// ============================================
// BASE URL
// ============================================
define('BASE_URL', 'http://localhost/Project-PemWeb/public/');
define('STORAGE_URL', 'http://localhost/Project-PemWeb/storage/');  // URL untuk akses storage
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