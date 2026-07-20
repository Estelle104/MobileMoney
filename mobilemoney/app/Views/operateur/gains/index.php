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

<div class="card p-0 border-0 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover table-borderless align-middle mb-0">
            <thead class="bg-light text-secondary small fw-semibold">
                <tr>
                    <th class="px-4 py-3">Type d'opération</th>
                    <th class="px-4 py-3 text-end">Total des frais collectés</th>
                </tr>
            </thead>
            <tbody class="border-top">
                <tr>
                    <td class="px-4 py-3 fw-medium"><span class="badge bg-danger-subtle text-danger p-2 me-2"><i class="bi bi-arrow-up-right"></i></span>Retrait</td>
                    <td class="px-4 py-3 text-end amount text-primary"><?= number_format($gains['retrait'], 2, ',', ' ') ?> Ar</td>
                </tr>
                <tr>
                    <td class="px-4 py-3 fw-medium"><span class="badge bg-info-subtle text-info p-2 me-2"><i class="bi bi-arrow-left-right"></i></span>Transfert</td>
                    <td class="px-4 py-3 text-end amount text-primary"><?= number_format($gains['transfert'], 2, ',', ' ') ?> Ar</td>
                </tr>
                <?php if ($gains['depot'] > 0): ?>
                    <tr>
                        <td class="px-4 py-3 fw-medium"><span class="badge bg-success-subtle text-success p-2 me-2"><i class="bi bi-arrow-down-left"></i></span>Dépôt</td>
                        <td class="px-4 py-3 text-end amount text-primary"><?= number_format($gains['depot'], 2, ',', ' ') ?> Ar</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot class="bg-light border-top border-2">
                <tr>
                    <th class="px-4 py-3 fs-5">Total général</th>
                    <th class="px-4 py-3 text-end amount text-success fs-5"><?= number_format($totalGeneral, 2, ',', ' ') ?> Ar</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?= $this->endSection() ?>