<?php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Panel - ' . APP_NAME ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #475569; border-radius: 2px; }
    </style>
</head>
<body class="bg-slate-100">

    <!-- Sidebar Overlay (Mobile) -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed top-0 left-0 w-64 h-screen bg-slate-800 z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
        
        <!-- Sidebar Header -->
        <div class="h-16 flex items-center px-6 border-b border-slate-700">
            <a href="<?= BASE_URL ?>admin/dashboard" class="flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-500 rounded-lg flex items-center justify-center text-white font-bold shadow-lg">
                    H
                </div>
                <span class="text-white font-bold text-lg"><?= APP_NAME ?></span>
            </a>
        </div>

        <!-- Sidebar Navigation -->
        <nav class="p-4 h-[calc(100vh-64px-80px)] overflow-y-auto sidebar-scroll">
            
            <div class="mb-4">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 px-3">Main Menu</p>
                
                <!-- Dashboard -->
                <a href="<?= BASE_URL ?>admin/dashboard" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 transition-all duration-200
                   <?= $this->isActive('admin/dashboard') ? 'bg-blue-500/10 text-blue-400 border-l-2 border-blue-400' : 'text-slate-400 hover:bg-slate-700 hover:text-white' ?>">
                    <i class="fas fa-tachometer-alt w-5 text-center"></i>
                    <span class="font-medium">Dashboard</span>
                </a>

                <!-- Bookings -->
                <a href="<?= BASE_URL ?>admin/bookings" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 transition-all duration-200
                   <?= $this->isActive('admin/bookings') ? 'bg-blue-500/10 text-blue-400 border-l-2 border-blue-400' : 'text-slate-400 hover:bg-slate-700 hover:text-white' ?>">
                    <i class="fas fa-calendar-check w-5 text-center"></i>
                    <span class="font-medium">Bookings</span>
                </a>

                <!-- Rooms -->
                <a href="<?= BASE_URL ?>admin/rooms" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 transition-all duration-200
                   <?= $this->isActive('admin/rooms') ? 'bg-blue-500/10 text-blue-400 border-l-2 border-blue-400' : 'text-slate-400 hover:bg-slate-700 hover:text-white' ?>">
                    <i class="fas fa-door-open w-5 text-center"></i>
                    <span class="font-medium">Rooms</span>
                </a>

                <!-- Users -->
                <a href="<?= BASE_URL ?>admin/users" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 transition-all duration-200
                   <?= $this->isActive('admin/users') ? 'bg-blue-500/10 text-blue-400 border-l-2 border-blue-400' : 'text-slate-400 hover:bg-slate-700 hover:text-white' ?>">
                    <i class="fas fa-users w-5 text-center"></i>
                    <span class="font-medium">Users</span>
                </a>
            </div>

            <div class="mb-4">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 px-3">Settings</p>
                
                <!-- Settings -->
                <a href="<?= BASE_URL ?>admin/dashboard?tab=settings" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-slate-400 hover:bg-slate-700 hover:text-white transition-all duration-200">
                    <i class="fas fa-cog w-5 text-center"></i>
                    <span class="font-medium">Settings</span>
                </a>

                <!-- Logout -->
                <a href="<?= BASE_URL ?>auth/logout" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-red-400 hover:bg-red-500/10 transition-all duration-200">
                    <i class="fas fa-sign-out-alt w-5 text-center"></i>
                    <span class="font-medium">Logout</span>
                </a>
            </div>

        </nav>

        <!-- Sidebar Footer - User Info -->
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-slate-700 bg-slate-800/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                    <?= strtoupper(substr($_SESSION['user']->name ?? 'A', 0, 1)) ?>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white font-medium text-sm truncate">
                        <?= htmlspecialchars($_SESSION['user']->name ?? 'Admin') ?>
                    </p>
                    <p class="text-slate-500 text-xs">Administrator</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="lg:ml-64 min-h-screen flex flex-col">
        
        <!-- Header -->
        <header class="h-16 bg-white border-b border-slate-200 sticky top-0 z-30 flex items-center justify-between px-4 lg:px-6">
            
            <!-- Left: Toggle & Breadcrumb -->
            <div class="flex items-center gap-4">
                <!-- Mobile Toggle -->
                <button id="sidebarToggle" class="lg:hidden w-10 h-10 flex items-center justify-center text-slate-600 hover:bg-slate-100 rounded-lg">
                    <i class="fas fa-bars text-lg"></i>
                </button>
                
                <!-- Breadcrumb -->
                <nav class="hidden sm:flex items-center gap-2 text-sm">
                    <a href="<?= BASE_URL ?>admin/dashboard" class="text-slate-500 hover:text-blue-500">
                        <i class="fas fa-home"></i>
                    </a>
                    <i class="fas fa-chevron-right text-slate-300 text-xs"></i>
                    <span class="text-slate-700 font-medium"><?= $pageTitle ?? 'Dashboard' ?></span>
                </nav>
            </div>

            <!-- Right: Actions -->
            <div class="flex items-center gap-2">
                <!-- Notifications -->
                <button class="relative w-10 h-10 flex items-center justify-center text-slate-600 hover:bg-slate-100 rounded-lg">
                    <i class="fas fa-bell"></i>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>

                <!-- View Site -->
                <a href="<?= BASE_URL ?>" target="_blank" 
                   class="hidden sm:flex items-center gap-2 px-3 py-2 text-sm font-medium text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                    <i class="fas fa-external-link-alt"></i>
                    <span>View Site</span>
                </a>
            </div>
        </header>

        <!-- Flash Messages -->
        <?php if (isset($_SESSION['flash']['success'])): ?>
        <div class="mx-4 lg:mx-6 mt-4">
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-3">
                <i class="fas fa-check-circle text-green-500"></i>
                <span class="text-sm"><?= $_SESSION['flash']['success'] ?></span>
                <button onclick="this.parentElement.remove()" class="ml-auto text-green-500 hover:text-green-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <?php unset($_SESSION['flash']['success']); endif; ?>

        <?php if (isset($_SESSION['flash']['error'])): ?>
        <div class="mx-4 lg:mx-6 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-3">
                <i class="fas fa-exclamation-circle text-red-500"></i>
                <span class="text-sm"><?= $_SESSION['flash']['error'] ?></span>
                <button onclick="this.parentElement.remove()" class="ml-auto text-red-500 hover:text-red-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <?php unset($_SESSION['flash']['error']); endif; ?>

        <!-- Main Content Area -->
        <main class="flex-1 p-4 lg:p-6">
            <?= $content ?>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-slate-200 px-4 lg:px-6 py-4">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-2 text-sm text-slate-500">
                <p>
                    &copy; <?= date('Y') ?> <span class="font-semibold text-slate-700"><?= APP_NAME ?></span>. All rights reserved.
                </p>
                <p>
                    Made with <i class="fas fa-heart text-red-500"></i> by Your Team
                </p>
            </div>
        </footer>
    </div>

    <!-- JavaScript -->
    <script>
        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.remove('hidden');
            document.body.classList.add('overflow-hidden', 'lg:overflow-auto');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        sidebarToggle?.addEventListener('click', openSidebar);
        sidebarOverlay?.addEventListener('click', closeSidebar);

        // Close sidebar on window resize to desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                closeSidebar();
            }
        });
    </script>
</body>
</html>