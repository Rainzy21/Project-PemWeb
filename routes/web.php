<?php

use Core\Router;

$router = new Router();

// Home
$router->get('', 'Home@index');
$router->get('home', 'Home@index');

// Auth
$router->get('login', 'AuthController@login');
$router->post('login', 'AuthController@doLogin');
$router->get('register', 'AuthController@register');
$router->post('register', 'AuthController@doRegister');
$router->get('logout', 'AuthController@logout');
$router->get('forgot-password', 'AuthController@forgotPassword');
$router->post('forgot-password', 'AuthController@doForgotPassword');
$router->get('profile', 'AuthController@profile');
$router->post('profile/update', 'AuthController@updateProfile');
$router->post('profile/password', 'AuthController@updatePassword');

// Rooms
$router->get('rooms', 'RoomController@index');
$router->get('rooms/search', 'RoomController@search');
$router->get('rooms/types', 'RoomController@types');
$router->get('rooms/filter', 'RoomController@filterByType');
$router->get('rooms/info/{id}', 'RoomController@getInfo');
$router->get('rooms/availability/{id}', 'RoomController@checkAvailability');
$router->get('rooms/{id}', 'RoomController@detail');

// Booking
$router->get('booking/{id}', 'BookingController@create');
$router->post('booking', 'BookingController@store');
$router->get('my-bookings', 'BookingController@myBookings');
$router->get('booking/detail/{id}', 'BookingController@detail');
$router->get('booking/cancel/{id}', 'BookingController@cancel');
$router->post('booking/check-availability', 'BookingController@checkAvailability');

// Dashboard Admin
$router->get('dashboard', 'DashboardController@index');
$router->get('dashboard/users', 'DashboardController@users');
$router->get('dashboard/user/{id}', 'DashboardController@userDetail');
$router->get('dashboard/user/delete/{id}', 'DashboardController@deleteUser');
$router->get('dashboard/rooms', 'DashboardController@rooms');
$router->get('dashboard/rooms/create', 'DashboardController@createRoom');
$router->get('dashboard/rooms/edit/{id}', 'DashboardController@editRoom');
$router->post('dashboard/rooms/store', 'DashboardController@storeRoom');
$router->get('dashboard/rooms/delete/{id}', 'DashboardController@deleteRoom');
$router->get('dashboard/rooms/toggle/{id}', 'DashboardController@toggleRoomAvailability');
$router->get('dashboard/bookings', 'DashboardController@bookings');
$router->get('dashboard/booking/{id}', 'DashboardController@bookingDetail');
$router->post('dashboard/booking/status/{id}', 'DashboardController@updateBookingStatus');
$router->get('dashboard/booking/confirm/{id}', 'DashboardController@confirmBooking');
$router->get('dashboard/booking/checkin/{id}', 'DashboardController@checkInBooking');
$router->get('dashboard/booking/checkout/{id}', 'DashboardController@checkOutBooking');
$router->get('dashboard/booking/cancel/{id}', 'DashboardController@cancelBooking');
$router->get('dashboard/reports', 'DashboardController@reports');

// Admin Dashboard
$router->get('admin', 'Admin\\DashboardController@index');
$router->get('admin/dashboard', 'Admin\\DashboardController@index');
$router->get('admin/analytics', 'Admin\\DashboardController@analytics');
$router->get('admin/reports', 'Admin\\DashboardController@reports');
$router->get('admin/reports/export', 'Admin\\DashboardController@exportReport');
$router->get('admin/settings', 'Admin\\DashboardController@settings');
$router->get('admin/activity-log', 'Admin\\DashboardController@activityLog');

// Admin Bookings
$router->get('admin/bookings', 'Admin\\BookingController@index');
$router->get('admin/bookings/search', 'Admin\\BookingController@search');
$router->get('admin/bookings/types', 'Admin\\BookingController@types');
$router->get('admin/bookings/filter', 'Admin\\BookingController@filterByType');
$router->get('admin/bookings/info/{id}', 'Admin\\BookingController@getInfo');
$router->get('admin/bookings/availability/{id}', 'Admin\\BookingController@checkAvailability');
$router->get('admin/bookings/{id}', 'Admin\\BookingController@detail');
$router->get('admin/bookings/cancel/{id}', 'Admin\\BookingController@cancel');
$router->post('admin/bookings/check-availability', 'Admin\\BookingController@checkAvailability');

return $router;