<?php
?>
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <!-- Total Users -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-wider text-blue-100 mb-1">Total Users</p>
                <h2 class="text-3xl font-bold"><?= $stats['totalUsers'] ?? 0 ?></h2>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Rooms -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-wider text-green-100 mb-1">Total Rooms</p>
                <h2 class="text-3xl font-bold"><?= $stats['totalRooms'] ?? 0 ?></h2>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-door-open text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Bookings -->
    <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 text-white rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-wider text-cyan-100 mb-1">Total Bookings</p>
                <h2 class="text-3xl font-bold"><?= $stats['totalBookings'] ?? 0 ?></h2>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-calendar-check text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Revenue -->
    <div class="bg-gradient-to-br from-amber-500 to-amber-600 text-white rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-wider text-amber-100 mb-1">Revenue</p>
                <h2 class="text-2xl font-bold">Rp <?= number_format($stats['totalRevenue'] ?? 0, 0, ',', '.') ?></h2>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-money-bill-wave text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Today's Activity -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Today's Check-ins -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-sign-in-alt text-blue-500"></i>
            </div>
            <h5 class="font-semibold text-slate-800">Today's Check-ins</h5>
        </div>
        <div class="p-6">
            <?php if (empty($todayCheckIns)): ?>
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-inbox text-slate-400 text-2xl"></i>
                    </div>
                    <p class="text-slate-500 text-sm">No check-ins today</p>
                </div>
            <?php else: ?>
                <ul class="divide-y divide-slate-100">
                    <?php foreach ($todayCheckIns as $checkIn): ?>
                    <li class="py-3 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center text-sm font-medium text-slate-600">
                                <?= strtoupper(substr($checkIn->guest_name ?? $checkIn['guest_name'] ?? 'G', 0, 1)) ?>
                            </div>
                            <span class="text-slate-700 font-medium"><?= htmlspecialchars($checkIn->guest_name ?? $checkIn['guest_name'] ?? '') ?></span>
                        </div>
                        <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full">
                            Room <?= $checkIn->room_number ?? $checkIn['room_number'] ?? '' ?>
                        </span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <!-- Today's Check-outs -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
            <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-sign-out-alt text-slate-500"></i>
            </div>
            <h5 class="font-semibold text-slate-800">Today's Check-outs</h5>
        </div>
        <div class="p-6">
            <?php if (empty($todayCheckOuts)): ?>
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-inbox text-slate-400 text-2xl"></i>
                    </div>
                    <p class="text-slate-500 text-sm">No check-outs today</p>
                </div>
            <?php else: ?>
                <ul class="divide-y divide-slate-100">
                    <?php foreach ($todayCheckOuts as $checkOut): ?>
                    <li class="py-3 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center text-sm font-medium text-slate-600">
                                <?= strtoupper(substr($checkOut->guest_name ?? $checkOut['guest_name'] ?? 'G', 0, 1)) ?>
                            </div>
                            <span class="text-slate-700 font-medium"><?= htmlspecialchars($checkOut->guest_name ?? $checkOut['guest_name'] ?? '') ?></span>
                        </div>
                        <span class="bg-slate-100 text-slate-600 text-xs font-semibold px-3 py-1 rounded-full">
                            Room <?= $checkOut->room_number ?? $checkOut['room_number'] ?? '' ?>
                        </span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Recent Bookings -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-calendar-check text-indigo-500"></i>
            </div>
            <h5 class="font-semibold text-slate-800">Recent Bookings</h5>
        </div>
        <a href="<?= BASE_URL ?>admin/bookings" class="text-sm text-blue-500 hover:text-blue-600 font-medium">
            View All <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Guest</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Room</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Check In</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Check Out</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($recentBookings)): ?>
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-calendar-times text-slate-400 text-2xl"></i>
                        </div>
                        <p class="text-slate-500">No bookings found</p>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($recentBookings as $booking): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-slate-200 rounded-full flex items-center justify-center text-sm font-medium text-slate-600">
                                    <?= strtoupper(substr($booking->guest_name ?? $booking['guest_name'] ?? 'G', 0, 1)) ?>
                                </div>
                                <span class="font-medium text-slate-700"><?= htmlspecialchars($booking->guest_name ?? $booking['guest_name'] ?? '') ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            <?= $booking->room_number ?? $booking['room_number'] ?? '' ?>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            <?= date('d M Y', strtotime($booking->check_in_date ?? $booking['check_in_date'])) ?>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            <?= date('d M Y', strtotime($booking->check_out_date ?? $booking['check_out_date'])) ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php 
                            $status = $booking->status ?? $booking['status'] ?? '';
                            $statusClasses = match($status) {
                                'confirmed' => 'bg-green-100 text-green-700',
                                'pending' => 'bg-amber-100 text-amber-700',
                                'checked_in' => 'bg-blue-100 text-blue-700',
                                'checked_out' => 'bg-slate-100 text-slate-600',
                                'cancelled' => 'bg-red-100 text-red-700',
                                default => 'bg-slate-100 text-slate-600'
                            };
                            ?>
                            <span class="<?= $statusClasses ?> text-xs font-semibold px-2.5 py-1 rounded-full">
                                <?= ucfirst(str_replace('_', ' ', $status)) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>