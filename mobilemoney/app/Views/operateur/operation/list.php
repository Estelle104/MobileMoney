<?= $this->extend('layouts/operateur') ?>

<?= $this->section('content') ?>

<h1>Barème de frais — <?= esc($typeOperation['libelle']) ?></h1>

<a href="<?= site_url('operateur/operation/ajouter') ?>" class="btn btn-primary">
    + Ajouter une tranche
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
            <th>Montant min</th>
            <th>Montant max</th>
            <th>Frais</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($tranches)): ?>
            <tr>
                <td colspan="4">Aucune tranche configurée pour ce type d'opération.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($tranches as $tranche): ?>
                <tr>
                    <td><?= esc($tranche['montant_min']) ?></td>
                    <td><?= esc($tranche['montant_max']) ?></td>
                    <td><?= esc($tranche['frais']) ?></td>
                    <td>
                        <a href="<?= site_url('operateur/operation/modifier/' . $tranche['id']) ?>">
                            Modifier
                        </a>

                        <form action="<?= site_url('operateur/operation/supprimer/' . $tranche['id']) ?>"
                              method="post"
                              style="display:inline"
                              onsubmit="return confirm('Supprimer cette tranche ?');">
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