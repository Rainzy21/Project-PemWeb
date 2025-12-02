<?php

namespace Core\Traits;

trait ParsesUrl
{
    /**
     * Parse URL from request
     */
    protected function parseUrl(): array
    {
        if (isset($_GET['url'])) {
            $url = filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL);
            return explode('/', $url);
        }

        return [];
    }

    /**
     * Get URL segment
     */
    protected function getSegment(array $url, int $index): ?string
    {
        return $url[$index] ?? null;
    }

    /**
     * Remove segment from URL
     */
    protected function removeSegment(array &$url, int $index): void
    {
        unset($url[$index]);
    }

    /**
     * Extract remaining params (renamed from getParams)
     */
    protected function extractParams(array $url): array
    {
        return $url ? array_values($url) : [];
    }
}