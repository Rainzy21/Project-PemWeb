<?php

namespace Core\Traits;

trait ResolvesApp
{
    /**
     * Resolve controller from URL
     */
    protected function resolveController(array &$url): string
    {
        $segment = $this->getSegment($url, 0);

        if ($segment && $this->controllerExists($segment)) {
            $this->removeSegment($url, 0);
            return ucfirst($segment) . 'Controller';
        }

        return $this->defaultController;
    }

    /**
     * Check if controller file exists
     */
    protected function controllerExists(string $name): bool
    {
        // Cek file dengan suffix 'Controller'
        $path = $this->controllerPath . ucfirst($name) . 'Controller.php';
        return file_exists($path);
    }

    /**
     * Resolve method from URL
     */
    protected function resolveMethod(object $controller, array &$url): string
    {
        $segment = $this->getSegment($url, 0);

        if ($segment && method_exists($controller, $segment)) {
            $this->removeSegment($url, 0);
            return $segment;
        }

        return $this->defaultMethod;
    }

    /**
     * Create controller instance
     */
    protected function createController(string $name): object
    {
        // Gunakan namespace lengkap
        $controllerClass = 'App\\Controllers\\' . $name;
        
        // Autoloader akan handle loading file-nya
        if (!class_exists($controllerClass)) {
            throw new \Exception("Controller '$name' not found");
        }
        
        return new $controllerClass();
    }

    /**
     * Execute controller method
     */
    protected function executeAction(object $controller, string $method, array $params): void
    {
        call_user_func_array([$controller, $method], $params);
    }
}