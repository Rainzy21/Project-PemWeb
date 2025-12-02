<?php

namespace App\Controllers\Admin;

use Core\Controller;

class DashboardController extends Controller
{
    /**
     * Constructor - require admin login
     */
    public function __construct()
    {
        parent::__construct();
        $this->requireLogin();
        $this->requireAdmin();
    }

    /**
     * Check if user is admin
     */
    private function requireAdmin()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']->role !== 'admin') {
            $this->setFlash('error', 'Anda tidak memiliki akses ke halaman ini');
            $this->redirect('/');
        }
    }

    /**
     * Dashboard utama admin
     */
    public function index()
    {
        $userModel = $this->loadModel('User');
        $roomModel = $this->loadModel('Room');
        $bookingModel = $this->loadModel('Booking');

        // Statistik utama
        $stats = [
            'total_users' => $userModel->count(),
            'total_guests' => count($userModel->getGuests()),
            'total_admins' => count($userModel->getAdmins()),
            'total_rooms' => $roomModel->count(),
            'available_rooms' => count($roomModel->getAvailable()),
            'occupied_rooms' => $roomModel->count() - count($roomModel->getAvailable()),
            'total_bookings' => $bookingModel->count(),
            'pending_bookings' => count($bookingModel->getPending()),
            'confirmed_bookings' => count($bookingModel->getConfirmed()),
            'today_checkins' => count($bookingModel->getTodayCheckIns()),
            'today_checkouts' => count($bookingModel->getTodayCheckOuts()),
            'total_revenue' => $this->calculateTotalRevenue($bookingModel),
            'monthly_revenue' => $this->calculateMonthlyRevenue($bookingModel)
        ];

        // Recent bookings (5 terbaru)
        $recentBookings = $bookingModel->getAllWithDetails();
        $recentBookings = array_slice($recentBookings, 0, 5);

        // Today's activities
        $todayCheckIns = $bookingModel->getTodayCheckIns();
        $todayCheckOuts = $bookingModel->getTodayCheckOuts();

        // Room statistics by type
        $roomStats = [
            'standard' => count($roomModel->getStandard()),
            'deluxe' => count($roomModel->getDeluxe()),
            'suite' => count($roomModel->getSuite())
        ];

        // Booking statistics by status
        $bookingStats = [
            'pending' => count($bookingModel->getByStatus('pending')),
            'confirmed' => count($bookingModel->getByStatus('confirmed')),
            'checked_in' => count($bookingModel->getByStatus('checked_in')),
            'checked_out' => count($bookingModel->getByStatus('checked_out')),
            'cancelled' => count($bookingModel->getByStatus('cancelled'))
        ];

        $this->view->setLayout('admin')->render('admin/dashboard/index', [
            'title' => 'Dashboard Admin - ' . APP_NAME,
            'stats' => $stats,
            'recentBookings' => $recentBookings,
            'todayCheckIns' => $todayCheckIns,
            'todayCheckOuts' => $todayCheckOuts,
            'roomStats' => $roomStats,
            'bookingStats' => $bookingStats
        ]);
    }

    /**
     * Calculate total revenue (from checked_out bookings)
     */
    private function calculateTotalRevenue($bookingModel)
    {
        $bookings = $bookingModel->getByStatus('checked_out');
        $total = 0;
        foreach ($bookings as $booking) {
            $total += $booking->total_price;
        }
        return $total;
    }

    /**
     * Calculate current month revenue
     */
    private function calculateMonthlyRevenue($bookingModel)
    {
        $result = $bookingModel->raw(
            "SELECT SUM(total_price) as revenue 
             FROM bookings 
             WHERE status = 'checked_out' 
             AND MONTH(created_at) = MONTH(CURRENT_DATE())
             AND YEAR(created_at) = YEAR(CURRENT_DATE())"
        );

        return $result[0]->revenue ?? 0;
    }

    /**
     * Analytics page
     */
    public function analytics()
    {
        $bookingModel = $this->loadModel('Booking');

        // Monthly revenue for last 12 months
        $monthlyRevenue = $this->getMonthlyRevenueChart($bookingModel);

        // Booking trends
        $bookingTrends = $this->getBookingTrends($bookingModel);

        // Room type popularity
        $roomPopularity = $this->getRoomTypePopularity($bookingModel);

        $this->view->setLayout('admin')->render('admin/dashboard/analytics', [
            'title' => 'Analytics - ' . APP_NAME,
            'monthlyRevenue' => $monthlyRevenue,
            'bookingTrends' => $bookingTrends,
            'roomPopularity' => $roomPopularity
        ]);
    }

    /**
     * Get monthly revenue for chart
     */
    private function getMonthlyRevenueChart($bookingModel)
    {
        $result = $bookingModel->raw(
            "SELECT 
                MONTH(created_at) as month, 
                YEAR(created_at) as year, 
                SUM(total_price) as revenue,
                COUNT(*) as total_bookings
             FROM bookings 
             WHERE status = 'checked_out' 
             AND created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 12 MONTH)
             GROUP BY YEAR(created_at), MONTH(created_at) 
             ORDER BY year ASC, month ASC"
        );

        return $result;
    }

    /**
     * Get booking trends
     */
    private function getBookingTrends($bookingModel)
    {
        $result = $bookingModel->raw(
            "SELECT 
                DATE(created_at) as date,
                COUNT(*) as total
             FROM bookings 
             WHERE created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY)
             GROUP BY DATE(created_at)
             ORDER BY date ASC"
        );

        return $result;
    }

    /**
     * Get room type popularity
     */
    private function getRoomTypePopularity($bookingModel)
    {
        $result = $bookingModel->raw(
            "SELECT 
                r.room_type,
                COUNT(b.id) as total_bookings,
                SUM(b.total_price) as total_revenue
             FROM bookings b
             JOIN rooms r ON b.room_id = r.id
             WHERE b.status IN ('confirmed', 'checked_in', 'checked_out')
             GROUP BY r.room_type
             ORDER BY total_bookings DESC"
        );

        return $result;
    }

    /**
     * Reports page
     */
    public function reports()
    {
        $bookingModel = $this->loadModel('Booking');
        $roomModel = $this->loadModel('Room');

        // Date range filter
        $startDate = $_GET['start_date'] ?? date('Y-m-01'); // First day of month
        $endDate = $_GET['end_date'] ?? date('Y-m-d'); // Today

        // Revenue report
        $revenueReport = $this->getRevenueReport($bookingModel, $startDate, $endDate);

        // Occupancy report
        $occupancyReport = $this->getOccupancyReport($bookingModel, $roomModel, $startDate, $endDate);

        // Booking summary
        $bookingSummary = $this->getBookingSummary($bookingModel, $startDate, $endDate);

        $this->view->setLayout('admin')->render('admin/dashboard/reports', [
            'title' => 'Laporan - ' . APP_NAME,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'revenueReport' => $revenueReport,
            'occupancyReport' => $occupancyReport,
            'bookingSummary' => $bookingSummary
        ]);
    }

    /**
     * Get revenue report
     */
    private function getRevenueReport($bookingModel, $startDate, $endDate)
    {
        $result = $bookingModel->raw(
            "SELECT 
                SUM(CASE WHEN status = 'checked_out' THEN total_price ELSE 0 END) as realized_revenue,
                SUM(CASE WHEN status IN ('pending', 'confirmed', 'checked_in') THEN total_price ELSE 0 END) as potential_revenue,
                SUM(CASE WHEN status = 'cancelled' THEN total_price ELSE 0 END) as cancelled_revenue,
                COUNT(*) as total_bookings
             FROM bookings 
             WHERE DATE(created_at) BETWEEN :start AND :end",
            [':start' => $startDate, ':end' => $endDate]
        );

        return $result[0] ?? null;
    }

    /**
     * Get occupancy report
     */
    private function getOccupancyReport($bookingModel, $roomModel, $startDate, $endDate)
    {
        $totalRooms = $roomModel->count();
        
        // Calculate total room nights available
        $date1 = new \DateTime($startDate);
        $date2 = new \DateTime($endDate);
        $days = $date1->diff($date2)->days + 1;
        $totalRoomNights = $totalRooms * $days;

        // Calculate occupied room nights
        $result = $bookingModel->raw(
            "SELECT SUM(DATEDIFF(check_out_date, check_in_date)) as occupied_nights
             FROM bookings 
             WHERE status IN ('checked_in', 'checked_out')
             AND check_in_date <= :end AND check_out_date >= :start",
            [':start' => $startDate, ':end' => $endDate]
        );

        $occupiedNights = $result[0]->occupied_nights ?? 0;
        $occupancyRate = $totalRoomNights > 0 ? round(($occupiedNights / $totalRoomNights) * 100, 2) : 0;

        return [
            'total_rooms' => $totalRooms,
            'total_days' => $days,
            'total_room_nights' => $totalRoomNights,
            'occupied_nights' => $occupiedNights,
            'occupancy_rate' => $occupancyRate
        ];
    }

    /**
     * Get booking summary
     */
    private function getBookingSummary($bookingModel, $startDate, $endDate)
    {
        $result = $bookingModel->raw(
            "SELECT 
                status,
                COUNT(*) as count,
                SUM(total_price) as total_value
             FROM bookings 
             WHERE DATE(created_at) BETWEEN :start AND :end
             GROUP BY status",
            [':start' => $startDate, ':end' => $endDate]
        );

        return $result;
    }

    /**
     * Export report to PDF/CSV
     */
    public function exportReport()
    {
        $format = $_GET['format'] ?? 'csv';
        $type = $_GET['type'] ?? 'bookings';
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');

        $bookingModel = $this->loadModel('Booking');

        if ($format === 'csv') {
            $this->exportToCsv($bookingModel, $type, $startDate, $endDate);
        }
    }

    /**
     * Export to CSV
     */
    private function exportToCsv($bookingModel, $type, $startDate, $endDate)
    {
        $filename = $type . '_report_' . $startDate . '_to_' . $endDate . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        if ($type === 'bookings') {
            $bookings = $bookingModel->raw(
                "SELECT b.*, u.name as guest_name, r.room_number, r.room_type
                 FROM bookings b
                 JOIN users u ON b.user_id = u.id
                 JOIN rooms r ON b.room_id = r.id
                 WHERE DATE(b.created_at) BETWEEN :start AND :end
                 ORDER BY b.created_at DESC",
                [':start' => $startDate, ':end' => $endDate]
            );

            // Header
            fputcsv($output, ['ID', 'Guest', 'Room', 'Type', 'Check In', 'Check Out', 'Total', 'Status', 'Created']);

            foreach ($bookings as $booking) {
                fputcsv($output, [
                    $booking->id,
                    $booking->guest_name,
                    $booking->room_number,
                    $booking->room_type,
                    $booking->check_in_date,
                    $booking->check_out_date,
                    $booking->total_price,
                    $booking->status,
                    $booking->created_at
                ]);
            }
        } elseif ($type === 'revenue') {
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

            fputcsv($output, ['Date', 'Bookings', 'Revenue']);

            foreach ($revenues as $revenue) {
                fputcsv($output, [
                    $revenue->date,
                    $revenue->bookings,
                    $revenue->revenue
                ]);
            }
        }

        fclose($output);
        exit;
    }

    /**
     * Settings page
     */
    public function settings()
    {
        $this->view->setLayout('admin')->render('admin/dashboard/settings', [
            'title' => 'Settings - ' . APP_NAME
        ]);
    }

    /**
     * Activity log
     */
    public function activityLog()
    {
        $bookingModel = $this->loadModel('Booking');

        // Recent activities (bookings created/updated)
        $activities = $bookingModel->raw(
            "SELECT b.*, u.name as guest_name, r.room_number,
                    'booking' as activity_type
             FROM bookings b
             JOIN users u ON b.user_id = u.id
             JOIN rooms r ON b.room_id = r.id
             ORDER BY b.updated_at DESC
             LIMIT 50"
        );

        $this->view->setLayout('admin')->render('admin/dashboard/activity-log', [
            'title' => 'Activity Log - ' . APP_NAME,
            'activities' => $activities
        ]);
    }
}