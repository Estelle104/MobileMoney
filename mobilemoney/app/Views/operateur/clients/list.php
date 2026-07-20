<?= $this->extend('layouts/operateur') ?>

<?= $this->section('content') ?>

<h1>Comptes clients</h1>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<table class="table">
    <thead>
        <tr>
            <th>Numéro</th>
            <th>Solde</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($clients)): ?>
            <tr>
                <td colspan="3">Aucun client enregistré.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?= esc($client['numero']) ?></td>
                    <td><?= number_format($client['solde'], 2) ?> Ar</td>
                    <td>
                        <a href="<?= site_url('operateur/clients/detail/' . $client['id']) ?>">
                            Voir détail
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?= $this->endSection() ?>