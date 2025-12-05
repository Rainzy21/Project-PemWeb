<?php
/**
 * Booking Create Form View
 * 
 * Variables:
 * - $room: object room yang akan dibooking
 */

// Helper functions
function formatPrice($price) {
    return 'Rp ' . number_format($price, 0, ',', '.');
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

// Default dates
$tomorrow = date('Y-m-d', strtotime('+1 day'));
$dayAfterTomorrow = date('Y-m-d', strtotime('+2 days'));
?>

<!-- Page Header -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-8">
    <div class="container mx-auto px-4">
        <div class="flex items-center gap-4">
            <a href="<?= BASE_URL ?>room/detail/<?= $room->id ?>" 
               class="w-10 h-10 flex items-center justify-center rounded-lg bg-white/20 hover:bg-white/30 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">Booking Kamar <?= htmlspecialchars($room->room_number) ?></h1>
                <p class="text-blue-100 mt-1">Lengkapi data untuk melakukan reservasi</p>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column - Booking Form -->
        <div class="lg:col-span-2">
            <form action="<?= BASE_URL ?>booking/store" method="POST" id="bookingForm" class="space-y-6">
                <input type="hidden" name="room_id" value="<?= $room->id ?>">
                
                <!-- Date Selection -->
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                        <h2 class="font-semibold text-slate-800">
                            <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
                            Pilih Tanggal Menginap
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Check-in Date -->
                            <div>
                                <label for="check_in" class="block text-sm font-medium text-slate-700 mb-2">
                                    <i class="fas fa-sign-in-alt text-green-500 mr-1"></i>
                                    Tanggal Check-in
                                </label>
                                <input type="date" 
                                       name="check_in" 
                                       id="check_in"
                                       value="<?= $_SESSION['old']['check_in'] ?? $tomorrow ?>"
                                       min="<?= date('Y-m-d') ?>"
                                       required
                                       class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <p class="mt-1 text-xs text-slate-500">Check-in mulai pukul 14:00 WIB</p>
                            </div>
                            
                            <!-- Check-out Date -->
                            <div>
                                <label for="check_out" class="block text-sm font-medium text-slate-700 mb-2">
                                    <i class="fas fa-sign-out-alt text-red-500 mr-1"></i>
                                    Tanggal Check-out
                                </label>
                                <input type="date" 
                                       name="check_out" 
                                       id="check_out"
                                       value="<?= $_SESSION['old']['check_out'] ?? $dayAfterTomorrow ?>"
                                       min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                                       required
                                       class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <p class="mt-1 text-xs text-slate-500">Check-out sebelum pukul 12:00 WIB</p>
                            </div>
                        </div>
                        
                        <!-- Availability Check Result -->
                        <div id="availabilityResult" class="mt-4 hidden">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
                
                <!-- Guest Info (from session) -->
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                        <h2 class="font-semibold text-slate-800">
                            <i class="fas fa-user text-blue-500 mr-2"></i>
                            Data Tamu
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap</label>
                                <div class="px-4 py-3 bg-slate-100 rounded-lg text-slate-700">
                                    <?= htmlspecialchars($_SESSION['user_name'] ?? '-') ?>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                                <div class="px-4 py-3 bg-slate-100 rounded-lg text-slate-700">
                                    <?= htmlspecialchars($_SESSION['user_email'] ?? '-') ?>
                                </div>
                            </div>
                        </div>
                        <p class="mt-4 text-sm text-slate-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Data tamu diambil dari akun Anda. 
                            <a href="<?= BASE_URL ?>profile" class="text-blue-600 hover:underline">Ubah profil</a>
                        </p>
                    </div>
                </div>
                
                <!-- Terms & Policies -->
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                        <h2 class="font-semibold text-slate-800">
                            <i class="fas fa-file-contract text-blue-500 mr-2"></i>
                            Syarat & Ketentuan
                        </h2>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-2 text-sm text-slate-600">
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                                <span>Check-in mulai pukul 14:00 WIB, check-out sebelum pukul 12:00 WIB</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                                <span>Pembatalan gratis dapat dilakukan sebelum status dikonfirmasi</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                                <span>Tamu wajib menunjukkan identitas (KTP/Passport) saat check-in</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                                <span>Pembayaran dilakukan di tempat saat check-in</span>
                            </li>
                        </ul>
                        
                        <div class="mt-4 pt-4 border-t border-slate-200">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" 
                                       name="agree_terms" 
                                       id="agreeTerms"
                                       required
                                       class="mt-1 w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                                <span class="text-sm text-slate-700">
                                    Saya menyetujui <a href="#" class="text-blue-600 hover:underline">syarat & ketentuan</a> 
                                    serta <a href="#" class="text-blue-600 hover:underline">kebijakan privasi</a> yang berlaku
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Submit Button (Mobile) -->
                <div class="lg:hidden">
                    <button type="submit" 
                            id="submitBtn"
                            class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle"></i>
                        <span>Konfirmasi Booking</span>
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Right Column - Room Summary -->
        <div class="space-y-6">
            
            <!-- Room Card -->
            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden sticky top-4">
                <img src="<?= $roomImage ?>" 
                     alt="<?= htmlspecialchars($room->room_number) ?>"
                     class="w-full h-48 object-cover">
                
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-lg text-slate-800">Kamar <?= htmlspecialchars($room->room_number) ?></h3>
                            <span class="inline-block mt-1 <?= getTypeBadge($room->room_type) ?> px-2 py-0.5 rounded text-xs font-medium capitalize">
                                <?= $room->room_type ?>
                            </span>
                        </div>
                        <div class="text-right">
                            <p class="text-xl font-bold text-blue-600"><?= formatPrice($room->price_per_night) ?></p>
                            <p class="text-xs text-slate-500">/malam</p>
                        </div>
                    </div>
                    
                    <?php if (!empty($room->description)): ?>
                    <p class="text-sm text-slate-600 mb-4 line-clamp-2"><?= htmlspecialchars($room->description) ?></p>
                    <?php endif; ?>
                    
                    <div class="border-t border-slate-200 pt-4">
                        <h4 class="text-sm font-semibold text-slate-700 mb-3">Ringkasan Booking</h4>
                        
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-600">Harga per malam</span>
                                <span class="text-slate-800"><?= formatPrice($room->price_per_night) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Jumlah malam</span>
                                <span class="text-slate-800" id="nightsCount">1</span>
                            </div>
                            <div class="border-t border-slate-200 pt-2 mt-2">
                                <div class="flex justify-between">
                                    <span class="font-semibold text-slate-800">Total</span>
                                    <span class="font-bold text-blue-600 text-lg" id="totalPrice"><?= formatPrice($room->price_per_night) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Submit Button (Desktop) - Using JavaScript -->
                    <button type="button" 
                            onclick="document.getElementById('bookingForm').submit();"
                            id="submitBtnDesktop"
                            class="hidden lg:flex w-full mt-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-colors items-center justify-center gap-2">
                        <i class="fas fa-check-circle"></i>
                        <span>Konfirmasi Booking</span>
                    </button>
                </div>
            </div>
            
            <!-- Help Card -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div>
                        <p class="font-medium text-blue-800 mb-1">Butuh Bantuan?</p>
                        <p class="text-sm text-blue-600">Hubungi kami di <strong>+62 21 1234 5678</strong></p>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkInInput = document.getElementById('check_in');
    const checkOutInput = document.getElementById('check_out');
    const nightsCount = document.getElementById('nightsCount');
    const totalPrice = document.getElementById('totalPrice');
    const availabilityResult = document.getElementById('availabilityResult');
    const pricePerNight = <?= $room->price_per_night ?>;
    const roomId = <?= $room->id ?>;
    
    // Format price
    function formatPrice(price) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
    }
    
    // Calculate nights
    function calculateNights() {
        const checkIn = new Date(checkInInput.value);
        const checkOut = new Date(checkOutInput.value);
        const diffTime = checkOut - checkIn;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        return diffDays > 0 ? diffDays : 1;
    }
    
    // Update summary
    function updateSummary() {
        const nights = calculateNights();
        const total = nights * pricePerNight;
        
        nightsCount.textContent = nights;
        totalPrice.textContent = formatPrice(total);
    }
    
    // Check availability
    function checkAvailability() {
        const checkIn = checkInInput.value;
        const checkOut = checkOutInput.value;
        
        if (!checkIn || !checkOut) return;
        
        availabilityResult.classList.remove('hidden');
        availabilityResult.innerHTML = `
            <div class="flex items-center gap-2 text-slate-600">
                <i class="fas fa-spinner fa-spin"></i>
                <span>Memeriksa ketersediaan...</span>
            </div>
        `;
        
        fetch('<?= BASE_URL ?>booking/check-availability', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `room_id=${roomId}&check_in=${checkIn}&check_out=${checkOut}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.available) {
                availabilityResult.innerHTML = `
                    <div class="flex items-center gap-2 p-3 bg-green-50 text-green-700 rounded-lg">
                        <i class="fas fa-check-circle"></i>
                        <span>Kamar tersedia untuk tanggal yang dipilih</span>
                    </div>
                `;
            } else {
                availabilityResult.innerHTML = `
                    <div class="flex items-center gap-2 p-3 bg-red-50 text-red-700 rounded-lg">
                        <i class="fas fa-times-circle"></i>
                        <span>Kamar tidak tersedia untuk tanggal yang dipilih</span>
                    </div>
                `;
            }
        })
        .catch(error => {
            availabilityResult.innerHTML = `
                <div class="flex items-center gap-2 p-3 bg-yellow-50 text-yellow-700 rounded-lg">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Gagal memeriksa ketersediaan</span>
                </div>
            `;
        });
    }
    
    // Update check-out min date when check-in changes
    checkInInput.addEventListener('change', function() {
        const nextDay = new Date(this.value);
        nextDay.setDate(nextDay.getDate() + 1);
        checkOutInput.min = nextDay.toISOString().split('T')[0];
        
        if (new Date(checkOutInput.value) <= new Date(this.value)) {
            checkOutInput.value = nextDay.toISOString().split('T')[0];
        }
        
        updateSummary();
        checkAvailability();
    });
    
    checkOutInput.addEventListener('change', function() {
        updateSummary();
        checkAvailability();
    });
    
    // Initial calculation
    updateSummary();
});
</script>