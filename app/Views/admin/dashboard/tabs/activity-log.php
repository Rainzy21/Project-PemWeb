<?php
?>
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-history text-purple-500"></i>
            </div>
            <div>
                <h5 class="font-semibold text-slate-800">Activity Log</h5>
                <p class="text-xs text-slate-500"><?= count($activities ?? []) ?> recent activities</p>
            </div>
        </div>
        <button class="text-sm text-slate-500 hover:text-slate-700">
            <i class="fas fa-sync-alt"></i>
        </button>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Date/Time</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Guest</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Room</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Activity</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($activities)): ?>
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-inbox text-slate-400 text-2xl"></i>
                        </div>
                        <p class="text-slate-500">No activities found</p>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($activities as $activity): 
                        $guestName = is_object($activity) ? ($activity->guest_name ?? '') : ($activity['guest_name'] ?? '');
                        $roomNumber = is_object($activity) ? ($activity->room_number ?? '') : ($activity['room_number'] ?? '');
                        $activityType = is_object($activity) ? ($activity->activity_type ?? 'booking') : ($activity['activity_type'] ?? 'booking');
                        $status = is_object($activity) ? ($activity->status ?? '') : ($activity['status'] ?? '');
                        $updatedAt = is_object($activity) ? ($activity->updated_at ?? '') : ($activity['updated_at'] ?? '');
                        
                        $statusClasses = match($status) {
                            'confirmed' => 'bg-green-100 text-green-700',
                            'pending' => 'bg-amber-100 text-amber-700',
                            'checked_in' => 'bg-blue-100 text-blue-700',
                            'checked_out' => 'bg-slate-100 text-slate-600',
                            'cancelled' => 'bg-red-100 text-red-700',
                            default => 'bg-slate-100 text-slate-600'
                        };
                    ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm text-slate-700"><?= date('d M Y', strtotime($updatedAt)) ?></div>
                            <div class="text-xs text-slate-500"><?= date('H:i', strtotime($updatedAt)) ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-slate-200 rounded-full flex items-center justify-center text-sm font-medium text-slate-600">
                                    <?= strtoupper(substr($guestName, 0, 1)) ?>
                                </div>
                                <span class="font-medium text-slate-700"><?= htmlspecialchars($guestName) ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">Room <?= $roomNumber ?></td>
                        <td class="px-6 py-4">
                            <span class="bg-indigo-100 text-indigo-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                <?= ucfirst($activityType) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="<?= $statusClasses ?> text-xs font-semibold px-2.5 py-1 rounded-full">
                                <?= ucfirst(str_replace('_', ' ', $status)) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>