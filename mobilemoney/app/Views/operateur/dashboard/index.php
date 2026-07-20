<?= $this->extend('layouts/operateur') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 fw-bold">Tableau de bord</h1>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i><?= esc(session()->getFlashdata('success')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Cartes de statistiques rapides -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card h-100 p-4 border-0 shadow-sm">
            <span class="text-secondary small fw-medium mb-1"><i class="bi bi-gear-fill me-1"></i> Préfixes configurés</span>
            <h2 class="amount text-primary my-2"><?= $nbPrefixes ?></h2>
            <a href="<?= site_url('operateur/configuration/list') ?>" class="text-decoration-none small fw-semibold mt-auto">Gérer les préfixes <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card h-100 p-4 border-0 shadow-sm">
            <span class="text-secondary small fw-medium mb-1"><i class="bi bi-people-fill me-1"></i> Clients enregistrés</span>
            <h2 class="amount text-primary my-2"><?= $nbClients ?></h2>
            <a href="<?= site_url('operateur/clients/list') ?>" class="text-decoration-none small fw-semibold mt-auto">Voir les clients <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card h-100 p-4 border-0 shadow-sm bg-primary text-white">
            <span class="text-white-50 small fw-medium mb-1"><i class="bi bi-wallet2 me-1"></i> Solde total des clients</span>
            <h2 class="amount my-2 text-white"><?= number_format($soldeTotal, 2, ',', ' ') ?> <span class="fs-5 fw-normal">Ar</span></h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card h-100 p-4 border-0 shadow-sm">
            <span class="text-secondary small fw-medium mb-1"><i class="bi bi-graph-up-arrow me-1 text-success"></i> Gains du jour</span>
            <h2 class="amount text-success my-2"><?= number_format($gainsAujourdhui, 2, ',', ' ') ?> <span class="fs-5 fw-normal">Ar</span></h2>
            <a href="<?= site_url('operateur/gains') ?>" class="text-decoration-none text-success small fw-semibold mt-auto">Voir tous les gains <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card p-4 border-0 shadow-sm h-100">
            <h4 class="mb-3 h5 fw-bold"><i class="bi bi-cash-stack me-2 text-primary"></i>Barèmes de frais</h4>
            <p class="text-secondary small mb-4">Accès rapide aux configurations par type d'opération</p>
            <div class="d-flex flex-wrap gap-2">
                <?php foreach ($types as $type): ?>
                    <a href="<?= site_url('operateur/operation/list/' . $type['id']) ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                        <?= esc(ucfirst($type['libelle'])) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card p-4 border-0 shadow-sm h-100">
            <h4 class="mb-3 h5 fw-bold">Actions rapides</h4>
            <div class="list-group list-group-flush mt-2">
                <a href="<?= site_url('operateur/configuration/creer') ?>" class="list-group-item list-group-item-action border-0 px-0 py-2 d-flex align-items-center">
                    <span class="fw-medium">Ajouter un préfixe</span>
                    <i class="bi bi-chevron-right ms-auto text-secondary small"></i>
                </a>
                <a href="<?= site_url('operateur/operation/ajouter') ?>" class="list-group-item list-group-item-action border-0 px-0 py-2 d-flex align-items-center">
                    <span class="fw-medium">Ajouter une tranche de frais</span>
                    <i class="bi bi-chevron-right ms-auto text-secondary small"></i>
                </a>
                <a href="<?= site_url('operateur/clients/list') ?>" class="list-group-item list-group-item-action border-0 px-0 py-2 d-flex align-items-center">
                    <span class="fw-medium">Consulter les comptes clients</span>
                    <i class="bi bi-chevron-right ms-auto text-secondary small"></i>
                </a>
                <a href="<?= site_url('operateur/gains/filtrer') ?>" class="list-group-item list-group-item-action border-0 px-0 py-2 d-flex align-items-center">
                    <span class="fw-medium">Filtrer les gains par date</span>
                    <i class="bi bi-chevron-right ms-auto text-secondary small"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>