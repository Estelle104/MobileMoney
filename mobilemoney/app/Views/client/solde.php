<h2>Mon solde</h2>


<p>
    Numéro :
    <?= esc($client['numero']) ?>
</p>


<h3>
    Solde disponible :
    <?= number_format($client['solde'], 2, ',', ' ') ?>
    Ar
</h3>


<a href="<?= site_url('client/dashboard') ?>">
    Retour
</a>