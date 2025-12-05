<?php

namespace Core;

class View
{
    protected string $viewPath = '';
    protected array $data = [];
    protected ?string $layout = null;
    protected string $content = '';

    public function __construct()
    {
        $this->viewPath = dirname(__DIR__) . '/app/Views/';
    }

    /**
     * Magic getter for data
     */
    public function __get(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    /**
     * Check if variable exists
     */
    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    /**
     * Set data
     */
    public function set(string $key, mixed $value): self
    {
        $this->data[$key] = $value;
        return $this;
    }

    /**
     * Share data globally
     */
    public function share(array $data): self
    {
        $this->data = array_merge($this->data, $data);
        return $this;
    }

    /**
     * Render view file
     */
    public function render($view, $data = [])
    {
        $this->data = array_merge($this->data, $data);
        
        $filePath = $this->viewPath . str_replace('.', '/', $view) . '.php';
        
        if (!file_exists($filePath)) {
            throw new \Exception("View file not found: {$filePath}");
        }
        
        extract($this->data);
        ob_start();
        include $filePath;
        $content = ob_get_clean();

        if ($this->layout) {
            $this->content = $content;
            $layoutPath = $this->viewPath . 'layouts/' . $this->layout . '.php';
            
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
     * Get content for layout
     */
    public function content()
    {
        return $this->content;
    }

    /**
     * Include partial/component
     */
    public function partial($view, $data = [])
    {
        $filePath = $this->viewPath . 'partials/' . str_replace('.', '/', $view) . '.php';
        
        if (!file_exists($filePath)) {
            throw new \Exception("Partial not found: {$filePath}");
        }

        extract(array_merge($this->data, $data));
        include $filePath;
    }

    /**
     * Escape HTML
     */
    public function e($text)
    {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }

    /**
     * Generate URL
     */
    public function url($path = '')
    {
        return BASE_URL . ltrim($path, '/');
    }

    /**
     * Asset URL
     */
    public function asset($path)
    {
        return BASE_URL . 'assets/' . ltrim($path, '/');
    }

    /**
     * Get flash message
     */
    public function flash($type = null)
    {
        if (!isset($_SESSION['flash'])) {
            return null;
        }

        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);

        if ($type) {
            return $flash['type'] === $type ? $flash['message'] : null;
        }

        return $flash;
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
     * Old input value
     */
    public function old($key, $default = '')
    {
        return $_SESSION['old'][$key] ?? $default;
    }

    /**
     * Check if current URL matches the given path (for active menu)
     */
    public function isActive($path)
    {
        $currentUri = trim($_SERVER['REQUEST_URI'], '/');
        $path = trim($path, '/');
        
        // Remove query string
        $currentUri = strtok($currentUri, '?');
        
        // Remove base path (e.g., Project-PemWeb/public)
        $basePath = trim(parse_url(BASE_URL, PHP_URL_PATH), '/');
        if ($basePath && strpos($currentUri, $basePath) === 0) {
            $currentUri = trim(substr($currentUri, strlen($basePath)), '/');
        }
        
        // Exact match or starts with path
        return ($currentUri === $path || strpos($currentUri, $path . '/') === 0);
    }
}