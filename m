<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Money</title>

    <!-- 1. Google Fonts : Inter (En ligne) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- 2. Bootstrap 5 CSS (En ligne via CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- 3. Bootstrap Icons (En ligne via CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- 4. Personnalisation de la Palette Fintech pour Bootstrap 5 -->
    <style>
        :root {
            /* Surcharge des couleurs système Bootstrap */
            --bs-body-bg: #F8FAFC;         /* Fond principal (Off-white) */
            --bs-body-color: #0F172A;      /* Texte principal (Bleu nuit) */
            --bs-primary: #2563EB;         /* Bleu Banque */
            --bs-primary-hover: #1D4ED8;
            --bs-success: #10B981;         /* Vert Émeraude */
            --bs-danger: #EF4444;          /* Rouge Écarlate */
            --bs-border-color: #E2E8F0;    /* Bordures subtiles */
            --bs-font-sans-serif: 'Inter', -apple-system, blinkmacsystemfont, sans-serif;
        }

        body {
            font-family: var(--bs-font-sans-serif);
        }

        /* Personnalisation des cartes Bootstrap */
        .card {
            border: 1px solid var(--bs-border-color);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        /* Alignement parfait des montants financiers */
        .amount {
            font-variant-numeric: tabular-nums;
            font-weight: 700;
        }

        /* Boutons personnalisés avec la palette */
        .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
            padding: 10px 20px;
            font-weight: 600;
            border-radius: 8px;
        }

        .btn-primary:hover {
            background-color: var(--bs-primary-hover);
            border-color: var(--bs-primary-hover);
        }
    </style>
</head>
<body>

    <!-- Contenu de la page -->
    <div class="container py-5">
        
        <!-- Exemple : Carte de Solde & Formulaire avec classes Bootstrap 5 -->
        <div class="row justify-content-center">
            <div class="col-md-6">
                
                <!-- Carte Solde -->
                <div class="card p-4 mb-4">
                    <span class="text-secondary small fw-medium">Solde actuel</span>
                    <h2 class="amount text-primary my-2">50 000,00 Ar</h2>
                    <span class="badge bg-success-subtle text-success w-auto align-self-start px-2 py-1">
                        <i class="bi bi-check-circle-fill me-1"></i> Compte Actif
                    </span>
                </div>

                <!-- Carte Formulaire -->
                <div class="card p-4">
                    <h4 class="mb-3">Effectuer un dépôt</h4>
                    
                    <form action="#" method="post">
                        <div class="mb-3">
                            <label for="montant" class="form-label text-secondary small fw-semibold">Montant du dépôt (Ar)</label>
                            <input type="number" id="montant" name="montant" class="form-control form-control-lg" placeholder="Ex: 25000" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-arrow-down-circle me-2"></i> Valider le dépôt
                        </button>
                    </form>
                </div>

            </div>
        </div>

    </div>

    <!-- Bootstrap 5 JS Bundle (En ligne via CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>