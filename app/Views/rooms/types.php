<?php
/**
 * Room Types View
 * 
 * Variables:
 * - $types: array of room types with rooms
 */

// Helper functions
function formatPrice($price) {
    return 'Rp ' . number_format($price, 0, ',', '.');
}

function getTypeIcon($type) {
    $icons = [
        'standard' => 'fa-bed',
        'deluxe' => 'fa-star',
        'suite' => 'fa-crown',
    ];
    return $icons[$type] ?? 'fa-door-open';
}

function getTypeColor($type) {
    $colors = [
        'standard' => ['bg' => 'bg-blue-500', 'light' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-200'],
        'deluxe' => ['bg' => 'bg-purple-500', 'light' => 'bg-purple-50', 'text' => 'text-purple-600', 'border' => 'border-purple-200'],
        'suite' => ['bg' => 'bg-amber-500', 'light' => 'bg-amber-50', 'text' => 'text-amber-600', 'border' => 'border-amber-200'],
    ];
    return $colors[$type] ?? ['bg' => 'bg-slate-500', 'light' => 'bg-slate-50', 'text' => 'text-slate-600', 'border' => 'border-slate-200'];
}

function getTypeFeatures($type) {
    $features = [
        'standard' => ['WiFi Gratis', 'AC', 'TV LED', 'Kamar Mandi Dalam'],
        'deluxe' => ['WiFi Gratis', 'AC', 'TV LED 42"', 'Kamar Mandi Dalam', 'Mini Bar', 'Balkon'],
        'suite' => ['WiFi Gratis', 'AC', 'Smart TV 55"', 'Kamar Mandi Mewah', 'Mini Bar', 'Balkon Luas', 'Ruang Tamu', 'Jacuzzi'],
    ];
    return $features[$type] ?? [];
}
?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Tipe Kamar Kami</h1>
            <p class="text-xl text-blue-100">Temukan kamar yang sesuai dengan kebutuhan dan budget Anda</p>
        </div>
    </div>
</section>

<!-- Room Types -->
<section class="py-16 bg-slate-50">
    <div class="container mx-auto px-4">
        
        <?php foreach ($types as $typeKey => $type): ?>
        <?php 
            $colors = getTypeColor($typeKey);
            $features = getTypeFeatures($typeKey);
            $availableRooms = array_filter($type['rooms'], fn($r) => $r->is_available);
            $minPrice = !empty($type['rooms']) ? min(array_column($type['rooms'], 'price_per_night')) : 0;
            $maxPrice = !empty($type['rooms']) ? max(array_column($type['rooms'], 'price_per_night')) : 0;
        ?>
        
        <div class="mb-16 last:mb-0">
            <!-- Type Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 <?= $colors['bg'] ?> text-white rounded-xl flex items-center justify-center">
                        <i class="fas <?= getTypeIcon($typeKey) ?> text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800"><?= $type['name'] ?> Room</h2>
                        <p class="text-slate-500"><?= $type['description'] ?></p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-sm text-slate-500">Mulai dari</p>
                        <p class="text-2xl font-bold <?= $colors['text'] ?>"><?= formatPrice($minPrice) ?><span class="text-sm font-normal text-slate-500">/malam</span></p>
                    </div>
                    <span class="px-3 py-1 <?= $colors['light'] ?> <?= $colors['text'] ?> rounded-full text-sm font-medium">
                        <?= count($availableRooms) ?> tersedia
                    </span>
                </div>
            </div>
            
            <!-- Features -->
            <div class="bg-white border <?= $colors['border'] ?> rounded-xl p-6 mb-6">
                <h3 class="font-semibold text-slate-700 mb-4">
                    <i class="fas fa-check-circle <?= $colors['text'] ?> mr-2"></i>
                    Fasilitas <?= $type['name'] ?>
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <?php foreach ($features as $feature): ?>
                    <div class="flex items-center gap-2 text-sm text-slate-600">
                        <i class="fas fa-check <?= $colors['text'] ?>"></i>
                        <span><?= $feature ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Rooms Grid -->
            <?php if (empty($type['rooms'])): ?>
                <div class="bg-white border border-slate-200 rounded-xl p-8 text-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-door-closed text-slate-400 text-2xl"></i>
                    </div>
                    <p class="text-slate-500">Belum ada kamar tersedia untuk tipe ini</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($type['rooms'] as $room): ?>
                    <?php 
                        $roomImage = !empty($room->image) 
                            ? STORAGE_URL . str_replace('storage/', '', $room->image)
                            : 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&q=80&w=800';
                    ?>
                    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow group">
                        <div class="relative">
                            <img src="<?= $roomImage ?>" 
                                 alt="<?= htmlspecialchars($room->room_number) ?>"
                                 class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                            
                            <div class="absolute top-3 left-3">
                                <span class="<?= $colors['bg'] ?> text-white px-3 py-1 rounded-lg text-sm font-medium">
                                    No. <?= htmlspecialchars($room->room_number) ?>
                                </span>
                            </div>
                            
                            <?php if (!$room->is_available): ?>
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                                <span class="bg-red-500 text-white px-4 py-2 rounded-lg font-semibold">
                                    Tidak Tersedia
                                </span>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="p-5">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="font-bold text-slate-800">Kamar <?= htmlspecialchars($room->room_number) ?></h3>
                                    <p class="text-sm text-slate-500 capitalize"><?= $room->room_type ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold <?= $colors['text'] ?>"><?= formatPrice($room->price_per_night) ?></p>
                                    <p class="text-xs text-slate-500">/malam</p>
                                </div>
                            </div>
                            
                            <?php if (!empty($room->description)): ?>
                            <p class="text-sm text-slate-600 mb-4 line-clamp-2"><?= htmlspecialchars($room->description) ?></p>
                            <?php endif; ?>
                            
                            <div class="flex gap-2">
                                <a href="<?= BASE_URL ?>room/detail/<?= $room->id ?>" 
                                   class="flex-1 text-center py-2 border <?= $colors['border'] ?> <?= $colors['text'] ?> rounded-lg text-sm font-medium hover:<?= $colors['light'] ?> transition-colors">
                                    Detail
                                </a>
                                <?php if ($room->is_available): ?>
                                <a href="<?= BASE_URL ?>booking/create/<?= $room->id ?>" 
                                   class="flex-1 text-center py-2 <?= $colors['bg'] ?> text-white rounded-lg text-sm font-medium hover:opacity-90 transition-opacity">
                                    Booking
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
        
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-slate-800 mb-4">Butuh Bantuan Memilih?</h2>
            <p class="text-slate-600 mb-8">Tim kami siap membantu Anda menemukan kamar yang sempurna sesuai kebutuhan</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="<?= BASE_URL ?>rooms" 
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-search"></i>
                    Lihat Semua Kamar
                </a>
                <a href="<?= BASE_URL ?>#contact" 
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 border border-slate-300 text-slate-700 font-medium rounded-lg hover:bg-slate-50 transition-colors">
                    <i class="fas fa-phone"></i>
                    Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</section>