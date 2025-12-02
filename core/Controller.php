<?php

namespace Core;

use Core\Traits\LoadsModels;
use Core\Traits\HandlesRequest;
use Core\Traits\HandlesResponse;
use Core\Traits\ValidatesInput;
use Core\Traits\ChecksAuth;

class Controller
{
    use LoadsModels, HandlesRequest, HandlesResponse, ValidatesInput, ChecksAuth;

    protected View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    /**
     * Render view
     */
    protected function render(string $view, array $data = []): void
    {
        $this->view->render($view, $data);
    }
}