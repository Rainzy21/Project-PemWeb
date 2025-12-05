<?php
/**
 * Booking Detail View
 * 
 * Variables:
 * - $booking: object booking with room and user details
 */

// Helper functions
function formatPrice($price) {
    return 'Rp ' . number_format($price, 0, ',', '.');
}

function formatDate($date) {
    return date('d M Y', strtotime($date));
}

function formatDateTime($date) {
    return date('d M Y, H:i', strtotime($date));
}

function getStatusBadge($status) {
    $badges = [
        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'fa-clock', 'label' => 'Menunggu Konfirmasi'],
        'confirmed' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => 'fa-check-circle', 'label' => 'Dikonfirmasi'],
        'checked_in' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'fa-sign-in-alt', 'label' => 'Sedang Menginap'],
        'checked_out' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-800', 'icon' => 'fa-sign-out-alt', 'label' => 'Selesai'],
        'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'fa-times-circle', 'label' => 'Dibatalkan'],
    ];
    return $badges[$status] ?? ['bg' => 'bg-slate-100', 'text' => 'text-slate-800', 'icon' => 'fa-question-circle', 'label' => ucfirst($status)];
}

function getTypeBadge($type) {
    $colors = [
        'standard' => 'bg-blue-100 text-blue-800',
        'deluxe' => 'bg-purple-100 text-purple-800',
        'suite' => 'bg-amber-100 text-amber-800',
    ];
    return $colors[$type] ?? 'bg-slate-100 text-slate-800';
}

// Calculate nights
$checkIn = new DateTime($booking->check_in_date);
$checkOut = new DateTime($booking->check_out_date);
$nights = $checkIn->diff($checkOut)->days;

// Status info
$statusInfo = getStatusBadge($booking->status);
$isCancellable = in_array($booking->status, ['pending', 'confirmed']);
?>

<!-- Page Header -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-8">
    <div class="container mx-auto px-4">
        <div class="flex items-center gap-4 mb-4">
            <a href="<?= BASE_URL ?>my-bookings" 
               class="w-10 h-10 flex items-center justify-center rounded-lg bg-white/20 hover:bg-white/30 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">Detail Booking #<?= $booking->id ?></h1>
                <p class="text-blue-100 mt-1">Dibuat pada <?= formatDateTime($booking->created_at) ?></p>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column - Booking Details -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Status Card -->
            <div class="bg-white border border-slate-200 rounded-xl p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 <?= $statusInfo['bg'] ?> <?= $statusInfo['text'] ?> rounded-xl flex items-center justify-center">
                            <i class="fas <?= $statusInfo['icon'] ?> text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500">Status Booking</p>
                            <p class="text-xl font-bold <?= $statusInfo['text'] ?>"><?= $statusInfo['label'] ?></p>
                        </div>
                    </div>
                    
                    <?php if ($booking->status === 'pending'): ?>
                    <div class="flex items-center gap-2 text-yellow-700 bg-yellow-50 px-4 py-2 rounded-lg">
                        <i class="fas fa-info-circle"></i>
                        <span class="text-sm">Menunggu konfirmasi dari admin</span>
                    </div>
                    <?php elseif ($booking->status === 'confirmed'): ?>
                    <div class="flex items-center gap-2 text-blue-700 bg-blue-50 px-4 py-2 rounded-lg">
                        <i class="fas fa-info-circle"></i>
                        <span class="text-sm">Silakan check-in pada tanggal yang ditentukan</span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Room Info -->
            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="font-semibold text-slate-800">
                        <i class="fas fa-door-open text-slate-400 mr-2"></i>
                        Informasi Kamar
                    </h2>
                </div>
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row gap-6">
                        <div class="flex-1">
                            <div class="flex items-start gap-4">
                                <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-bed text-2xl"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="text-lg font-bold text-slate-800">Kamar <?= htmlspecialchars($booking->room_number) ?></h3>
                                        <span class="<?= getTypeBadge($booking->room_type) ?> px-2 py-0.5 rounded text-xs font-medium capitalize">
                                            <?= $booking->room_type ?>
                                        </span>
                                    </div>
                                    <p class="text-slate-500 text-sm mb-3"><?= htmlspecialchars($booking->room_description ?? 'Kamar nyaman dengan fasilitas lengkap') ?></p>
                                    <p class="text-blue-600 font-semibold"><?= formatPrice($booking->price_per_night) ?> <span class="text-slate-500 font-normal">/ malam</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="sm:border-l sm:border-slate-200 sm:pl-6">
                            <a href="<?= BASE_URL ?>room/detail/<?= $booking->room_id ?>" 
                               class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 text-sm font-medium">
                                <i class="fas fa-external-link-alt"></i>
                                Lihat Detail Kamar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Stay Details -->
            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="font-semibold text-slate-800">
                        <i class="fas fa-calendar-alt text-slate-400 mr-2"></i>
                        Detail Menginap
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <!-- Check-in -->
                        <div class="text-center p-4 bg-green-50 rounded-xl">
                            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-sign-in-alt"></i>
                            </div>
                            <p class="text-sm text-slate-500 mb-1">Check-in</p>
                            <p class="font-bold text-slate-800"><?= formatDate($booking->check_in_date) ?></p>
                            <p class="text-xs text-slate-500 mt-1">Mulai 14:00 WIB</p>
                        </div>
                        
                        <!-- Duration -->
                        <div class="text-center p-4 bg-blue-50 rounded-xl">
                            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-moon"></i>
                            </div>
                            <p class="text-sm text-slate-500 mb-1">Durasi</p>
                            <p class="font-bold text-slate-800"><?= $nights ?> Malam</p>
                            <p class="text-xs text-slate-500 mt-1"><?= $nights + 1 ?> Hari</p>
                        </div>
                        
                        <!-- Check-out -->
                        <div class="text-center p-4 bg-red-50 rounded-xl">
                            <div class="w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-sign-out-alt"></i>
                            </div>
                            <p class="text-sm text-slate-500 mb-1">Check-out</p>
                            <p class="font-bold text-slate-800"><?= formatDate($booking->check_out_date) ?></p>
                            <p class="text-xs text-slate-500 mt-1">Sebelum 12:00 WIB</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Guest Info -->
            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="font-semibold text-slate-800">
                        <i class="fas fa-user text-slate-400 mr-2"></i>
                        Data Tamu
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div>
                            <p class="text-sm text-slate-500 mb-1">Nama Lengkap</p>
                            <p class="font-medium text-slate-800"><?= htmlspecialchars($booking->guest_name) ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500 mb-1">Email</p>
                            <p class="font-medium text-slate-800"><?= htmlspecialchars($booking->guest_email) ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500 mb-1">Telepon</p>
                            <p class="font-medium text-slate-800"><?= htmlspecialchars($booking->guest_phone ?? '-') ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        
        <!-- Right Column - Summary & Actions -->
        <div class="space-y-6">
            
            <!-- Payment Summary -->
            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="font-semibold text-slate-800">
                        <i class="fas fa-receipt text-slate-400 mr-2"></i>
                        Ringkasan Pembayaran
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-600">Harga per malam</span>
                            <span class="text-slate-800"><?= formatPrice($booking->price_per_night) ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Jumlah malam</span>
                            <span class="text-slate-800">× <?= $nights ?></span>
                        </div>
                        <div class="border-t border-slate-200 pt-3 mt-3">
                            <div class="flex justify-between">
                                <span class="text-slate-600">Subtotal</span>
                                <span class="text-slate-800"><?= formatPrice($booking->price_per_night * $nights) ?></span>
                            </div>
                        </div>
                        <div class="flex justify-between text-green-600">
                            <span>Pajak & Biaya</span>
                            <span>Termasuk</span>
                        </div>
                    </div>
                    
                    <div class="border-t border-slate-200 mt-4 pt-4">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-slate-800">Total Pembayaran</span>
                            <span class="text-2xl font-bold text-blue-600"><?= formatPrice($booking->total_price) ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="bg-white border border-slate-200 rounded-xl p-6">
                <h3 class="font-semibold text-slate-800 mb-4">
                    <i class="fas fa-cog text-slate-400 mr-2"></i>
                    Aksi
                </h3>
                
                <div class="space-y-3">
                    <!-- Invoice -->
                    <a href="<?= BASE_URL ?>booking/invoice/<?= $booking->id ?>" 
                       class="w-full flex items-center gap-3 p-3 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors">
                        <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <div>
                            <p class="font-medium text-slate-800">Lihat Invoice</p>
                            <p class="text-xs text-slate-500">Download atau cetak invoice</p>
                        </div>
                    </a>
                    
                    <!-- Cancel (if cancellable) -->
                    <?php if ($isCancellable): ?>
                    <a href="<?= BASE_URL ?>booking/cancel/<?= $booking->id ?>" 
                       onclick="return confirm('Yakin ingin membatalkan booking ini?')"
                       class="w-full flex items-center gap-3 p-3 rounded-lg border border-red-200 hover:bg-red-50 transition-colors">
                        <div class="w-10 h-10 bg-red-100 text-red-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div>
                            <p class="font-medium text-red-600">Batalkan Booking</p>
                            <p class="text-xs text-slate-500">Pembatalan tidak dapat dibatalkan</p>
                        </div>
                    </a>
                    <?php endif; ?>
                    
                    <!-- Back to My Bookings -->
                    <a href="<?= BASE_URL ?>my-bookings" 
                       class="w-full flex items-center gap-3 p-3 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors">
                        <div class="w-10 h-10 bg-slate-100 text-slate-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-list"></i>
                        </div>
                        <div>
                            <p class="font-medium text-slate-800">Booking Saya</p>
                            <p class="text-xs text-slate-500">Kembali ke daftar booking</p>
                        </div>
                    </a>
                </div>
            </div>
            
            <!-- Booking ID Card -->
            <div class="bg-slate-800 text-white rounded-xl p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-white/10 rounded-lg flex items-center justify-center">
                        <i class="fas fa-ticket-alt text-xl"></i>
                    </div>
                    <div>
                        <p class="text-slate-400 text-sm">Booking ID</p>
                        <p class="font-bold text-2xl">#<?= $booking->id ?></p>
                    </div>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Kamar</span>
                        <span class="font-medium"><?= htmlspecialchars($booking->room_number) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Tipe</span>
                        <span class="font-medium capitalize"><?= $booking->room_type ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Tamu</span>
                        <span class="font-medium"><?= htmlspecialchars($booking->guest_name) ?></span>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-white/10">
                    <p class="text-xs text-slate-400 text-center">Tunjukkan ID ini saat check-in</p>
                </div>
            </div>
            
            <!-- Help -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <div>
                        <p class="font-medium text-blue-800 mb-1">Butuh Bantuan?</p>
                        <p class="text-sm text-blue-600">Hubungi kami di <strong>+62 21 1234 5678</strong> atau email ke <strong>help@hotel.com</strong></p>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>