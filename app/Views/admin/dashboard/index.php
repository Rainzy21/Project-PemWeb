<?php
$tabs = [
    'home' => ['icon' => 'fa-home', 'label' => 'Home'],
    'analytics' => ['icon' => 'fa-chart-line', 'label' => 'Analytics'],
    'reports' => ['icon' => 'fa-file-alt', 'label' => 'Reports'],
    'settings' => ['icon' => 'fa-cog', 'label' => 'Settings'],
    'activity' => ['icon' => 'fa-history', 'label' => 'Activity Log']
];

$activeTab = $activeTab ?? 'home';
?>

<!-- Page Header -->
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Dashboard</h1>
    <p class="text-slate-500 text-sm mt-1">Welcome back, <?= htmlspecialchars($_SESSION['user']->name ?? 'Admin') ?>!</p>
</div>

<!-- Tabs Navigation -->
<div class="bg-white rounded-xl shadow-sm mb-6 overflow-hidden">
    <div class="border-b border-slate-200">
        <nav class="flex overflow-x-auto scrollbar-hide" id="dashboardTabs">
            <?php foreach ($tabs as $key => $tab): ?>
            <button type="button"
                    class="tab-btn flex items-center gap-2 px-6 py-4 text-sm font-medium border-b-2 transition-all duration-200 whitespace-nowrap focus:outline-none
                    <?= $activeTab === $key 
                        ? 'border-blue-500 text-blue-600 bg-blue-50/50' 
                        : 'border-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-50' ?>"
                    data-tab="<?= $key ?>">
                <i class="fas <?= $tab['icon'] ?> text-base"></i>
                <span><?= $tab['label'] ?></span>
            </button>
            <?php endforeach; ?>
        </nav>
    </div>
</div>

<!-- Tabs Content -->
<div id="tabContent">
    <!-- HOME TAB -->
    <div class="tab-pane transition-opacity duration-200 <?= $activeTab === 'home' ? 'block' : 'hidden' ?>" id="home-content">
        <?php 
        $tabFile = __DIR__ . '/tabs/home.php';
        if (file_exists($tabFile)) {
            include $tabFile;
        } else {
            echo '<div class="bg-white rounded-xl shadow-sm p-8 text-center text-slate-500">Tab content not found</div>';
        }
        ?>
    </div>

    <!-- ANALYTICS TAB -->
    <div class="tab-pane transition-opacity duration-200 <?= $activeTab === 'analytics' ? 'block' : 'hidden' ?>" id="analytics-content">
        <?php 
        $tabFile = __DIR__ . '/tabs/analytics.php';
        if (file_exists($tabFile)) {
            include $tabFile;
        } else {
            echo '<div class="bg-white rounded-xl shadow-sm p-8 text-center text-slate-500">Tab content not found</div>';
        }
        ?>
    </div>

    <!-- REPORTS TAB -->
    <div class="tab-pane transition-opacity duration-200 <?= $activeTab === 'reports' ? 'block' : 'hidden' ?>" id="reports-content">
        <?php 
        $tabFile = __DIR__ . '/tabs/reports.php';
        if (file_exists($tabFile)) {
            include $tabFile;
        } else {
            echo '<div class="bg-white rounded-xl shadow-sm p-8 text-center text-slate-500">Tab content not found</div>';
        }
        ?>
    </div>

    <!-- SETTINGS TAB -->
    <div class="tab-pane transition-opacity duration-200 <?= $activeTab === 'settings' ? 'block' : 'hidden' ?>" id="settings-content">
        <?php 
        $tabFile = __DIR__ . '/tabs/settings.php';
        if (file_exists($tabFile)) {
            include $tabFile;
        } else {
            echo '<div class="bg-white rounded-xl shadow-sm p-8 text-center text-slate-500">Tab content not found</div>';
        }
        ?>
    </div>

    <!-- ACTIVITY LOG TAB -->
    <div class="tab-pane transition-opacity duration-200 <?= $activeTab === 'activity' ? 'block' : 'hidden' ?>" id="activity-content">
        <?php 
        $tabFile = __DIR__ . '/tabs/activity.php';
        if (file_exists($tabFile)) {
            include $tabFile;
        } else {
            echo '<div class="bg-white rounded-xl shadow-sm p-8 text-center text-slate-500">Tab content not found</div>';
        }
        ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');

    tabButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.dataset.tab;
            
            // Update button states
            tabButtons.forEach(b => {
                b.classList.remove('border-blue-500', 'text-blue-600', 'bg-blue-50/50');
                b.classList.add('border-transparent', 'text-slate-500');
            });
            this.classList.remove('border-transparent', 'text-slate-500');
            this.classList.add('border-blue-500', 'text-blue-600', 'bg-blue-50/50');
            
            // Hide all panes then show selected
            tabPanes.forEach(pane => {
                pane.classList.add('hidden');
                pane.classList.remove('block');
            });
            
            const targetPane = document.getElementById(tabId + '-content');
            if (targetPane) {
                targetPane.classList.remove('hidden');
                targetPane.classList.add('block');
            }
            
            // Update URL without reload
            const url = new URL(window.location);
            url.searchParams.set('tab', tabId);
            window.history.pushState({}, '', url);
        });
    });
});
</script>

<style>
/* Hide scrollbar for tabs */
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>