<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Controllers\Traits\RequiresAdmin;
use App\Controllers\Traits\CalculatesRevenue;
use App\Controllers\Traits\GeneratesStatistics;
use App\Controllers\Traits\GeneratesReports;
use App\Controllers\Traits\ExportsCsv;

class DashboardController extends Controller
{
    use RequiresAdmin, CalculatesRevenue, GeneratesStatistics, GeneratesReports, ExportsCsv;

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
     * Dashboard utama admin dengan tabs
     */
    public function index()
    {
        $userModel = $this->loadModel('User');
        $roomModel = $this->loadModel('Room');
        $bookingModel = $this->loadModel('Booking');

        $activeTab = $_GET['tab'] ?? 'home';
        $dateRange = $this->getReportDateRange();

        $recentBookings = array_slice($bookingModel->getAllWithDetails(), 0, 5);

        // Data untuk semua tabs
        $data = [
            'title' => 'Dashboard Admin - ' . APP_NAME,
            'activeTab' => $activeTab,

            // Home tab data
            'stats' => $this->getDashboardStats($userModel, $roomModel, $bookingModel),
            'recentBookings' => $recentBookings,
            'todayCheckIns' => $bookingModel->getTodayCheckIns(),
            'todayCheckOuts' => $bookingModel->getTodayCheckOuts(),
            'roomStats' => $this->getRoomStats($roomModel),
            'bookingStats' => $this->getBookingStats($bookingModel),

            // Analytics tab data
            'monthlyRevenue' => $this->getMonthlyRevenueChart($bookingModel),
            'bookingTrends' => $this->getBookingTrends($bookingModel),
            'roomPopularity' => $this->getRoomTypePopularity($bookingModel),

            // Reports tab data
            'startDate' => $dateRange['start'],
            'endDate' => $dateRange['end'],
            'revenueReport' => $this->getRevenueReport($bookingModel, $dateRange['start'], $dateRange['end']),
            'occupancyReport' => $this->getOccupancyReport($bookingModel, $roomModel, $dateRange['start'], $dateRange['end']),
            'bookingSummary' => $this->getBookingSummary($bookingModel, $dateRange['start'], $dateRange['end']),

            // Activity Log tab data
            'activities' => $this->getRecentActivities($bookingModel)
        ];

        $this->view->setLayout('admin')->render('admin/dashboard/index', $data);
    }

    /**
     * Get recent activities
     */
    private function getRecentActivities($bookingModel)
    {
        return $bookingModel->raw(
            "SELECT b.*, u.name as guest_name, r.room_number, 'booking' as activity_type
             FROM bookings b
             JOIN users u ON b.user_id = u.id
             JOIN rooms r ON b.room_id = r.id
             ORDER BY b.updated_at DESC
             LIMIT 50"
        );
    }

    /**
     * Export report to CSV
     */
    public function exportReport()
    {
        $format = $_GET['format'] ?? 'csv';
        $type = $_GET['type'] ?? 'bookings';
        $dateRange = $this->getReportDateRange();

        if ($format !== 'csv') {
            return $this->redirect('admin/dashboard?tab=reports');
        }

        $bookingModel = $this->loadModel('Booking');
        $filename = "{$type}_report_{$dateRange['start']}_to_{$dateRange['end']}.csv";

        if ($type === 'bookings') {
            $this->exportToCsv(
                $filename,
                ['ID', 'Guest', 'Room', 'Type', 'Check In', 'Check Out', 'Total', 'Status', 'Created'],
                $this->getBookingsExportData($bookingModel, $dateRange['start'], $dateRange['end'])
            );
        } elseif ($type === 'revenue') {
            $this->exportToCsv(
                $filename,
                ['Date', 'Bookings', 'Revenue'],
                $this->getRevenueExportData($bookingModel, $dateRange['start'], $dateRange['end'])
            );
        }
    }
}