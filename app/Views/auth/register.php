<?php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - <?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-[#E6EEFA] min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-[450px] p-8 md:p-10">
        
        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <a href="<?= BASE_URL ?>" class="w-14 h-14 bg-[#1A65EB] rounded-full flex items-center justify-center text-white shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </a>
        </div>

        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-semibold text-gray-900 mb-2">Daftar Akun Baru</h1>
            <p class="text-gray-500 text-sm">Buat akun untuk mulai memesan kamar</p>
        </div>

        <!-- Error Message -->
        <?php if (isset($_SESSION['flash']['error'])): ?>
            <div class="bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-lg mb-5">
                <?= $_SESSION['flash']['error'] ?>
            </div>
            <?php unset($_SESSION['flash']['error']); ?>
        <?php endif; ?>

        <!-- Register Form -->
        <form action="<?= BASE_URL ?>auth/doRegister" method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" placeholder="John Doe" required
                    value="<?= htmlspecialchars($_SESSION['old_input']['name'] ?? '') ?>"
                    class="w-full bg-[#F3F4F6] border-none rounded-lg px-4 py-3 text-gray-700 text-sm focus:ring-2 focus:ring-[#1A65EB] focus:bg-white outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-1.5">Email</label>
                <input type="email" name="email" placeholder="email@example.com" required
                    value="<?= htmlspecialchars($_SESSION['old_input']['email'] ?? '') ?>"
                    class="w-full bg-[#F3F4F6] border-none rounded-lg px-4 py-3 text-gray-700 text-sm focus:ring-2 focus:ring-[#1A65EB] focus:bg-white outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-1.5">Nomor Telepon</label>
                <input type="tel" name="phone" placeholder="08123456789" required
                    value="<?= htmlspecialchars($_SESSION['old_input']['phone'] ?? '') ?>"
                    class="w-full bg-[#F3F4F6] border-none rounded-lg px-4 py-3 text-gray-700 text-sm focus:ring-2 focus:ring-[#1A65EB] focus:bg-white outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-1.5">Password</label>
                <input type="password" name="password" placeholder="Minimal 6 karakter" required
                    class="w-full bg-[#F3F4F6] border-none rounded-lg px-4 py-3 text-gray-700 text-sm focus:ring-2 focus:ring-[#1A65EB] focus:bg-white outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-1.5">Konfirmasi Password</label>
                <input type="password" name="confirm_password" placeholder="Ulangi password" required
                    class="w-full bg-[#F3F4F6] border-none rounded-lg px-4 py-3 text-gray-700 text-sm focus:ring-2 focus:ring-[#1A65EB] focus:bg-white outline-none">
            </div>
            <button type="submit" class="w-full bg-[#020617] hover:bg-gray-800 text-white font-medium py-3 rounded-lg transition duration-200 mt-2">
                Daftar
            </button>
        </form>

        <!-- Login Link -->
        <div class="text-center mt-6 text-sm text-gray-600">
            <span>Sudah punya akun?</span> 
            <a href="<?= BASE_URL ?>auth/login" class="text-[#1A65EB] font-medium hover:underline">Login di sini</a>
        </div>

        <!-- Back to Home -->
        <div class="text-center mt-4">
            <a href="<?= BASE_URL ?>" class="text-gray-400 text-sm hover:text-gray-600">← Kembali ke Beranda</a>
        </div>
    </div>

</body>
</html>