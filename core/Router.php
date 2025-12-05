<?php

namespace Core;

use Core\Traits\ParsesRoutes;
use Core\Traits\ResolvesController;
use Core\Traits\HandlesErrors;

class Router
{
    use ParsesRoutes, ResolvesController, HandlesErrors;

    protected array $routes = [];
    protected array $params = [];
    protected string $controller = '';
    protected string $method = '';

    /**
     * Add GET route
     */
    public function get(string $route, string $action): self
    {
        return $this->addRoute('GET', $route, $action);
    }

    /**
     * Add POST route
     */
    public function post(string $route, string $action): self
    {
        return $this->addRoute('POST', $route, $action);
    }

    /**
     * Add route to collection
     */
    protected function addRoute(string $method, string $route, string $action): self
    {
        $pattern = $this->convertToRegex($route);
        $this->routes[$method][$pattern] = $action;
        
        return $this;
    }

    /**
     * Match URL to route
     */
    public function match(string $url, string $method): bool
    {
        $method = strtoupper($method);

        if (!isset($this->routes[$method])) {
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
     * Dispatch the route
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

        // Ubah dari: ucfirst($segments[0]) . 'Controller'
        // Menjadi: ucfirst($segments[0]) (tanpa 'Controller')
        $controller = !empty($segments[0]) ? ucfirst($segments[0]) : 'Home';
        $method = $segments[1] ?? 'index';
        $params = array_slice($segments, 2);

        $this->dispatchToController($controller, $method, $params);
    }

    /**
     * Get params
     */
    public function getParams(): array
    {
        return $this->params;
    }
}