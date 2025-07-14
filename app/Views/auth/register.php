<h2>Registrasi</h2>

<?php if(session('success')): ?>
<div style="color:green"><?= session('success') ?></div>
<?php endif; ?>

<?php if(session('error')): ?>
<div style="color:red"><?= session('error') ?></div>
<?php endif; ?>


<form method="post" action="/register">
    <input type="text" name="first_name" placeholder="Nama Depan" value="<?= old('first_name') ?>"><br>
    <input type="text" name="last_name" placeholder="Nama Belakang" value="<?= old('last_name') ?>"><br>
    <input type="email" name="email" placeholder="Email" value="<?= old('email') ?>"><br>
    <input type="password" name="password" placeholder="Password"><br>
    <input type="password" name="confirm" placeholder="Ulangi Password"><br>
    <button type="submit">Daftar</button>
</form>

<?php if(session('errors')): ?>
<ul style="color:red">
    <?php foreach(session('errors') as $field => $err): ?>
    <li><?= $err ?></li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>










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

            <h2 class="text-2xl font-semibold text-center">Lengkapi data<br>untuk membuat akun</h2>


            <?php $validation = session('errors') ? (object) session('errors') : null; ?>

            <form action="<?= base_url('register') ?>" method="post" class="space-y-4">
                <?= csrf_field() ?>

                <!-- Email -->
                <div>
                    <label for="email" class="sr-only">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <i class="fa-solid fa-at text-gray-500"></i>
                        </div>
                        <input type="email" name="email" id="email" value="<?= old('email') ?>"
                            class="w-full ps-10 py-2 border <?= isset($validation->email) ? 'border-red-500' : 'border-gray-300' ?> rounded-md focus:ring-gray-500 focus:border-gray-500"
                            placeholder="masukan email anda" />
                    </div>
                    <?php if (isset($validation->email)): ?>
                    <p class="text-red-500 text-sm mt-1 text-right"><?= $validation->email ?></p>
                    <?php endif; ?>
                </div>

                <!-- Nama Depan -->
                <div>
                    <label for="first_name" class="sr-only">Nama Depan</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <i class="fa-solid fa-user text-gray-500"></i>
                        </div>
                        <input type="text" name="first_name" id="first_name" value="<?= old('first_name') ?>"
                            class="w-full ps-10 py-2 border <?= isset($validation->first_name) ? 'border-red-500' : 'border-gray-300' ?> rounded-md focus:ring-gray-500 focus:border-gray-500"
                            placeholder="nama depan" />
                    </div>
                    <?php if (isset($validation->first_name)): ?>
                    <p class="text-red-500 text-sm mt-1 text-right"><?= $validation->first_name ?></p>
                    <?php endif; ?>
                </div>

                <!-- Nama Belakang -->
                <div>
                    <label for="last_name" class="sr-only">nama belakang</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <i class="fa-solid fa-user text-gray-500"></i>
                        </div>
                        <input type="text" name="last_name" id="last_name" value="<?= old('last_name') ?>"
                            class="w-full ps-10 py-2 border <?= isset($validation->last_name) ? 'border-red-500' : 'border-gray-300' ?> rounded-md focus:ring-gray-500 focus:border-gray-500"
                            placeholder="nama belakang" />
                    </div>
                    <?php if (isset($validation->last_name)): ?>
                    <p class="text-red-500 text-sm mt-1 text-right"><?= $validation->last_name ?></p>
                    <?php endif; ?>
                </div>


                <!-- Password -->
                <div>
                    <label for="password" class="sr-only">password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-500"></i>
                        </div>
                        <input type="password" name="password" id="password"
                            class="w-full ps-10 py-2 border <?= isset($validation->password) ? 'border-red-500' : 'border-gray-300' ?> rounded-md focus:ring-gray-500 focus:border-gray-500"
                            placeholder="masukan password anda" />
                    </div>
                    <?php if (isset($validation->password)): ?>
                    <p class="text-red-500 text-sm mt-1 text-right"><?= $validation->password ?></p>
                    <?php endif; ?>
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label for="password" class="sr-only">konfirmasi password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-500"></i>
                        </div>
                        <input type="password" name="confirm" id="confirm"
                            class="w-full ps-10 py-2 border <?= isset($validation->confirm) ? 'border-red-500' : 'border-gray-300' ?> rounded-md focus:ring-gray-500 focus:border-gray-500"
                            placeholder="konfirmasi password" />
                    </div>
                    <?php if (isset($validation->confirm)): ?>
                    <p class="text-red-500 text-sm mt-1 text-right"><?= $validation->confirm ?></p>
                    <?php endif; ?>
                </div>


                <!-- Submit -->
                <button type="submit"
                    class="w-full mt-8 bg-red-600 text-white font-medium py-2 rounded hover:bg-red-700 transition">
                    Registrasi
                </button>

                <!-- Login link -->
                <p class="text-center text-sm">sudah punya akun? <a href="<?= base_url('login') ?>"
                        class="text-red-600 hover:underline font-medium">login di sini</a></p>

                <!-- Alert Error -->
                <?php if (session()->getFlashdata('error')): ?>
                <div
                    class="mt-5 text-red-500 text-sm bg-red-50 border border-red-100 rounded px-4 py-2 mt-2 flex items-center justify-between">
                    <?= session('error') ?>
                    <button type="button" onclick="this.parentElement.remove()"
                        class="text-red-500 ml-4 font-bold">✕</button>
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