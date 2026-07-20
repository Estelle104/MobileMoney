<h2>
    Faire un transfert
</h2>


<?php if (session()->getFlashdata('error')): ?>

    <p style="color:red">
        <?= session()->getFlashdata('error') ?>
    </p>

<?php endif; ?>


<form method="post"
    action="<?= site_url('client/transfert/valider') ?>">


    <?= csrf_field() ?>


    <label>
        Numéro destinataire
    </label>

    <input
        type="text"
        name="numero_destinataire"
        required>


    <br>


    <label>
        Montant
    </label>

    <input
        type="number"
        name="montant"
        required>


    <p>
        Frais :
        <span id="frais">
            0
        </span>
        Ar
    </p>


    <button>
        Envoyer
    </button>


</form>