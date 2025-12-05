<?php
/**
 * Profile View
 * Menampilkan dan mengedit profil user
 * 
 * Variables:
 * - $user: object user yang login
 */
?>

<!-- Page Header -->
<section class="mb-8">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Profil Saya</h1>
    <p class="text-gray-500 mt-1">Kelola informasi akun Anda</p>
</section>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Profile Card -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 text-center">
            <!-- Avatar -->
            <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-4xl font-bold text-blue-600">
                    <?= strtoupper(substr($user->name ?? 'U', 0, 1)) ?>
                </span>
            </div>
            
            <h2 class="text-xl font-bold text-gray-900"><?= htmlspecialchars($user->name ?? '') ?></h2>
            <p class="text-gray-500 text-sm"><?= htmlspecialchars($user->email ?? '') ?></p>
            
            <?php if (isset($user->role)): ?>
                <span class="inline-block mt-3 px-3 py-1 rounded-full text-xs font-medium <?= $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' ?>">
                    <?= ucfirst($user->role) ?>
                </span>
            <?php endif; ?>
            
            <!-- Quick Info -->
            <div class="mt-6 pt-6 border-t border-gray-100 text-left space-y-3">
                <div class="flex items-center gap-3 text-sm">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <span class="text-gray-600"><?= htmlspecialchars($user->phone ?? '-') ?></span>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-gray-600">
                        Bergabung <?= isset($user->created_at) ? date('d M Y', strtotime($user->created_at)) : '-' ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Forms -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Update Profile Form -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Informasi Profil
                </h3>
            </div>
            
            <form action="<?= BASE_URL ?>auth/updateProfile" method="POST" class="p-6 space-y-4">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" id="name" 
                           value="<?= htmlspecialchars($user->name ?? '') ?>" 
                           class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5"
                           placeholder="Masukkan nama lengkap" required>
                </div>

                <!-- Email (readonly) -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" 
                           value="<?= htmlspecialchars($user->email ?? '') ?>" 
                           class="w-full bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg p-2.5 cursor-not-allowed"
                           disabled readonly>
                    <p class="text-xs text-gray-500 mt-1">Email tidak dapat diubah</p>
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                    <input type="tel" name="phone" id="phone" 
                           value="<?= htmlspecialchars($user->phone ?? '') ?>" 
                           class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5"
                           placeholder="Contoh: 081234567890" required>
                </div>

                <div class="pt-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-5 rounded-lg transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Password Form -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Ubah Password
                </h3>
            </div>
            
            <form action="<?= BASE_URL ?>auth/updatePassword" method="POST" class="p-6 space-y-4">
                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                    <input type="password" name="current_password" id="current_password" 
                           class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5"
                           placeholder="Masukkan password saat ini" required>
                </div>

                <!-- New Password -->
                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                    <input type="password" name="new_password" id="new_password" 
                           class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5"
                           placeholder="Minimal 6 karakter" required minlength="6">
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                    <input type="password" name="confirm_password" id="confirm_password" 
                           class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5"
                           placeholder="Ulangi password baru" required>
                </div>

                <div class="pt-2">
                    <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-medium py-2.5 px-5 rounded-lg transition">
                        Ubah Password
                    </button>
                </div>
            </form>
        </div>

        <!-- Danger Zone -->
        <div class="bg-white rounded-xl shadow-lg border border-red-200 overflow-hidden">
            <div class="px-6 py-4 bg-red-50 border-b border-red-200">
                <h3 class="font-semibold text-red-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    Zona Berbahaya
                </h3>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-medium text-gray-900">Logout dari Akun</h4>
                        <p class="text-sm text-gray-500">Keluar dari sesi saat ini</p>
                    </div>
                    <form action="<?= BASE_URL ?>logout" method="POST" 
                          onsubmit="return confirm('Yakin ingin logout?')">
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition text-sm">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>