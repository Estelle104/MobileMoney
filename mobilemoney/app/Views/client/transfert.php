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

                <div class="mb-4">
                    <label for="montant" class="form-label text-label">Montant à envoyer</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0 bg-transparent text-secondary">Ar</span>
                        <input type="text" name="montant" id="montant" class="form-control border-start-0 ps-0" placeholder="Ex: 10000" required>
                    </div>
                </div>

                <div class="mb-4 d-flex justify-content-between align-items-center p-3 rounded-3" style="background-color: #F8FAFC; border: 1px dashed #CBD5E1;">
                    <span class="text-secondary fw-medium small">Frais estimés</span>
                    <span class="fs-5 fw-bold text-dark"><span id="frais">0</span> <span class="fs-6 fw-normal text-secondary">Ar</span></span>
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
    const fraisSpan = document.getElementById('frais');
    let timeout = null;

    numeroInput.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');
    });

    montantInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        
        clearTimeout(timeout);
        const montant = this.value;
        
        if (montant === '' || montant == 0) {
            fraisSpan.textContent = '0';
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
                    'type_operation': 'transfert'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.frais !== undefined) {
                    fraisSpan.textContent = new Intl.NumberFormat('fr-FR').format(data.frais);
                }
            })
            .catch(error => console.error('Error fetching frais:', error));
        }, 500);
    });
});
</script>
<?= $this->endSection() ?>