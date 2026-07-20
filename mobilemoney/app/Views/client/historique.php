<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="p-4 p-md-5 border-bottom bg-white d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div>
                    <h3 class="mb-1"><i class="bi bi-clock-history text-primary me-2"></i> Historique</h3>
                    <p class="text-secondary mb-0 small">Vos dernières opérations</p>
                </div>
                <a href="<?= site_url('client/dashboard') ?>" class="btn btn-light btn-sm rounded-pill px-3 fw-medium border shadow-sm text-dark">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col" class="text-label ps-4 py-3">Date</th>
                            <th scope="col" class="text-label py-3">Opération</th>
                            <th scope="col" class="text-label text-end py-3">Montant</th>
                            <th scope="col" class="text-label text-end py-3">Frais</th>
                            <th scope="col" class="text-label pe-4 py-3">Détails</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historique as $operation): ?>
                            <tr class="border-bottom">
                                <td class="text-nowrap text-secondary small ps-4 py-3">
                                    <i class="bi bi-calendar3 me-2"></i><?= esc($operation['date_transaction']) ?>
                                </td>
                                <td class="py-3">
                                    <?php 
                                        $type = strtolower($operation['type_operation']);
                                        $badgeClass = 'bg-secondary';
                                        $icon = '';
                                        if ($type === 'depot') {
                                            $badgeClass = 'bg-success bg-opacity-10 text-success';
                                            $icon = '<i class="bi bi-arrow-down-circle-fill me-1"></i>';
                                        } elseif ($type === 'retrait') {
                                            $badgeClass = 'bg-danger bg-opacity-10 text-danger';
                                            $icon = '<i class="bi bi-arrow-up-circle-fill me-1"></i>';
                                        } elseif ($type === 'transfert') {
                                            $badgeClass = 'bg-primary bg-opacity-10 text-primary';
                                            $icon = '<i class="bi bi-send-fill me-1"></i>';
                                        }
                                    ?>
                                    <span class="badge <?= $badgeClass ?> px-3 py-2 rounded-pill fw-medium">
                                        <?= $icon ?> <?= esc(ucfirst($operation['type_operation'])) ?>
                                    </span>
                                </td>
                                <td class="text-end fw-bold text-dark py-3">
                                    <?= number_format($operation['montant'], 2, ',', ' ') ?> Ar
                                </td>
                                <td class="text-end text-secondary small py-3">
                                    <?= number_format($operation['frais'], 2, ',', ' ') ?> Ar
                                </td>
                                <td class="pe-4 py-3">
                                    <?php if ($operation['type_operation'] == "transfert"): ?>
                                        <?php if (!empty($operation['is_transfert_multiple'])): ?>
                                            <div class="d-inline-flex align-items-center bg-light rounded-pill px-3 py-1">
                                                <i class="bi bi-people text-primary me-2"></i>
                                                <span class="small text-secondary">
                                                    Transfert multiple vers <strong class="text-dark"><?= esc($operation['nb_destinataires']) ?></strong> destinataires
                                                </span>
                                            </div>
                                        <?php elseif ($operation['numero_source'] == session('numero')): ?>
                                            <div class="d-inline-flex align-items-center bg-light rounded-pill px-3 py-1">
                                                <i class="bi bi-arrow-right text-danger me-2"></i>
                                                <span class="small text-secondary">Vers <strong class="text-dark"><?= esc($operation['numero_destinataire'] ?? $operation['numero_destinataire_externe']) ?></strong></span>
                                            </div>
                                        <?php else: ?>
                                            <div class="d-inline-flex align-items-center bg-light rounded-pill px-3 py-1">
                                                <i class="bi bi-arrow-left text-success me-2"></i>
                                                <span class="small text-secondary">De <strong class="text-dark"><?= esc($operation['numero_source']) ?></strong></span>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        
                        <?php if(empty($historique)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                        <i class="bi bi-inbox fs-1"></i>
                                    </div>
                                    <p class="mb-0">Aucune opération trouvée dans l'historique.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
