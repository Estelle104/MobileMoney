<?= $this->extend('layouts/operateur') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4 border-0 shadow-sm mt-4">
            <h1 class="h4 mb-4 fw-bold">Ajouter une tranche de frais</h1>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= esc(session()->getFlashdata('error')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

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

            <form action="<?= site_url('operateur/operation/enregistrer') ?>" method="post">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="id_type_operation" class="form-label text-secondary small fw-semibold">Type d'opération</label>
                    <select name="id_type_operation" id="id_type_operation" class="form-select" required>
                        <option value="">-- Choisir --</option>
                        <?php foreach ($types as $type): ?>
                            <option value="<?= $type['id'] ?>" <?= old('id_type_operation') == $type['id'] ? 'selected' : '' ?>>
                                <?= esc($type['libelle']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="montant_min" class="form-label text-secondary small fw-semibold">Montant min</label>
                        <input type="number" step="0.01" name="montant_min" id="montant_min" class="form-control" value="<?= old('montant_min') ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="montant_max" class="form-label text-secondary small fw-semibold">Montant max</label>
                        <input type="number" step="0.01" name="montant_max" id="montant_max" class="form-control" value="<?= old('montant_max') ?>" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="frais" class="form-label text-secondary small fw-semibold">Frais (en Ar ou en % si < 1)</label>
                    <input type="number" step="0.01" name="frais" id="frais" class="form-control" value="<?= old('frais') ?>" required>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="<?= site_url('operateur/dashboard') ?>" class="btn btn-light px-4">Annuler</a>
                    <button type="submit" class="btn btn-primary px-4">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>