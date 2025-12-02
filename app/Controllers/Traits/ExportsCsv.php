<?php

namespace App\Controllers\Traits;

trait ExportsCsv
{
    /**
     * Export data to CSV
     */
    protected function exportToCsv(string $filename, array $headers, array $data): void
    {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, $headers);

        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }

    /**
     * Get bookings data for export
     */
    protected function getBookingsExportData($bookingModel, string $startDate, string $endDate): array
    {
        $bookings = $bookingModel->raw(
            "SELECT b.*, u.name as guest_name, r.room_number, r.room_type
             FROM bookings b
             JOIN users u ON b.user_id = u.id
             JOIN rooms r ON b.room_id = r.id
             WHERE DATE(b.created_at) BETWEEN :start AND :end
             ORDER BY b.created_at DESC",
            [':start' => $startDate, ':end' => $endDate]
        );

        return array_map(fn($b) => [
            $b->id,
            $b->guest_name,
            $b->room_number,
            $b->room_type,
            $b->check_in_date,
            $b->check_out_date,
            $b->total_price,
            $b->status,
            $b->created_at
        ], $bookings);
    }

    /**
     * Get revenue data for export
     */
    protected function getRevenueExportData($bookingModel, string $startDate, string $endDate): array
    {
        $revenues = $bookingModel->raw(
            "SELECT 
                DATE(created_at) as date,
                COUNT(*) as bookings,
                SUM(total_price) as revenue
             FROM bookings 
             WHERE status = 'checked_out'
             AND DATE(created_at) BETWEEN :start AND :end
             GROUP BY DATE(created_at)
             ORDER BY date ASC",
            [':start' => $startDate, ':end' => $endDate]
        );

        return array_map(fn($r) => [$r->date, $r->bookings, $r->revenue], $revenues);
    }
}