<?= $this->extend('layouts/operateur') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 fw-bold">Configuration des préfixes</h1>
    <a href="<?= site_url('operateur/configuration/creer') ?>" class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-circle me-1"></i> Ajouter un préfixe
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i><?= esc(session()->getFlashdata('success')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i><?= esc(session()->getFlashdata('error')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card p-0 border-0 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover table-borderless align-middle mb-0">
            <thead class="bg-light text-secondary small fw-semibold">
                <tr>
                    <th class="px-4 py-3">Code Préfixe</th>
                    <th class="px-4 py-3 text-end">Actions</th>
                </tr>
            </thead>
            <tbody class="border-top">
                <?php if (empty($prefixes)): ?>
                    <tr>
                        <td colspan="2" class="px-4 py-4 text-center text-secondary">Aucun préfixe configuré.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($prefixes as $prefixe): ?>
                        <tr>
                            <td class="px-4 py-3 fw-medium text-primary"><span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2"><?= esc($prefixe['code']) ?></span></td>
                            <td class="px-4 py-3 text-end">
                                <a href="<?= site_url('operateur/configuration/modifier/' . $prefixe['id']) ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 me-2">
                                    <i class="bi bi-pencil me-1"></i> Modifier
                                </a>

                                <form action="<?= site_url('operateur/configuration/supprimer/' . $prefixe['id']) ?>"
                                    method="post"
                                    class="d-inline"
                                    onsubmit="return confirm('Confirmer la suppression du préfixe <?= esc($prefixe['code']) ?> ?');">
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