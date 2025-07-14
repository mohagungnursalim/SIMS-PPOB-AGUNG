<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<main class="pt-20 px-6 md:px-12 lg:px-24 pb-16">
    <?= view('components/profile_saldo') ?>

    <h1 class="text-l mb-6">Silahkan masukkan <br><span class="text-xl font-bold">Nominal Top Up</span></h1>

    <div x-data="topupFlowbite()" x-init="init()" class="w-full max-w-6xl mx-auto py-10 px-6 sm:px-10 lg:px-20 flex flex-col items-center">

        <!-- FORM  -->
        <form action="<?= base_url('topup') ?>" method="post" x-ref="form">
            <?= csrf_field() ?>
            <input type="hidden" name="top_up_amount" :value="amount">
        </form>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start w-full">

            <!-- Kolom Input -->
            <div class="md:col-span-2 w-full">
                <div class="relative mb-4">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                        <i class="fa-regular fa-credit-card mr-2"></i>
                    </div>
                    <input type="text" x-model="amount" placeholder="Masukkan nominal Top Up"
                        @input="amount = $el.value.replace(/\D/g, '')"
                        class="bg-gray-50 rounded border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5">
                </div>

                <!-- Tombol Top Up (Show Modal) -->
                <button
                    type="button"
                    :disabled="!isValid"
                    :class="isValid ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
                    data-modal-target="modal-konfirmasi" data-modal-toggle="modal-konfirmasi"
                    class="w-full text-lg py-3 rounded transition">
                    Top Up
                </button>
            </div>

            <!-- Preset Nominal -->
            <div class="grid grid-cols-3 gap-2 mt-6">
                <template x-for="nom in [10000, 20000, 50000, 100000, 250000, 500000]">
                    <button type="button" @click="amount = nom.toString()"
                        class="border border-gray-300 px-3 py-2 text-sm rounded hover:bg-gray-100">
                        Rp <span x-text="nom.toLocaleString('id-ID')"></span>
                    </button>
                </template>
            </div>
        </div>

        <!-- Modal Konfirmasi Top Up -->
        <div id="modal-konfirmasi" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black/50 backdrop-blur-sm">

            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow p-6 text-center">

                    <!-- Icon -->
                    <div class="flex justify-center mb-4">
                        <i class="fa-solid fa-circle-question text-yellow-500 text-4xl"></i>
                    </div>

                    <!-- Judul & Nominal -->
                    <h3 class="mb-1 text-gray-800">Anda yakin untuk Top Up sebesar</h3>
                    <p class="text-2xl font-bold text-gray-900 mb-4">
                        Rp<span x-text="Number(amount).toLocaleString('id-ID')"></span> ?
                    </p>

                    <!-- Tombol Aksi -->
                    <div class="flex flex-col justify-center gap-2">
                        <!-- Tombol Ya, lanjutkan -->
                        <button @click="submitForm()" data-modal-hide="modal-konfirmasi"
                            class="text-green-600 font-semibold hover:underline text-sm">
                            Ya, lanjutkan
                        </button>

                        <!-- Tombol Batal -->
                        <button data-modal-hide="modal-konfirmasi" type="button"
                            class="text-gray-500 font-medium hover:underline text-sm">
                            Batalkan
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal Feedback Top Up -->
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

                    <!-- Judul -->
                    <h3 class="mb-1 text-gray-800 font-semibold">Top Up Saldo</h3>

                    <!-- Status -->
                    <h3 class="mb-1 text-gray-800 font-bold">
                        <?= session('success') ? 'Berhasil' : 'Gagal' ?>
                    </h3>

                    <!-- Pesan -->
                     <p>Total Saldo Anda:</p>
                    <p class="text-2xl font-bold text-gray-900 mb-4">
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

    </div>
</main>

<!-- Script untuk menginisialisasi Alpine.js dan mengatur validasi input -->
<script>
    function topupFlowbite() {
        return {
            amount: '',
            isValid: false,
            init() {
                this.$watch('amount', val => {
                    const num = parseInt(val);
                    this.isValid = !isNaN(num) && num >= 10000 && num <= 1000000;
                });
            },
            submitForm() {
                this.$refs.form.submit();
            }
        };
    }
</script>

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
        } else {
            console.error("Modal Flowbite gagal ditampilkan.");
        }
    });
</script>
<?php endif; ?>


<?= $this->endSection() ?>
