<?= $this->extend('layouts/operateur') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 fw-bold">Barème de frais — <span class="text-primary"><?= esc($typeOperation['libelle']) ?></span></h1>
    <a href="<?= site_url('operateur/operation/ajouter') ?>" class="btn btn-primary shadow-sm">
        + Ajouter une tranche
    </a>
</div>

<ul class="nav nav-pills mb-4">
    <?php foreach ($typesOperations as $type): ?>
        <li class="nav-item me-2">
            <a class="nav-link <?= ($type['id'] == $typeOperation['id']) ? 'active rounded-pill' : 'text-secondary rounded-pill bg-white border border-light' ?>" 
               href="<?= site_url('operateur/operation/list/' . $type['id']) ?>">
                <?= esc(ucfirst($type['libelle'])) ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= esc(session()->getFlashdata('success')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= esc(session()->getFlashdata('error')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card p-0 border-0 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover table-borderless align-middle mb-0">
            <thead class="bg-light text-secondary small fw-semibold">
                <tr>
                    <th class="px-4 py-3">Montant min</th>
                    <th class="px-4 py-3">Montant max</th>
                    <th class="px-4 py-3">Frais</th>
                    <th class="px-4 py-3 text-end">Actions</th>
                </tr>
            </thead>
            <tbody class="border-top">
                <?php if (empty($tranches)): ?>
                    <tr>
                        <td colspan="4" class="px-4 py-4 text-center text-secondary">Aucune tranche configurée pour ce type d'opération.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($tranches as $tranche): ?>
                        <tr>
                            <td class="px-4 py-3 amount"><?= number_format($tranche['montant_min'], 2, ',', ' ') ?> Ar</td>
                            <td class="px-4 py-3 amount"><?= number_format($tranche['montant_max'], 2, ',', ' ') ?> Ar</td>
                            <td class="px-4 py-3 amount text-primary fw-bold">
                                <?php if ($tranche['frais'] < 1): ?>
                                    <?= ($tranche['frais'] * 100) ?> %
                                <?php else: ?>
                                    <?= number_format($tranche['frais'], 2, ',', ' ') ?> Ar
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-end">
                                <a href="<?= site_url('operateur/operation/modifier/' . $tranche['id']) ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 me-2">
                                    <i class="bi bi-pencil me-1"></i> Modifier
                                </a>

                                <form action="<?= site_url('operateur/operation/supprimer/' . $tranche['id']) ?>"
                                      method="post"
                                      class="d-inline"
                                      onsubmit="return confirm('Supprimer cette tranche ?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                        <i class="bi bi-trash me-1"></i> Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>