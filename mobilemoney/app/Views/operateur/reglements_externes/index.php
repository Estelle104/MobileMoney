<?= $this->extend('layouts/operateur') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 fw-bold">Règlements avec les autres opérateurs</h1>
    <a href="<?= site_url('operateur/reglements-externes/creer') ?>" class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-circle me-1"></i> Ajouter un règlement
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i><?= esc(session()->getFlashdata('success')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card p-4 border-0 shadow-sm mb-4">
    <form action="<?= site_url('operateur/reglements-externes/filtrer') ?>" method="get" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label for="date_debut" class="form-label text-secondary small fw-semibold">Du</label>
            <input type="date" class="form-control" name="date_debut" id="date_debut" value="<?= esc($dateDebut ?? '') ?>">
        </div>
        <div class="col-md-4">
            <label for="date_fin" class="form-label text-secondary small fw-semibold">Au</label>
            <input type="date" class="form-control" name="date_fin" id="date_fin" value="<?= esc($dateFin ?? '') ?>">
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary me-2">Filtrer</button>
            <a href="<?= site_url('operateur/reglements-externes') ?>" class="btn btn-outline-secondary">Réinitialiser</a>
        </div>
    </form>
</div>

<div class="card p-0 border-0 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover table-borderless align-middle mb-0">
            <thead class="bg-light text-secondary small fw-semibold">
                <tr>
                    <th class="px-4 py-3">Opérateur externe</th>
                    <th class="px-4 py-3 text-end">Montant total transféré</th>
                    <th class="px-4 py-3 text-end">Montant déjà réglé</th>
                    <th class="px-4 py-3 text-end">Solde à payer</th>
                </tr>
            </thead>
            <tbody class="border-top">
                <?php if (empty($situations)): ?>
                    <tr>
                        <td colspan="4" class="px-4 py-4 text-center text-secondary">Aucun opérateur externe configuré.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($situations as $s): ?>
                        <tr>
                            <td class="px-4 py-3 fw-medium text-dark"><i class="bi bi-building me-2 text-primary"></i><?= esc($s['nom_operateur_externe']) ?></td>
                            <td class="px-4 py-3 text-end text-secondary"><?= number_format($s['total_transfere'], 2, ',', ' ') ?> Ar</td>
                            <td class="px-4 py-3 text-end text-success"><?= number_format($s['total_regle'], 2, ',', ' ') ?> Ar</td>
                            <td class="px-4 py-3 text-end fw-bold <?= $s['solde'] > 0 ? 'text-danger' : 'text-success' ?>">
                                <?= number_format($s['solde'] < 0 ? 0 : $s['solde'], 2, ',', ' ') ?> Ar
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
