<?php

session_start();

// Load config
require_once __DIR__ . '/../config/config.php';

// Autoload dengan namespace
spl_autoload_register(function ($class) {
    $baseDir = dirname(__DIR__);
    
    // Mapping namespace ke direktori
    $namespaceMap = [
        'Core\\Traits\\' => '/core/Traits/',
        'Core\\' => '/core/',
        'App\\Controllers\\Traits\\' => '/app/Controllers/Traits/',
        'App\\Controllers\\Admin\\' => '/app/Controllers/Admin/',  // Tambah ini
        'App\\Controllers\\' => '/app/Controllers/',
        'App\\Models\\Traits\\' => '/app/Models/Traits/',
        'App\\Models\\' => '/app/Models/'
    ];

    foreach ($namespaceMap as $namespace => $dir) {
        if (strpos($class, $namespace) === 0) {
            $relativeClass = substr($class, strlen($namespace));
            // Ganti backslash dengan forward slash untuk path
            $relativeClass = str_replace('\\', '/', $relativeClass);
            $file = $baseDir . $dir . $relativeClass . '.php';
            
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});

// Initialize App
$app = new Core\App();