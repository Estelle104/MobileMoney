<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card p-4 p-md-5 text-center position-relative overflow-hidden">
            <!-- Decoration -->
            <div class="position-absolute rounded-circle bg-primary opacity-10" style="width: 150px; height: 150px; top: -50px; right: -50px;"></div>
            
            <h3 class="mb-4 position-relative z-1"><i class="bi bi-wallet2 text-primary me-2"></i> Choix pourcentage epargne/h3>
            
            <div class="mb-5 position-relative z-1">
                <p class="text-label mb-1">Entrer pourcentage</p>
                <form action="/client/enregistrer_epargne" method="post">
                    <input type="number" placeholder="Entrer votre pourcentage" name="pct_epargne">
                    <input type="submit" value="Enregistrer">
                </form>
            </div>
        

            <a href="<?= site_url('client/dashboard') ?>" class="btn btn-light w-100 border position-relative z-1 text-dark py-3 fw-semibold">
                <i class="bi bi-arrow-left me-1"></i> Retour au tableau de bord
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>