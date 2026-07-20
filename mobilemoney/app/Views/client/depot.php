<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card p-4 p-md-5">
            <h3 class="mb-1 text-center"><i class="bi bi-arrow-down-circle text-success me-2"></i> Faire un dépôt</h3>
            <p class="text-secondary text-center mb-4 small">Ajoutez des fonds à votre compte</p>

            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger border-0 bg-danger-subtle text-danger rounded-3">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= site_url('client/depot/valider') ?>">
                <?= csrf_field() ?>

                <div class="mb-4">
                    <label for="montant" class="form-label text-label">Montant du dépôt</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0 bg-transparent text-secondary">Ar</span>
                        <input type="text" name="montant" id="montant" class="form-control border-start-0 ps-0" placeholder="Ex: 50000" required>
                    </div>
                </div>

                <button type="submit" class="btn w-100 py-3 mb-3 text-white fw-bold" style="background-color: var(--bs-success);">
                    Confirmer le dépôt
                </button>
                
                <a href="<?= site_url('client/dashboard') ?>" class="text-decoration-none text-secondary d-block text-center small fw-medium">
                    <i class="bi bi-arrow-left me-1"></i> Annuler et retourner
                </a>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const montantInput = document.getElementById('montant');
    if(montantInput){
        montantInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
        });
    }
});
</script>
<?= $this->endSection() ?>