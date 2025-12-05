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
$router->post('logout', 'AuthController@logout');  // ⚠️ Ubah GET ke POST
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
$router->post('booking/cancel/{id}', 'BookingController@cancel');  // ⚠️ Ubah GET ke POST
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

$router->get('admin/users', 'Admin\\AdminUserController@index');
$router->get('admin/users/create', 'Admin\\AdminUserController@create');
$router->post('admin/users/store', 'Admin\\AdminUserController@store');
$router->get('admin/users/export', 'Admin\\AdminUserController@export');
$router->get('admin/users/stats', 'Admin\\AdminUserController@stats');
$router->post('admin/users/bulk-action', 'Admin\\AdminUserController@bulkAction');
$router->get('admin/users/password-requests', 'Admin\\AdminUserController@passwordRequests');
$router->post('admin/users/password-requests/{id}/approve', 'Admin\\AdminUserController@approvePasswordRequest');
$router->post('admin/users/password-requests/{id}/reject', 'Admin\\AdminUserController@rejectPasswordRequest');

// Specific {id} routes
$router->get('admin/users/{id}/edit', 'Admin\\AdminUserController@edit');
$router->post('admin/users/{id}/update', 'Admin\\AdminUserController@update');
$router->post('admin/users/{id}/reset-password', 'Admin\\AdminUserController@resetPassword');
$router->post('admin/users/{id}/delete', 'Admin\\AdminUserController@delete');
$router->post('admin/users/{id}/toggle-role', 'Admin\\AdminUserController@toggleRole');

// Generic {id} LAST
$router->get('admin/users/{id}', 'Admin\\AdminUserController@detail');

// ============================================
// ADMIN - Rooms Management
// ============================================

$router->get('admin/rooms', 'Admin\\AdminRoomController@index');
$router->get('admin/rooms/create', 'Admin\\AdminRoomController@create');
$router->post('admin/rooms/store', 'Admin\\AdminRoomController@store');
$router->get('admin/rooms/stats', 'Admin\\AdminRoomController@stats');
$router->post('admin/rooms/bulk-update', 'Admin\\AdminRoomController@bulkUpdate');

// Specific {id} routes
$router->get('admin/rooms/{id}/edit', 'Admin\\AdminRoomController@edit');
$router->post('admin/rooms/{id}/update', 'Admin\\AdminRoomController@update');
$router->post('admin/rooms/{id}/delete', 'Admin\\AdminRoomController@delete');
$router->post('admin/rooms/{id}/toggle', 'Admin\\AdminRoomController@toggleAvailability');
$router->get('admin/rooms/{id}/check', 'Admin\\AdminRoomController@checkAvailability');

// Generic {id} LAST
$router->get('admin/rooms/{id}', 'Admin\\AdminRoomController@detail');

// ============================================
// ADMIN - Bookings Management
// ============================================

$router->get('admin/bookings', 'Admin\\AdminBookingController@index');
$router->get('admin/bookings/create', 'Admin\\AdminBookingController@create');
$router->post('admin/bookings/store', 'Admin\\AdminBookingController@store');
$router->get('admin/bookings/export', 'Admin\\AdminBookingController@export');
$router->get('admin/bookings/today-checkins', 'Admin\\AdminBookingController@todayCheckIns');
$router->get('admin/bookings/today-checkouts', 'Admin\\AdminBookingController@todayCheckOuts');

// Specific routes BEFORE generic {id} route - All POST for actions
$router->post('admin/bookings/{id}/status', 'Admin\\AdminBookingController@updateStatus');
$router->post('admin/bookings/{id}/confirm', 'Admin\\AdminBookingController@confirm');
$router->post('admin/bookings/{id}/checkin', 'Admin\\AdminBookingController@checkIn');
$router->post('admin/bookings/{id}/checkout', 'Admin\\AdminBookingController@checkOut');
$router->post('admin/bookings/{id}/cancel', 'Admin\\AdminBookingController@cancel');
$router->post('admin/bookings/{id}/delete', 'Admin\\AdminBookingController@delete');
$router->get('admin/bookings/{id}/invoice', 'Admin\\AdminBookingController@invoice');

// Generic {id} route LAST
$router->get('admin/bookings/{id}', 'Admin\\AdminBookingController@detail');

return $router;