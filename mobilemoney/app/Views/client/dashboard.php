<h2>

    Bienvenue

    <?= session('numero') ?>

</h2>

<ul>

    <li>

        <a href="<?= site_url('client/solde') ?>">

            Voir le solde

        </a>

    </li>

    <li>

        <a href="<?= site_url('client/depot') ?>">

            Faire un dépôt

        </a>

    </li>

    <li>

        <a href="<?= site_url('client/retrait') ?>">

            Faire un retrait

        </a>

    </li>

    <li>

        <a href="<?= site_url('client/transfert') ?>">

            Faire un transfert

        </a>

    </li>

    <li>

        <a href="<?= site_url('client/historique') ?>">

            Historique

        </a>

    </li>

    <li>

        <a href="<?= site_url('client/logout') ?>">

            Déconnexion

        </a>

    </li>

</ul>