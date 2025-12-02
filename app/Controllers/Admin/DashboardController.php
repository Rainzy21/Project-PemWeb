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
     * Dashboard utama admin
     */
    public function index()
    {
        $userModel = $this->loadModel('User');
        $roomModel = $this->loadModel('Room');
        $bookingModel = $this->loadModel('Booking');

        $recentBookings = array_slice($bookingModel->getAllWithDetails(), 0, 5);

        $this->view->setLayout('admin')->render('admin/dashboard/index', [
            'title' => 'Dashboard Admin - ' . APP_NAME,
            'stats' => $this->getDashboardStats($userModel, $roomModel, $bookingModel),
            'recentBookings' => $recentBookings,
            'todayCheckIns' => $bookingModel->getTodayCheckIns(),
            'todayCheckOuts' => $bookingModel->getTodayCheckOuts(),
            'roomStats' => $this->getRoomStats($roomModel),
            'bookingStats' => $this->getBookingStats($bookingModel)
        ]);
    }

    /**
     * Analytics page
     */
    public function analytics()
    {
        $bookingModel = $this->loadModel('Booking');

        $this->view->setLayout('admin')->render('admin/dashboard/analytics', [
            'title' => 'Analytics - ' . APP_NAME,
            'monthlyRevenue' => $this->getMonthlyRevenueChart($bookingModel),
            'bookingTrends' => $this->getBookingTrends($bookingModel),
            'roomPopularity' => $this->getRoomTypePopularity($bookingModel)
        ]);
    }

    /**
     * Reports page
     */
    public function reports()
    {
        $bookingModel = $this->loadModel('Booking');
        $roomModel = $this->loadModel('Room');
        $dateRange = $this->getReportDateRange();

        $this->view->setLayout('admin')->render('admin/dashboard/reports', [
            'title' => 'Laporan - ' . APP_NAME,
            'startDate' => $dateRange['start'],
            'endDate' => $dateRange['end'],
            'revenueReport' => $this->getRevenueReport($bookingModel, $dateRange['start'], $dateRange['end']),
            'occupancyReport' => $this->getOccupancyReport($bookingModel, $roomModel, $dateRange['start'], $dateRange['end']),
            'bookingSummary' => $this->getBookingSummary($bookingModel, $dateRange['start'], $dateRange['end'])
        ]);
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
            return $this->redirect('admin/reports');
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
        $activities = $this->loadModel('Booking')->raw(
            "SELECT b.*, u.name as guest_name, r.room_number, 'booking' as activity_type
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