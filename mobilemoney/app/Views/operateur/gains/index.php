<?= $this->extend('layouts/operateur') ?>

<?= $this->section('content') ?>

<h1>Situation des gains</h1>

<form action="<?= site_url('operateur/gains/filtrer') ?>" method="get">
    <label for="date_debut">Du</label>
    <input type="date" name="date_debut" id="date_debut" value="<?= esc($dateDebut ?? '') ?>">

    <label for="date_fin">Au</label>
    <input type="date" name="date_fin" id="date_fin" value="<?= esc($dateFin ?? '') ?>">

    <button type="submit">Filtrer</button>
    <a href="<?= site_url('operateur/gains') ?>">Réinitialiser</a>
</form>

<table class="table">
    <thead>
        <tr>
            <th>Type d'opération</th>
            <th>Total des frais collectés</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Retrait</td>
            <td><?= number_format($gains['retrait'], 2) ?> Ar</td>
        </tr>
        <tr>
            <td>Transfert</td>
            <td><?= number_format($gains['transfert'], 2) ?> Ar</td>
        </tr>
        <?php if ($gains['depot'] > 0): ?>
            <tr>
                <td>Dépôt</td>
                <td><?= number_format($gains['depot'], 2) ?> Ar</td>
            </tr>
        <?php endif; ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Total général</th>
            <th><?= number_format($totalGeneral, 2) ?> Ar</th>
        </tr>
    </tfoot>
</table>

<?= $this->endSection() ?>