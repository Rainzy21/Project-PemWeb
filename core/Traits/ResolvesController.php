<?php

namespace Core\Traits;

trait ResolvesController
{
    /**
     * Build full controller class name
     */
    protected function resolveControllerClass(string $controller): string
    {
        return "App\\Controllers\\{$controller}";
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