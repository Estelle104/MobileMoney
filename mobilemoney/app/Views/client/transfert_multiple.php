<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card p-4 p-md-5">
            <h3 class="mb-1 text-center"><i class="bi bi-send-plus text-primary me-2"></i> Transfert multiple</h3>
            <p class="text-secondary text-center mb-4 small">Divisez un montant entre plusieurs numéros du même opérateur</p>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger border-0 bg-danger-subtle text-danger rounded-3">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= site_url('client/transfert-multiple/valider') ?>">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label text-label">Numéros destinataires</label>
                    <div id="numeroList" class="d-grid gap-2">
                        <input type="text" name="numeros[]" class="form-control numero-input" placeholder="0340000000" minlength="10" maxlength="10" pattern="[0-9]{10}" required>
                        <input type="text" name="numeros[]" class="form-control numero-input" placeholder="0340000001" minlength="10" maxlength="10" pattern="[0-9]{10}" required>
                    </div>
                    <button type="button" id="addNumero" class="btn btn-light border w-100 mt-2">
                        <i class="bi bi-plus-circle me-1"></i> Ajouter un numéro
                    </button>
                </div>

                <div class="mb-4">
                    <label for="montant_total" class="form-label text-label">Montant total à envoyer</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0 bg-transparent text-secondary">Ar</span>
                        <input type="text" name="montant_total" id="montant_total" class="form-control border-start-0 ps-0" placeholder="Ex: 30000" required>
                    </div>
                </div>

                <div class="mb-4 p-3 rounded-3" style="background-color: #F8FAFC; border: 1px dashed #CBD5E1;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary fw-medium small">Destinataires</span>
                        <span class="fw-bold text-dark" id="nbDestinataires">2</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary fw-medium small">Montant par numéro</span>
                        <span class="fw-bold text-dark"><span id="montantIndividuel">0</span> Ar</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary fw-medium small">Frais estimés</span>
                        <span class="fw-bold text-dark"><span id="fraisTotal">0</span> Ar</span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary fw-medium small">Total débité</span>
                        <span class="fs-5 fw-bold text-dark"><span id="totalDebite">0</span> <span class="fs-6 fw-normal text-secondary">Ar</span></span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 mb-3">
                    Envoyer à tous
                </button>

                <a href="<?= site_url('client/dashboard') ?>" class="text-decoration-none text-secondary d-block text-center small fw-medium">
                    <i class="bi bi-arrow-left me-1"></i> Annuler et retourner
                </a>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const numeroList = document.getElementById('numeroList');
    const addNumero = document.getElementById('addNumero');
    const montantInput = document.getElementById('montant_total');
    const nbDestinataires = document.getElementById('nbDestinataires');
    const montantIndividuel = document.getElementById('montantIndividuel');
    const fraisTotal = document.getElementById('fraisTotal');
    const totalDebite = document.getElementById('totalDebite');
    let timeout = null;

    function formatAmount(amount) {
        return new Intl.NumberFormat('fr-FR', { maximumFractionDigits: 2 }).format(Number(amount || 0));
    }

    function getNumeros() {
        return Array.from(document.querySelectorAll('.numero-input'))
            .map(input => input.value.trim())
            .filter(value => value !== '');
    }

    function refreshSummary() {
        const montantTotal = Number(montantInput.value || 0);
        const count = getNumeros().length;
        const individuel = count > 0 ? montantTotal / count : 0;

        nbDestinataires.textContent = count;
        montantIndividuel.textContent = formatAmount(individuel);
        totalDebite.textContent = formatAmount(montantTotal);
        fraisTotal.textContent = '0';

        clearTimeout(timeout);

        if (montantTotal <= 0 || count < 2 || individuel <= 0) {
            return;
        }

        timeout = setTimeout(() => {
            fetch('<?= site_url('client/calcul-frais') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
                    'montant': individuel,
                    'type_operation': 'transfert'
                })
            })
            .then(response => response.json())
            .then(data => {
                const frais = Number(data.frais || 0) * count;
                fraisTotal.textContent = formatAmount(frais);
                totalDebite.textContent = formatAmount(montantTotal + frais);
            })
            .catch(error => console.error('Error fetching frais:', error));
        }, 500);
    }

    addNumero.addEventListener('click', function() {
        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'numeros[]';
        input.className = 'form-control numero-input';
        input.placeholder = '0340000000';
        input.minLength = 10;
        input.maxLength = 10;
        input.pattern = '[0-9]{10}';
        input.required = true;
        numeroList.appendChild(input);
        input.focus();
        input.addEventListener('input', sanitizeNumero);
        refreshSummary();
    });

    function sanitizeNumero() {
        this.value = this.value.replace(/\D/g, '');
        refreshSummary();
    }

    document.querySelectorAll('.numero-input').forEach(input => {
        input.addEventListener('input', sanitizeNumero);
    });

    montantInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        refreshSummary();
    });

    refreshSummary();
});
</script>
<?= $this->endSection() ?>
