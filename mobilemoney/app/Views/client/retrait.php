<h2>
    Faire un retrait
</h2>


<?php if (session()->getFlashdata('error')): ?>

    <p style="color:red">

        <?= session()->getFlashdata('error') ?>

    </p>

<?php endif; ?>


<form method="post"
    action="<?= site_url('client/retrait/valider') ?>">


    <?= csrf_field() ?>


    <label>
        Montant
    </label>


    <input
        type="number"
        name="montant"
        id="montant"
        required>


    <p>
        Frais :
        <span id="frais">
            0
        </span> Ar
    </p>


    <button>
        Valider
    </button>


</form>