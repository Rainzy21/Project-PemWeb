<?php
?>
<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Kelola Kamar</h1>
        <p class="text-slate-500 text-sm mt-1">Kelola semua kamar hotel</p>
    </div>
    <a href="<?= BASE_URL ?>admin/rooms/create" 
       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors">
        <i class="fas fa-plus"></i>
        <span>Tambah Kamar</span>
    </a>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
    <!-- Total Kamar -->
    <div class="bg-white border border-slate-200 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-slate-100 text-slate-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-door-open"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800"><?= $stats['total'] ?? 0 ?></p>
                <p class="text-xs text-slate-500">Total Kamar</p>
            </div>
        </div>
    </div>
    
    <!-- Tersedia -->
    <div class="bg-white border border-slate-200 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-green-100 text-green-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-green-600"><?= $stats['available'] ?? 0 ?></p>
                <p class="text-xs text-slate-500">Tersedia</p>
            </div>
        </div>
    </div>
    
    <!-- Standard -->
    <div class="bg-white border border-slate-200 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-bed"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-blue-600"><?= $stats['standard'] ?? 0 ?></p>
                <p class="text-xs text-slate-500">Standard</p>
            </div>
        </div>
    </div>
    
    <!-- Deluxe -->
    <div class="bg-white border border-slate-200 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-star"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-purple-600"><?= $stats['deluxe'] ?? 0 ?></p>
                <p class="text-xs text-slate-500">Deluxe</p>
            </div>
        </div>
    </div>
    
    <!-- Suite -->
    <div class="bg-white border border-slate-200 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-yellow-100 text-yellow-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-crown"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-yellow-600"><?= $stats['suite'] ?? 0 ?></p>
                <p class="text-xs text-slate-500">Suite</p>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="bg-white border border-slate-200 rounded-xl p-4 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
        <!-- Filter by Type -->
        <div class="flex flex-wrap items-center gap-2">
            <span class="text-sm font-medium text-slate-600">Tipe:</span>
            <a href="<?= BASE_URL ?>admin/rooms<?= $selectedStatus ? '?status=' . $selectedStatus : '' ?>" 
               class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors <?= !$selectedType ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
                Semua
            </a>
            <a href="<?= BASE_URL ?>admin/rooms?type=standard<?= $selectedStatus ? '&status=' . $selectedStatus : '' ?>" 
               class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors <?= $selectedType === 'standard' ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
                Standard
            </a>
            <a href="<?= BASE_URL ?>admin/rooms?type=deluxe<?= $selectedStatus ? '&status=' . $selectedStatus : '' ?>" 
               class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors <?= $selectedType === 'deluxe' ? 'bg-purple-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
                Deluxe
            </a>
            <a href="<?= BASE_URL ?>admin/rooms?type=suite<?= $selectedStatus ? '&status=' . $selectedStatus : '' ?>" 
               class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors <?= $selectedType === 'suite' ? 'bg-yellow-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
                Suite
            </a>
        </div>
        
        <!-- Divider -->
        <div class="hidden sm:block w-px h-6 bg-slate-200"></div>
        
        <!-- Filter by Status -->
        <div class="flex flex-wrap items-center gap-2">
            <span class="text-sm font-medium text-slate-600">Status:</span>
            <a href="<?= BASE_URL ?>admin/rooms<?= $selectedType ? '?type=' . $selectedType : '' ?>" 
               class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors <?= !$selectedStatus ? 'bg-green-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
                Semua
            </a>
            <a href="<?= BASE_URL ?>admin/rooms?status=available<?= $selectedType ? '&type=' . $selectedType : '' ?>" 
               class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors <?= $selectedStatus === 'available' ? 'bg-green-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
                Tersedia
            </a>
            <a href="<?= BASE_URL ?>admin/rooms?status=unavailable<?= $selectedType ? '&type=' . $selectedType : '' ?>" 
               class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors <?= $selectedStatus === 'unavailable' ? 'bg-red-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
                Tidak Tersedia
            </a>
        </div>
    </div>
</div>

<!-- Bulk Actions Form -->
<form id="bulkForm" action="<?= BASE_URL ?>admin/rooms/bulk-update" method="POST">
    
    <!-- Bulk Action Bar -->
    <div id="bulkActionBar" class="hidden bg-blue-50 border border-blue-200 rounded-xl p-4 mb-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-square text-blue-500"></i>
                <span class="text-sm font-medium text-blue-700">
                    <span id="selectedCount">0</span> kamar dipilih
                </span>
            </div>
            <div class="flex items-center gap-2">
                <select name="action" class="px-3 py-2 text-sm border border-blue-200 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Pilih Aksi</option>
                    <option value="set_available">Set Tersedia</option>
                    <option value="set_unavailable">Set Tidak Tersedia</option>
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

    <!-- Rooms Table -->
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-left">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 rounded border-slate-300 text-blue-500 focus:ring-blue-500">
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Kamar</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Tipe</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Harga/Malam</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php if (empty($rooms)): ?>
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-door-open text-2xl text-slate-400"></i>
                                </div>
                                <h3 class="text-slate-600 font-medium mb-1">Tidak ada kamar</h3>
                                <p class="text-slate-400 text-sm mb-4">Belum ada data kamar<?= $selectedType ? ' dengan tipe ' . $selectedType : '' ?><?= $selectedStatus ? ' dengan status ' . $selectedStatus : '' ?></p>
                                <a href="<?= BASE_URL ?>admin/rooms/create" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors">
                                    <i class="fas fa-plus"></i>
                                    <span>Tambah Kamar</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($rooms as $room): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3">
                            <input type="checkbox" name="ids[]" value="<?= $room->id ?>" class="room-checkbox w-4 h-4 rounded border-slate-300 text-blue-500 focus:ring-blue-500">
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <?php if (!empty($room->image)): ?>
                                <img src="<?= STORAGE_URL ?><?= str_replace('storage/', '', htmlspecialchars($room->image)) ?>" 
                                     alt="Room <?= htmlspecialchars($room->room_number) ?>"
                                     class="w-12 h-12 rounded-lg object-cover">
                                <?php else: ?>
                                <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-image text-slate-400"></i>
                                </div>
                                <?php endif; ?>
                                <div>
                                    <p class="font-semibold text-slate-800"><?= htmlspecialchars($room->room_number) ?></p>
                                    <p class="text-xs text-slate-500 line-clamp-1"><?= htmlspecialchars($room->description ?? '-') ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <?php
                            $typeClasses = [
                                'standard' => 'bg-blue-100 text-blue-700',
                                'deluxe' => 'bg-purple-100 text-purple-700',
                                'suite' => 'bg-yellow-100 text-yellow-700'
                            ];
                            $typeClass = $typeClasses[$room->room_type] ?? 'bg-slate-100 text-slate-700';
                            ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium <?= $typeClass ?>">
                                <?= ucfirst($room->room_type) ?>
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-semibold text-slate-800">Rp <?= number_format($room->price_per_night, 0, ',', '.') ?></span>
                        </td>
                        <td class="px-4 py-3">
                            <?php if ($room->is_available): ?>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                Tersedia
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                Tidak Tersedia
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1">
                                <!-- Detail -->
                                <a href="<?= BASE_URL ?>admin/rooms/<?= $room->id ?>" 
                                   class="p-2 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                   title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <!-- Edit -->
                                <a href="<?= BASE_URL ?>admin/rooms/<?= $room->id ?>/edit" 
                                   class="p-2 text-slate-500 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <!-- Toggle Availability -->
                                <form action="<?= BASE_URL ?>admin/rooms/<?= $room->id ?>/toggle" method="POST" class="inline">
                                    <button type="submit" 
                                            class="p-2 text-slate-500 hover:bg-slate-100 rounded-lg transition-colors"
                                            title="<?= $room->is_available ? 'Set Tidak Tersedia' : 'Set Tersedia' ?>"
                                            onclick="return confirm('<?= $room->is_available ? 'Set kamar ini tidak tersedia?' : 'Set kamar ini tersedia?' ?>')">
                                        <i class="fas fa-<?= $room->is_available ? 'toggle-on text-green-500' : 'toggle-off text-slate-400' ?>"></i>
                                    </button>
                                </form>
                                
                                <!-- Delete -->
                                <form action="<?= BASE_URL ?>admin/rooms/<?= $room->id ?>/delete" method="POST" class="inline">
                                    <button type="submit" 
                                            class="p-2 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Hapus"
                                            onclick="return confirm('Hapus kamar ini? Pastikan tidak ada booking aktif.')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Table Footer -->
        <?php if (!empty($rooms)): ?>
        <div class="px-4 py-3 bg-slate-50 border-t border-slate-200">
            <p class="text-sm text-slate-600">
                Menampilkan <span class="font-medium"><?= count($rooms) ?></span> kamar
                <?= $selectedType ? 'tipe <span class="font-medium">' . ucfirst($selectedType) . '</span>' : '' ?>
                <?= $selectedStatus ? ($selectedStatus === 'available' ? '(tersedia)' : '(tidak tersedia)') : '' ?>
            </p>
        </div>
        <?php endif; ?>
    </div>
</form>

<!-- JavaScript for Bulk Selection -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.room-checkbox');
    const bulkActionBar = document.getElementById('bulkActionBar');
    const selectedCount = document.getElementById('selectedCount');

    function updateBulkActionBar() {
        const checked = document.querySelectorAll('.room-checkbox:checked');
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
            const allChecked = document.querySelectorAll('.room-checkbox:checked').length === checkboxes.length;
            if (selectAll) selectAll.checked = allChecked;
            updateBulkActionBar();
        });
    });
});
</script>