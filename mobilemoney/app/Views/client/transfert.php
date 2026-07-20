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
                    <div id="statut_numero" class="small mt-1"></div>
                </div>

                <div class="mb-4">
                    <label for="montant" class="form-label text-label">Montant à envoyer</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0 bg-transparent text-secondary">Ar</span>
                        <input type="text" name="montant" id="montant" class="form-control border-start-0 ps-0" placeholder="Ex: 10000" required>
                    </div>
                </div>

                <div id="bloc_frais_retrait" class="mb-3 d-none">
                    <div class="form-check p-3 rounded-3" style="background-color: #F8FAFC; border: 1px dashed #CBD5E1;">
                        <input class="form-check-input" type="checkbox" name="inclure_frais_retrait" value="1" id="inclure_frais_retrait">
                        <label class="form-check-label small" for="inclure_frais_retrait">
                            Inclure les frais de retrait du destinataire
                        </label>
                    </div>
                </div>

                <div class="mb-4 d-flex justify-content-between align-items-center p-3 rounded-3" style="background-color: #F8FAFC; border: 1px dashed #CBD5E1;">
                    <span class="text-secondary fw-medium small">Frais estimés</span>
                    <span class="fs-5 fw-bold text-dark"><span id="frais">0</span> <span class="fs-6 fw-normal text-secondary">Ar</span></span>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 mb-3" id="btn_envoyer">
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
    const statutDiv = document.getElementById('statut_numero');
    const blocFraisRetrait = document.getElementById('bloc_frais_retrait');
    const checkboxRetrait = document.getElementById('inclure_frais_retrait');
    const btnEnvoyer = document.getElementById('btn_envoyer');

    let timeoutMontant = null;
    let timeoutNumero = null;

    let numeroValide = false;

    numeroInput.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');

        blocFraisRetrait.classList.add('d-none');
        checkboxRetrait.checked = false;
        statutDiv.textContent = '';
        numeroValide = false;

        clearTimeout(timeoutNumero);

        if (this.value.length !== 10) {
            return;
        }

        timeoutNumero = setTimeout(() => {
            fetch('<?= site_url('client/verifier-destinataire') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
                    'numero': this.value
                })
            })
            .then(response => response.json())
            .then(data => {

                if (!data.valide) {
                    if (data.erreur === 'soi-meme') {
                        statutDiv.innerHTML = '<span class="text-danger">Vous ne pouvez pas vous transférer à vous-même</span>';
                    } else {
                        statutDiv.innerHTML = '<span class="text-danger">Ce numéro n\'appartient à aucun opérateur reconnu</span>';
                    }
                    numeroValide = false;
                    return;
                }

                numeroValide = true;

                if (data.type === 'interne' && data.meme_operateur) {
                    statutDiv.innerHTML = '<span class="text-success">Compte trouvé (même opérateur)</span>';
                    blocFraisRetrait.classList.remove('d-none');
                } else if (data.type === 'interne') {
                    statutDiv.innerHTML = '<span class="text-secondary">Compte trouvé</span>';
                    blocFraisRetrait.classList.add('d-none');
                } else if (data.type === 'externe') {
                    statutDiv.innerHTML = '<span class="text-secondary">Transfert externe vers ' + data.nom_operateur + ' (commission ' + data.commission + '%)</span>';
                    blocFraisRetrait.classList.add('d-none');
                }
            })
            .catch(error => console.error('Error verifying numero:', error));

        }, 500);
    });

    montantInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');

        clearTimeout(timeoutMontant);
        const montant = this.value;

        if (montant === '' || montant == 0) {
            fraisSpan.textContent = '0';
            return;
        }

        timeoutMontant = setTimeout(() => {
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