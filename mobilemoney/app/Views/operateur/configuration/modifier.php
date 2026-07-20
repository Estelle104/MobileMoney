<?= $this->extend('layouts/operateur') ?>

<?= $this->section('content') ?>

<h1>Modifier le prefixe</h1>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<form action="<?= site_url('operateur/configuration/mettreajour/' . $prefixe['id']) ?>" method="post">
    <?= csrf_field() ?>

    <label for="code">Code prefixe (3 chiffres)</label>
    <input type="text" name="code" id="code"
        maxlength="3" value="<?= old('code', $prefixe['code']) ?>" required>

    <p>Modifier ce code mettra à jour automatiquement les numeros de tous les clients rattaches</p>

    <button type="submit">Mettre à jour</button>
    <a href="<?= site_url('operateur/configuration/list') ?>">Annuler</a>
</form>

<?= $this->endSection() ?>