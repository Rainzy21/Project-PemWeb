<?php
?>
<!-- Date Filter -->
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <form method="GET" class="flex flex-col md:flex-row gap-4 items-end">
        <input type="hidden" name="tab" value="reports">
        <div class="flex-1">
            <label class="block text-sm font-medium text-slate-700 mb-2">Start Date</label>
            <input type="date" name="start_date" value="<?= $startDate ?? '' ?>" 
                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="flex-1">
            <label class="block text-sm font-medium text-slate-700 mb-2">End Date</label>
            <input type="date" name="end_date" value="<?= $endDate ?? '' ?>" 
                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors flex items-center gap-2">
                <i class="fas fa-filter"></i>
                <span>Filter</span>
            </button>
            <a href="<?= BASE_URL ?>admin/reports/export?type=bookings&start_date=<?= $startDate ?? '' ?>&end_date=<?= $endDate ?? '' ?>" 
               class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors flex items-center gap-2">
                <i class="fas fa-download"></i>
                <span>Export CSV</span>
            </a>
        </div>
    </form>
</div>

<!-- Report Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <!-- Revenue Report -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
            <h5 class="text-white font-semibold flex items-center gap-2">
                <i class="fas fa-money-bill-wave"></i>
                Revenue Report
            </h5>
        </div>
        <div class="p-6">
            <?php 
            $totalRevenue = is_object($revenueReport) 
                ? ($revenueReport->total ?? 0) 
                : ($revenueReport['total'] ?? 0);
            ?>
            <h3 class="text-3xl font-bold text-green-600">Rp <?= number_format($totalRevenue, 0, ',', '.') ?></h3>
            <p class="text-slate-500 text-sm mt-1">Total revenue for selected period</p>
        </div>
    </div>

    <!-- Occupancy Report -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 px-6 py-4">
            <h5 class="text-white font-semibold flex items-center gap-2">
                <i class="fas fa-bed"></i>
                Occupancy Report
            </h5>
        </div>
        <div class="p-6">
            <?php 
            $occupancyRate = is_object($occupancyReport) 
                ? ($occupancyReport->rate ?? 0) 
                : ($occupancyReport['rate'] ?? 0);
            ?>
            <h3 class="text-3xl font-bold text-cyan-600"><?= number_format($occupancyRate, 1) ?>%</h3>
            <p class="text-slate-500 text-sm mt-1">Average occupancy rate</p>
        </div>
    </div>
</div>

<!-- Booking Summary -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
            <i class="fas fa-list text-indigo-500"></i>
        </div>
        <h5 class="font-semibold text-slate-800">Booking Summary</h5>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Count</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Total Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($bookingSummary)): ?>
                <tr>
                    <td colspan="3" class="px-6 py-8 text-center text-slate-500">No data available</td>
                </tr>
                <?php else: ?>
                    <?php foreach ($bookingSummary as $summary): 
                        $status = is_object($summary) ? ($summary->status ?? '') : ($summary['status'] ?? '');
                        $count = is_object($summary) ? ($summary->count ?? 0) : ($summary['count'] ?? 0);
                        $total = is_object($summary) ? ($summary->total ?? 0) : ($summary['total'] ?? 0);
                        
                        $statusClasses = match($status) {
                            'confirmed' => 'bg-green-100 text-green-700',
                            'pending' => 'bg-amber-100 text-amber-700',
                            'checked_in' => 'bg-blue-100 text-blue-700',
                            'checked_out' => 'bg-slate-100 text-slate-600',
                            'cancelled' => 'bg-red-100 text-red-700',
                            default => 'bg-slate-100 text-slate-600'
                        };
                    ?>
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <span class="<?= $statusClasses ?> text-xs font-semibold px-2.5 py-1 rounded-full">
                                <?= ucfirst(str_replace('_', ' ', $status)) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-700 font-medium"><?= $count ?></td>
                        <td class="px-6 py-4 text-slate-700">Rp <?= number_format($total, 0, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>