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