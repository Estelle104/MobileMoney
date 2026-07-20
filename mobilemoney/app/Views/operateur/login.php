<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <div class="content-login">
        <h1>Login</h1>
        <?php if (session()->getFlashdata('error')) : ?>
            <div class="error"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>
        <?php if (isset($error)) : ?>
            <div class="error"><?= esc($error) ?></div>
        <?php endif; ?>
        <form action="<?= site_url('operateur/checklogin') ?>" method="post">
            <input type="text" name="email" value="<?= old('email') ?>" placeholder="Entrer votre Email" required><br>
            <input type="password" name="mdp" placeholder="Entrer votre Mot de passe" required><br>
            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>
