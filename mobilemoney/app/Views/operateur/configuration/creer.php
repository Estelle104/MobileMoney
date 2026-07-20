<?= $this->extend('layouts/operateur') ?>

<?= $this->section('content') ?>

<h1>Créer un préfixe</h1>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-error">
        <ul>
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= site_url('operateur/configuration/enregistrer') ?>" method="post">
    <?= csrf_field() ?>

    <label for="code">Code préfixe (3 chiffres)</label>
    <input type="text"
           name="code"
           id="code"
           maxlength="3"
           value="<?= old('code') ?>"
           required>

    <button type="submit">Enregistrer</button>
    <a href="<?= site_url('operateur/configuration/list') ?>">Annuler</a>
</form>

<?= $this->endSection() ?>