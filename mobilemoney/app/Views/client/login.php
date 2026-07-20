<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center align-items-center" style="min-height: 75vh;">
    <div class="col-md-6 col-lg-4">
        <div class="text-center mb-4">
            <div class="bg-dark text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 64px; height: 64px;">
                <i class="bi bi-wallet2 fs-2"></i>
            </div>
            <h2 class="fw-bold mb-1">MobileMoney</h2>
            <p class="text-secondary small">Connectez-vous à votre espace client</p>
        </div>

        <div class="card p-4 p-md-5">
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger border-0 bg-danger-subtle text-danger rounded-3 small">
                    <i class="bi bi-exclamation-circle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('client/checklogin') ?>" method="post">
                <?= csrf_field() ?>

                <div class="mb-4">
                    <label class="form-label text-label">Numéro de téléphone</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0 bg-transparent text-secondary"><i class="bi bi-phone"></i></span>
                        <input
                            type="text"
                            name="numero"
                            id="numero"
                            maxlength="10"
                            class="form-control border-start-0 ps-0"
                            placeholder="Ex: 0340000000"
                            value="<?= old('numero') ?>"
                            required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 mb-2">
                    Accéder à mon compte
                </button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const numero = document.getElementById('numero');
    if (numero) {
        numero.addEventListener('input', () => {
            numero.value = numero.value.replace(/\D/g, '');
        });
    }
</script>
<?= $this->endSection() ?>