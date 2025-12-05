<?php
?>
<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Kelola Users</h1>
        <p class="text-slate-500 text-sm mt-1">Kelola semua pengguna sistem</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="<?= BASE_URL ?>admin/users/export<?= $selectedRole ? '?role=' . $selectedRole : '' ?>" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fas fa-file-export"></i>
            <span>Export CSV</span>
        </a>
        <a href="<?= BASE_URL ?>admin/users/create" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fas fa-user-plus"></i>
            <span>Tambah User</span>
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <!-- Total Users -->
    <div class="bg-white border border-slate-200 rounded-xl p-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-slate-100 text-slate-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800"><?= $stats['total'] ?? 0 ?></p>
                <p class="text-sm text-slate-500">Total Users</p>
            </div>
        </div>
    </div>
    
    <!-- Guests -->
    <div class="bg-white border border-slate-200 rounded-xl p-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-user text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-blue-600"><?= $stats['guests'] ?? 0 ?></p>
                <p class="text-sm text-slate-500">Guests</p>
            </div>
        </div>
    </div>
    
    <!-- Admins -->
    <div class="bg-white border border-slate-200 rounded-xl p-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-user-shield text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-purple-600"><?= $stats['admins'] ?? 0 ?></p>
                <p class="text-sm text-slate-500">Admins</p>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Search -->
<div class="bg-white border border-slate-200 rounded-xl p-4 mb-6">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <!-- Filter by Role -->
        <div class="flex flex-wrap items-center gap-2">
            <span class="text-sm font-medium text-slate-600 mr-1">Role:</span>
            <a href="<?= BASE_URL ?>admin/users<?= $searchQuery ? '?search=' . urlencode($searchQuery) : '' ?>" 
               class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors <?= !$selectedRole ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
                Semua
            </a>
            <a href="<?= BASE_URL ?>admin/users?role=guest<?= $searchQuery ? '&search=' . urlencode($searchQuery) : '' ?>" 
               class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors <?= $selectedRole === 'guest' ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
                Guest
            </a>
            <a href="<?= BASE_URL ?>admin/users?role=admin<?= $searchQuery ? '&search=' . urlencode($searchQuery) : '' ?>" 
               class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors <?= $selectedRole === 'admin' ? 'bg-purple-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
                Admin
            </a>
        </div>
        
        <!-- Search -->
        <form action="<?= BASE_URL ?>admin/users" method="GET" class="flex items-center gap-2">
            <?php if ($selectedRole): ?>
            <input type="hidden" name="role" value="<?= htmlspecialchars($selectedRole) ?>">
            <?php endif; ?>
            <div class="relative">
                <input type="text" 
                       name="search" 
                       value="<?= htmlspecialchars($searchQuery ?? '') ?>"
                       placeholder="Cari nama, email, phone..."
                       class="w-64 pl-10 pr-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
            </div>
            <button type="submit" 
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-lg transition-colors">
                Cari
            </button>
            <?php if ($searchQuery): ?>
            <a href="<?= BASE_URL ?>admin/users<?= $selectedRole ? '?role=' . $selectedRole : '' ?>" 
               class="px-3 py-2 text-slate-500 hover:text-red-500 transition-colors"
               title="Clear search">
                <i class="fas fa-times"></i>
            </a>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Bulk Actions Form -->
<form id="bulkForm" action="<?= BASE_URL ?>admin/users/bulk-action" method="POST">
    
    <!-- Bulk Action Bar -->
    <div id="bulkActionBar" class="hidden bg-blue-50 border border-blue-200 rounded-xl p-4 mb-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-square text-blue-500"></i>
                <span class="text-sm font-medium text-blue-700">
                    <span id="selectedCount">0</span> user dipilih
                </span>
            </div>
            <div class="flex items-center gap-2">
                <select name="action" class="px-3 py-2 text-sm border border-blue-200 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Pilih Aksi</option>
                    <option value="make_admin">Jadikan Admin</option>
                    <option value="make_guest">Jadikan Guest</option>
                    <option value="delete">Hapus</option>
                </select>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors"
                        onclick="return confirm('Yakin ingin melakukan aksi ini?')">
                    Terapkan
                </button>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-left">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 rounded border-slate-300 text-blue-500 focus:ring-blue-500">
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">User</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Phone</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Role</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Terdaftar</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-users text-2xl text-slate-400"></i>
                                </div>
                                <h3 class="text-slate-600 font-medium mb-1">Tidak ada user</h3>
                                <p class="text-slate-400 text-sm mb-4">
                                    <?php if ($searchQuery): ?>
                                        Tidak ditemukan user dengan kata kunci "<?= htmlspecialchars($searchQuery) ?>"
                                    <?php elseif ($selectedRole): ?>
                                        Tidak ada user dengan role <?= $selectedRole ?>
                                    <?php else: ?>
                                        Belum ada data user
                                    <?php endif; ?>
                                </p>
                                <a href="<?= BASE_URL ?>admin/users/create" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors">
                                    <i class="fas fa-user-plus"></i>
                                    <span>Tambah User</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($users as $user): ?>
                    <?php $isSelf = isset($_SESSION['user_id']) && $user->id == $_SESSION['user_id']; ?>
                    <tr class="hover:bg-slate-50 transition-colors <?= $isSelf ? 'bg-blue-50/50' : '' ?>">
                        <td class="px-4 py-3">
                            <?php if (!$isSelf): ?>
                            <input type="checkbox" name="ids[]" value="<?= $user->id ?>" class="user-checkbox w-4 h-4 rounded border-slate-300 text-blue-500 focus:ring-blue-500">
                            <?php else: ?>
                            <span class="text-slate-300" title="Tidak dapat memilih diri sendiri">
                                <i class="fas fa-lock text-xs"></i>
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <?php if (!empty($user->profile_image)): ?>
                              <img src="<?= STORAGE_URL ?><?= str_replace('storage/', '', htmlspecialchars($user->profile_image)) ?>" 
                                     alt="<?= htmlspecialchars($user->name) ?>"
                                     class="w-10 h-10 rounded-full object-cover border-2 border-white shadow">
                                <?php else: ?>
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold shadow">
                                    <?= strtoupper(substr($user->name, 0, 1)) ?>
                                </div>
                                <?php endif; ?>
                                <div>
                                    <p class="font-medium text-slate-800">
                                        <?= htmlspecialchars($user->name) ?>
                                        <?php if ($isSelf): ?>
                                        <span class="text-xs text-blue-500 font-normal">(Anda)</span>
                                        <?php endif; ?>
                                    </p>
                                    <p class="text-xs text-slate-500">ID: <?= $user->id ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <a href="mailto:<?= htmlspecialchars($user->email) ?>" class="text-slate-600 hover:text-blue-500 transition-colors">
                                <?= htmlspecialchars($user->email) ?>
                            </a>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-slate-600"><?= htmlspecialchars($user->phone ?? '-') ?></span>
                        </td>
                        <td class="px-4 py-3">
                            <?php if ($user->role === 'admin'): ?>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                <i class="fas fa-shield-alt text-[10px]"></i>
                                Admin
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                <i class="fas fa-user text-[10px]"></i>
                                Guest
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-slate-600 text-sm"><?= date('d M Y', strtotime($user->created_at)) ?></span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1">
                                <!-- Detail -->
                                <a href="<?= BASE_URL ?>admin/users/<?= $user->id ?>" 
                                   class="p-2 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                   title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <!-- Edit -->
                                <a href="<?= BASE_URL ?>admin/users/<?= $user->id ?>/edit" 
                                   class="p-2 text-slate-500 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <?php if (!$isSelf): ?>
                                <!-- Toggle Role -->
                                <form action="<?= BASE_URL ?>admin/users/<?= $user->id ?>/toggle-role" method="POST" class="inline">
                                    <button type="submit" 
                                            class="p-2 text-slate-500 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors"
                                            title="<?= $user->role === 'admin' ? 'Jadikan Guest' : 'Jadikan Admin' ?>"
                                            onclick="return confirm('<?= $user->role === 'admin' ? 'Ubah role menjadi Guest?' : 'Ubah role menjadi Admin?' ?>')">
                                        <i class="fas fa-<?= $user->role === 'admin' ? 'user' : 'user-shield' ?>"></i>
                                    </button>
                                </form>
                                
                                <!-- Delete -->
                                <form action="<?= BASE_URL ?>admin/users/<?= $user->id ?>/delete" method="POST" class="inline">
                                    <button type="submit" 
                                            class="p-2 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Hapus"
                                            onclick="return confirm('Hapus user ini? Pastikan tidak ada booking aktif.')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <?php else: ?>
                                <span class="p-2 text-slate-300" title="Tidak dapat mengubah diri sendiri">
                                    <i class="fas fa-ban"></i>
                                </span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Table Footer -->
        <?php if (!empty($users)): ?>
        <div class="px-4 py-3 bg-slate-50 border-t border-slate-200">
            <p class="text-sm text-slate-600">
                Menampilkan <span class="font-medium"><?= count($users) ?></span> user
                <?= $selectedRole ? 'dengan role <span class="font-medium">' . ucfirst($selectedRole) . '</span>' : '' ?>
                <?= $searchQuery ? 'untuk pencarian "<span class="font-medium">' . htmlspecialchars($searchQuery) . '</span>"' : '' ?>
            </p>
        </div>
        <?php endif; ?>
    </div>
</form>

<!-- JavaScript for Bulk Selection -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.user-checkbox');
    const bulkActionBar = document.getElementById('bulkActionBar');
    const selectedCount = document.getElementById('selectedCount');

    function updateBulkActionBar() {
        const checked = document.querySelectorAll('.user-checkbox:checked');
        if (checked.length > 0) {
            bulkActionBar.classList.remove('hidden');
            selectedCount.textContent = checked.length;
        } else {
            bulkActionBar.classList.add('hidden');
        }
    }

    selectAll?.addEventListener('change', function() {
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateBulkActionBar();
    });

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            const allChecked = document.querySelectorAll('.user-checkbox:checked').length === checkboxes.length;
            if (selectAll) selectAll.checked = allChecked;
            updateBulkActionBar();
        });
    });
});
</script>