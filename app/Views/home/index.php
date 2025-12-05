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
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="<?= BASE_URL ?>room" class="text-gray-600 hover:text-[#1A65EB] font-medium">Kamar</a>
                <?php else: ?>
                    <a href="#rooms" class="text-gray-600 hover:text-[#1A65EB] font-medium">Kamar</a>
                <?php endif; ?>
                <a href="#facilities" class="text-gray-600 hover:text-[#1A65EB] font-medium">Fasilitas</a>
            </div>

            <div class="hidden md:flex items-center gap-3">
                <?php if (isset($_SESSION['user'])): ?>
                    <!-- User sudah login -->
                    <a href="<?= BASE_URL ?>booking/myBookings" class="text-[#1A65EB] font-semibold hover:bg-blue-50 px-4 py-2 rounded-lg">My Bookings</a>
                    <span class="text-sm font-medium text-gray-700">Halo, <?= htmlspecialchars($_SESSION['user']->name ?? 'User') ?></span>
                    <div class="w-9 h-9 bg-gray-200 rounded-full flex items-center justify-center font-bold text-gray-600">
                        <?= strtoupper(substr($_SESSION['user']->name ?? 'U', 0, 1)) ?>
                    </div>
                    <a href="<?= BASE_URL ?>auth/logout" class="text-xs text-red-500 hover:underline ml-2">Logout</a>
                <?php else: ?>
                    <!-- User belum login -->
                    <a href="<?= BASE_URL ?>auth/login" class="text-[#1A65EB] font-semibold hover:bg-blue-50 px-4 py-2 rounded-lg">Login</a>
                    <a href="<?= BASE_URL ?>auth/register" class="bg-[#020617] text-white px-5 py-2.5 rounded-lg font-medium hover:bg-gray-800 shadow-lg">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="h-screen flex items-center justify-center bg-[url('https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?ixlib=rb-4.0.3&w=1920&q=80')] bg-cover bg-center relative">
        <div class="absolute inset-0 bg-black/50"></div>
        <div class="relative z-10 text-center px-4 max-w-4xl text-white">
            <h1 class="text-5xl font-bold mb-6">Temukan Kenyamanan<br>Bersama <?= APP_NAME ?></h1>
            <p class="text-lg mb-10 text-gray-200">Nikmati pengalaman menginap bintang 5 dengan harga terbaik.</p>
            <a href="<?= isset($_SESSION['user']) ? BASE_URL . 'room' : '#rooms' ?>" class="inline-block bg-[#1A65EB] hover:bg-blue-700 text-white text-lg font-semibold px-8 py-4 rounded-full shadow-xl transition">Booking Sekarang</a>
        </div>
    </section>

    <!-- Rooms Section -->
    <section id="rooms" class="py-24 px-4 max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Pilihan Kamar Terbaik</h2>
        <div class="grid md:grid-cols-3 gap-8">
            
            <!-- Room 1 -->
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 overflow-hidden flex flex-col">
                <img src="https://images.unsplash.com/photo-1590490360182-c33d57733427?w=800&q=80" class="h-64 object-cover" alt="Deluxe Ocean View">
                <div class="p-6 flex-1 flex flex-col">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Deluxe Ocean View</h3>
                    <p class="text-gray-500 text-sm mb-4">Pemandangan laut yang menakjubkan.</p>
                    <div class="mt-auto flex justify-between items-center">
                        <span class="text-lg font-bold text-[#1A65EB]">Rp 1.500.000</span>
                        <a href="<?= isset($_SESSION['user']) ? BASE_URL . 'booking/create/1' : BASE_URL . 'auth/login' ?>" class="bg-[#020617] text-white px-5 py-2 rounded-lg text-sm hover:bg-gray-800">Booking</a>
                    </div>
                </div>
            </div>

            <!-- Room 2 -->
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 overflow-hidden flex flex-col">
                <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=800&q=80" class="h-64 object-cover" alt="Executive Suite">
                <div class="p-6 flex-1 flex flex-col">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Executive Suite</h3>
                    <p class="text-gray-500 text-sm mb-4">Suite eksklusif dengan ruang tamu.</p>
                    <div class="mt-auto flex justify-between items-center">
                        <span class="text-lg font-bold text-[#1A65EB]">Rp 2.500.000</span>
                        <a href="<?= isset($_SESSION['user']) ? BASE_URL . 'booking/create/2' : BASE_URL . 'auth/login' ?>" class="bg-[#020617] text-white px-5 py-2 rounded-lg text-sm hover:bg-gray-800">Booking</a>
                    </div>
                </div>
            </div>

            <!-- Room 3 -->
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 overflow-hidden flex flex-col">
                <img src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=800&q=80" class="h-64 object-cover" alt="Standard Room">
                <div class="p-6 flex-1 flex flex-col">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Standard Room</h3>
                    <p class="text-gray-500 text-sm mb-4">Nyaman dan ekonomis.</p>
                    <div class="mt-auto flex justify-between items-center">
                        <span class="text-lg font-bold text-[#1A65EB]">Rp 800.000</span>
                        <a href="<?= isset($_SESSION['user']) ? BASE_URL . 'booking/create/3' : BASE_URL . 'auth/login' ?>" class="bg-[#020617] text-white px-5 py-2 rounded-lg text-sm hover:bg-gray-800">Booking</a>
                    </div>
                </div>
            </div>

        </div>

        <!-- Tombol Lihat Semua (hanya jika sudah login) -->
        <?php if (isset($_SESSION['user'])): ?>
        <div class="text-center mt-12">
            <a href="<?= BASE_URL ?>room" class="inline-block border-2 border-[#1A65EB] text-[#1A65EB] hover:bg-[#1A65EB] hover:text-white px-8 py-3 rounded-full font-semibold transition">
                Lihat Semua Kamar →
            </a>
        </div>
        <?php endif; ?>
    </section>

    <!-- Facilities Section -->
    <section id="facilities" class="py-24 px-4 bg-gray-100">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Fasilitas Hotel</h2>
            <div class="grid md:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-xl text-center shadow-sm">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">🏊</span>
                    </div>
                    <h4 class="font-bold text-gray-900">Swimming Pool</h4>
                    <p class="text-sm text-gray-500 mt-2">Kolam renang outdoor</p>
                </div>
                <div class="bg-white p-6 rounded-xl text-center shadow-sm">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">🍽️</span>
                    </div>
                    <h4 class="font-bold text-gray-900">Restaurant</h4>
                    <p class="text-sm text-gray-500 mt-2">Restoran 24 jam</p>
                </div>
                <div class="bg-white p-6 rounded-xl text-center shadow-sm">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">📶</span>
                    </div>
                    <h4 class="font-bold text-gray-900">Free WiFi</h4>
                    <p class="text-sm text-gray-500 mt-2">Internet cepat gratis</p>
                </div>
                <div class="bg-white p-6 rounded-xl text-center shadow-sm">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">🚗</span>
                    </div>
                    <h4 class="font-bold text-gray-900">Parking</h4>
                    <p class="text-sm text-gray-500 mt-2">Parkir luas & aman</p>
                </div>
            </div>
        </div>
    </section>

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
                    <li><a href="<?= isset($_SESSION['user']) ? BASE_URL . 'room' : '#rooms' ?>" class="hover:text-white">Kamar</a></li>
                    <li><a href="#facilities" class="hover:text-white">Fasilitas</a></li>
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
