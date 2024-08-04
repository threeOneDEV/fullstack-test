<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mt-5">
    <h1 class="mb-4">Комментарии</h1>

    <div id="comments-section" class="mb-5"></div>

    <div id="pagination" class="mb-5"></div>

    <?= view('comment/create') ?>

</div>

    <?= view('comment/ajax') ?>

<?= $this->endSection() ?>