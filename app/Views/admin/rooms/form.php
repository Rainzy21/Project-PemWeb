<?php
$isEdit = $action === 'edit';
$formAction = $isEdit ? BASE_URL . "admin/rooms/{$room->id}/update" : BASE_URL . "admin/rooms/store";
$pageTitle = $isEdit ? 'Edit Kamar ' . $room->room_number : 'Tambah Kamar Baru';

// Get old input or room data
$old = $_SESSION['old'] ?? [];
$roomNumber = $old['room_number'] ?? ($room->room_number ?? '');
$roomType = $old['room_type'] ?? ($room->room_type ?? 'standard');
$pricePerNight = $old['price_per_night'] ?? ($room->price_per_night ?? '');
$description = $old['description'] ?? ($room->description ?? '');
$isAvailable = isset($old['is_available']) ? $old['is_available'] : ($room->is_available ?? 1);

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
                    <i class="fas fa-<?= $isEdit ? 'edit' : 'plus' ?>"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800"><?= $pageTitle ?></h2>
                    <p class="text-xs text-slate-500"><?= $isEdit ? 'Perbarui informasi kamar' : 'Isi detail kamar baru' ?></p>
                </div>
            </div>
            <a href="<?= BASE_URL ?>admin/rooms" 
               class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                <i class="fas fa-times"></i>
            </a>
        </div>
        
        <!-- Modal Body (Scrollable) -->
        <div class="overflow-y-auto max-h-[calc(90vh-140px)]">
            <form id="roomForm" action="<?= $formAction ?>" method="POST" enctype="multipart/form-data">
                
                <div class="p-6 space-y-5">
                    
                    <!-- Room Number & Type -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Room Number -->
                        <div>
                            <label for="room_number" class="block text-sm font-medium text-slate-700 mb-1.5">
                                Nomor Kamar <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="room_number" 
                                   name="room_number" 
                                   value="<?= htmlspecialchars($roomNumber) ?>"
                                   placeholder="Contoh: 101, A-201"
                                   required
                                   class="w-full px-3.5 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm">
                        </div>
                        
                        <!-- Room Type -->
                        <div>
                            <label for="room_type" class="block text-sm font-medium text-slate-700 mb-1.5">
                                Tipe Kamar <span class="text-red-500">*</span>
                            </label>
                            <select id="room_type" 
                                    name="room_type" 
                                    required
                                    class="w-full px-3.5 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm bg-white">
                                <option value="standard" <?= $roomType === 'standard' ? 'selected' : '' ?>>🛏️ Standard</option>
                                <option value="deluxe" <?= $roomType === 'deluxe' ? 'selected' : '' ?>>⭐ Deluxe</option>
                                <option value="suite" <?= $roomType === 'suite' ? 'selected' : '' ?>>👑 Suite</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Price per Night -->
                    <div>
                        <label for="price_per_night" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Harga per Malam <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-sm font-medium">Rp</span>
                            <input type="number" 
                                   id="price_per_night" 
                                   name="price_per_night" 
                                   value="<?= htmlspecialchars($pricePerNight) ?>"
                                   placeholder="0"
                                   min="1"
                                   required
                                   class="w-full pl-10 pr-3.5 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm">
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Deskripsi
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="3"
                                  placeholder="Deskripsi kamar, fasilitas, dll..."
                                  class="w-full px-3.5 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm resize-none"><?= htmlspecialchars($description) ?></textarea>
                    </div>
                    
                    <!-- Image Upload -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Gambar Kamar
                        </label>
                        
                        <div class="flex items-start gap-4">
                            <?php if ($isEdit && !empty($room->image)): ?>
                            <!-- Current Image -->
                            <div class="flex-shrink-0">
                                <img src="<?= STORAGE_URL ?><?= str_replace('storage/', '', htmlspecialchars($room->image)) ?>"
                                     alt="Current"
                                     class="w-24 h-24 object-cover rounded-lg border border-slate-200">
                            </div>
                            <?php endif; ?>
                            
                            <!-- Upload Area -->
                            <div class="flex-1">
                                <div class="border-2 border-dashed border-slate-300 rounded-lg p-4 text-center hover:border-blue-400 hover:bg-blue-50/50 transition-all cursor-pointer"
                                     onclick="document.getElementById('image').click()">
                                    <input type="file" 
                                           id="image" 
                                           name="image" 
                                           accept="image/jpeg,image/png,image/jpg,image/webp"
                                           class="hidden"
                                           onchange="previewImage(this)">
                                    
                                    <div id="uploadPlaceholder">
                                        <i class="fas fa-cloud-upload-alt text-2xl text-slate-400 mb-2"></i>
                                        <p class="text-sm text-slate-600"><?= $isEdit ? 'Upload gambar baru' : 'Upload gambar' ?></p>
                                        <p class="text-xs text-slate-400 mt-1">PNG, JPG, WEBP (Maks. 2MB)</p>
                                    </div>
                                    
                                    <div id="imagePreview" class="hidden">
                                        <img id="previewImg" src="" alt="Preview" class="max-h-24 mx-auto rounded-lg mb-2">
                                        <p class="text-xs text-blue-500">Klik untuk ganti</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Availability Toggle -->
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg border border-slate-200">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 text-green-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check-circle text-sm"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-slate-800">Tersedia untuk Booking</h3>
                                <p class="text-xs text-slate-500">Kamar dapat dipesan tamu</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" 
                                   name="is_available" 
                                   value="1" 
                                   <?= $isAvailable ? 'checked' : '' ?>
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-100 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                        </label>
                    </div>
                    
                </div>
                
                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-3">
                    <a href="<?= BASE_URL ?>admin/rooms" 
                       class="px-4 py-2.5 border border-slate-300 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-100 transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-5 py-2.5 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors inline-flex items-center gap-2">
                        <i class="fas fa-<?= $isEdit ? 'save' : 'check' ?>"></i>
                        <span><?= $isEdit ? 'Simpan' : 'Tambah Kamar' ?></span>
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
        window.location.href = '<?= BASE_URL ?>admin/rooms';
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        window.location.href = '<?= BASE_URL ?>admin/rooms';
    }
});

// Image preview function
function previewImage(input) {
    const placeholder = document.getElementById('uploadPlaceholder');
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Check file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal 2MB');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            placeholder.classList.add('hidden');
            preview.classList.remove('hidden');
        }
        
        reader.readAsDataURL(file);
    } else {
        placeholder.classList.remove('hidden');
        preview.classList.add('hidden');
    }
}
</script>