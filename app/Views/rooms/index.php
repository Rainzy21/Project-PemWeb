<?php
?>
<!-- Search Section -->
<section class="bg-white rounded-xl shadow-lg p-6 mb-10 border border-gray-100">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Cari Ketersediaan Kamar</h2>
    
    <form action="<?= BASE_URL ?>room/search" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
        
        <!-- Kata Kunci -->
        <div class="md:col-span-12 lg:col-span-3">
            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Kata Kunci</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" id="search" value="<?= htmlspecialchars($search ?? '') ?>" class="pl-10 w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="Cari nama kamar...">
            </div>
        </div>

        <!-- Check-in -->
        <div class="md:col-span-3 lg:col-span-2">
            <label for="check_in" class="block text-sm font-medium text-gray-700 mb-1">Check-in</label>
            <input type="date" name="check_in" id="check_in" value="<?= htmlspecialchars($checkIn ?? '') ?>" min="<?= date('Y-m-d') ?>" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
        </div>

        <!-- Check-out -->
        <div class="md:col-span-3 lg:col-span-2">
            <label for="check_out" class="block text-sm font-medium text-gray-700 mb-1">Check-out</label>
            <input type="date" name="check_out" id="check_out" value="<?= htmlspecialchars($checkOut ?? '') ?>" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
        </div>

        <!-- Tipe -->
        <div class="md:col-span-3 lg:col-span-2">
            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
            <select id="type" name="type" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                <option value="">Semua</option>
                <option value="standard" <?= ($selectedType ?? '') === 'standard' ? 'selected' : '' ?>>Standard</option>
                <option value="deluxe" <?= ($selectedType ?? '') === 'deluxe' ? 'selected' : '' ?>>Deluxe</option>
                <option value="suite" <?= ($selectedType ?? '') === 'suite' ? 'selected' : '' ?>>Suite</option>
            </select>
        </div>

        <!-- Harga Min -->
        <div class="md:col-span-3 lg:col-span-1">
            <label for="min_price" class="block text-sm font-medium text-gray-700 mb-1">Min</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-2 flex items-center text-gray-500 text-xs">Rp</span>
                <input type="number" name="min_price" id="min_price" value="<?= htmlspecialchars($minPrice ?? '') ?>" class="pl-7 w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="0">
            </div>
        </div>

        <!-- Harga Max -->
        <div class="md:col-span-3 lg:col-span-1">
            <label for="max_price" class="block text-sm font-medium text-gray-700 mb-1">Max</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-2 flex items-center text-gray-500 text-xs">Rp</span>
                <input type="number" name="max_price" id="max_price" value="<?= htmlspecialchars($maxPrice ?? '') ?>" class="pl-7 w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="Max">
            </div>
        </div>

        <!-- Button -->
        <div class="md:col-span-6 lg:col-span-1">
            <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                Cari
            </button>
        </div>
    </form>
</section>

<!-- Quick Filter by Type -->
<section class="mb-8">
    <div class="flex flex-wrap items-center gap-3">
        <span class="text-sm text-gray-500">Filter cepat:</span>
        <a href="<?= BASE_URL ?>room" class="px-4 py-2 rounded-full text-sm font-medium transition <?= empty($selectedType) ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Semua
        </a>
        <a href="<?= BASE_URL ?>room?type=standard" class="px-4 py-2 rounded-full text-sm font-medium transition <?= ($selectedType ?? '') === 'standard' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Standard
        </a>
        <a href="<?= BASE_URL ?>room?type=deluxe" class="px-4 py-2 rounded-full text-sm font-medium transition <?= ($selectedType ?? '') === 'deluxe' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Deluxe
        </a>
        <a href="<?= BASE_URL ?>room?type=suite" class="px-4 py-2 rounded-full text-sm font-medium transition <?= ($selectedType ?? '') === 'suite' ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Suite
        </a>
        <span class="mx-2 text-gray-300">|</span>
        <a href="<?= BASE_URL ?>room/types" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
            Lihat semua tipe kamar →
        </a>
    </div>
</section>

<!-- Search Result Info (jika dari search) -->
<?php if (!empty($checkIn) && !empty($checkOut)): ?>
<section class="mb-6">
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span class="text-blue-800 text-sm">
                Menampilkan kamar tersedia untuk: 
                <strong><?= date('d M Y', strtotime($checkIn)) ?></strong> - 
                <strong><?= date('d M Y', strtotime($checkOut)) ?></strong>
            </span>
        </div>
        <a href="<?= BASE_URL ?>room" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
            Reset pencarian ×
        </a>
    </div>
</section>
<?php endif; ?>

<!-- Room List Section -->
<section class="mb-12">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-2xl font-bold text-gray-900">
            Kamar Tersedia
            <span class="text-lg font-normal text-gray-500">(<?= count($rooms) ?> kamar)</span>
        </h3>
    </div>
    
    <?php if (empty($rooms)): ?>
        <!-- Empty State -->
        <div class="bg-white rounded-xl border border-dashed border-gray-300 p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900">Tidak ada kamar ditemukan</h3>
            <p class="text-gray-500 max-w-sm mx-auto mt-2">Coba kata kunci lain atau reset filter pencarian Anda.</p>
            <a href="<?= BASE_URL ?>room" class="inline-block mt-4 text-blue-600 hover:text-blue-800 font-medium">
                Reset Filter
            </a>
        </div>
    <?php else: ?>
        <!-- Room Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="room-grid">
            <?php foreach ($rooms as $room): ?>
                <?php
                    $badgeColor = match($room->room_type) {
                        'deluxe' => 'bg-purple-600',
                        'suite' => 'bg-amber-600',
                        default => 'bg-blue-600'
                    };
                ?>
                <div class="bg-white rounded-xl shadow-sm hover:shadow-xl transition duration-300 overflow-hidden border border-gray-100 flex flex-col" data-room-id="<?= $room->id ?>">
                    <div class="relative">
                        <img class="w-full h-56 object-cover" src="<?= STORAGE_URL ?><?= str_replace('storage/', '', htmlspecialchars($room->image)) ?>" alt="<?= htmlspecialchars($room->room_number) ?>">
                        <div class="absolute top-4 right-4 bg-black/70 backdrop-blur-sm text-white text-xs font-bold px-3 py-1 rounded-full">No. <?= htmlspecialchars($room->room_number) ?></div>
                        <div class="absolute bottom-4 left-4 <?= $badgeColor ?> text-white text-xs font-semibold px-2.5 py-1 rounded"><?= ucfirst($room->room_type) ?></div>
                    </div>
                    <div class="p-5 flex-1 flex flex-col">
                        <h4 class="text-lg font-bold text-gray-900 mb-2"><?= htmlspecialchars($room->room_number) ?></h4>
                        <p class="text-gray-500 text-sm mb-4 line-clamp-2"><?= htmlspecialchars($room->description ?? 'Kamar nyaman dengan fasilitas lengkap') ?></p>
                        <div class="mt-auto flex items-center justify-between pt-4 border-t border-gray-100">
                            <div>
                                <span class="text-xs text-gray-400 block">Mulai dari</span>
                                <span class="text-lg font-bold text-blue-600">Rp <?= number_format($room->price_per_night, 0, ',', '.') ?></span>
                            </div>
                            <?php if (!empty($checkIn) && !empty($checkOut)): ?>
                                <a href="<?= BASE_URL ?>booking/create/<?= $room->id ?>?check_in=<?= $checkIn ?>&check_out=<?= $checkOut ?>" class="text-sm font-medium bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                    Pesan
                                </a>
                            <?php else: ?>
                                <a href="<?= BASE_URL ?>room/detail/<?= $room->id ?>" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                    Lihat Detail &rarr;
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<!-- Room Types Overview -->
<?php if (empty($checkIn) && empty($checkOut) && empty($selectedType)): ?>
<section class="mb-12">
    <h3 class="text-xl font-bold text-gray-900 mb-6">Tipe Kamar Kami</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="<?= BASE_URL ?>room?type=standard" class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white hover:shadow-xl transition group">
            <div class="flex items-center justify-between mb-4">
                <svg class="w-10 h-10 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="text-white/80 group-hover:translate-x-1 transition">→</span>
            </div>
            <h4 class="text-lg font-bold mb-1">Standard</h4>
            <p class="text-white/80 text-sm">Kamar nyaman untuk perjalanan bisnis</p>
        </a>
        <a href="<?= BASE_URL ?>room?type=deluxe" class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white hover:shadow-xl transition group">
            <div class="flex items-center justify-between mb-4">
                <svg class="w-10 h-10 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                <span class="text-white/80 group-hover:translate-x-1 transition">→</span>
            </div>
            <h4 class="text-lg font-bold mb-1">Deluxe</h4>
            <p class="text-white/80 text-sm">Ruangan luas dengan fasilitas premium</p>
        </a>
        <a href="<?= BASE_URL ?>room?type=suite" class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl p-6 text-white hover:shadow-xl transition group">
            <div class="flex items-center justify-between mb-4">
                <svg class="w-10 h-10 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                <span class="text-white/80 group-hover:translate-x-1 transition">→</span>
            </div>
            <h4 class="text-lg font-bold mb-1">Suite</h4>
            <p class="text-white/80 text-sm">Kemewahan maksimal dengan layanan eksklusif</p>
        </a>
    </div>
</section>
<?php endif; ?>

<!-- JavaScript for AJAX features -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkInInput = document.getElementById('check_in');
    const checkOutInput = document.getElementById('check_out');
    
    // Auto-set minimum check-out date based on check-in
    checkInInput?.addEventListener('change', function() {
        const checkInDate = new Date(this.value);
        checkInDate.setDate(checkInDate.getDate() + 1);
        const minCheckOut = checkInDate.toISOString().split('T')[0];
        checkOutInput.min = minCheckOut;
        
        if (checkOutInput.value && checkOutInput.value <= this.value) {
            checkOutInput.value = minCheckOut;
        }
    });

    // Check availability via AJAX when hovering room card (optional enhancement)
    const roomCards = document.querySelectorAll('[data-room-id]');
    roomCards.forEach(card => {
        card.addEventListener('mouseenter', async function() {
            const roomId = this.dataset.roomId;
            const checkIn = checkInInput?.value;
            const checkOut = checkOutInput?.value;
            
            if (checkIn && checkOut) {
                try {
                    const response = await fetch(`<?= BASE_URL ?>room/checkAvailability/${roomId}?check_in=${checkIn}&check_out=${checkOut}`);
                    const data = await response.json();
                    
                    // Could show availability badge or update UI
                    if (!data.available) {
                        this.classList.add('opacity-50');
                    }
                } catch (e) {
                    console.log('Could not check availability');
                }
            }
        });
    });
});
</script>