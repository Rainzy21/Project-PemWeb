<?php

namespace Core\Traits;

trait HandlesRequest
{
    /**
     * Get request data
     */
    protected function getRequest(?string $key = null, mixed $default = null): mixed
    {
        $data = array_merge($_GET, $_POST);

        if ($key === null) {
            return $data;
        }

        return $data[$key] ?? $default;
    }

    /**
     * Get only specified keys
     */
    protected function only(array $keys): array
    {
        $data = $this->getRequest();
        return array_intersect_key($data, array_flip($keys));
    }

    /**
     * Get all except specified keys
     */
    protected function except(array $keys): array
    {
        $data = $this->getRequest();
        return array_diff_key($data, array_flip($keys));
    }

    /**
     * Check if request method is POST
     */
    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Check if request method is GET
     */
    protected function isGet(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    /**
     * Check if request is AJAX
     */
    protected function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Get request method
     */
    protected function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}