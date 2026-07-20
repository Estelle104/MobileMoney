<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des configurations</title>
</head>
<body>
    <div class="content">
        <h1>Liste des configurations</h1>
        <a href="<?= site_url('operateur/configuration/creer') ?>">Créer une nouvelle configuration</a>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Code</th>
                    <th>Opérateur</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($prefixes as $config) : ?>
                    <tr>
                        <td><?= esc($config['id']) ?></td>
                        <td><?= esc($config['code']) ?></td>
                        <td><?= esc($config['id_operateur']) ?></td>
                        <td>
                            <a href="<?= site_url('operateur/configuration/modifier/' . $config['id']) ?>">Modifier</a> |
                            <form action="<?= site_url('operateur/configuration/supprimer/' . $config['id']) ?>" method="post" style="display:inline;">
                                <?= csrf_field() ?>
                                <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette configuration ?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>