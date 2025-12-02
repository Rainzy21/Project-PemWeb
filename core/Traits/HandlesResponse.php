<?php

namespace Core\Traits;

trait HandlesResponse
{
    /**
     * Redirect to another page
     */
    protected function redirect(string $url): void
    {
        if (strpos($url, 'http') !== 0) {
            $url = BASE_URL . ltrim($url, '/');
        }
        header("Location: {$url}");
        exit;
    }

    /**
     * Redirect back to previous page
     */
    protected function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? BASE_URL;
        header("Location: {$referer}");
        exit;
    }

    /**
     * Return JSON response
     */
    protected function json(mixed $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Return success JSON
     */
    protected function jsonSuccess(mixed $data = null, string $message = 'Success'): void
    {
        $this->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ]);
    }

    /**
     * Return error JSON
     */
    protected function jsonError(string $message = 'Error', int $statusCode = 400): void
    {
        $this->json([
            'success' => false,
            'message' => $message
        ], $statusCode);
    }

    /**
     * Set flash message
     */
    protected function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }
}