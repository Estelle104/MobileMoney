<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Opérateur</title>
    
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
<body class="d-flex align-items-center bg-body-tertiary" style="min-height: 100vh;">
    <main class="w-100 m-auto" style="max-width: 400px;">
        <div class="card p-4">
            <div class="text-center mb-4">
                <i class="bi bi-wallet2 text-primary display-4"></i>
                <h1 class="h3 mb-3 fw-bold mt-2">Connexion Opérateur</h1>
            </div>
            
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= esc(session()->getFlashdata('error')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= esc(session()->getFlashdata('success')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if (isset($error)) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= esc($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <form action="<?= site_url('operateur/checklogin') ?>" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label text-secondary small fw-semibold">Adresse Email</label>
                    <input type="text" id="email" name="email" value="<?= old('email') ?>" class="form-control form-control-lg" placeholder="Ex: admin@mobilemoney.mg" required>
                </div>
                
                <div class="mb-4">
                    <label for="mdp" class="form-label text-secondary small fw-semibold">Mot de passe</label>
                    <input type="password" id="mdp" name="mdp" class="form-control form-control-lg" placeholder="Votre mot de passe" required>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 btn-lg">
                    Se connecter
                </button>
            </form>
        </div>
    </main>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
