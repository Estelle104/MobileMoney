<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Money</title>

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
            --bs-primary-hover: #334155;
            --bs-success: #10B981;
            --bs-danger: #EF4444;
            --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.02), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
            --font-main: 'Inter', sans-serif;
        }

        body {
            font-family: var(--font-main);
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            -webkit-font-smoothing: antialiased;
        }

        .navbar {
            background-color: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0,0,0,0.03);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: -0.5px;
            color: var(--bs-primary) !important;
        }

        .card {
            border: 1px solid rgba(0,0,0,0.03);
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            background-color: #ffffff;
        }

        .form-control, .input-group-text {
            border-radius: 10px;
            border: 1px solid #E2E8F0;
            padding: 0.75rem 1rem;
            background-color: #F8FAFC;
            font-weight: 500;
        }

        .form-control:focus {
            background-color: #FFFFFF;
            border-color: #CBD5E1;
            box-shadow: 0 0 0 4px rgba(203, 213, 225, 0.2);
        }

        .btn {
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background-color: var(--bs-primary);
            border: none;
        }
        
        .btn-primary:hover {
            background-color: var(--bs-primary-hover);
            transform: translateY(-1px);
        }

        .text-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        h2, h3, h4 {
            font-weight: 700;
            letter-spacing: -0.5px;
            color: #0F172A;
        }
        
        .nav-pill-custom {
            background: #F1F5F9;
            border-radius: 20px;
            padding: 0.4rem 1.2rem;
            font-weight: 500;
            font-size: 0.85rem;
            color: #334155;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <?php if (session()->has('numero')): ?>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid px-4 px-md-5">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?= site_url('client/dashboard') ?>">
                <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    <i class="bi bi-wallet2 small"></i>
                </div>
                MobileMoney
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-3 mt-3 mt-lg-0">
                    <li class="nav-item">
                        <span class="nav-pill-custom d-inline-flex align-items-center gap-2">
                            <i class="bi bi-person-fill text-secondary"></i> <?= esc(session('numero')) ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-light btn-sm rounded-pill px-4 fw-medium border shadow-sm" href="<?= site_url('client/logout') ?>">
                            Déconnexion
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <div class="container-fluid px-4 px-md-5 py-5">
        <?= $this->renderSection('content') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
