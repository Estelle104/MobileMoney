<?= $this->extend('layouts/operateur') ?>

<?= $this->section('content') ?>

<h1>Détail du client — <?= esc($client['numero']) ?></h1>

<p><strong>Solde actuel :</strong> <?= number_format($client['solde'], 2) ?> Ar</p>
<p><strong>Client depuis :</strong> <?= esc($client['created_at']) ?></p>

<a href="<?= site_url('operateur/clients/list') ?>">&larr; Retour à la liste</a>

<h2>Historique des opérations</h2>

<table class="table">
    <thead>
        <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Sens</th>
            <th>Montant</th>
            <th>Frais</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($historique)): ?>
            <tr>
                <td colspan="5">Aucune opération enregistrée.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($historique as $operation): ?>
                <tr>
                    <td><?= esc($operation['date_transaction']) ?></td>
                    <td><?= esc($operation['id_type_operation']) ?></td>
                    <td>
                        <?php if ($operation['id_client_source'] == $client['id']): ?>
                            Envoyé
                        <?php else: ?>
                            Reçu
                        <?php endif; ?>
                    </td>
                    <td><?= number_format($operation['montant'], 2) ?> Ar</td>
                    <td><?= number_format($operation['frais'], 2) ?> Ar</td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?= $this->endSection() ?>