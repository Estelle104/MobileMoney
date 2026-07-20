<h2>
    Historique des opérations
</h2>



<table border="1" cellpadding="8">

    <tr>

        <th>
            Date
        </th>

        <th>
            Type
        </th>

        <th>
            Montant
        </th>

        <th>
            Frais
        </th>

        <th>
            Sens
        </th>

    </tr>



    <?php foreach ($historique as $operation): ?>


        <tr>


            <td>
                <?= esc($operation['date_transaction']) ?>
            </td>



            <td>
                <?= esc(ucfirst($operation['type_operation'])) ?>
            </td>



            <td>
                <?= number_format(
                    $operation['montant'],
                    2,
                    ',',
                    ' '
                ) ?>
                Ar
            </td>



            <td>
                <?= number_format(
                    $operation['frais'],
                    2,
                    ',',
                    ' '
                ) ?>
                Ar
            </td>



            <td>


                <?php if ($operation['type_operation'] == "transfert"): ?>


                    <?php if ($operation['numero_source'] == session('numero')): ?>

                        Envoyé

                        vers

                        <?= esc($operation['numero_destinataire']) ?>


                    <?php else: ?>


                        Reçu

                        de

                        <?= esc($operation['numero_source']) ?>


                    <?php endif; ?>



                <?php else: ?>


                    -

                <?php endif; ?>


            </td>


        </tr>


    <?php endforeach; ?>


</table>