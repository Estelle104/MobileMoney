<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Opérateur</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <header>
        <nav>
            <a href="<?= site_url('operateur/dashboard') ?>">Dashboard</a>
            <a href="<?= site_url('operateur/configuration/list') ?>">Préfixes</a>
            <a href="<?= site_url('operateur/operation/list') ?>">Opérations</a>
            <a href="<?= site_url('operateur/clients/list') ?>">Clients</a>
            <a href="<?= site_url('operateur/gains') ?>">Gains</a>
            <a href="<?= site_url('operateur/logout') ?>">Déconnexion</a>
        </nav>
    </header>

    <main>
        <?= $this->renderSection('content') ?>
    </main>
</body>
</html>