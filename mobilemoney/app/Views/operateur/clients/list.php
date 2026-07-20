<?= $this->extend('layouts/operateur') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 fw-bold">Comptes clients</h1>
</div>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i><?= esc(session()->getFlashdata('error')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card p-0 border-0 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover table-borderless align-middle mb-0">
            <thead class="bg-light text-secondary small fw-semibold">
                <tr>
                    <th class="px-4 py-3">Numéro</th>
                    <th class="px-4 py-3">Solde</th>
                    <th class="px-4 py-3 text-end">Actions</th>
                </tr>
            </thead>
            <tbody class="border-top">
                <?php if (empty($clients)): ?>
                    <tr>
                        <td colspan="3" class="px-4 py-4 text-center text-secondary">Aucun client enregistré.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($clients as $client): ?>
                        <tr>
                            <td class="px-4 py-3 fw-medium"><?= esc($client['numero']) ?></td>
                            <td class="px-4 py-3 amount text-primary"><?= number_format($client['solde'], 2, ',', ' ') ?> Ar</td>
                            <td class="px-4 py-3 text-end">
                                <a href="<?= site_url('operateur/clients/detail/' . $client['id']) ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="bi bi-eye me-1"></i> Voir détail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>