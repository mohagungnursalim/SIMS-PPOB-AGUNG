<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-8">
  <!-- Profil -->
  <div class="flex flex-col gap-2">
    <?php
      $imageUrl = $user['profile_image'] ?? '';
      $defaultImage = base_url('img/foto-profil.png');
      $finalImage = (empty($imageUrl) || str_ends_with($imageUrl, '/null')) 
          ? $defaultImage 
          : esc($imageUrl);
    ?>
    <img src="<?= $finalImage ?>" alt="Avatar" class="w-16 h-16 rounded-full object-cover">
    <div>
      <p class="text-gray-500 text-sm">Selamat datang,</p>
      <h1 class="text-xl font-bold"><?= esc($user['first_name']) ?> <?= esc($user['last_name']) ?></h1>
    </div>
  </div>

  <!-- Saldo Card -->
  <div x-data="{ show: false }" class="bg-red-600 text-white rounded-xl p-6 w-1/2 md:w-1/3 relative overflow-hidden">
    <div class="text-sm">Saldo anda</div>
    <div class="text-2xl font-bold mt-2"
         x-text="show ? 'Rp <?= number_format($balance, 0, ',', '.') ?>' : 'Rp ••••••••'"></div>
    <button type="button" @click="show = !show"
            class="mt-3 inline-flex items-center space-x-1 text-sm hover:text-white/80 transition">
      <span x-text="show ? 'Sembunyikan Saldo' : 'Lihat Saldo'"></span>
      <i :class="show ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
    </button>
  </div>
</div>
