<?php

$isSelf = isset($_SESSION['user_id']) && $user->id == $_SESSION['user_id'];
?>

<!-- Modal Backdrop -->
<div id="modalBackdrop" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    
    <!-- Modal Container -->
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-hidden animate-modal">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-blue-500 to-purple-600">
            <div class="flex items-center gap-4">
                <!-- User Avatar -->
                <?php if (!empty($user->profile_image)): ?>
                <img src="<?= STORAGE_URL ?><?= str_replace('storage/', '', htmlspecialchars($user->profile_image)) ?>" 
                     alt="<?= htmlspecialchars($user->name) ?>"
                     class="w-14 h-14 rounded-full object-cover border-3 border-white shadow-lg">
                <?php else: ?>
                <div class="w-14 h-14 bg-white/20 backdrop-blur rounded-full flex items-center justify-center text-white text-xl font-bold border-3 border-white/50">
                    <?= strtoupper(substr($user->name, 0, 1)) ?>
                </div>
                <?php endif; ?>
                
                <div>
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <?= htmlspecialchars($user->name) ?>
                        <?php if ($isSelf): ?>
                        <span class="text-xs bg-white/20 px-2 py-0.5 rounded-full">(Anda)</span>
                        <?php endif; ?>
                    </h2>
                    <p class="text-white/80 text-sm"><?= htmlspecialchars($user->email) ?></p>
                </div>
            </div>
            <a href="<?= BASE_URL ?>admin/users" 
               class="w-8 h-8 flex items-center justify-center rounded-lg text-white/70 hover:text-white hover:bg-white/10 transition-colors">
                <i class="fas fa-times"></i>
            </a>
        </div>
        
        <!-- Modal Body (Scrollable) -->
        <div class="overflow-y-auto max-h-[calc(90vh-180px)]">
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-3 gap-4 p-6 bg-slate-50 border-b border-slate-200">
                <div class="text-center">
                    <p class="text-2xl font-bold text-slate-800"><?= $userStats->total_bookings ?? 0 ?></p>
                    <p class="text-xs text-slate-500">Total Booking</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-green-600">Rp <?= number_format($userStats->total_spent ?? 0, 0, ',', '.') ?></p>
                    <p class="text-xs text-slate-500">Total Spent</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-red-500"><?= $userStats->cancelled_bookings ?? 0 ?></p>
                    <p class="text-xs text-slate-500">Dibatalkan</p>
                </div>
            </div>
            
            <!-- User Information -->
            <div class="p-6 space-y-6">
                
                <!-- Info Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- ID -->
                    <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg">
                        <div class="w-10 h-10 bg-slate-200 text-slate-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-hashtag"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">User ID</p>
                            <p class="font-medium text-slate-800">#<?= $user->id ?></p>
                        </div>
                    </div>
                    
                    <!-- Role -->
                    <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg">
                        <div class="w-10 h-10 <?= $user->role === 'admin' ? 'bg-purple-100 text-purple-600' : 'bg-blue-100 text-blue-600' ?> rounded-lg flex items-center justify-center">
                            <i class="fas fa-<?= $user->role === 'admin' ? 'user-shield' : 'user' ?>"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Role</p>
                            <p class="font-medium <?= $user->role === 'admin' ? 'text-purple-600' : 'text-blue-600' ?>">
                                <?= ucfirst($user->role) ?>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Email -->
                    <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg">
                        <div class="w-10 h-10 bg-green-100 text-green-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs text-slate-500">Email</p>
                            <a href="mailto:<?= htmlspecialchars($user->email) ?>" class="font-medium text-slate-800 hover:text-blue-500 truncate block">
                                <?= htmlspecialchars($user->email) ?>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Phone -->
                    <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg">
                        <div class="w-10 h-10 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Telepon</p>
                            <a href="tel:<?= htmlspecialchars($user->phone ?? '') ?>" class="font-medium text-slate-800 hover:text-blue-500">
                                <?= htmlspecialchars($user->phone ?? '-') ?>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Created At -->
                    <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg">
                        <div class="w-10 h-10 bg-cyan-100 text-cyan-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Terdaftar</p>
                            <p class="font-medium text-slate-800"><?= date('d M Y, H:i', strtotime($user->created_at)) ?></p>
                        </div>
                    </div>
                    
                    <!-- Updated At -->
                    <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg">
                        <div class="w-10 h-10 bg-pink-100 text-pink-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Diperbarui</p>
                            <p class="font-medium text-slate-800"><?= date('d M Y, H:i', strtotime($user->updated_at ?? $user->created_at)) ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Reset Password Section -->
                <?php if (!$isSelf): ?>
                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <button type="button" 
                            onclick="toggleResetPassword()"
                            class="w-full flex items-center justify-between p-4 bg-slate-50 hover:bg-slate-100 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-red-100 text-red-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-key"></i>
                            </div>
                            <div class="text-left">
                                <p class="font-medium text-slate-800">Reset Password</p>
                                <p class="text-xs text-slate-500">Atur password baru untuk user ini</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-down text-slate-400 transition-transform" id="resetPasswordIcon"></i>
                    </button>
                    
                    <div id="resetPasswordForm" class="hidden border-t border-slate-200">
                        <form action="<?= BASE_URL ?>admin/users/<?= $user->id ?>/reset-password" method="POST" class="p-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Password Baru</label>
                                <div class="relative">
                                    <input type="password" 
                                           name="new_password" 
                                           id="new_password"
                                           placeholder="Minimal 6 karakter"
                                           required
                                           minlength="6"
                                           class="w-full pl-3.5 pr-10 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <button type="button" onclick="togglePassword('new_password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                        <i class="fas fa-eye" id="new_password-icon"></i>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Konfirmasi Password</label>
                                <div class="relative">
                                    <input type="password" 
                                           name="confirm_password" 
                                           id="confirm_password"
                                           placeholder="Ulangi password baru"
                                           required
                                           minlength="6"
                                           class="w-full pl-3.5 pr-10 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <button type="button" onclick="togglePassword('confirm_password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                        <i class="fas fa-eye" id="confirm_password-icon"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="submit" 
                                    class="w-full py-2.5 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-colors"
                                    onclick="return confirm('Reset password user ini?')">
                                <i class="fas fa-key mr-2"></i>Reset Password
                            </button>
                        </form>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Booking History -->
                <div>
                    <h3 class="text-sm font-semibold text-slate-800 mb-3 flex items-center gap-2">
                        <i class="fas fa-history text-slate-400"></i>
                        Riwayat Booking
                    </h3>
                    
                    <?php if (empty($bookings)): ?>
                    <div class="text-center py-8 bg-slate-50 rounded-xl">
                        <div class="w-12 h-12 bg-slate-200 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-calendar-times text-slate-400"></i>
                        </div>
                        <p class="text-slate-500 text-sm">Belum ada riwayat booking</p>
                    </div>
                    <?php else: ?>
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        <?php foreach ($bookings as $booking): ?>
                        <?php
                        $statusClasses = [
                            'pending' => 'bg-yellow-100 text-yellow-700',
                            'confirmed' => 'bg-blue-100 text-blue-700',
                            'checked_in' => 'bg-green-100 text-green-700',
                            'checked_out' => 'bg-purple-100 text-purple-700',
                            'cancelled' => 'bg-red-100 text-red-700'
                        ];
                        $statusClass = $statusClasses[$booking->status] ?? 'bg-slate-100 text-slate-700';
                        ?>
                        <a href="<?= BASE_URL ?>admin/bookings/<?= $booking->id ?>" 
                           class="flex items-center justify-between p-3 bg-slate-50 hover:bg-slate-100 rounded-lg transition-colors group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white border border-slate-200 rounded-lg flex items-center justify-center text-slate-600">
                                    <i class="fas fa-bed"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-800 text-sm">Booking #<?= $booking->id ?></p>
                                    <p class="text-xs text-slate-500">
                                        <?= date('d M Y', strtotime($booking->check_in_date)) ?> - <?= date('d M Y', strtotime($booking->check_out_date)) ?>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-medium text-slate-800">Rp <?= number_format($booking->total_price, 0, ',', '.') ?></span>
                                <span class="px-2 py-1 rounded-full text-xs font-medium <?= $statusClass ?>">
                                    <?= ucfirst(str_replace('_', ' ', $booking->status)) ?>
                                </span>
                                <i class="fas fa-chevron-right text-slate-300 group-hover:text-slate-500 transition-colors"></i>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
            <a href="<?= BASE_URL ?>admin/users" 
               class="px-4 py-2.5 border border-slate-300 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-100 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            
            <div class="flex items-center gap-2">
                <?php if (!$isSelf): ?>
                <!-- Toggle Role -->
                <form action="<?= BASE_URL ?>admin/users/<?= $user->id ?>/toggle-role" method="POST" class="inline">
                    <button type="submit" 
                            class="px-4 py-2.5 border border-purple-300 text-purple-700 text-sm font-medium rounded-lg hover:bg-purple-50 transition-colors"
                            onclick="return confirm('<?= $user->role === 'admin' ? 'Ubah role menjadi Guest?' : 'Ubah role menjadi Admin?' ?>')">
                        <i class="fas fa-<?= $user->role === 'admin' ? 'user' : 'user-shield' ?> mr-2"></i>
                        <?= $user->role === 'admin' ? 'Jadikan Guest' : 'Jadikan Admin' ?>
                    </button>
                </form>
                
                <!-- Delete -->
                <form action="<?= BASE_URL ?>admin/users/<?= $user->id ?>/delete" method="POST" class="inline">
                    <button type="submit" 
                            class="px-4 py-2.5 border border-red-300 text-red-700 text-sm font-medium rounded-lg hover:bg-red-50 transition-colors"
                            onclick="return confirm('Hapus user ini? Pastikan tidak ada booking aktif.')">
                        <i class="fas fa-trash mr-2"></i>Hapus
                    </button>
                </form>
                <?php endif; ?>
                
                <!-- Edit -->
                <a href="<?= BASE_URL ?>admin/users/<?= $user->id ?>/edit" 
                   class="px-4 py-2.5 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit User
                </a>
            </div>
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
    
    /* Scrollbar styling */
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
        window.location.href = '<?= BASE_URL ?>admin/users';
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        window.location.href = '<?= BASE_URL ?>admin/users';
    }
});

// Toggle reset password form
function toggleResetPassword() {
    const form = document.getElementById('resetPasswordForm');
    const icon = document.getElementById('resetPasswordIcon');
    
    form.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}

// Toggle password visibility
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(inputId + '-icon');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>