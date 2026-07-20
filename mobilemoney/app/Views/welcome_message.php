<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Money - Bienvenue</title>

    <!-- Google Fonts : Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --bs-body-bg: #F4F6F8;
            --bs-body-color: #1E293B;
            --bs-primary: #0F172A;
            --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.02), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
            --font-main: 'Inter', sans-serif;
        }

        body {
            font-family: var(--font-main);
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .card {
            border: 1px solid rgba(0,0,0,0.03);
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            background-color: #ffffff;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important;
        }

        .icon-box {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        
        h1 {
            font-weight: 700;
            letter-spacing: -1px;
            color: var(--bs-primary);
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center text-center mb-5">
            <div class="col-lg-8">
                <div class="bg-dark text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 72px; height: 72px;">
                    <i class="bi bi-wallet2 fs-1"></i>
                </div>
                <h1 class="display-5 mb-3">Bienvenue sur MobileMoney</h1>
                <p class="lead text-secondary">Veuillez choisir votre espace de connexion pour continuer.</p>
            </div>
        </div>

        <div class="row justify-content-center g-4">
            <div class="col-md-5 col-lg-4">
                <a href="<?= site_url('client/login') ?>" class="text-decoration-none">
                    <div class="card p-5 text-center h-100 card-hover">
                        <div>
                            <div class="icon-box bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-person-circle fs-1"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold text-dark mb-2">Espace Client</h3>
                        <p class="text-secondary small mb-0">Consultez votre solde, effectuez des transferts et gérez votre argent en toute sécurité.</p>
                    </div>
                </a>
            </div>

            <div class="col-md-5 col-lg-4">
                <a href="<?= site_url('operateur/login') ?>" class="text-decoration-none">
                    <div class="card p-5 text-center h-100 card-hover">
                        <div>
                            <div class="icon-box bg-danger bg-opacity-10 text-danger">
                                <i class="bi bi-shield-lock fs-1"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold text-dark mb-2">Espace Opérateur</h3>
                        <p class="text-secondary small mb-0">Gérez les préfixes, configurez les barèmes et supervisez la plateforme.</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
