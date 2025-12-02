<?php

namespace Core;

use Core\Traits\ParsesUrl;
use Core\Traits\ResolvesApp;

class App
{
    use ParsesUrl, ResolvesApp;

    protected string $defaultController = 'Home';
    protected string $defaultMethod = 'index';
    protected string $controllerPath;

    protected string $controller;
    protected string $method;
    protected array $params = [];

    public function __construct()
    {
        $this->controllerPath = dirname(__DIR__) . '/app/controllers/';
        $this->dispatch();
    }

    /**
     * Dispatch request to controller
     */
    protected function dispatch(): void
    {
        $url = $this->parseUrl();

        // Resolve controller
        $this->controller = $this->resolveController($url);
        $controllerInstance = $this->createController($this->controller);

        // Resolve method
        $this->method = $this->resolveMethod($controllerInstance, $url);

        // Get remaining params from trait
        $this->params = $this->extractParams($url);

        // Execute
        $this->executeAction($controllerInstance, $this->method, $this->params);
    }

    /**
     * Get current controller name
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * Get current method name
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Get current params
     */
    public function getCurrentParams(): array
    {
        return $this->params;
    }
}