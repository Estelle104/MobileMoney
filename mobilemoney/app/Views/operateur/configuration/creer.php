<?= $this->extend('layouts/operateur') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card p-4 border-0 shadow-sm mt-4">
            <h1 class="h4 mb-4 fw-bold">Créer un préfixe</h1>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                   <strong>Erreurs de validation:</strong>
                    <ul class="mb-0 mt-2">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('operateur/configuration/enregistrer') ?>" method="post">
                <?= csrf_field() ?>

                <div class="mb-4">
                    <label for="code" class="form-label text-secondary small fw-semibold">Code préfixe (3 chiffres)</label>
                    <input type="text"
                           name="code"
                           id="code"
                           class="form-control form-control-lg"
                           maxlength="3"
                           value="<?= old('code') ?>"
                           placeholder="Ex: 034"
                           required>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="<?= site_url('operateur/configuration/list') ?>" class="btn btn-light px-4">Annuler</a>
                    <button type="submit" class="btn btn-primary px-4">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>