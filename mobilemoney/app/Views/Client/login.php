<!DOCTYPE html>

<html>

<head>

    <title>Connexion Client</title>

</head>

<body>

    <h2>Connexion</h2>

    <?php if (session()->getFlashdata('error')) : ?>

        <p style="color:red">

            <?= session()->getFlashdata('error') ?>

        </p>

    <?php endif; ?>

    <form action="<?= site_url('client/checklogin') ?>" method="post">

        <?= csrf_field() ?>

        <label>

            Numéro téléphone

        </label>

        <br>

        <input
            type="text"
            name="numero"
            maxlength="10"
            value="<?= old('numero') ?>"
            required>

        <br><br>

        <button>

            Connexion

        </button>

    </form>

    <script>
        const numero = document.querySelector('[name=numero]');

        numero.addEventListener('input', () => {

            numero.value = numero.value.replace(/\D/g, '');

        });
    </script>

</body>

</html>