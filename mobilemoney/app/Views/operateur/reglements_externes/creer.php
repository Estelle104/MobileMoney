<?= $this->extend('layouts/operateur') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 fw-bold">Ajouter un règlement</h1>
    <a href="<?= site_url('operateur/reglements-externes') ?>" class="btn btn-outline-secondary shadow-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('operateur/reglements-externes/enregistrer') ?>" method="post">
            <?= csrf_field() ?>
            <div class="row g-4">
                <div class="col-md-6">
                    <label for="nom_operateur_externe" class="form-label fw-medium">Opérateur externe <span class="text-danger">*</span></label>
                    <select class="form-select" id="nom_operateur_externe" name="nom_operateur_externe" required>
                        <option value="">Sélectionnez un opérateur</option>
                        <?php foreach ($nomsExternes as $nom): ?>
                            <option value="<?= esc($nom) ?>"><?= esc($nom) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="montant" class="form-label fw-medium">Montant réglé (Ar) <span class="text-danger">*</span></label>
                    <input type="number" 
                           step="0.01"
                           min="0"
                           class="form-control" 
                           id="montant" 
                           name="montant" 
                           required>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-lg me-1"></i> Enregistrer le règlement
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
