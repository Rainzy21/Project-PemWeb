<?php
// Determine if this is check-in or check-out page based on the URL or a passed variable
$isCheckIn = strpos($_SERVER['REQUEST_URI'], 'check-in') !== false || strpos($_SERVER['REQUEST_URI'], 'checkin') !== false;
$pageType = $isCheckIn ? 'check-in' : 'check-out';
$pageTitle = $isCheckIn ? 'Check-in Hari Ini' : 'Check-out Hari Ini';
$iconClass = $isCheckIn ? 'fa-sign-in-alt' : 'fa-sign-out-alt';
$colorClass = $isCheckIn ? 'blue' : 'orange';
$actionText = $isCheckIn ? 'Check In' : 'Check Out';
$actionUrl = $isCheckIn ? 'check-in' : 'check-out';
$waitingStatus = $isCheckIn ? 'confirmed' : 'checked_in';
$doneStatus = $isCheckIn ? 'checked_in' : 'checked_out';
$doneText = $isCheckIn ? 'Sudah Check-in' : 'Sudah Check-out';
$waitingText = $isCheckIn ? 'Menunggu' : 'Checked In';
$dateColumn = $isCheckIn ? 'check_out_date' : 'check_in_date';
$dateLabel = $isCheckIn ? 'Check-out' : 'Check-in';
?>
<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">
            <a href="<?= BASE_URL ?>admin/bookings" class="hover:text-blue-500">Bookings</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-slate-700"><?= $pageTitle ?></span>
        </div>
        <h1 class="text-2xl font-bold text-slate-800"><?= $pageTitle ?></h1>
        <p class="text-slate-500 text-sm mt-1">
            <i class="fas fa-calendar-day mr-1"></i>
            <?= $date ?>
        </p>
    </div>
    <div class="flex items-center gap-2">
        <!-- Toggle Button -->
        <?php if ($isCheckIn): ?>
        <a href="<?= BASE_URL ?>admin/bookings/today-checkouts" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fas fa-sign-out-alt"></i>
            <span>Lihat Check-out</span>
        </a>
        <?php else: ?>
        <a href="<?= BASE_URL ?>admin/bookings/today-checkins" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fas fa-sign-in-alt"></i>
            <span>Lihat Check-in</span>
        </a>
        <?php endif; ?>
        
        <a href="<?= BASE_URL ?>admin/bookings" 
           class="inline-flex items-center gap-2 px-4 py-2 border border-slate-300 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-100 transition-colors">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>
</div>

<!-- Info Card -->
<div class="bg-<?= $colorClass ?>-50 border border-<?= $colorClass ?>-200 rounded-xl p-4 mb-6">
    <div class="flex items-center gap-3">
        <div class="w-12 h-12 bg-<?= $colorClass ?>-100 text-<?= $colorClass ?>-600 rounded-xl flex items-center justify-center">
            <i class="fas <?= $iconClass ?> text-xl"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-<?= $colorClass ?>-700"><?= count($bookings) ?></p>
            <p class="text-sm text-<?= $colorClass ?>-600">Tamu yang dijadwalkan <?= $pageType ?> hari ini</p>
        </div>
    </div>
</div>

<!-- Bookings List -->
<div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
    <?php if (empty($bookings)): ?>
    <div class="px-4 py-12 text-center">
        <div class="flex flex-col items-center">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-calendar-check text-2xl text-slate-400"></i>
            </div>
            <h3 class="text-slate-600 font-medium mb-1">Tidak ada <?= $pageType ?> hari ini</h3>
            <p class="text-slate-400 text-sm">Belum ada tamu yang dijadwalkan <?= $pageType ?> pada <?= $date ?></p>
        </div>
    </div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Booking</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Tamu</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Kamar</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider"><?= $dateLabel ?></th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php foreach ($bookings as $booking): ?>
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-4 py-3">
                        <a href="<?= BASE_URL ?>admin/bookings/<?= $booking->id ?>" class="font-medium text-blue-600 hover:text-blue-800">
                            #<?= $booking->id ?>
                        </a>
                    </td>
                    <td class="px-4 py-3">
                        <p class="font-medium text-slate-800"><?= htmlspecialchars($booking->guest_name) ?></p>
                    </td>
                    <td class="px-4 py-3">
                        <span class="font-medium text-slate-800"><?= htmlspecialchars($booking->room_number) ?></span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-slate-600">
                            <?= date('d M Y', strtotime($isCheckIn ? $booking->check_out_date : $booking->check_in_date)) ?>
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <?php if ($booking->status === $doneStatus): ?>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-<?= $isCheckIn ? 'green' : 'purple' ?>-100 text-<?= $isCheckIn ? 'green' : 'purple' ?>-700">
                            <i class="fas fa-check-circle text-[10px]"></i>
                            <?= $doneText ?>
                        </span>
                        <?php else: ?>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-<?= $isCheckIn ? 'yellow' : 'green' ?>-100 text-<?= $isCheckIn ? 'yellow' : 'green' ?>-700">
                            <i class="fas fa-<?= $isCheckIn ? 'clock' : 'bed' ?> text-[10px]"></i>
                            <?= $waitingText ?>
                        </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1">
                            <!-- Detail -->
                            <a href="<?= BASE_URL ?>admin/bookings/<?= $booking->id ?>" 
                               class="p-2 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                               title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            <?php if ($booking->status === $waitingStatus): ?>
                            <!-- Action Button -->
                            <form action="<?= BASE_URL ?>admin/bookings/<?= $booking->id ?>/<?= $actionUrl ?>" method="POST" class="inline">
                                <button type="submit" 
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-<?= $colorClass ?>-500 hover:bg-<?= $colorClass ?>-600 text-white text-sm font-medium rounded-lg transition-colors"
                                        onclick="return confirm('<?= $actionText ?> tamu ini?')">
                                    <i class="fas <?= $iconClass ?>"></i>
                                    <span><?= $actionText ?></span>
                                </button>
                            </form>
                            <?php endif; ?>
                            
                            <?php if (!$isCheckIn): ?>
                            <!-- Invoice (only for check-out) -->
                            <a href="<?= BASE_URL ?>admin/bookings/<?= $booking->id ?>/invoice" 
                               class="p-2 text-slate-500 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors"
                               title="Invoice"
                               target="_blank">
                                <i class="fas fa-file-invoice"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Table Footer -->
    <div class="px-4 py-3 bg-slate-50 border-t border-slate-200">
        <p class="text-sm text-slate-600">
            Total <span class="font-medium"><?= count($bookings) ?></span> <?= $pageType ?> hari ini
        </p>
    </div>
    <?php endif; ?>
</div>