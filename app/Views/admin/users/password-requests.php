<?php
/**
 * Password Reset Requests View
 * Menampilkan dan mengelola permintaan reset password
 */

function formatDate($date) {
    return date('d M Y H:i', strtotime($date));
}

function getStatusBadge($status) {
    $badges = [
        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Pending'],
        'processed' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Selesai'],
        'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Ditolak'],
    ];
    $badge = $badges[$status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($status)];
    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $badge['bg'] . ' ' . $badge['text'] . '">' . $badge['label'] . '</span>';
}

$pendingCount = count($requests ?? []);
?>

<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Password Reset Requests</h1>
        <p class="text-slate-500 text-sm mt-1">Kelola permintaan reset password dari user</p>
    </div>
    <a href="<?= BASE_URL ?>admin/users" 
       class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-lg transition-colors">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali ke Users</span>
    </a>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white border border-slate-200 rounded-xl p-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-clock text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-yellow-600"><?= $pendingCount ?></p>
                <p class="text-sm text-slate-500">Pending</p>
            </div>
        </div>
    </div>
    <div class="bg-white border border-slate-200 rounded-xl p-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-check text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-green-600">
                    <?= count(array_filter($allRequests ?? [], fn($r) => $r->status === 'processed')) ?>
                </p>
                <p class="text-sm text-slate-500">Diproses</p>
            </div>
        </div>
    </div>
    <div class="bg-white border border-slate-200 rounded-xl p-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-red-100 text-red-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-times text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-red-600">
                    <?= count(array_filter($allRequests ?? [], fn($r) => $r->status === 'rejected')) ?>
                </p>
                <p class="text-sm text-slate-500">Ditolak</p>
            </div>
        </div>
    </div>
</div>

<!-- Pending Requests -->
<div class="bg-white border border-slate-200 rounded-xl overflow-hidden mb-6">
    <div class="px-6 py-4 bg-yellow-50 border-b border-yellow-100">
        <h2 class="font-semibold text-yellow-800 flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i>
            Request Pending (<?= $pendingCount ?>)
        </h2>
    </div>

    <?php if (empty($requests)): ?>
        <div class="p-12 text-center">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check-circle text-2xl text-slate-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-slate-800 mb-2">Tidak Ada Request Pending</h3>
            <p class="text-slate-500">Semua permintaan reset password sudah diproses</p>
        </div>
    <?php else: ?>
        <div class="divide-y divide-slate-100">
            <?php foreach ($requests as $req): ?>
                <div class="p-6 hover:bg-slate-50 transition">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <!-- User Info -->
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-xl font-bold text-blue-600">
                                    <?= strtoupper(substr($req->user_name ?? 'U', 0, 1)) ?>
                                </span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-800"><?= htmlspecialchars($req->user_name) ?></h3>
                                <p class="text-sm text-slate-500"><?= htmlspecialchars($req->email) ?></p>
                                <p class="text-xs text-slate-400 mt-1">
                                    <i class="fas fa-clock mr-1"></i>
                                    <?= formatDate($req->created_at) ?>
                                </p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                            <!-- Approve Form -->
                            <form action="<?= BASE_URL ?>admin/users/password-requests/<?= $req->id ?>/approve" 
                                  method="POST" 
                                  class="flex items-center gap-2"
                                  onsubmit="return confirm('Reset password user ini?')">
                                <input type="password" 
                                       name="new_password" 
                                       placeholder="Password baru (min 6)" 
                                       required 
                                       minlength="6"
                                       class="w-40 px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <button type="submit" 
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-lg transition-colors">
                                    <i class="fas fa-check"></i>
                                    Reset
                                </button>
                            </form>

                            <!-- Reject Form -->
                            <form action="<?= BASE_URL ?>admin/users/password-requests/<?= $req->id ?>/reject" 
                                  method="POST"
                                  onsubmit="return confirm('Tolak permintaan ini?')">
                                <button type="submit" 
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-colors">
                                    <i class="fas fa-times"></i>
                                    Tolak
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- History -->
<div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
    <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
        <h2 class="font-semibold text-slate-800 flex items-center gap-2">
            <i class="fas fa-history"></i>
            Riwayat Request
        </h2>
    </div>

    <?php 
    $processedRequests = array_filter($allRequests ?? [], fn($r) => $r->status !== 'pending');
    ?>

    <?php if (empty($processedRequests)): ?>
        <div class="p-8 text-center text-slate-500">
            Belum ada riwayat request
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-600 uppercase">User</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-600 uppercase">Email</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-600 uppercase">Tanggal Request</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-600 uppercase">Diproses</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-600 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($processedRequests as $req): ?>
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 text-sm font-medium text-slate-800">
                                <?= htmlspecialchars($req->user_name) ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                <?= htmlspecialchars($req->email) ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                <?= formatDate($req->created_at) ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                <?= $req->processed_at ? formatDate($req->processed_at) : '-' ?>
                            </td>
                            <td class="px-6 py-4">
                                <?= getStatusBadge($req->status) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>