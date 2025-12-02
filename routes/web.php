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
$router->get('admin', 'AdminDashboardController@index');
$router->get('admin/dashboard', 'AdminDashboardController@index');
$router->get('admin/analytics', 'AdminDashboardController@analytics');
$router->get('admin/reports', 'AdminDashboardController@reports');
$router->get('admin/reports/export', 'AdminDashboardController@exportReport');
$router->get('admin/settings', 'AdminDashboardController@settings');
$router->get('admin/activity-log', 'AdminDashboardController@activityLog');

// ============================================
// ADMIN - Users Management
// ============================================

$router->get('admin/users', 'AdminUserController@index');
$router->get('admin/users/create', 'AdminUserController@create');
$router->post('admin/users/store', 'AdminUserController@store');
$router->get('admin/users/export', 'AdminUserController@export');
$router->get('admin/users/stats', 'AdminUserController@stats');
$router->post('admin/users/bulk-action', 'AdminUserController@bulkAction');
// Routes dengan parameter harus di bawah
$router->get('admin/users/{id}', 'AdminUserController@detail');
$router->get('admin/users/{id}/edit', 'AdminUserController@edit');
$router->post('admin/users/{id}/update', 'AdminUserController@update');
$router->post('admin/users/{id}/reset-password', 'AdminUserController@resetPassword');
$router->get('admin/users/{id}/delete', 'AdminUserController@delete');
$router->get('admin/users/{id}/toggle-role', 'AdminUserController@toggleRole');

// ============================================
// ADMIN - Rooms Management
// ============================================

$router->get('admin/rooms', 'AdminRoomController@index');
$router->get('admin/rooms/create', 'AdminRoomController@create');
$router->post('admin/rooms/store', 'AdminRoomController@store');
$router->get('admin/rooms/stats', 'AdminRoomController@stats');
$router->post('admin/rooms/bulk-update', 'AdminRoomController@bulkUpdate');
// Routes dengan parameter harus di bawah
$router->get('admin/rooms/{id}', 'AdminRoomController@detail');
$router->get('admin/rooms/{id}/edit', 'AdminRoomController@edit');
$router->post('admin/rooms/{id}/update', 'AdminRoomController@update');
$router->get('admin/rooms/{id}/delete', 'AdminRoomController@delete');
$router->get('admin/rooms/{id}/toggle', 'AdminRoomController@toggleAvailability');
$router->get('admin/rooms/{id}/check', 'AdminRoomController@checkAvailability');

// ============================================
// ADMIN - Bookings Management
// ============================================

$router->get('admin/bookings', 'AdminBookingController@index');
$router->get('admin/bookings/create', 'AdminBookingController@create');
$router->post('admin/bookings/store', 'AdminBookingController@store');
$router->get('admin/bookings/export', 'AdminBookingController@export');
$router->get('admin/bookings/today-checkins', 'AdminBookingController@todayCheckIns');
$router->get('admin/bookings/today-checkouts', 'AdminBookingController@todayCheckOuts');
// Routes dengan parameter harus di bawah
$router->get('admin/bookings/{id}', 'AdminBookingController@detail');
$router->post('admin/bookings/{id}/status', 'AdminBookingController@updateStatus');
$router->get('admin/bookings/{id}/confirm', 'AdminBookingController@confirm');
$router->get('admin/bookings/{id}/checkin', 'AdminBookingController@checkIn');
$router->get('admin/bookings/{id}/checkout', 'AdminBookingController@checkOut');
$router->get('admin/bookings/{id}/cancel', 'AdminBookingController@cancel');
$router->get('admin/bookings/{id}/delete', 'AdminBookingController@delete');
$router->get('admin/bookings/{id}/invoice', 'AdminBookingController@invoice');

return $router;