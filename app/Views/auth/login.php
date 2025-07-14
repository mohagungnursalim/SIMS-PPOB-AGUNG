
<?= $this->extend('auth/layouts/main') ?>
<?= $this->section('content') ?>

<div class="min-h-screen grid grid-cols-1 md:grid-cols-2">
    <!-- Left Side (Form) -->
    <div class="flex flex-col justify-center px-8 md:px-24">
      <div class="w-full max-w-md mx-auto space-y-6">
        <!-- Logo -->
        <div class="flex items-center space-x-3 justify-center mb-6">
            <img src="<?= base_url('img/logo.png') ?>" alt="Logo SIMS PPOB" class="h-8 w-8 object-contain">
            <h1 class="text-red-600 font-semibold text-xl">SIMS PPOB</h1>
    </div>

        <h2 class="text-2xl font-semibold text-center">Masuk atau buat akun<br>untuk memulai</h2>

        <?php $validation = session('errors') ? (object) session('errors') : null; ?>

        <form action="<?= base_url('login') ?>" method="post" class="space-y-4">
          <?= csrf_field() ?>

          <!-- Email -->
          <div>
            <label for="email" class="sr-only">Email</label>
            <div class="relative">
              <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <i class="fa-solid fa-at text-gray-500"></i>
              </div>
              <input type="email" name="email" id="email" value="<?= old('email') ?>"
                class="w-full ps-10 py-2 border <?= isset($validation->email) ? 'border-red-500' : 'border-gray-300' ?> rounded-md focus:ring-red-500 focus:border-red-500"
                placeholder="masukan email anda"/>
            </div>
            <?php if (isset($validation->email)): ?>
              <p class="text-red-500 text-sm mt-1 text-right"><?= $validation->email ?></p>
            <?php endif; ?>
          </div>


          <!-- Password -->
          <div>
            <label for="password" class="sr-only">Password</label>
            <div class="relative">
              <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <i class="fa-solid fa-lock text-gray-500"></i>
              </div>
              <input type="password" name="password" id="password"
                class="w-full ps-10 py-2 border <?= isset($validation->password) ? 'border-red-500' : 'border-gray-300' ?> rounded-md focus:ring-red-500 focus:border-red-500"
                placeholder="masukan password anda"/>
            </div>
            <?php if (isset($validation->password)): ?>
              <p class="text-red-500 text-sm mt-1 text-right"><?= $validation->password ?></p>
            <?php endif; ?>
          </div>


          <!-- Submit -->
          <button type="submit"
            class="w-full mt-8 bg-red-600 text-white font-medium py-2 rounded hover:bg-red-700 transition">
            Masuk
          </button>

          <!-- Register link -->
          <p class="text-center text-sm">Belum punya akun? <a href="<?= base_url('register') ?>"
              class="text-red-600 hover:underline font-medium">registrasi di sini</a></p>

          <!-- Alert Error -->
          <?php if (session()->getFlashdata('error')): ?>
            <div class="mt-5 text-red-500 text-sm bg-red-50 border border-red-100 rounded px-4 py-2 mt-2 flex items-center justify-between">
              <?= session('error') ?>
              <button type="button" onclick="this.parentElement.remove()" class="text-red-500 ml-4 font-bold">✕</button>
            </div>
          <?php endif; ?>

        </form>
      </div>
    </div>

   
    <div class="hidden md:flex items-center justify-center bg-red-50">
      <img src="<?= base_url('img/ilustrasi-login.png') ?>" alt="Illustration" class="max-w-x" />
    </div>

  </div>
<?= $this->endsection() ?>