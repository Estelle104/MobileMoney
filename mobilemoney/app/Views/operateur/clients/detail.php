<?= $this->extend('layouts/operateur') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Détail du client</h1>
        <p class="text-secondary mb-0"><span class="badge bg-primary-subtle text-primary px-2 fs-6 me-1"><?= esc($client['numero']) ?></span> Client depuis le <?= esc(date('d/m/Y', strtotime($client['created_at']))) ?></p>
    </div>
    <a href="<?= site_url('operateur/clients/list') ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Retour à la liste
    </a>
</div>

<div class="row mb-5">
    <div class="col-md-4">
        <div class="card p-4 border-0 shadow-sm bg-primary text-white h-100">
            <span class="text-white-50 small fw-medium mb-1"> Solde actuel</span>
            <h2 class="amount my-2 text-white"><?= number_format($client['solde'], 2, ',', ' ') ?> <span class="fs-5 fw-normal">Ar</span></h2>
        </div>
    </div>
</div>

<h4 class="mb-3 fw-bold h5">Historique des opérations</h4>

<div class="card p-0 border-0 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover table-borderless align-middle mb-0">
            <thead class="bg-light text-secondary small fw-semibold">
                <tr>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Type</th>
                    <th class="px-4 py-3">Sens</th>
                    <th class="px-4 py-3 text-end">Montant</th>
                    <th class="px-4 py-3 text-end">Frais appliqués</th>
                </tr>
            </thead>
            <tbody class="border-top">
                <?php if (empty($historique)): ?>
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-secondary">Aucune opération enregistrée pour ce client.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($historique as $operation): ?>
                        <tr>
                            <td class="px-4 py-3"><?= esc(date('d/m/Y H:i', strtotime($operation['date_transaction']))) ?></td>
                            <td class="px-4 py-3 text-capitalize"><?= esc($operation['id_type_operation']) ?></td>
                            <td class="px-4 py-3">
                                <?php if ($operation['id_client_source'] == $client['id']): ?>
                                    <span class="badge bg-danger-subtle text-danger"><i class="bi bi-arrow-up-right me-1"></i>Envoyé</span>
                                <?php else: ?>
                                    <span class="badge bg-success-subtle text-success"><i class="bi bi-arrow-down-left me-1"></i>Reçu</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-end amount fw-medium">
                                <?php if ($operation['id_client_source'] == $client['id']): ?>
                                    <span class="text-danger">- <?= number_format($operation['montant'], 2, ',', ' ') ?></span>
                                <?php else: ?>
                                    <span class="text-success">+ <?= number_format($operation['montant'], 2, ',', ' ') ?></span>
                                <?php endif; ?> Ar
                            </td>
                            <td class="px-4 py-3 text-end amount text-secondary"><?= number_format($operation['frais'], 2, ',', ' ') ?> Ar</td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>