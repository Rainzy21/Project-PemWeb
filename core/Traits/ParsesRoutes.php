<?php

namespace Core\Traits;

trait ParsesRoutes
{
    /**
     * Convert route to regex pattern
     */
    protected function convertToRegex(string $route): string
    {
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z0-9-]+)', $route);
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
        
        return '/^' . $route . '$/i';
    }

    /**
     * Extract named parameters from matches
     */
    protected function extractParams(array $matches): array
    {
        $params = [];
        
        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $params[$key] = $value;
            }
        }
        
        return $params;
    }

    /**
     * Parse controller@method string
     */
    protected function parseAction(string $action): array
    {
        $parts = explode('@', $action);
        
        return [
            'controller' => $parts[0],
            'method' => $parts[1] ?? 'index'
        ];
    }

    /**
     * Clean URL from query string
     */
    protected function cleanUrl(string $url): string
    {
        $url = parse_url($url, PHP_URL_PATH);
        $url = trim($url, '/');
        
        return $url === '' ? 'home' : $url;
    }
}