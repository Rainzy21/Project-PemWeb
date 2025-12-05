<?php

namespace Core\Traits;

trait ResolvesController
{
    /**
     * Build full controller class name
     */
    protected function resolveControllerClass(string $controller): string
    {
        // Jika sudah mengandung namespace (ada backslash), gunakan langsung
        if (str_contains($controller, '\\')) {
            // Admin\DashboardController -> App\Controllers\Admin\DashboardController
            $controller = str_replace('Controller', '', $controller);
            return "App\\Controllers\\{$controller}Controller";
        }
        
        // Hapus 'Controller' suffix jika sudah ada
        $controller = str_replace('Controller', '', $controller);
        
        return "App\\Controllers\\{$controller}Controller";
    }

    /**
     * Create controller instance
     */
    protected function createController(string $controllerClass): ?object
    {
        if (!class_exists($controllerClass)) {
            return null;
        }
        
        return new $controllerClass();
    }

    /**
     * Execute controller method
     */
    protected function callAction(object $controller, string $method, array $params): bool
    {
        if (!method_exists($controller, $method)) {
            return false;
        }
        
        call_user_func_array([$controller, $method], $params);
        return true;
    }

    /**
     * Dispatch to controller
     */
    protected function dispatchToController(string $controller, string $method, array $params): bool
    {
        $controllerClass = $this->resolveControllerClass($controller);
        $controllerInstance = $this->createController($controllerClass);
        
        if (!$controllerInstance) {
            $this->error(404, "Controller {$controller} not found");
            return false;
        }
        
        if (!$this->callAction($controllerInstance, $method, $params)) {
            $this->error(404, "Method {$method} not found");
            return false;
        }
        
        return true;
    }
}