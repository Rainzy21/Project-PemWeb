<?php
/**
 * My Bookings View
 * Menampilkan daftar booking milik user yang login
 * 
 * Variables:
 * - $bookings: array of booking objects
 */

// Helper function untuk status badge
function getStatusBadge($status) {
    $badges = [
        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Menunggu Konfirmasi'],
        'confirmed' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Dikonfirmasi'],
        'checked_in' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Check-in'],
        'checked_out' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Check-out'],
        'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Dibatalkan'],
    ];
    
    $badge = $badges[$status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($status)];
    
    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $badge['bg'] . ' ' . $badge['text'] . '">' . $badge['label'] . '</span>';
}

// Helper untuk format tanggal
function formatDate($date) {
    return date('d M Y', strtotime($date));
}

// Helper untuk format harga
function formatPrice($price) {
    return 'Rp ' . number_format($price, 0, ',', '.');
}
?>

<!-- Page Header -->
<section class="mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Booking Saya</h1>
            <p class="text-gray-500 mt-1">Kelola semua reservasi kamar Anda</p>
        </div>
        <a href="<?= BASE_URL ?>room" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium transition shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Booking Baru
        </a>
    </div>
</section>

<!-- Stats Summary -->
<?php if (!empty($bookings)): ?>
<section class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <?php
    $stats = [
        'pending' => 0,
        'confirmed' => 0,
        'checked_in' => 0,
        'completed' => 0
    ];
    foreach ($bookings as $b) {
        if ($b->status === 'pending') $stats['pending']++;
        elseif ($b->status === 'confirmed') $stats['confirmed']++;
        elseif ($b->status === 'checked_in') $stats['checked_in']++;
        elseif (in_array($b->status, ['checked_out', 'cancelled'])) $stats['completed']++;
    }
    ?>
    <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-4">
        <div class="text-2xl font-bold text-yellow-600"><?= $stats['pending'] ?></div>
        <div class="text-sm text-yellow-700">Menunggu</div>
    </div>
    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
        <div class="text-2xl font-bold text-blue-600"><?= $stats['confirmed'] ?></div>
        <div class="text-sm text-blue-700">Dikonfirmasi</div>
    </div>
    <div class="bg-green-50 border border-green-100 rounded-xl p-4">
        <div class="text-2xl font-bold text-green-600"><?= $stats['checked_in'] ?></div>
        <div class="text-sm text-green-700">Check-in</div>
    </div>
    <div class="bg-gray-50 border border-gray-100 rounded-xl p-4">
        <div class="text-2xl font-bold text-gray-600"><?= $stats['completed'] ?></div>
        <div class="text-sm text-gray-700">Selesai</div>
    </div>
</section>
<?php endif; ?>

<!-- Bookings List -->
<section class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
    <?php if (empty($bookings)): ?>
        <!-- Empty State -->
        <div class="p-12 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Booking</h3>
            <p class="text-gray-500 mb-6">Anda belum memiliki reservasi kamar. Mulai booking sekarang!</p>
            <a href="<?= BASE_URL ?>room" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Cari Kamar
            </a>
        </div>
    <?php else: ?>
        <!-- Table Header (Desktop) -->
        <div class="hidden md:grid md:grid-cols-12 gap-4 px-6 py-4 bg-gray-50 border-b border-gray-200 text-sm font-semibold text-gray-600">
            <div class="col-span-1">ID</div>
            <div class="col-span-3">Kamar</div>
            <div class="col-span-2">Check-in</div>
            <div class="col-span-2">Check-out</div>
            <div class="col-span-2">Total</div>
            <div class="col-span-1">Status</div>
            <div class="col-span-1 text-right">Aksi</div>
        </div>

        <!-- Booking Items -->
        <?php foreach ($bookings as $booking): ?>
            <div class="border-b border-gray-100 last:border-0 hover:bg-gray-50 transition">
                <!-- Desktop View -->
                <div class="hidden md:grid md:grid-cols-12 gap-4 px-6 py-4 items-center">
                    <div class="col-span-1">
                        <span class="text-sm font-medium text-gray-900">#<?= $booking->id ?></span>
                    </div>
                    <div class="col-span-3">
                        <div class="font-medium text-gray-900">
                            <?= htmlspecialchars($booking->room_number ?? 'Kamar #' . $booking->room_id) ?>
                        </div>
                        <?php if (isset($booking->room_type)): ?>
                            <div class="text-sm text-gray-500 capitalize"><?= htmlspecialchars($booking->room_type) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-span-2">
                        <div class="text-sm text-gray-900"><?= formatDate($booking->check_in_date) ?></div>
                    </div>
                    <div class="col-span-2">
                        <div class="text-sm text-gray-900"><?= formatDate($booking->check_out_date) ?></div>
                    </div>
                    <div class="col-span-2">
                        <div class="font-semibold text-gray-900"><?= formatPrice($booking->total_price) ?></div>
                    </div>
                    <div class="col-span-1">
                        <?= getStatusBadge($booking->status) ?>
                    </div>
                    <div class="col-span-1 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="<?= BASE_URL ?>booking/detail/<?= $booking->id ?>" class="text-blue-600 hover:text-blue-800" title="Detail">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <?php if ($booking->status === 'pending'): ?>
                                <a href="<?= BASE_URL ?>booking/cancel/<?= $booking->id ?>" 
                                   onclick="return confirm('Yakin ingin membatalkan booking ini?')"
                                   class="text-red-600 hover:text-red-800" title="Batalkan">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Mobile View -->
                <div class="md:hidden p-4">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <span class="text-xs text-gray-500">Booking #<?= $booking->id ?></span>
                            <h3 class="font-semibold text-gray-900">
                                <?= htmlspecialchars($booking->room_number ?? 'Kamar #' . $booking->room_id) ?>
                            </h3>
                            <?php if (isset($booking->room_type)): ?>
                                <span class="text-sm text-gray-500 capitalize"><?= htmlspecialchars($booking->room_type) ?></span>
                            <?php endif; ?>
                        </div>
                        <?= getStatusBadge($booking->status) ?>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3 text-sm mb-3">
                        <div>
                            <span class="text-gray-500">Check-in:</span>
                            <div class="font-medium"><?= formatDate($booking->check_in_date) ?></div>
                        </div>
                        <div>
                            <span class="text-gray-500">Check-out:</span>
                            <div class="font-medium"><?= formatDate($booking->check_out_date) ?></div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                        <div class="font-bold text-gray-900"><?= formatPrice($booking->total_price) ?></div>
                        <div class="flex items-center gap-3">
                            <a href="<?= BASE_URL ?>booking/detail/<?= $booking->id ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Detail
                            </a>
                            <?php if ($booking->status === 'pending'): ?>
                                <a href="<?= BASE_URL ?>booking/cancel/<?= $booking->id ?>" 
                                   onclick="return confirm('Yakin ingin membatalkan booking ini?')"
                                   class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    Batalkan
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>

<!-- Info Section -->
<section class="mt-8">
    <div class="bg-blue-50 border border-blue-100 rounded-xl p-6">
        <h3 class="font-semibold text-blue-900 mb-3 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Informasi Booking
        </h3>
        <ul class="text-sm text-blue-800 space-y-2">
            <li>• Booking dengan status <strong>Menunggu</strong> dapat dibatalkan kapan saja</li>
            <li>• Setelah dikonfirmasi, hubungi admin untuk pembatalan</li>
            <li>• Pastikan melakukan check-in sesuai tanggal yang ditentukan</li>
            <li>• Pembayaran dilakukan saat check-in di hotel</li>
        </ul>
    </div>
</section>