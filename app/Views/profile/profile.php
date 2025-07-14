<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<main class="flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">

    <form action="<?= base_url('profile/image') ?>" method="post" enctype="multipart/form-data" class="flex flex-col items-center">
        <label for="imageInput" class="cursor-pointer relative">
        <?php
            $imageUrl = $user['profile_image'] ?? '';
            $defaultImage = base_url('img/foto-profil.png');

            // Cek apakah image kosong atau mengandung null dari response API
            $finalImage = (empty($imageUrl) || str_ends_with($imageUrl, '/null'))
                ? $defaultImage
                : esc($imageUrl);
        ?>
        <img src="<?= $finalImage ?>" alt="Avatar" class="mt-2 w-20 h-20 rounded-full object-cover">

            <span class="absolute bottom-0 right-0 bg-white p-1 rounded-full shadow">
                <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M17.414 2.586a2 2 0 00-2.828 0L6 11.172V14h2.828l8.586-8.586a2 2 0 000-2.828zM5 18h14v-2H5v2z"/>
                </svg>
            </span>
        </label>
        <input id="imageInput" type="file" name="profile_image" class="hidden" onchange="this.form.submit()">
    </form>

    <h2 class="mt-4 text-xl font-semibold"><?= esc($user['first_name'] . ' ' . $user['last_name']) ?></h2>

    <form action="<?= base_url('profile/update') ?>" method="post" class="mt-6 space-y-4 w-full max-w-md">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="text-green-600"><?= session('success') ?></div>
        <?php elseif (session()->getFlashdata('error')): ?>
            <div class="text-red-600"><?= session('error') ?></div>
        <?php endif; ?>

        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" class="mt-1 w-full border px-3 py-2 rounded bg-gray-100" value="<?= esc($user['email']) ?>" disabled>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Nama Depan</label>
            <input type="text" name="first_name" value="<?= esc($user['first_name']) ?>" class="mt-1 w-full border px-3 py-2 rounded">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Nama Belakang</label>
            <input type="text" name="last_name" value="<?= esc($user['last_name']) ?>" class="mt-1 w-full border px-3 py-2 rounded">
        </div>

        <div class="flex gap-4 mt-4">
            <button type="submit" class="flex-1 bg-red-600 text-white py-2 rounded">Simpan</button>
        </div>

        <div class="mt-4">
            <a href="<?= base_url('logout') ?>" class="w-full block text-center border border-red-600 text-red-600 py-2 rounded">Logout</a>
        </div>
    </form>

</main>

<?= $this->endSection() ?>
