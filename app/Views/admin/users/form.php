<?php
$isEdit = $action === 'edit';
$formAction = $isEdit ? BASE_URL . "admin/users/{$user->id}/update" : BASE_URL . "admin/users/store";
$pageTitle = $isEdit ? 'Edit User' : 'Tambah User Baru';

// Get old input or user data
$old = $_SESSION['old'] ?? [];
$name = $old['name'] ?? ($user->name ?? '');
$email = $old['email'] ?? ($user->email ?? '');
$phone = $old['phone'] ?? ($user->phone ?? '');
$role = $old['role'] ?? ($user->role ?? 'guest');

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
                    <i class="fas fa-<?= $isEdit ? 'user-edit' : 'user-plus' ?>"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800"><?= $pageTitle ?></h2>
                    <p class="text-xs text-slate-500"><?= $isEdit ? 'Perbarui informasi user' : 'Isi detail user baru' ?></p>
                </div>
            </div>
            <a href="<?= BASE_URL ?>admin/users" 
               class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                <i class="fas fa-times"></i>
            </a>
        </div>
        
        <!-- Modal Body (Scrollable) -->
        <div class="overflow-y-auto max-h-[calc(90vh-140px)]">
            <form id="userForm" action="<?= $formAction ?>" method="POST" enctype="multipart/form-data">
                
                <div class="p-6 space-y-5">
                    
                    <!-- Profile Image Section -->
                    <div class="flex items-center gap-6 p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <!-- Current/Preview Avatar -->
                        <div class="flex-shrink-0">
                            <?php if ($isEdit && !empty($user->profile_image)): ?>
                            <img id="avatarPreview" 
                                 src="<?= STORAGE_URL ?><?= str_replace('storage/', '', htmlspecialchars($user->profile_image)) ?>" 
                                 alt="Profile"
                                 class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-lg">
                            <?php else: ?>
                            <div id="avatarPlaceholder" class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-bold border-4 border-white shadow-lg">
                                <?= $name ? strtoupper(substr($name, 0, 1)) : '?' ?>
                            </div>
                            <img id="avatarPreview" src="" alt="Preview" class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-lg hidden">
                            <?php endif; ?>
                        </div>
                        
                        <!-- Upload Button -->
                        <div class="flex-1">
                            <input type="file" 
                                   id="profile_image" 
                                   name="profile_image" 
                                   accept="image/jpeg,image/png,image/jpg,image/webp"
                                   class="hidden"
                                   onchange="previewProfileImage(this)">
                            <button type="button" 
                                    onclick="document.getElementById('profile_image').click()"
                                    class="px-4 py-2 bg-white border border-slate-300 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-50 transition-colors inline-flex items-center gap-2">
                                <i class="fas fa-camera"></i>
                                <span><?= $isEdit ? 'Ganti Foto' : 'Upload Foto' ?></span>
                            </button>
                            <p class="text-xs text-slate-500 mt-2">PNG, JPG, WEBP (Maks. 2MB)</p>
                        </div>
                    </div>
                    
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="<?= htmlspecialchars($name) ?>"
                                   placeholder="Masukkan nama lengkap"
                                   required
                                   minlength="3"
                                   class="w-full pl-10 pr-3.5 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm">
                        </div>
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="<?= htmlspecialchars($email) ?>"
                                   placeholder="contoh@email.com"
                                   required
                                   class="w-full pl-10 pr-3.5 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm">
                        </div>
                    </div>
                    
                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Nomor Telepon <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                                <i class="fas fa-phone"></i>
                            </span>
                            <input type="tel" 
                                   id="phone" 
                                   name="phone" 
                                   value="<?= htmlspecialchars($phone) ?>"
                                   placeholder="08xxxxxxxxxx"
                                   required
                                   class="w-full pl-10 pr-3.5 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm">
                        </div>
                    </div>
                    
                    <!-- Password (only for create) -->
                    <?php if (!$isEdit): ?>
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Minimal 6 karakter"
                                   required
                                   minlength="6"
                                   class="w-full pl-10 pr-10 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm">
                            <button type="button" 
                                    onclick="togglePassword('password')"
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <i class="fas fa-eye" id="password-icon"></i>
                            </button>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Minimal 6 karakter</p>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Role Selection -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-3">
                            Role User <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <!-- Guest Option -->
                            <label class="relative cursor-pointer">
                                <input type="radio" 
                                       name="role" 
                                       value="guest" 
                                       <?= $role === 'guest' ? 'checked' : '' ?>
                                       class="peer sr-only">
                                <div class="p-4 border-2 border-slate-200 rounded-xl peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-800">Guest</p>
                                            <p class="text-xs text-slate-500">Pengguna biasa</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="absolute top-3 right-3 w-5 h-5 border-2 border-slate-300 rounded-full peer-checked:border-blue-500 peer-checked:bg-blue-500 flex items-center justify-center transition-all">
                                    <i class="fas fa-check text-white text-xs opacity-0 peer-checked:opacity-100"></i>
                                </div>
                            </label>
                            
                            <!-- Admin Option -->
                            <label class="relative cursor-pointer">
                                <input type="radio" 
                                       name="role" 
                                       value="admin" 
                                       <?= $role === 'admin' ? 'checked' : '' ?>
                                       class="peer sr-only">
                                <div class="p-4 border-2 border-slate-200 rounded-xl peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-user-shield"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-800">Admin</p>
                                            <p class="text-xs text-slate-500">Akses penuh</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="absolute top-3 right-3 w-5 h-5 border-2 border-slate-300 rounded-full peer-checked:border-purple-500 peer-checked:bg-purple-500 flex items-center justify-center transition-all">
                                    <i class="fas fa-check text-white text-xs opacity-0 peer-checked:opacity-100"></i>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <?php if ($isEdit): ?>
                    <!-- Info Note for Edit -->
                    <div class="flex items-start gap-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                        <i class="fas fa-info-circle text-amber-500 mt-0.5"></i>
                        <div class="text-sm text-amber-700">
                            <p class="font-medium">Catatan:</p>
                            <p>Untuk mengubah password, gunakan fitur "Reset Password" di halaman detail user.</p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                </div>
                
                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-3">
                    <a href="<?= BASE_URL ?>admin/users" 
                       class="px-4 py-2.5 border border-slate-300 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-100 transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-5 py-2.5 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors inline-flex items-center gap-2">
                        <i class="fas fa-<?= $isEdit ? 'save' : 'user-plus' ?>"></i>
                        <span><?= $isEdit ? 'Simpan Perubahan' : 'Tambah User' ?></span>
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
    
    /* Radio checked state for icon */
    input[type="radio"]:checked ~ div .fa-check {
        opacity: 1;
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
        window.location.href = '<?= BASE_URL ?>admin/users';
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        window.location.href = '<?= BASE_URL ?>admin/users';
    }
});

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

// Preview profile image
function previewProfileImage(input) {
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
            const placeholder = document.getElementById('avatarPlaceholder');
            const preview = document.getElementById('avatarPreview');
            
            if (placeholder) {
                placeholder.classList.add('hidden');
            }
            
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        }
        
        reader.readAsDataURL(file);
    }
}

// Update avatar placeholder when name changes
document.getElementById('name')?.addEventListener('input', function() {
    const placeholder = document.getElementById('avatarPlaceholder');
    if (placeholder && !document.getElementById('avatarPreview').src) {
        const initial = this.value ? this.value.charAt(0).toUpperCase() : '?';
        placeholder.textContent = initial;
    }
});
</script>