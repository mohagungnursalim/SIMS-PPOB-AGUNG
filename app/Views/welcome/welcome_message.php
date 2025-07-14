<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<head>
  <style>
    /* Sembunyikan Scrollbar */
    .scrollbar-hide::-webkit-scrollbar {
      display: none;
    }

    .scrollbar-hide {
      -ms-overflow-style: none;
      /* IE and Edge */
      scrollbar-width: none;
      /* Firefox */
    }
  </style>
</head>
<!-- Konten Utama -->
<main class="pt-20 px-6 md:px-12 lg:px-24 pb-16">

    <!-- Profil Saldo Component -->
    <?= view('components/profile_saldo') ?>


  <!-- Services -->
  <section class="mt-6">
    <div class="flex gap-4 overflow-x-auto pb-2 scrollbar-hide">
      <?php foreach ($services as $service): ?>
        <a href="<?= base_url('payment/' . esc($service['service_code'])) ?>" 
          class="flex-shrink-0 w-16 text-center hover:opacity-80 transition">
          <img src="<?= esc($service['service_icon']) ?>" alt="<?= esc($service['service_name']) ?>"
            class="w-10 h-10 mx-auto mb-1 object-contain">
          <p class="text-xs leading-tight"><?= esc($service['service_name']) ?></p>
        </a>
      <?php endforeach; ?>
    </div>
  </section>



  <!-- Promo Section -->
  <div>
    <h2 class="text-lg font-semibold mb-4">Temukan promo menarik</h2>
    <section class="px-4 py-8">
      <div class="flex overflow-x-auto space-x-4 snap-x snap-mandatory scroll-smooth scrollbar-hide">
        <?php foreach ($banners as $banner): ?>
        <div class="min-w-[300px] snap-start shrink-0 bg-white rounded-lg shadow-md overflow-hidden">
          <img src="<?= esc($banner['banner_image']) ?>" alt="<?= esc($banner['banner_name']) ?>"
            class="w-full h-40 object-cover">

        </div>
        <?php endforeach; ?>
      </div>
    </section>


  </div>
</main>



<?= $this->endsection() ?>