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
            return ucfirst($segment);
        }

        return $this->defaultController;
    }

    /**
     * Check if controller file exists
     */
    protected function controllerExists(string $name): bool
    {
        $path = $this->controllerPath . ucfirst($name) . '.php';
        return file_exists($path);
    }

    /**
     * Resolve method from URL
     */
    protected function resolveMethod(object $controller, array &$url): string
    {
        $segment = $this->getSegment($url, 1);

        if ($segment && method_exists($controller, $segment)) {
            $this->removeSegment($url, 1);
            return $segment;
        }

        return $this->defaultMethod;
    }

    /**
     * Create controller instance
     */
    protected function createController(string $name): object
    {
        require_once $this->controllerPath . $name . '.php';
        return new $name();
    }

    /**
     * Execute controller method
     */
    protected function executeAction(object $controller, string $method, array $params): void
    {
        call_user_func_array([$controller, $method], $params);
    }
}