<?php

use Core\Router;

$router = new Router();

// ============================================
// PUBLIC ROUTES
// ============================================

// Home
$router->get('', 'HomeController@index');
$router->get('home', 'HomeController@index');

// Auth - Guest
$router->get('login', 'AuthController@login');
$router->post('login', 'AuthController@doLogin');
$router->get('register', 'AuthController@register');
$router->post('register', 'AuthController@doRegister');
$router->get('logout', 'AuthController@logout');
$router->get('forgot-password', 'AuthController@forgotPassword');
$router->post('forgot-password', 'AuthController@doForgotPassword');

// Auth - Profile (requires login)
$router->get('profile', 'AuthController@profile');
$router->post('profile/update', 'AuthController@updateProfile');
$router->post('profile/password', 'AuthController@updatePassword');

// ============================================
// ROOMS (Public)
// ============================================

$router->get('rooms', 'RoomController@index');
$router->get('rooms/search', 'RoomController@search');
$router->get('rooms/types', 'RoomController@types');
$router->get('rooms/filter', 'RoomController@filterByType');
// Routes dengan parameter harus di bawah
$router->get('rooms/info/{id}', 'RoomController@getInfo');
$router->get('rooms/availability/{id}', 'RoomController@checkAvailability');
$router->get('rooms/{id}', 'RoomController@detail');

// ============================================
// BOOKING (Requires Login - Guest)
// ============================================

$router->get('my-bookings', 'BookingController@myBookings');
$router->post('booking/check-availability', 'BookingController@checkAvailability');
$router->get('booking/create/{id}', 'BookingController@create');
$router->post('booking/store', 'BookingController@store');
$router->get('booking/detail/{id}', 'BookingController@detail');
$router->get('booking/cancel/{id}', 'BookingController@cancel');
$router->get('booking/invoice/{id}', 'BookingController@invoice');

// ============================================
// ADMIN ROUTES
// ============================================

// Dashboard
$router->get('admin', 'Admin\\DashboardController@index');
$router->get('admin/dashboard', 'Admin\\DashboardController@index');
$router->get('admin/analytics', 'Admin\\DashboardController@analytics');
$router->get('admin/reports', 'Admin\\DashboardController@reports');
$router->get('admin/reports/export', 'Admin\\DashboardController@exportReport');
$router->get('admin/settings', 'Admin\\DashboardController@settings');
$router->get('admin/activity-log', 'Admin\\DashboardController@activityLog');

// ============================================
// ADMIN - Users Management
// ============================================

$router->get('admin/users', 'Admin\\UserController@index');
$router->get('admin/users/create', 'Admin\\UserController@create');
$router->post('admin/users/store', 'Admin\\UserController@store');
$router->get('admin/users/export', 'Admin\\UserController@export');
$router->get('admin/users/stats', 'Admin\\UserController@stats');
$router->post('admin/users/bulk-action', 'Admin\\UserController@bulkAction');
// Routes dengan parameter harus di bawah
$router->get('admin/users/{id}', 'Admin\\UserController@detail');
$router->get('admin/users/{id}/edit', 'Admin\\UserController@edit');
$router->post('admin/users/{id}/update', 'Admin\\UserController@update');
$router->post('admin/users/{id}/reset-password', 'Admin\\UserController@resetPassword');
$router->get('admin/users/{id}/delete', 'Admin\\UserController@delete');
$router->get('admin/users/{id}/toggle-role', 'Admin\\UserController@toggleRole');

// ============================================
// ADMIN - Rooms Management
// ============================================

$router->get('admin/rooms', 'Admin\\RoomController@index');
$router->get('admin/rooms/create', 'Admin\\RoomController@create');
$router->post('admin/rooms/store', 'Admin\\RoomController@store');
$router->get('admin/rooms/stats', 'Admin\\RoomController@stats');
$router->post('admin/rooms/bulk-update', 'Admin\\RoomController@bulkUpdate');
// Routes dengan parameter harus di bawah
$router->get('admin/rooms/{id}', 'Admin\\RoomController@detail');
$router->get('admin/rooms/{id}/edit', 'Admin\\RoomController@edit');
$router->post('admin/rooms/{id}/update', 'Admin\\RoomController@update');
$router->get('admin/rooms/{id}/delete', 'Admin\\RoomController@delete');
$router->get('admin/rooms/{id}/toggle', 'Admin\\RoomController@toggleAvailability');
$router->get('admin/rooms/{id}/check', 'Admin\\RoomController@checkAvailability');

// ============================================
// ADMIN - Bookings Management
// ============================================

$router->get('admin/bookings', 'Admin\\BookingController@index');
$router->get('admin/bookings/create', 'Admin\\BookingController@create');
$router->post('admin/bookings/store', 'Admin\\BookingController@store');
$router->get('admin/bookings/export', 'Admin\\BookingController@export');
$router->get('admin/bookings/today-checkins', 'Admin\\BookingController@todayCheckIns');
$router->get('admin/bookings/today-checkouts', 'Admin\\BookingController@todayCheckOuts');
// Routes dengan parameter harus di bawah
$router->get('admin/bookings/{id}', 'Admin\\BookingController@detail');
$router->post('admin/bookings/{id}/status', 'Admin\\BookingController@updateStatus');
$router->get('admin/bookings/{id}/confirm', 'Admin\\BookingController@confirm');
$router->get('admin/bookings/{id}/checkin', 'Admin\\BookingController@checkIn');
$router->get('admin/bookings/{id}/checkout', 'Admin\\BookingController@checkOut');
$router->get('admin/bookings/{id}/cancel', 'Admin\\BookingController@cancel');
$router->get('admin/bookings/{id}/delete', 'Admin\\BookingController@delete');
$router->get('admin/bookings/{id}/invoice', 'Admin\\BookingController@invoice');

return $router;