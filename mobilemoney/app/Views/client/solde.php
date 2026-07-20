<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card p-4 p-md-5 text-center position-relative overflow-hidden">
            <!-- Decoration -->
            <div class="position-absolute rounded-circle bg-primary opacity-10" style="width: 150px; height: 150px; top: -50px; right: -50px;"></div>
            
            <h3 class="mb-4 position-relative z-1"><i class="bi bi-wallet2 text-primary me-2"></i> Mon Solde</h3>
            
            <div class="mb-5 position-relative z-1">
                <p class="text-label mb-1">Montant disponible</p>
                <h2 class="amount-display text-dark mb-0" style="font-size: 2.5rem; font-weight: 700; letter-spacing: -1px;">
                    <?= number_format($client['solde'], 2, ',', ' ') ?> <span class="fs-4 text-secondary">Ar</span>
                </h2>
            </div>
            
            <div class="bg-light rounded-4 p-3 mb-4 d-flex justify-content-between align-items-center position-relative z-1 border border-light-subtle">
                <span class="text-secondary small fw-medium">Compte N°</span>
                <span class="fw-bold text-dark fs-5"><?= esc($client['numero']) ?></span>
            </div>
            
            <div class="d-flex align-items-center justify-content-center gap-2 mb-4 position-relative z-1">
                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-medium border border-success border-opacity-25">
                    <i class="bi bi-check-circle-fill me-1"></i> Actif et vérifié
                </span>
            </div>

            <a href="<?= site_url('client/dashboard') ?>" class="btn btn-light w-100 border position-relative z-1 text-dark py-3 fw-semibold">
                <i class="bi bi-arrow-left me-1"></i> Retour au tableau de bord
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>