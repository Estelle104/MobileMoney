<?= $this->extend('layouts/operateur') ?>

<?= $this->section('content') ?>

<h1>Ajouter une tranche de frais</h1>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-error">
        <ul>
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= site_url('operateur/operation/enregistrer') ?>" method="post">
    <?= csrf_field() ?>

    <label for="id_type_operation">Type d'opération</label>
    <select name="id_type_operation" id="id_type_operation" required>
        <option value="">-- Choisir --</option>
        <?php foreach ($types as $type): ?>
            <option value="<?= $type['id'] ?>" <?= old('id_type_operation') == $type['id'] ? 'selected' : '' ?>>
                <?= esc($type['libelle']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="montant_min">Montant min</label>
    <input type="number" step="0.01" name="montant_min" id="montant_min" value="<?= old('montant_min') ?>" required>

    <label for="montant_max">Montant max</label>
    <input type="number" step="0.01" name="montant_max" id="montant_max" value="<?= old('montant_max') ?>" required>

    <label for="frais">Frais</label>
    <input type="number" step="0.01" name="frais" id="frais" value="<?= old('frais') ?>" required>

    <button type="submit">Enregistrer</button>
    <a href="<?= site_url('operateur/dashboard') ?>">Annuler</a>
</form>

<?= $this->endSection() ?>