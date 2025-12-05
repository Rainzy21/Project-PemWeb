<?php
/**
 * Room Detail View
 */

// Helper functions
function formatPrice($price) {
    return 'Rp ' . number_format($price, 0, ',', '.');
}

function getBadgeColor($type) {
    return match($type) {
        'deluxe' => 'bg-purple-600',
        'suite' => 'bg-amber-600',
        default => 'bg-blue-600'
    };
}

function getBadgeColorLight($type) {
    return match($type) {
        'deluxe' => 'bg-purple-100 text-purple-800',
        'suite' => 'bg-amber-100 text-amber-800',
        default => 'bg-blue-100 text-blue-800'
    };
}

// Helper untuk mendapatkan URL gambar room
function getRoomImage($room, $size = '1200') {
    if (!empty($room->image)) {
        // $room->image = "storage/uploads/rooms/xxx.jpg"
        // Hapus "storage/" karena sudah ada di STORAGE_URL
        $imagePath = str_replace('storage/', '', $room->image);
        return STORAGE_URL . $imagePath;
    }
    return "https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&q=80&w={$size}";
}

$roomImage = getRoomImage($room, '1200');
?>


<!-- DEBUG: Lihat URL yang dihasilkan -->
<!-- URL Gambar: <?= $roomImage ?> -->

<!-- Breadcrumb -->
<nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
    <a href="<?= BASE_URL ?>" class="hover:text-blue-600">Beranda</a>
    <span>›</span>
    <a href="<?= BASE_URL ?>room" class="hover:text-blue-600">Kamar</a>
    <span>›</span>
    <span class="text-gray-900"><?= htmlspecialchars($room->room_number) ?></span>
</nav>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Room Image -->
        <div class="relative rounded-2xl overflow-hidden shadow-lg">
            <img src="<?= $roomImage ?>" 
                 alt="<?= htmlspecialchars($room->room_number) ?>" 
                 class="w-full h-[400px] object-cover">
            
            <!-- Badges -->
            <div class="absolute top-4 left-4 flex items-center gap-2">
                <span class="<?= getBadgeColor($room->room_type) ?> text-white text-sm font-semibold px-3 py-1.5 rounded-lg shadow">
                    <?= ucfirst($room->room_type) ?>
                </span>
                <?php if ($room->is_available): ?>
                    <span class="bg-green-500 text-white text-sm font-medium px-3 py-1.5 rounded-lg shadow">
                        Tersedia
                    </span>
                <?php else: ?>
                    <span class="bg-red-500 text-white text-sm font-medium px-3 py-1.5 rounded-lg shadow">
                        Tidak Tersedia
                    </span>
                <?php endif; ?>
            </div>
            
            <div class="absolute top-4 right-4 bg-black/70 backdrop-blur-sm text-white font-bold px-4 py-2 rounded-lg">
                No. <?= htmlspecialchars($room->room_number) ?>
            </div>
        </div>

        <!-- Room Info -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                        Kamar <?= htmlspecialchars($room->room_number) ?>
                    </h1>
                    <div class="flex items-center gap-3">
                        <span class="<?= getBadgeColorLight($room->room_type) ?> text-sm font-medium px-3 py-1 rounded-full">
                            <?= ucfirst($room->room_type) ?> Room
                        </span>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-sm text-gray-500">Harga per malam</span>
                    <div class="text-2xl md:text-3xl font-bold text-blue-600">
                        <?= formatPrice($room->price_per_night) ?>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Deskripsi</h3>
                <p class="text-gray-600 leading-relaxed">
                    <?= nl2br(htmlspecialchars($room->description ?? 'Kamar nyaman dengan fasilitas lengkap untuk kenyamanan Anda selama menginap. Dilengkapi dengan tempat tidur berkualitas, AC, TV, dan kamar mandi pribadi.')) ?>
                </p>
            </div>

            <!-- Facilities -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Fasilitas Kamar</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    <?php 
                    $facilities = [
                        ['icon' => 'fa-bed', 'name' => 'Tempat Tidur Nyaman'],
                        ['icon' => 'fa-snowflake', 'name' => 'AC'],
                        ['icon' => 'fa-tv', 'name' => 'TV LED'],
                        ['icon' => 'fa-wifi', 'name' => 'WiFi Gratis'],
                        ['icon' => 'fa-shower', 'name' => 'Kamar Mandi Pribadi'],
                        ['icon' => 'fa-coffee', 'name' => 'Coffee Maker'],
                    ];
                    
                    // Tambah fasilitas untuk tipe tertentu
                    if ($room->room_type === 'deluxe' || $room->room_type === 'suite') {
                        $facilities[] = ['icon' => 'fa-couch', 'name' => 'Sofa'];
                        $facilities[] = ['icon' => 'fa-utensils', 'name' => 'Mini Bar'];
                    }
                    if ($room->room_type === 'suite') {
                        $facilities[] = ['icon' => 'fa-hot-tub', 'name' => 'Bathtub'];
                        $facilities[] = ['icon' => 'fa-city', 'name' => 'City View'];
                    }
                    ?>
                    <?php foreach ($facilities as $facility): ?>
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                                <i class="fas <?= $facility['icon'] ?> text-sm"></i>
                            </div>
                            <span class="text-sm text-gray-700"><?= $facility['name'] ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Policies -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Kebijakan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-green-100 text-green-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-clock text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Check-in</p>
                            <p class="text-sm text-gray-500">14:00 - 23:00</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-red-100 text-red-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-sign-out-alt text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Check-out</p>
                            <p class="text-sm text-gray-500">Sebelum 12:00</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user-friends text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Kapasitas</p>
                            <p class="text-sm text-gray-500">
                                <?= $room->room_type === 'suite' ? '4 Tamu' : ($room->room_type === 'deluxe' ? '3 Tamu' : '2 Tamu') ?>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-ban text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Larangan</p>
                            <p class="text-sm text-gray-500">Dilarang merokok</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar - Booking Card -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 sticky top-28">
            <div class="text-center mb-6">
                <span class="text-sm text-gray-500">Harga mulai dari</span>
                <div class="text-3xl font-bold text-blue-600 mt-1">
                    <?= formatPrice($room->price_per_night) ?>
                </div>
                <span class="text-sm text-gray-500">per malam</span>
            </div>

            <!-- Check Availability Form -->
            <form id="checkAvailabilityForm" class="space-y-4 mb-6">
                <input type="hidden" name="room_id" value="<?= $room->id ?>">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Check-in</label>
                    <input type="date" 
                           name="check_in" 
                           id="check_in"
                           min="<?= date('Y-m-d') ?>"
                           required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Check-out</label>
                    <input type="date" 
                           name="check_out" 
                           id="check_out"
                           min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                           required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <button type="button" 
                        onclick="checkAvailability()"
                        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 rounded-lg transition">
                    Cek Ketersediaan
                </button>
            </form>

            <!-- Availability Result -->
            <div id="availabilityResult" class="hidden mb-6">
                <div id="availableMessage" class="hidden bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center gap-2 text-green-700 font-medium mb-2">
                        <i class="fas fa-check-circle"></i>
                        <span>Kamar Tersedia!</span>
                    </div>
                    <div class="text-sm text-green-600 space-y-1">
                        <p><span id="resultNights">0</span> malam</p>
                        <p class="text-lg font-bold" id="resultTotal">Rp 0</p>
                    </div>
                </div>
                <div id="unavailableMessage" class="hidden bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center gap-2 text-red-700 font-medium">
                        <i class="fas fa-times-circle"></i>
                        <span>Kamar tidak tersedia untuk tanggal tersebut</span>
                    </div>
                </div>
            </div>

            <!-- Book Button -->
            <?php if ($room->is_available): ?>
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="<?= BASE_URL ?>booking/create/<?= $room->id ?>" 
                       id="bookNowBtn"
                       class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center font-semibold py-3 rounded-lg transition shadow-lg">
                        Booking Sekarang
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>auth/login" 
                       class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center font-semibold py-3 rounded-lg transition shadow-lg">
                        Login untuk Booking
                    </a>
                <?php endif; ?>
            <?php else: ?>
                <button disabled 
                        class="w-full bg-gray-300 text-gray-500 font-semibold py-3 rounded-lg cursor-not-allowed">
                    Tidak Tersedia
                </button>
            <?php endif; ?>

            <!-- Contact -->
            <div class="mt-6 pt-6 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-500 mb-2">Butuh bantuan?</p>
                <a href="tel:+628123456789" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium">
                    <i class="fas fa-phone"></i>
                    +62 812-3456-789
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Similar Rooms -->
<?php if (!empty($similarRooms)): ?>
<section class="mt-12">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Kamar Serupa</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php foreach ($similarRooms as $similar): ?>
            <?php if ($similar->id !== $room->id): ?>
            <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition overflow-hidden border border-gray-100">
                <div class="relative">
                    <img src="<?= getRoomImage($similar, '400') ?>" 
                         alt="<?= htmlspecialchars($similar->room_number) ?>"
                         class="w-full h-48 object-cover">
                    <span class="absolute top-3 left-3 <?= getBadgeColor($similar->room_type) ?> text-white text-xs font-semibold px-2 py-1 rounded">
                        <?= ucfirst($similar->room_type) ?>
                    </span>
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-gray-900 mb-1"><?= htmlspecialchars($similar->room_number) ?></h3>
                    <p class="text-sm text-gray-500 mb-3 line-clamp-2"><?= htmlspecialchars($similar->description ?? 'Kamar nyaman dengan fasilitas lengkap') ?></p>
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-blue-600"><?= formatPrice($similar->price_per_night) ?></span>
                        <a href="<?= BASE_URL ?>room/detail/<?= $similar->id ?>" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                            Lihat →
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- JavaScript for Check Availability -->
<script>
const roomId = <?= $room->id ?>;
const pricePerNight = <?= $room->price_per_night ?>;

// Update check_out min date when check_in changes
document.getElementById('check_in').addEventListener('change', function() {
    const checkIn = new Date(this.value);
    checkIn.setDate(checkIn.getDate() + 1);
    const minCheckOut = checkIn.toISOString().split('T')[0];
    document.getElementById('check_out').min = minCheckOut;
    
    // Reset check_out if it's before new min
    const checkOut = document.getElementById('check_out');
    if (checkOut.value && checkOut.value <= this.value) {
        checkOut.value = minCheckOut;
    }
});

function checkAvailability() {
    const checkIn = document.getElementById('check_in').value;
    const checkOut = document.getElementById('check_out').value;
    
    if (!checkIn || !checkOut) {
        alert('Pilih tanggal check-in dan check-out');
        return;
    }
    
    // Calculate nights locally first
    const date1 = new Date(checkIn);
    const date2 = new Date(checkOut);
    const nights = Math.ceil((date2 - date1) / (1000 * 60 * 60 * 24));
    
    if (nights <= 0) {
        alert('Tanggal check-out harus setelah check-in');
        return;
    }
    
    // Show loading
    const resultDiv = document.getElementById('availabilityResult');
    resultDiv.classList.remove('hidden');
    resultDiv.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-blue-500"></i> Mengecek...</div>';
    
    // AJAX call to check availability
    fetch(`<?= BASE_URL ?>room/checkAvailability/${roomId}?check_in=${checkIn}&check_out=${checkOut}`)
        .then(response => response.json())
        .then(data => {
            resultDiv.innerHTML = '';
            
            const availableDiv = document.getElementById('availableMessage');
            const unavailableDiv = document.getElementById('unavailableMessage');
            
            // Re-add the elements
            resultDiv.innerHTML = `
                <div id="availableMessage" class="${data.available ? '' : 'hidden'} bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center gap-2 text-green-700 font-medium mb-2">
                        <i class="fas fa-check-circle"></i>
                        <span>Kamar Tersedia!</span>
                    </div>
                    <div class="text-sm text-green-600 space-y-1">
                        <p>${data.nights || nights} malam</p>
                        <p class="text-lg font-bold">${data.total_price_formatted || formatPrice(nights * pricePerNight)}</p>
                    </div>
                </div>
                <div id="unavailableMessage" class="${data.available ? 'hidden' : ''} bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center gap-2 text-red-700 font-medium">
                        <i class="fas fa-times-circle"></i>
                        <span>Kamar tidak tersedia untuk tanggal tersebut</span>
                    </div>
                </div>
            `;
            
            // Update booking link with dates
            const bookBtn = document.getElementById('bookNowBtn');
            if (bookBtn && data.available) {
                bookBtn.href = `<?= BASE_URL ?>booking/create/${roomId}?check_in=${checkIn}&check_out=${checkOut}`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Fallback: just show calculated price
            const totalPrice = nights * pricePerNight;
            resultDiv.innerHTML = `
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="text-sm text-blue-600 space-y-1">
                        <p>${nights} malam</p>
                        <p class="text-lg font-bold">${formatPrice(totalPrice)}</p>
                    </div>
                    <p class="text-xs text-blue-500 mt-2">*Ketersediaan akan dikonfirmasi saat booking</p>
                </div>
            `;
        });
}

function formatPrice(amount) {
    return 'Rp ' + amount.toLocaleString('id-ID');
}
</script>