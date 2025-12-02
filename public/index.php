<?php

session_start();

// Load config
require_once __DIR__ . '/../config/config.php';

// Autoload dengan namespace
spl_autoload_register(function ($class) {
    $baseDir = dirname(__DIR__);
    
    // Mapping namespace ke direktori (urutan: paling spesifik dulu)
    $namespaceMap = [
        'Core\\Traits\\' => '/core/Traits/',
        'Core\\' => '/core/',
        'App\\Controllers\\' => '/app/controllers/',
        'App\\Models\\Traits\\' => '/app/models/Traits/',
        'App\\Models\\' => '/app/models/'
    ];

    foreach ($namespaceMap as $namespace => $dir) {
        if (strpos($class, $namespace) === 0) {
            $relativeClass = substr($class, strlen($namespace));
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