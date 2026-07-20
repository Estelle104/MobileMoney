<?= $this->extend('layouts/operateur') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card p-4 border-0 shadow-sm mt-4">
            <h1 class="h4 mb-4 fw-bold">Modifier le préfixe</h1>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= esc(session()->getFlashdata('error')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('operateur/configuration/mettreajour/' . $prefixe['id']) ?>" method="post">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="code" class="form-label text-secondary small fw-semibold">Code préfixe (3 chiffres)</label>
                    <input type="text" name="code" id="code" class="form-control form-control-lg"
                        maxlength="3" value="<?= old('code', $prefixe['code']) ?>" required>
                </div>

                <div class="alert alert-warning small d-flex align-items-center" role="alert">
                    <div>Modifier ce code mettra à jour automatiquement les numéros de tous les clients rattachés.</div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="<?= site_url('operateur/configuration/list') ?>" class="btn btn-light px-4">Annuler</a>
                    <button type="submit" class="btn btn-primary px-4">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>