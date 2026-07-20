<?= $this->extend('layouts/operateur') ?>

<?= $this->section('content') ?>

<h1>Configuration des prefixes</h1>

<a href="<?= site_url('operateur/configuration/creer') ?>" class="btn btn-primary">
    + Ajouter un prefixe
</a>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<table class="table">
    <thead>
        <tr>
            <th>Code</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($prefixes)): ?>
            <tr>
                <td colspan="2">Aucun prefixe configure</td>
            </tr>
        <?php else: ?>
            <?php foreach ($prefixes as $prefixe): ?>
                <tr>
                    <td><?= esc($prefixe['code']) ?></td>
                    <td>
                        <a href="<?= site_url('operateur/configuration/modifier/' . $prefixe['id']) ?>">
                            Modifier
                        </a>

                        <form action="<?= site_url('operateur/configuration/supprimer/' . $prefixe['id']) ?>"
                            method="post"
                            style="display:inline"
                            onsubmit="return confirm('Confirmer la suppression du prefixe <?= esc($prefixe['code']) ?> ?');">
                            <?= csrf_field() ?>
                            <button type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?= $this->endSection() ?>