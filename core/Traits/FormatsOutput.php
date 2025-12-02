<?php

namespace Core\Traits;

trait FormatsOutput
{
    /**
     * Escape HTML
     */
    public function e(?string $text): string
    {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }

    /**
     * Format currency (Rupiah)
     */
    public function currency($amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    /**
     * Format date
     */
    public function date(string $date, string $format = 'd M Y'): string
    {
        return date($format, strtotime($date));
    }

    /**
     * Format datetime
     */
    public function datetime(string $date, string $format = 'd M Y H:i'): string
    {
        return date($format, strtotime($date));
    }

    /**
     * Format number
     */
    public function number($number, int $decimals = 0): string
    {
        return number_format($number, $decimals, ',', '.');
    }
}