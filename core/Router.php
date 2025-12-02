<?php

namespace Core;

class Router
{
    protected $routes = [];
    protected $params = [];
    protected $controller;
    protected $method;

    /**
     * Add GET route
     */
    public function get($route, $action)
    {
        $this->addRoute('GET', $route, $action);
    }

    /**
     * Add POST route
     */
    public function post($route, $action)
    {
        $this->addRoute('POST', $route, $action);
    }

    /**
     * Add route to collection
     */
    protected function addRoute($method, $route, $action)
    {
        // Convert route to regex pattern
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z0-9-]+)', $route);
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
        $route = '/^' . $route . '$/i';

        $this->routes[$method][$route] = $action;
    }

    /**
     * Match URL to route
     */
    public function match($url, $method)
    {
        $method = strtoupper($method);
        
        if (!isset($this->routes[$method])) {
            return false;
        }

        foreach ($this->routes[$method] as $route => $action) {
            if (preg_match($route, $url, $matches)) {
                // Extract named parameters
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $this->params[$key] = $match;
                    }
                }

                // Parse controller@method
                if (is_string($action)) {
                    $parts = explode('@', $action);
                    $this->controller = $parts[0];
                    $this->method = $parts[1] ?? 'index';
                }

                return true;
            }
        }

        return false;
    }

    /**
     * Dispatch the route
     */
    public function dispatch($url)
    {
        // Remove query string
        $url = parse_url($url, PHP_URL_PATH);
        $url = trim($url, '/');

        // Default route
        if ($url === '') {
            $url = 'home';
        }

        $method = $_SERVER['REQUEST_METHOD'];

        if ($this->match($url, $method)) {
            $controllerName = "App\\Controllers\\{$this->controller}";
            
            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                
                if (method_exists($controller, $this->method)) {
                    call_user_func_array([$controller, $this->method], $this->params);
                    return;
                } else {
                    $this->error(404, "Method {$this->method} not found");
                }
            } else {
                $this->error(404, "Controller {$this->controller} not found");
            }
        } else {
            // Auto routing fallback (controller/method/params)
            $this->autoRoute($url);
        }
    }

    /**
     * Auto routing: /controller/method/param1/param2
     */
    protected function autoRoute($url)
    {
        $segments = explode('/', $url);
        
        // Controller
        $controller = !empty($segments[0]) ? ucfirst($segments[0]) : 'Home';
        $controllerName = "App\\Controllers\\{$controller}";
        
        // Method
        $method = $segments[1] ?? 'index';
        
        // Parameters
        $params = array_slice($segments, 2);

        if (class_exists($controllerName)) {
            $controllerObj = new $controllerName();
            
            if (method_exists($controllerObj, $method)) {
                call_user_func_array([$controllerObj, $method], $params);
            } else {
                $this->error(404, "Method {$method} not found in {$controller}");
            }
        } else {
            $this->error(404, "Page not found");
        }
    }

    /**
     * Error handler
     */
    protected function error($code, $message = '')
    {
        http_response_code($code);
        
        $errorView = "../app/views/errors/{$code}.php";
        if (file_exists($errorView)) {
            require_once $errorView;
        } else {
            echo "<h1>Error {$code}</h1><p>{$message}</p>";
        }
        exit;
    }

    /**
     * Get params
     */
    public function getParams()
    {
        return $this->params;
    }
}