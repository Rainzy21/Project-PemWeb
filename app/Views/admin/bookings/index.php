<?php
?>
<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Kelola Booking</h1>
        <p class="text-slate-500 text-sm mt-1">Lihat dan kelola semua reservasi tamu</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="<?= BASE_URL ?>admin/bookings/export<?= $selectedStatus ? '?status=' . $selectedStatus : '' ?>" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fas fa-file-export"></i>
            <span>Export CSV</span>
        </a>
        <a href="<?= BASE_URL ?>admin/bookings/create" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fas fa-plus"></i>
            <span>Booking Baru</span>
        </a>
    </div>
</div>

<!-- Quick Links -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
    <a href="<?= BASE_URL ?>admin/bookings/today-checkins" 
       class="flex items-center gap-4 p-4 bg-white border border-slate-200 rounded-xl hover:shadow-md transition-all">
        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
            <i class="fas fa-sign-in-alt text-xl"></i>
        </div>
        <div>
            <h3 class="font-semibold text-slate-800">Check-in Hari Ini</h3>
            <p class="text-sm text-slate-500"><?= date('d M Y') ?></p>
        </div>
        <i class="fas fa-chevron-right text-slate-400 ml-auto"></i>
    </a>
    <a href="<?= BASE_URL ?>admin/bookings/today-checkouts" 
       class="flex items-center gap-4 p-4 bg-white border border-slate-200 rounded-xl hover:shadow-md transition-all">
        <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center">
            <i class="fas fa-sign-out-alt text-xl"></i>
        </div>
        <div>
            <h3 class="font-semibold text-slate-800">Check-out Hari Ini</h3>
            <p class="text-sm text-slate-500"><?= date('d M Y') ?></p>
        </div>
        <i class="fas fa-chevron-right text-slate-400 ml-auto"></i>
    </a>
</div>

<!-- Filter Status -->
<div class="bg-white border border-slate-200 rounded-xl p-4 mb-6">
    <div class="flex flex-wrap items-center gap-2">
        <span class="text-sm font-medium text-slate-600 mr-2">Filter Status:</span>
        <a href="<?= BASE_URL ?>admin/bookings" 
           class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors <?= !$selectedStatus ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
            Semua
        </a>
        <a href="<?= BASE_URL ?>admin/bookings?status=pending" 
           class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors <?= $selectedStatus === 'pending' ? 'bg-yellow-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
            Pending
        </a>
        <a href="<?= BASE_URL ?>admin/bookings?status=confirmed" 
           class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors <?= $selectedStatus === 'confirmed' ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
            Confirmed
        </a>
        <a href="<?= BASE_URL ?>admin/bookings?status=checked_in" 
           class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors <?= $selectedStatus === 'checked_in' ? 'bg-green-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
            Checked In
        </a>
        <a href="<?= BASE_URL ?>admin/bookings?status=checked_out" 
           class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors <?= $selectedStatus === 'checked_out' ? 'bg-purple-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
            Checked Out
        </a>
        <a href="<?= BASE_URL ?>admin/bookings?status=cancelled" 
           class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors <?= $selectedStatus === 'cancelled' ? 'bg-red-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
            Cancelled
        </a>
    </div>
</div>

<!-- Bookings Table -->
<div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Tamu</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Kamar</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Check-in</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Check-out</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Total</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if (empty($bookings)): ?>
                <tr>
                    <td colspan="8" class="px-4 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-calendar-times text-2xl text-slate-400"></i>
                            </div>
                            <h3 class="text-slate-600 font-medium mb-1">Tidak ada booking</h3>
                            <p class="text-slate-400 text-sm">Belum ada data booking<?= $selectedStatus ? ' dengan status ' . $selectedStatus : '' ?></p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($bookings as $booking): ?>
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-4 py-3">
                        <span class="font-medium text-slate-800">#<?= $booking->id ?></span>
                    </td>
                    <td class="px-4 py-3">
                        <div>
                            <p class="font-medium text-slate-800"><?= htmlspecialchars($booking->guest_name) ?></p>
                            <p class="text-xs text-slate-500"><?= htmlspecialchars($booking->guest_email) ?></p>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div>
                            <p class="font-medium text-slate-800"><?= htmlspecialchars($booking->room_number) ?></p>
                            <p class="text-xs text-slate-500"><?= htmlspecialchars($booking->room_type) ?></p>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-slate-600"><?= date('d M Y', strtotime($booking->check_in_date)) ?></span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-slate-600"><?= date('d M Y', strtotime($booking->check_out_date)) ?></span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="font-semibold text-slate-800">Rp <?= number_format($booking->total_price, 0, ',', '.') ?></span>
                    </td>
                    <td class="px-4 py-3">
                        <?php
                        $statusClasses = [
                            'pending' => 'bg-yellow-100 text-yellow-700',
                            'confirmed' => 'bg-blue-100 text-blue-700',
                            'checked_in' => 'bg-green-100 text-green-700',
                            'checked_out' => 'bg-purple-100 text-purple-700',
                            'cancelled' => 'bg-red-100 text-red-700'
                        ];
                        $statusLabels = [
                            'pending' => 'Pending',
                            'confirmed' => 'Confirmed',
                            'checked_in' => 'Checked In',
                            'checked_out' => 'Checked Out',
                            'cancelled' => 'Cancelled'
                        ];
                        $class = $statusClasses[$booking->status] ?? 'bg-slate-100 text-slate-700';
                        $label = $statusLabels[$booking->status] ?? ucfirst($booking->status);
                        ?>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium <?= $class ?>">
                            <?= $label ?>
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1">
                            <!-- Detail -->
                            <a href="<?= BASE_URL ?>admin/bookings/<?= $booking->id ?>" 
                               class="p-2 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                               title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            <!-- Action Buttons based on Status -->
                            <?php if ($booking->status === 'pending'): ?>
                            <form action="<?= BASE_URL ?>admin/bookings/<?= $booking->id ?>/confirm" method="POST" class="inline">
                                <button type="submit" 
                                        class="p-2 text-slate-500 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                        title="Konfirmasi"
                                        onclick="return confirm('Konfirmasi booking ini?')">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                            
                            <?php if ($booking->status === 'confirmed'): ?>
                            <form action="<?= BASE_URL ?>admin/bookings/<?= $booking->id ?>/check-in" method="POST" class="inline">
                                <button type="submit" 
                                        class="p-2 text-slate-500 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                        title="Check In"
                                        onclick="return confirm('Check-in tamu ini?')">
                                    <i class="fas fa-sign-in-alt"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                            
                            <?php if ($booking->status === 'checked_in'): ?>
                            <form action="<?= BASE_URL ?>admin/bookings/<?= $booking->id ?>/check-out" method="POST" class="inline">
                                <button type="submit" 
                                        class="p-2 text-slate-500 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-colors"
                                        title="Check Out"
                                        onclick="return confirm('Check-out tamu ini?')">
                                    <i class="fas fa-sign-out-alt"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                            
                            <?php if (in_array($booking->status, ['pending', 'confirmed'])): ?>
                            <form action="<?= BASE_URL ?>admin/bookings/<?= $booking->id ?>/cancel" method="POST" class="inline">
                                <button type="submit" 
                                        class="p-2 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                        title="Batalkan"
                                        onclick="return confirm('Batalkan booking ini?')">
                                    <i class="fas fa-ban"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                            
                            <!-- Invoice -->
                            <a href="<?= BASE_URL ?>admin/bookings/<?= $booking->id ?>/invoice" 
                               class="p-2 text-slate-500 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors"
                               title="Invoice"
                               target="_blank">
                                <i class="fas fa-file-invoice"></i>
                            </a>
                            
                            <!-- Delete (only for cancelled bookings) -->
                            <?php if ($booking->status === 'cancelled'): ?>
                            <form action="<?= BASE_URL ?>admin/bookings/<?= $booking->id ?>/delete" method="POST" class="inline">
                                <button type="submit" 
                                        class="p-2 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                        title="Hapus"
                                        onclick="return confirm('Hapus booking ini secara permanen?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
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
    <?php if (!empty($bookings)): ?>
    <div class="px-4 py-3 bg-slate-50 border-t border-slate-200">
        <p class="text-sm text-slate-600">
            Menampilkan <span class="font-medium"><?= count($bookings) ?></span> booking
            <?= $selectedStatus ? 'dengan status <span class="font-medium">' . ucfirst(str_replace('_', ' ', $selectedStatus)) . '</span>' : '' ?>
        </p>
    </div>
    <?php endif; ?>
</div>