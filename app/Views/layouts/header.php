<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>

    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>


<nav class="bg-white  fixed w-full z-20 top-0 start-0 border-b border-gray-200">
  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
  <a href="<?= base_url('/') ?>" class="flex items-center space-x-3 rtl:space-x-reverse">
      <img src="<?= base_url('img/logo.png') ?>" class="h-8" alt="SIMS PPOB Logo">
      <span class="self-center text-2xl font-semibold whitespace-nowrap">SIMS PPOB</span>
  </a>
  
  <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
  <?php
    $current = uri_string();
  ?>


  <ul class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white">
    <li>
      <a href="<?= base_url('/') ?>"
        class="block py-2 px-3 rounded-sm md:p-0
        <?= ($current === '' ? 'text-red-500 font-semibold md:text-red-500' : 'text-gray-900 hover:bg-gray-100 md:hover:text-blue-700') ?>">
        Home
      </a>
    </li>
    <li>
      <a href="<?= base_url('topup') ?>"
        class="block py-2 px-3 rounded-sm md:p-0
        <?= ($current === 'topup' ? 'text-red-500 font-semibold md:text-red-500' : 'text-gray-900 hover:bg-gray-100 md:hover:text-blue-700') ?>">
        Top Up
      </a>
    </li>
    <li>
      <a href="<?= base_url('transactions') ?>"
        class="block py-2 px-3 rounded-sm md:p-0
        <?= ($current === 'transactions' ? 'text-red-500 font-semibold md:text-red-500' : 'text-gray-900 hover:bg-gray-100 md:hover:text-blue-700') ?>">
        Transaction
      </a>
    </li>
    <li>
      <a href="<?= base_url('profile') ?>"
        class="block py-2 px-3 rounded-sm md:p-0
        <?= ($current === 'profile' ? 'text-red-500 font-semibold md:text-red-500' : 'text-gray-900 hover:bg-gray-100 md:hover:text-blue-700') ?>">
        Akun
      </a>
    </li>
  </ul>


  </div>
  </div>
</nav>
