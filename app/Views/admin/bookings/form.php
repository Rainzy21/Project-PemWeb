<?php
$isEdit = ($action ?? 'create') === 'edit';
$formAction = $isEdit ? BASE_URL . "admin/bookings/{$booking->id}/update" : BASE_URL . "admin/bookings/store";
$pageTitle = $isEdit ? 'Edit Booking #' . $booking->id : 'Buat Booking Baru';

// Get old input or booking data
$old = $_SESSION['old'] ?? [];
$userId = $old['user_id'] ?? ($booking->user_id ?? '');
$roomId = $old['room_id'] ?? ($booking->room_id ?? '');
$checkIn = $old['check_in_date'] ?? ($booking->check_in_date ?? date('Y-m-d'));
$checkOut = $old['check_out_date'] ?? ($booking->check_out_date ?? date('Y-m-d', strtotime('+1 day')));
$status = $old['status'] ?? ($booking->status ?? 'confirmed');

// Clear old input
unset($_SESSION['old']);
?>

<!-- Modal Backdrop -->
<div id="modalBackdrop" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    
    <!-- Modal Container -->
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden animate-modal">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-<?= $isEdit ? 'edit' : 'calendar-plus' ?>"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800"><?= $pageTitle ?></h2>
                    <p class="text-xs text-slate-500"><?= $isEdit ? 'Perbarui informasi booking' : 'Buat reservasi baru untuk tamu' ?></p>
                </div>
            </div>
            <a href="<?= BASE_URL ?>admin/bookings" 
               class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                <i class="fas fa-times"></i>
            </a>
        </div>
        
        <!-- Modal Body (Scrollable) -->
        <div class="overflow-y-auto max-h-[calc(90vh-140px)]">
            <form id="bookingForm" action="<?= $formAction ?>" method="POST">
                
                <div class="p-6 space-y-5">
                    
                    <!-- Guest & Room Selection -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Guest -->
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-slate-700 mb-1.5">
                                Pilih Tamu <span class="text-red-500">*</span>
                            </label>
                            <select id="user_id" 
                                    name="user_id" 
                                    required
                                    class="w-full px-3.5 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm bg-white">
                                <option value="">-- Pilih Tamu --</option>
                                <?php foreach ($guests as $guest): ?>
                                <option value="<?= $guest->id ?>" <?= $userId == $guest->id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($guest->name) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Room -->
                        <div>
                            <label for="room_id" class="block text-sm font-medium text-slate-700 mb-1.5">
                                Pilih Kamar <span class="text-red-500">*</span>
                            </label>
                            <select id="room_id" 
                                    name="room_id" 
                                    required
                                    class="w-full px-3.5 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm bg-white">
                                <option value="">-- Pilih Kamar --</option>
                                <?php foreach ($rooms as $room): ?>
                                <option value="<?= $room->id ?>" 
                                        data-price="<?= $room->price_per_night ?>"
                                        <?= $roomId == $room->id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($room->room_number) ?> - <?= ucfirst($room->room_type) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Date Selection -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Check-in Date -->
                        <div>
                            <label for="check_in_date" class="block text-sm font-medium text-slate-700 mb-1.5">
                                Tanggal Check-in <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   id="check_in_date" 
                                   name="check_in_date" 
                                   value="<?= htmlspecialchars($checkIn) ?>"
                                   <?= !$isEdit ? 'min="' . date('Y-m-d') . '"' : '' ?>
                                   required
                                   class="w-full px-3.5 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm">
                        </div>
                        
                        <!-- Check-out Date -->
                        <div>
                            <label for="check_out_date" class="block text-sm font-medium text-slate-700 mb-1.5">
                                Tanggal Check-out <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   id="check_out_date" 
                                   name="check_out_date" 
                                   value="<?= htmlspecialchars($checkOut) ?>"
                                   required
                                   class="w-full px-3.5 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm">
                        </div>
                    </div>
                    
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Status Booking <span class="text-red-500">*</span>
                        </label>
                        <select id="status" 
                                name="status" 
                                required
                                class="w-full px-3.5 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm bg-white">
                            <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>⏳ Pending</option>
                            <option value="confirmed" <?= $status === 'confirmed' ? 'selected' : '' ?>>✅ Confirmed</option>
                            <option value="checked_in" <?= $status === 'checked_in' ? 'selected' : '' ?>>🏨 Checked In</option>
                            <option value="checked_out" <?= $status === 'checked_out' ? 'selected' : '' ?>>🚪 Checked Out</option>
                            <option value="cancelled" <?= $status === 'cancelled' ? 'selected' : '' ?>>❌ Cancelled</option>
                        </select>
                    </div>
                    
                    <!-- Price Preview -->
                    <div id="pricePreview" class="hidden flex items-center justify-between p-3 bg-slate-50 rounded-lg border border-slate-200">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 text-green-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calculator text-sm"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-slate-800">Estimasi Total</h3>
                                <p class="text-xs text-slate-500"><span id="nightsCount">0</span> malam × Rp <span id="pricePerNight">0</span></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-green-600">Rp <span id="totalPrice">0</span></p>
                        </div>
                    </div>
                    
                    <?php if ($isEdit && isset($booking->total_price)): ?>
                    <!-- Current Total (for edit) -->
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-receipt text-sm"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-slate-800">Total Saat Ini</h3>
                                <p class="text-xs text-slate-500">Harga yang sudah tercatat</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-blue-600">Rp <?= number_format($booking->total_price, 0, ',', '.') ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                </div>
                
                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-3">
                    <a href="<?= BASE_URL ?>admin/bookings<?= $isEdit ? '/' . $booking->id : '' ?>" 
                       class="px-4 py-2.5 border border-slate-300 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-100 transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-5 py-2.5 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors inline-flex items-center gap-2">
                        <i class="fas fa-<?= $isEdit ? 'save' : 'check' ?>"></i>
                        <span><?= $isEdit ? 'Simpan Perubahan' : 'Buat Booking' ?></span>
                    </button>
                </div>
                
            </form>
        </div>
        
    </div>
</div>

<style>
    /* Modal Animation */
    @keyframes modalIn {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(-10px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
    
    .animate-modal {
        animation: modalIn 0.2s ease-out;
    }
    
    /* Hide scrollbar but keep functionality */
    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

<script>
// Close modal on backdrop click
document.getElementById('modalBackdrop').addEventListener('click', function(e) {
    if (e.target === this) {
        window.location.href = '<?= BASE_URL ?>admin/bookings<?= $isEdit ? '/' . $booking->id : '' ?>';
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        window.location.href = '<?= BASE_URL ?>admin/bookings<?= $isEdit ? '/' . $booking->id : '' ?>';
    }
});

// Price calculation
const roomSelect = document.getElementById('room_id');
const checkInInput = document.getElementById('check_in_date');
const checkOutInput = document.getElementById('check_out_date');
const pricePreview = document.getElementById('pricePreview');
const nightsCount = document.getElementById('nightsCount');
const pricePerNight = document.getElementById('pricePerNight');
const totalPrice = document.getElementById('totalPrice');

function calculatePrice() {
    const selectedOption = roomSelect.options[roomSelect.selectedIndex];
    const price = selectedOption ? parseInt(selectedOption.dataset.price) || 0 : 0;
    
    const checkIn = new Date(checkInInput.value);
    const checkOut = new Date(checkOutInput.value);
    
    if (price > 0 && checkIn && checkOut && checkOut > checkIn) {
        const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
        const total = nights * price;
        
        nightsCount.textContent = nights;
        pricePerNight.textContent = price.toLocaleString('id-ID');
        totalPrice.textContent = total.toLocaleString('id-ID');
        pricePreview.classList.remove('hidden');
    } else {
        pricePreview.classList.add('hidden');
    }
}

// Update check-out min date when check-in changes
checkInInput.addEventListener('change', function() {
    const nextDay = new Date(this.value);
    nextDay.setDate(nextDay.getDate() + 1);
    checkOutInput.min = nextDay.toISOString().split('T')[0];
    
    if (new Date(checkOutInput.value) <= new Date(this.value)) {
        checkOutInput.value = nextDay.toISOString().split('T')[0];
    }
    
    calculatePrice();
});

roomSelect.addEventListener('change', calculatePrice);
checkOutInput.addEventListener('change', calculatePrice);

// Initial calculation
calculatePrice();
</script>