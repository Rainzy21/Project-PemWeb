<?php
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- Navbar -->
    <nav class="bg-white/90 backdrop-blur-md fixed w-full z-40 top-0 border-b border-gray-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 h-20 flex justify-between items-center">
            <a href="<?= BASE_URL ?>" class="flex items-center gap-3 font-bold text-xl text-gray-900">
                <div class="w-10 h-10 bg-[#1A65EB] rounded-xl flex items-center justify-center text-white shadow-lg">H</div>
                <?= APP_NAME ?>
            </a>

            <div class="hidden md:flex items-center space-x-8">
                <a href="<?= BASE_URL ?>" class="text-gray-600 hover:text-[#1A65EB] font-medium">Beranda</a>
                <a href="<?= BASE_URL ?>room" class="text-gray-600 hover:text-[#1A65EB] font-medium">Kamar</a>
            </div>

            <div class="hidden md:flex items-center gap-3">
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="<?= BASE_URL ?>booking/myBookings" class="text-[#1A65EB] font-semibold hover:bg-blue-50 px-4 py-2 rounded-lg">My Bookings</a>
                    <a href="<?= BASE_URL ?>auth/profile" class="flex items-center gap-2">
                        <span class="text-sm font-medium text-gray-700">Halo, <?= htmlspecialchars($_SESSION['user']->name ?? 'User') ?></span>
                        <div class="w-9 h-9 bg-gray-200 rounded-full flex items-center justify-center font-bold text-gray-600">
                            <?= strtoupper(substr($_SESSION['user']->name ?? 'U', 0, 1)) ?>
                        </div>
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>auth/login" class="text-[#1A65EB] font-semibold hover:bg-blue-50 px-4 py-2 rounded-lg">Login</a>
                    <a href="<?= BASE_URL ?>auth/register" class="bg-[#020617] text-white px-5 py-2.5 rounded-lg font-medium hover:bg-gray-800 shadow-lg">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="pt-24 px-4">
        <?php if (isset($_SESSION['flash']['success'])): ?>
            <div class="max-w-7xl mx-auto mb-4">
                <div class="bg-green-50 border border-green-200 text-green-600 text-sm px-4 py-3 rounded-lg">
                    <?= $_SESSION['flash']['success'] ?>
                </div>
            </div>
            <?php unset($_SESSION['flash']['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['flash']['error'])): ?>
            <div class="max-w-7xl mx-auto mb-4">
                <div class="bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-lg">
                    <?= $_SESSION['flash']['error'] ?>
                </div>
            </div>
            <?php unset($_SESSION['flash']['error']); ?>
        <?php endif; ?>
    </div>

    <!-- Main Content -->
    <main class="min-h-screen px-4 pb-12">
        <div class="max-w-7xl mx-auto">
            <?= $content ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-[#020617] text-white py-12 px-4">
        <div class="max-w-7xl mx-auto grid md:grid-cols-3 gap-8">
            <div>
                <div class="flex items-center gap-3 font-bold text-xl mb-4">
                    <div class="w-10 h-10 bg-[#1A65EB] rounded-xl flex items-center justify-center">H</div>
                    <?= APP_NAME ?>
                </div>
                <p class="text-gray-400 text-sm">Pengalaman menginap terbaik dengan harga terjangkau.</p>
            </div>
            <div>
                <h4 class="font-bold mb-4">Quick Links</h4>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li><a href="<?= BASE_URL ?>" class="hover:text-white">Beranda</a></li>
                    <li><a href="<?= BASE_URL ?>room" class="hover:text-white">Kamar</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-4">Kontak</h4>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li>📍 Jl. Hotel No. 123, Jakarta</li>
                    <li>📞 +62 812-3456-7890</li>
                    <li>✉️ info@hotel.com</li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto mt-8 pt-8 border-t border-gray-800 text-center text-gray-500 text-sm">
            &copy; <?= date('Y') ?> <?= APP_NAME ?>. All rights reserved.
        </div>
    </footer>

</body>
</html>