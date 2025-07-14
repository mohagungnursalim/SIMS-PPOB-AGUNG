<!-- Templating Header -->
<?= $this->include('layouts/header') ?>


<!-- Isi Konten -->
<div class="container mx-auto px-4 py-6">
    <?= $this->renderSection('content') ?>
</div>


<!-- Templating Footer -->
 <?= $this->include('layouts/footer') ?>