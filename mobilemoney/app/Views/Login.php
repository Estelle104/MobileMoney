<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supermarché - Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-sm p-4" style="width: 100%; max-width: 400px;">
            
            <div class="text-center mb-4">
                <h2 class="fw-bold text-primary">Connexion</h2>
                <p class="text-muted">Gestion de Caisse </p>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger p-2 small text-center" role="alert">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
            
            <form action="<?= base_url('login') ?>" method="POST">
                
                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">Adresse Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="marie@gmail.com" value="marie@gmail.com" required>
                </div>

                <div class="mb-4">
                    <label for="mdp" class="form-label fw-bold">Mot de passe</label>
                    <input type="password" class="form-control" id="mdp" name="mdp" placeholder="••••••••" value="1234" required>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100">Se connecter</button>
            </form>

        </div>
    </div>

</body>
</html>