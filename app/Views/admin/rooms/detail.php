<?php
/**
 * Admin Room Detail View
 * 
 * Variables:
 * - $room: object room
 * - $bookings: array booking history
 * - $roomStats: array statistik room
 */

// Helper functions
function formatPrice($price) {
    return 'Rp ' . number_format($price, 0, ',', '.');
}

function formatDate($date) {
    return date('d M Y', strtotime($date));
}

function getStatusBadge($status) {
    $badges = [
        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Pending'],
        'confirmed' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Confirmed'],
        'checked_in' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Check-in'],
        'checked_out' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-800', 'label' => 'Check-out'],
        'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Cancelled'],
    ];
    $badge = $badges[$status] ?? ['bg' => 'bg-slate-100', 'text' => 'text-slate-800', 'label' => ucfirst($status)];
    return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ' . $badge['bg'] . ' ' . $badge['text'] . '">' . $badge['label'] . '</span>';
}

function getTypeBadge($type) {
    $colors = [
        'standard' => 'bg-blue-100 text-blue-800',
        'deluxe' => 'bg-purple-100 text-purple-800',
        'suite' => 'bg-amber-100 text-amber-800',
    ];
    return $colors[$type] ?? 'bg-slate-100 text-slate-800';
}

// Room image
$roomImage = !empty($room->image) 
    ? STORAGE_URL . str_replace('storage/', '', $room->image)
    : 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&q=80&w=800';
?>

<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div class="flex items-center gap-4">
        <a href="<?= BASE_URL ?>admin/rooms" 
           class="w-10 h-10 flex items-center justify-center rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Kamar <?= htmlspecialchars($room->room_number) ?></h1>
            <p class="text-slate-500 text-sm mt-0.5">Detail informasi kamar</p>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <a href="<?= BASE_URL ?>admin/rooms/<?= $room->id ?>/edit" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fas fa-edit"></i>
            <span>Edit</span>
        </a>
        <form action="<?= BASE_URL ?>admin/rooms/<?= $room->id ?>/delete" method="POST" class="inline"
              onsubmit="return confirm('Yakin ingin menghapus kamar ini?')">
            <button type="submit" 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-trash"></i>
                <span>Hapus</span>
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column - Room Info -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Room Image & Basic Info -->
        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
            <div class="relative">
                <img src="<?= $roomImage ?>" 
                     alt="<?= htmlspecialchars($room->room_number) ?>"
                     class="w-full h-64 object-cover">
                <div class="absolute top-4 left-4 flex items-center gap-2">
                    <span class="<?= getTypeBadge($room->room_type) ?> px-3 py-1 rounded-lg text-sm font-semibold">
                        <?= ucfirst($room->room_type) ?>
                    </span>
                    <?php if ($room->is_available): ?>
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-lg text-sm font-medium">
                            <i class="fas fa-check-circle mr-1"></i> Tersedia
                        </span>
                    <?php else: ?>
                        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-lg text-sm font-medium">
                            <i class="fas fa-times-circle mr-1"></i> Tidak Tersedia
                        </span>
                    <?php endif; ?>
                </div>
                <div class="absolute top-4 right-4 bg-black/70 text-white px-3 py-1 rounded-lg font-bold">
                    No. <?= htmlspecialchars($room->room_number) ?>
                </div>
            </div>
            
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Kamar <?= htmlspecialchars($room->room_number) ?></h2>
                        <p class="text-slate-500 text-sm capitalize"><?= $room->room_type ?> Room</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-slate-500">Harga per malam</p>
                        <p class="text-2xl font-bold text-blue-600"><?= formatPrice($room->price_per_night) ?></p>
                    </div>
                </div>
                
                <?php if (!empty($room->description)): ?>
                <div class="pt-4 border-t border-slate-100">
                    <h3 class="text-sm font-semibold text-slate-700 mb-2">Deskripsi</h3>
                    <p class="text-slate-600 text-sm leading-relaxed"><?= nl2br(htmlspecialchars($room->description)) ?></p>
                </div>
                <?php endif; ?>
                
                <div class="pt-4 border-t border-slate-100 mt-4">
                    <h3 class="text-sm font-semibold text-slate-700 mb-3">Informasi</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-calendar-plus text-slate-400 w-5"></i>
                            <span class="text-slate-600">Dibuat: <?= formatDate($room->created_at) ?></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-calendar-check text-slate-400 w-5"></i>
                            <span class="text-slate-600">Update: <?= formatDate($room->updated_at ?? $room->created_at) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Booking History -->
        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <h3 class="font-semibold text-slate-800">
                    <i class="fas fa-history text-slate-400 mr-2"></i>
                    Riwayat Booking
                </h3>
                <span class="text-sm text-slate-500"><?= count($bookings ?? []) ?> total</span>
            </div>
            
            <?php if (empty($bookings)): ?>
                <div class="p-8 text-center">
                    <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-calendar-times text-slate-400"></i>
                    </div>
                    <p class="text-slate-500 text-sm">Belum ada riwayat booking</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">ID</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Tamu</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Check-in</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Check-out</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Total</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php foreach ($bookings as $booking): ?>
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 text-sm">
                                    <a href="<?= BASE_URL ?>admin/bookings/<?= $booking->id ?>" class="text-blue-600 hover:underline font-medium">
                                        #<?= $booking->id ?>
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-700">
                                    <?= htmlspecialchars($booking->guest_name ?? 'User #' . $booking->user_id) ?>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600"><?= formatDate($booking->check_in_date) ?></td>
                                <td class="px-4 py-3 text-sm text-slate-600"><?= formatDate($booking->check_out_date) ?></td>
                                <td class="px-4 py-3 text-sm font-medium text-slate-800"><?= formatPrice($booking->total_price) ?></td>
                                <td class="px-4 py-3"><?= getStatusBadge($booking->status) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Right Column - Stats & Actions -->
    <div class="space-y-6">
        
        <!-- Statistics -->
        <div class="bg-white border border-slate-200 rounded-xl p-6">
            <h3 class="font-semibold text-slate-800 mb-4">
                <i class="fas fa-chart-bar text-slate-400 mr-2"></i>
                Statistik Kamar
            </h3>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <span class="text-sm text-slate-700">Total Booking</span>
                    </div>
                    <span class="text-xl font-bold text-blue-600"><?= $roomStats->total_bookings ?? 0 ?></span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 text-green-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-moon"></i>
                        </div>
                        <span class="text-sm text-slate-700">Total Malam</span>
                    </div>
                    <span class="text-xl font-bold text-green-600"><?= $roomStats->total_nights ?? 0 ?></span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-amber-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-coins"></i>
                        </div>
                        <span class="text-sm text-slate-700">Total Pendapatan</span>
                    </div>
                    <span class="text-lg font-bold text-amber-600"><?= formatPrice($roomStats->total_revenue ?? 0) ?></span>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="bg-white border border-slate-200 rounded-xl p-6">
            <h3 class="font-semibold text-slate-800 mb-4">
                <i class="fas fa-bolt text-slate-400 mr-2"></i>
                Aksi Cepat
            </h3>
            
            <div class="space-y-3">
                <!-- Toggle Availability -->
                <form action="<?= BASE_URL ?>admin/rooms/<?= $room->id ?>/toggle" method="POST">
                    <button type="submit" 
                            class="w-full flex items-center gap-3 p-3 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors text-left">
                        <?php if ($room->is_available): ?>
                            <div class="w-10 h-10 bg-red-100 text-red-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-ban"></i>
                            </div>
                            <div>
                                <p class="font-medium text-slate-800">Set Tidak Tersedia</p>
                                <p class="text-xs text-slate-500">Nonaktifkan kamar sementara</p>
                            </div>
                        <?php else: ?>
                            <div class="w-10 h-10 bg-green-100 text-green-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <p class="font-medium text-slate-800">Set Tersedia</p>
                                <p class="text-xs text-slate-500">Aktifkan kamar untuk booking</p>
                            </div>
                        <?php endif; ?>
                    </button>
                </form>
                
                <!-- Edit -->
                <a href="<?= BASE_URL ?>admin/rooms/<?= $room->id ?>/edit" 
                   class="flex items-center gap-3 p-3 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors">
                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div>
                        <p class="font-medium text-slate-800">Edit Kamar</p>
                        <p class="text-xs text-slate-500">Ubah informasi kamar</p>
                    </div>
                </a>
                
                <!-- View Public Page -->
                <a href="<?= BASE_URL ?>room/detail/<?= $room->id ?>" 
                   target="_blank"
                   class="flex items-center gap-3 p-3 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors">
                    <div class="w-10 h-10 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-external-link-alt"></i>
                    </div>
                    <div>
                        <p class="font-medium text-slate-800">Lihat Halaman Publik</p>
                        <p class="text-xs text-slate-500">Buka di tab baru</p>
                    </div>
                </a>
            </div>
        </div>
        
        <!-- Room Info Card -->
        <div class="bg-slate-800 text-white rounded-xl p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-white/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-door-open text-xl"></i>
                </div>
                <div>
                    <p class="font-bold text-lg"><?= htmlspecialchars($room->room_number) ?></p>
                    <p class="text-slate-400 text-sm capitalize"><?= $room->room_type ?></p>
                </div>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-400">Harga</span>
                    <span class="font-medium"><?= formatPrice($room->price_per_night) ?>/malam</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Status</span>
                    <span class="font-medium"><?= $room->is_available ? '✓ Tersedia' : '✗ Tidak Tersedia' ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">ID</span>
                    <span class="font-medium">#<?= $room->id ?></span>
                </div>
            </div>
        </div>
        
    </div>
</div>