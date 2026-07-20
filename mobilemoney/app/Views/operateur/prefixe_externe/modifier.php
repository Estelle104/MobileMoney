<?= $this->extend('layouts/operateur') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 fw-bold">Modifier le préfixe externe : <?= esc($prefixe['code']) ?></h1>
    <a href="<?= site_url('operateur/prefixe-externe/list') ?>" class="btn btn-outline-secondary shadow-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour à la liste
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('operateur/prefixe-externe/mettreajour/' . $prefixe['id']) ?>" method="post">
            <?= csrf_field() ?>
            <div class="row g-4">
                <div class="col-md-4">
                    <label for="code" class="form-label fw-medium">Code du préfixe (3 chiffres) <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control" 
                           id="code" 
                           name="code" 
                           value="<?= old('code', $prefixe['code']) ?>" 
                           required 
                           pattern="[0-9]{3}"
                           maxlength="3">
                </div>
                
                <div class="col-md-4">
                    <label for="nom_operateur_externe" class="form-label fw-medium">Nom de l'opérateur externe <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control" 
                           id="nom_operateur_externe" 
                           name="nom_operateur_externe" 
                           value="<?= old('nom_operateur_externe', $prefixe['nom_operateur_externe']) ?>" 
                           required 
                           maxlength="100">
                </div>

                <div class="col-md-4">
                    <label for="pourcentage_commission" class="form-label fw-medium">Pourcentage de commission (%) <span class="text-danger">*</span></label>
                    <input type="number" 
                           step="0.01"
                           min="0"
                           class="form-control" 
                           id="pourcentage_commission" 
                           name="pourcentage_commission" 
                           value="<?= old('pourcentage_commission', $prefixe['pourcentage_commission']) ?>" 
                           required>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-lg me-1"></i> Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
