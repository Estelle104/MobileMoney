<?= $this->extend('layouts/operateur') ?>

<?= $this->section('content') ?>

<h1>Tableau de bord</h1>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>

<!-- Cartes de statistiques rapides -->
<div class="dashboard-cards">
    <div class="card">
        <h3>Préfixes configurés</h3>
        <p class="card-value"><?= $nbPrefixes ?></p>
        <a href="<?= site_url('operateur/configuration/list') ?>">Gérer les préfixes</a>
    </div>

    <div class="card">
        <h3>Clients enregistrés</h3>
        <p class="card-value"><?= $nbClients ?></p>
        <a href="<?= site_url('operateur/clients/list') ?>">Voir les clients</a>
    </div>

    <div class="card">
        <h3>Solde total des clients</h3>
        <p class="card-value"><?= number_format($soldeTotal, 2) ?> Ar</p>
    </div>

    <div class="card">
        <h3>Gains du jour</h3>
        <p class="card-value"><?= number_format($gainsAujourdhui, 2) ?> Ar</p>
        <a href="<?= site_url('operateur/gains') ?>">Voir tous les gains</a>
    </div>
</div>

<!-- Accès rapide aux barèmes de frais par type d'opération -->
<h2>Barèmes de frais</h2>
<div class="dashboard-links">
    <?php foreach ($types as $type): ?>
        <a href="<?= site_url('operateur/operation/list/' . $type['id']) ?>" class="btn">
            <?= esc(ucfirst($type['libelle'])) ?>
        </a>
    <?php endforeach; ?>
</div>

<!-- Menu de navigation générale -->
<h2>Actions rapides</h2>
<ul class="dashboard-menu">
    <li><a href="<?= site_url('operateur/configuration/creer') ?>">+ Ajouter un préfixe</a></li>
    <li><a href="<?= site_url('operateur/operation/ajouter') ?>">+ Ajouter une tranche de frais</a></li>
    <li><a href="<?= site_url('operateur/clients/list') ?>">Consulter les comptes clients</a></li>
    <li><a href="<?= site_url('operateur/gains/filtrer') ?>">Filtrer les gains par date</a></li>
</ul>

<?= $this->endSection() ?>