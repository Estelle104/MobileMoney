<?= $this->extend('layouts/operateur') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 fw-bold">Situation des gains</h1>
</div>

<div class="card p-4 border-0 shadow-sm mb-4">
    <form action="<?= site_url('operateur/gains/filtrer') ?>" method="get" class="row g-3 align-items-end">
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
            <a href="<?= site_url('operateur/gains') ?>" class="btn btn-outline-secondary">Réinitialiser</a>
        </div>
    </form>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card p-0 border-0 shadow-sm overflow-hidden h-100">
            <div class="card-header bg-light border-0 py-3">
                <h5 class="mb-0 fs-6 fw-bold text-secondary">Opérations internes</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-borderless align-middle mb-0">
                    <thead class="bg-light text-secondary small fw-semibold">
                        <tr>
                            <th class="px-4 py-3">Type d'opération</th>
                            <th class="px-4 py-3 text-end">Frais collectés</th>
                        </tr>
                    </thead>
                    <tbody class="border-top">
                        <tr>
                            <td class="px-4 py-3 fw-medium"><span class="badge bg-danger-subtle text-danger p-2 me-2"><i class="bi bi-arrow-up-right"></i></span>Retrait</td>
                            <td class="px-4 py-3 text-end amount text-primary"><?= number_format($gainsInterne['retrait'], 2, ',', ' ') ?> Ar</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 fw-medium"><span class="badge bg-info-subtle text-info p-2 me-2"><i class="bi bi-arrow-left-right"></i></span>Transfert</td>
                            <td class="px-4 py-3 text-end amount text-primary"><?= number_format($gainsInterne['transfert'], 2, ',', ' ') ?> Ar</td>
                        </tr>
                        <?php if ($gainsInterne['depot'] > 0): ?>
                            <tr>
                                <td class="px-4 py-3 fw-medium"><span class="badge bg-success-subtle text-success p-2 me-2"><i class="bi bi-arrow-down-left"></i></span>Dépôt</td>
                                <td class="px-4 py-3 text-end amount text-primary"><?= number_format($gainsInterne['depot'], 2, ',', ' ') ?> Ar</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot class="bg-light border-top border-2">
                        <tr>
                            <th class="px-4 py-3 fs-6 text-secondary">Sous-total</th>
                            <th class="px-4 py-3 text-end amount text-dark fw-bold fs-6"><?= number_format(array_sum($gainsInterne), 2, ',', ' ') ?> Ar</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card p-0 border-0 shadow-sm overflow-hidden h-100">
            <div class="card-header bg-light border-0 py-3">
                <h5 class="mb-0 fs-6 fw-bold text-secondary">Transferts vers autres opérateurs</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-borderless align-middle mb-0">
                    <thead class="bg-light text-secondary small fw-semibold">
                        <tr>
                            <th class="px-4 py-3">Type d'opération</th>
                            <th class="px-4 py-3 text-end">Frais collectés</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($autresOperateurs as $gain): ?>
                            <tr>
                                <td><?= esc($gain['operateur_externe']) ?></td>
                                <td><?= esc($gain['libelle']) ?></td>
                                <td class="text-end">
                                    <?= number_format($gain['total_frais'], 2, ',', ' ') ?> Ar
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <?php  $sousTotalExterne = array_sum(array_column($autresOperateurs, 'total_frais')); ?>

                    <tfoot class="bg-light border-top border-2">
                        <tr>
                            <th colspan="2" class="px-4 py-3 fs-6 text-secondary">
                                Sous-total
                            </th>
                            <th class="px-4 py-3 text-end fw-bold fs-6">
                                <?= number_format($sousTotalExterne, 2, ',', ' ') ?> Ar
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card p-4 border-0 shadow-sm mt-4 text-white" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
    <div class="d-flex justify-content-between align-items-center">
        <h4 class="mb-0 fw-semibold"><i class="bi bi-wallet2 me-2 opacity-75"></i>Total général des gains</h4>
        <h3 class="mb-0 fw-bold"><?= number_format($totalGeneral, 2, ',', ' ') ?> Ar</h3>
    </div>
</div>

<?= $this->endSection() ?>