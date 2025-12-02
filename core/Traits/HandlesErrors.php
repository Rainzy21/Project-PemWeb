<?php

namespace Core\Traits;

trait HandlesErrors
{
    /**
     * Handle HTTP error
     */
    protected function error(int $code, string $message = ''): void
    {
        http_response_code($code);
        
        $errorView = dirname(__DIR__) . "/app/views/errors/{$code}.php";
        
        if (file_exists($errorView)) {
            require_once $errorView;
        } else {
            $this->renderDefaultError($code, $message);
        }
        
        exit;
    }

    /**
     * Render default error page
     */
    protected function renderDefaultError(int $code, string $message): void
    {
        $titles = [
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error'
        ];
        
        $title = $titles[$code] ?? 'Error';
        
        echo "<!DOCTYPE html>
        <html>
        <head><title>{$code} - {$title}</title></head>
        <body>
            <h1>Error {$code}</h1>
            <p>{$message}</p>
        </body>
        </html>";
    }
}