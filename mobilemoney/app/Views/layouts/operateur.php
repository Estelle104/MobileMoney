<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Opérateur</title>
    
    <!-- Google Fonts : Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/operateur.css') ?>">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light sticky-top">
            <div class="container">
                <a class="navbar-brand" href="<?= site_url('operateur/dashboard') ?>">
                    <i class="bi bi-wallet2 me-2"></i> MobileMoney Opérateur
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('operateur/dashboard') ?>"><i class="bi bi-house-door me-1"></i> Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('operateur/configuration/list') ?>"><i class="bi bi-gear me-1"></i> Préfixes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('operateur/operation/list') ?>"><i class="bi bi-arrow-left-right me-1"></i> Opérations</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('operateur/clients/list') ?>"><i class="bi bi-people me-1"></i> Clients</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('operateur/gains') ?>"><i class="bi bi-graph-up-arrow me-1"></i> Gains</a>
                        </li>
                        <li class="nav-item ms-lg-3">
                            <a class="btn btn-outline-danger btn-sm mt-1" href="<?= site_url('operateur/logout') ?>"><i class="bi bi-box-arrow-right me-1"></i> Déconnexion</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="container py-5">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>