<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card p-4 p-md-5">
            <h3 class="mb-1 text-center"><i class="bi bi-send text-primary me-2"></i> Transfert d'argent</h3>
            <p class="text-secondary text-center mb-4 small">Envoyez de l'argent vers un autre compte</p>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger border-0 bg-danger-subtle text-danger rounded-3">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= site_url('client/transfert/valider') ?>">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="numero_destinataire" class="form-label text-label">Numéro du destinataire</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0 bg-transparent text-secondary"><i class="bi bi-person"></i></span>
                        <input type="text" name="numero_destinataire" id="numero_destinataire" class="form-control border-start-0 ps-0" placeholder="0340000000" minlength="10" maxlength="10" pattern="[0-9]{10}" title="Le numéro doit contenir exactement 10 chiffres" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="montant" class="form-label text-label">Montant à envoyer</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0 bg-transparent text-secondary">Ar</span>
                        <input type="text" name="montant" id="montant" class="form-control border-start-0 ps-0" placeholder="Ex: 10000" required>
                    </div>
                </div>

                <div id="operatorInfo" class="alert alert-secondary border-0 py-2 px-3 small mb-3 d-none"></div>

                <div id="retraitOption" class="form-check form-switch mb-3 d-none">
                    <input class="form-check-input" type="checkbox" role="switch" name="frais_retrait_inclus" id="frais_retrait_inclus" value="1">
                    <label class="form-check-label fw-medium" for="frais_retrait_inclus">
                        Inclure les frais de retrait
                    </label>
                </div>

                <div class="mb-4 p-3 rounded-3" style="background-color: #F8FAFC; border: 1px dashed #CBD5E1;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary fw-medium small">Frais de transfert</span>
                        <span class="fw-bold text-dark"><span id="fraisTransfert">0</span> Ar</span>
                    </div>
                    <div id="commissionRow" class="d-flex justify-content-between align-items-center mb-2 d-none">
                        <span class="text-secondary fw-medium small">Commission autre opérateur</span>
                        <span class="fw-bold text-dark"><span id="commission">0</span> Ar</span>
                    </div>
                    <div id="fraisRetraitRow" class="d-flex justify-content-between align-items-center mb-2 d-none">
                        <span class="text-secondary fw-medium small">Frais de retrait inclus</span>
                        <span class="fw-bold text-dark"><span id="fraisRetrait">0</span> Ar</span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary fw-medium small">Total débité</span>
                        <span class="fs-5 fw-bold text-dark"><span id="totalDebite">0</span> <span class="fs-6 fw-normal text-secondary">Ar</span></span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 mb-3">
                    Envoyer l'argent
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
    const numeroInput = document.getElementById('numero_destinataire');
    const montantInput = document.getElementById('montant');
    const fraisTransfertSpan = document.getElementById('fraisTransfert');
    const fraisRetraitSpan = document.getElementById('fraisRetrait');
    const commissionSpan = document.getElementById('commission');
    const totalDebiteSpan = document.getElementById('totalDebite');
    const retraitOption = document.getElementById('retraitOption');
    const retraitCheckbox = document.getElementById('frais_retrait_inclus');
    const operatorInfo = document.getElementById('operatorInfo');
    const commissionRow = document.getElementById('commissionRow');
    const fraisRetraitRow = document.getElementById('fraisRetraitRow');
    let timeout = null;

    function formatAmount(amount) {
        return new Intl.NumberFormat('fr-FR').format(Number(amount || 0));
    }

    function calculateFrais() {
        const montant = montantInput.value;
        const numero = numeroInput.value;
        
        clearTimeout(timeout);
        
        if (montant === '' || montant == 0) {
            fraisTransfertSpan.textContent = '0';
            fraisRetraitSpan.textContent = '0';
            commissionSpan.textContent = '0';
            totalDebiteSpan.textContent = '0';
            operatorInfo.classList.add('d-none');
            retraitOption.classList.add('d-none');
            commissionRow.classList.add('d-none');
            fraisRetraitRow.classList.add('d-none');
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
                    'montant': montant,
                    'numero_destinataire': numero,
                    'type_operation': 'transfert',
                    'frais_retrait_inclus': retraitCheckbox.checked ? '1' : ''
                })
            })
            .then(response => response.json())
            .then(data => {
                fraisTransfertSpan.textContent = formatAmount(data.frais_transfert ?? data.frais);
                fraisRetraitSpan.textContent = formatAmount(data.frais_retrait);
                commissionSpan.textContent = formatAmount(data.commission);
                totalDebiteSpan.textContent = formatAmount(Number(montant || 0) + Number(data.total || 0));

                const hasNumero = numero.length >= 3;
                operatorInfo.classList.toggle('d-none', !hasNumero);
                commissionRow.classList.toggle('d-none', !data.is_externe);
                fraisRetraitRow.classList.toggle('d-none', !data.frais_retrait);
                retraitOption.classList.toggle('d-none', !data.is_meme_operateur);

                if (!data.is_meme_operateur) {
                    retraitCheckbox.checked = false;
                }

                if (hasNumero) {
                    operatorInfo.className = 'alert border-0 py-2 px-3 small mb-3 ' + (data.is_meme_operateur ? 'alert-success' : 'alert-warning');
                    operatorInfo.textContent = data.is_meme_operateur
                        ? 'Même opérateur : vous pouvez inclure les frais de retrait.'
                        : 'Autre opérateur : les frais de retrait ne sont pas inclus.';
                }
            })
            .catch(error => console.error('Error fetching frais:', error));
        }, 500);
    }

    numeroInput.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');
        calculateFrais();
    });

    montantInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        calculateFrais();
    });

    retraitCheckbox.addEventListener('change', calculateFrais);
});
</script>
<?= $this->endSection() ?>
