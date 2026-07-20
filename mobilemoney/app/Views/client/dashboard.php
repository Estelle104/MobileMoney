<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>
<div class="row mb-5 justify-content-center">
    <div class="col-lg-10">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 gap-3">
            <div>
                <h2 class="mb-1"><i class="bi bi-grid-1x2-fill text-primary me-2"></i> Tableau de bord</h2>
                <p class="text-secondary mb-0">Bienvenue sur votre espace personnel MobileMoney</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <a href="<?= site_url('client/solde') ?>" class="text-decoration-none">
                    <div class="card p-4 text-center h-100 card-hover">
                        <div class="mb-3">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="bi bi-wallet2 fs-3"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">Mon Solde</h5>
                        <p class="text-secondary small mb-0">Consulter l'état de votre compte</p>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-4">
                <a href="<?= site_url('client/depot') ?>" class="text-decoration-none">
                    <div class="card p-4 text-center h-100 card-hover">
                        <div class="mb-3">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="bi bi-arrow-down-circle fs-3"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">Dépôt</h5>
                        <p class="text-secondary small mb-0">Ajouter de l'argent</p>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-4">
                <a href="<?= site_url('client/retrait') ?>" class="text-decoration-none">
                    <div class="card p-4 text-center h-100 card-hover">
                        <div class="mb-3">
                            <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="bi bi-arrow-up-circle fs-3"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">Retrait</h5>
                        <p class="text-secondary small mb-0">Retirer des fonds</p>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-4">
                <a href="<?= site_url('client/transfert') ?>" class="text-decoration-none">
                    <div class="card p-4 text-center h-100 card-hover">
                        <div class="mb-3">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="bi bi-send fs-3"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">Transfert</h5>
                        <p class="text-secondary small mb-0">Envoyer à un proche</p>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-8">
                <a href="<?= site_url('client/historique') ?>" class="text-decoration-none">
                    <div class="card p-4 h-100 card-hover d-flex flex-row align-items-center">
                        <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle d-inline-flex align-items-center justify-content-center me-4" style="width: 60px; height: 60px; min-width: 60px;">
                            <i class="bi bi-clock-history fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-1">Historique des transactions</h5>
                            <p class="text-secondary small mb-0">Retrouvez la trace de toutes vos opérations passées (dépôts, retraits, transferts).</p>
                        </div>
                        <div class="ms-auto d-none d-sm-block">
                            <i class="bi bi-chevron-right text-secondary fs-4"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .card-hover {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: 1px solid transparent;
    }
    .card-hover:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.06) !important;
        border: 1px solid rgba(0,0,0,0.03);
    }
</style>
<?= $this->endSection() ?>