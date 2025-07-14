<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<main class="pt-20 px-6 md:px-12 lg:px-24 pb-16">

    <!-- Profil Saldo Component -->
    <?= view('components/profile_saldo') ?>
    <div class="max-w-xl mx-auto py-10 px-4" x-data="{ showModal: false }">
        <h2 class="text-xl font-semibold mb-4">Pembayaran</h2>

        <!-- Service Info -->
        <div class="flex items-center gap-3 mb-4">
            <img src="<?= esc($service['service_icon']) ?>" alt="icon" class="w-10 h-10">
            <span class="text-lg font-semibold"><?= esc($service['service_name']) ?></span>
        </div>

        <!-- Input Harga -->
        <div class="relative mb-4">
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
                <input type="text" disabled readonly
                    value="Rp <?= number_format($service['service_tariff'], 0, ',', '.') ?>"
                    class="bg-gray-50 rounded border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 ">
            </div>
        </div>

        <!-- Tombol Bayar -->
        <button data-modal-target="modal-konfirmasi" data-modal-toggle="modal-konfirmasi"
            class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-lg text-center">
            Bayar
        </button>

        <!-- Modal Konfirmasi Pembayaran -->
        <div id="modal-konfirmasi" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black/50 backdrop-blur-sm">

            <div class="relative p-4 w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow p-6 text-center">

                    <!-- Icon -->
                    <div class="flex justify-center mb-4">
                        <img src="<?= esc($service['service_icon']) ?>" alt="icon" class="w-12 h-12">
                    </div>

                    <!-- Judul & Nominal -->
                    <h3 class="mb-1 text-gray-800">Beli <?= esc($service['service_name']) ?> senilai
                    </h3>
                    <p class="text-2xl font-bold text-gray-900 mb-4">
                        Rp<?= number_format($service['service_tariff'], 0, ',', '.') ?> ?
                    </p>

                    <!-- Form -->
                    <form action="<?= base_url('transaction') ?>" method="post"
                        class="flex flex-col items-center gap-3">
                        <?= csrf_field() ?>
                        <input type="hidden" name="service_code" value="<?= esc($service['service_code']) ?>">

                        <!-- Tombol Lanjut -->
                        <button data-modal-hide="modal-konfirmasi" type="submit"
                            class="text-red-600 font-semibold hover:underline text-sm">
                            Ya, lanjutkan Bayar
                        </button>

                        <!-- Tombol Batal -->
                        <button data-modal-hide="modal-konfirmasi" type="button"
                            class="text-gray-500 font-medium hover:underline text-sm">
                            Batalkan
                        </button>
                    </form>
                </div>
            </div>
        </div>



        <!-- Modal feedback -->
        <div id="modal-feedback" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black/50 backdrop-blur-sm">

            <div class="relative p-4 w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow p-6 text-center">

                    <!-- Icon -->
                    <div class="flex justify-center mb-4">
                        <?php if (session('success')): ?>
                        <i class="fa-solid fa-circle-check text-green-500 text-4xl"></i>
                        <?php else: ?>
                        <i class="fa-solid fa-circle-xmark text-red-500 text-4xl"></i>
                        <?php endif; ?>
                    </div>

                    <!-- Judul & Nominal -->
                    <h3 class="mb-1 text-gray-800">Pembayaran <?= esc($service['service_name']) ?> senilai</h3>
                    <p class="text-2xl font-bold text-gray-900 mb-4">
                        Rp<?= number_format($service['service_tariff'], 0, ',', '.') ?>
                    </p>

                    <!-- Status -->
                    <h3 class="mb-1 text-gray-800 font-bold">
                        <?= session('success') ? 'Berhasil' : 'Gagal' ?>
                    </h3>

                    <!-- Pesan -->
                    <p class="text-sm text-gray-600 mb-4 leading-relaxed">
                        <?= esc(session('success') ?? session('error')) ?>
                    </p>

                    <!-- Tombol -->
                    <div class="flex justify-center">
                        <a href="<?= base_url('/') ?>"
                            class="<?= session('success') ? 'text-green-600' : 'text-red-600' ?> font-semibold hover:underline text-sm">
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>



        <!-- Script Trigger Modal Feedback -->
        <?php if (session('success') || session('error')): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const modalElement = document.getElementById('modal-feedback');
                    if (modalElement && window.Flowbite?.default?.Modal) {
                        const modal = new window.Flowbite.default.Modal(modalElement);
                        modal.show();

                        <?php if (session('success')): ?>
                        setTimeout(() => {
                            window.location.href = "<?= base_url('/') ?>";
                        }, 3000);
                        <?php endif; ?>
                    }
                });
            </script>
        <?php endif; ?>



    </div>
</main>


<?= $this->endSection() ?>