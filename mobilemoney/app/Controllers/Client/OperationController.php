<?php

namespace App\Controllers\Client;


use App\Controllers\BaseController;
use App\Models\Client;
use App\Models\Operation;
use App\Models\BaremeFrais;
use App\Models\TypeOperation;
use App\Models\Prefixe;
use App\Models\Transfert\PrefixeExterne;


class OperationController extends BaseController
{


    public function depot()
    {

        return view('client/depot');
    }




    public function validerDepot()
    {

        $montant = (float)$this->request->getPost('montant');
        $numero = $this->request->getPost('numero');

        if ($montant <= 0) {
            return redirect()
                ->back()
                ->with('error', 'Montant invalide');
        }

        if (!preg_match('/^[0-9]{10}$/', $numero)) {
            return redirect()
                ->back()
                ->with('error', 'Le numéro doit contenir exactement 10 chiffres.');
        }


        $idClient = session()->get('client_id');


        $typeModel = new TypeOperation();

        $baremeModel = new BaremeFrais();

        $operationModel = new Operation();

        $clientModel = new Client();



        // récupérer type dépôt

        $idDepot = $typeModel
            ->getIdParLibelle('depot');


        if (!$idDepot) {
            return redirect()
                ->back()
                ->with('error', 'Type dépôt introuvable');
        }



        // calcul frais

        $frais = $baremeModel
            ->getFraisParMontant(
                $idDepot,
                $montant
            );


        if ($frais === null) {
            $frais = 0;
        }



        // récupérer destinataire
        $destinataire = $clientModel->findByNumero($numero);

        if (!$destinataire) {
            return redirect()
                ->back()
                ->with('error', 'Le compte correspondant à ce numéro est introuvable.');
        }

        $idDestinataire = $destinataire['id'];

        // insertion opération
        $operationModel->insert([
            'id_client_source' => $idClient,
            'id_client_destinataire' => $idDestinataire,
            'id_type_operation' => $idDepot,
            'montant' => $montant,
            'frais' => $frais
        ]);

        // mise à jour solde du destinataire
        $clientModel->update(
            $idDestinataire,
            [
                'solde' => $destinataire['solde'] + $montant - $frais
            ]
        );


        return redirect()
            ->to('/client/solde')
            ->with(
                'success',
                'Dépôt de ' . $montant . ' Ar effectué sur le numéro ' . esc($numero)
            );
    }

    public function retrait()
    {
        return view('client/retrait');
    }

    public function validerRetrait()
    {

        $montant = (float)$this->request->getPost('montant');


        if ($montant <= 0) {
            return redirect()
                ->back()
                ->with('error', 'Montant invalide');
        }



        $idClient = session()->get('client_id');


        $clientModel = new Client();

        $operationModel = new Operation();

        $baremeModel = new BaremeFrais();

        $typeModel = new TypeOperation();



        // récupérer type retrait

        $idRetrait = $typeModel
            ->getIdParLibelle('retrait');



        if (!$idRetrait) {
            return redirect()
                ->back()
                ->with('error', 'Type retrait introuvable');
        }



        // Calcul frais

        $frais = $baremeModel
            ->getFraisParMontant(
                $idRetrait,
                $montant
            );


        if ($frais === null) {
            $frais = 0;
        }



        // Vérification solde

        $client = $clientModel->find($idClient);


        $total = $montant + $frais;


        if ($client['solde'] < $total) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Solde insuffisant'
                );
        }



        // création opération

        $operationModel->insert([

            'id_client_source' => $idClient,

            'id_client_destinataire' => null,

            'id_type_operation' => $idRetrait,

            'montant' => $montant,

            'frais' => $frais

        ]);



        // diminution solde

        $clientModel->update(

            $idClient,

            [

                'solde' =>
                $client['solde']
                    -
                    $total

            ]

        );



        return redirect()
            ->to('/client/solde')
            ->with(
                'success',
                'Retrait effectué'
            );
    }

    public function transfert()
    {
        return view('client/transfert');
    }

    // public function validerTransfert()
    // {

    //     $numeroDest =
    //         $this->request->getPost('numero_destinataire');

    //     if (!preg_match('/^[0-9]{10}$/', $numeroDest)) {
    //         return redirect()
    //             ->back()
    //             ->with('error', 'Le numéro du destinataire doit contenir exactement 10 chiffres.');
    //     }

    //     $montant =
    //         (float)$this->request->getPost('montant');



    //     if ($montant <= 0) {
    //         return redirect()
    //             ->back()
    //             ->with('error', 'Montant invalide');
    //     }



    //     $db = \Config\Database::connect();

    //     $db->transStart();



    //     $clientModel = new Client();

    //     $operationModel = new Operation();

    //     $baremeModel = new BaremeFrais();

    //     $typeModel = new TypeOperation();



    //     // client source

    //     $idSource =
    //         session()->get('client_id');



    //     $source =
    //         $clientModel->find($idSource);



    //     // destinataire

    //     $destinataire =
    //         $clientModel
    //         ->findByNumero($numeroDest);



    //     if (!$destinataire) {

    //         return redirect()
    //             ->back()
    //             ->with(
    //                 'error',
    //                 'Destinataire introuvable'
    //             );
    //     }



    //     if ($destinataire['id'] == $idSource) {

    //         return redirect()
    //             ->back()
    //             ->with(
    //                 'error',
    //                 'Impossible de se transférer à soi-même'
    //             );
    //     }



    //     // type transfert

    //     $idTransfert =
    //         $typeModel
    //         ->getIdParLibelle('transfert');



    //     // frais

    //     $frais =
    //         $baremeModel
    //         ->getFraisParMontant(
    //             $idTransfert,
    //             $montant
    //         );


    //     if ($frais === null) {
    //         $frais = 0;
    //     }



    //     // solde suffisant

    //     if ($source['solde'] < ($montant + $frais)) {

    //         return redirect()
    //             ->back()
    //             ->with(
    //                 'error',
    //                 'Solde insuffisant'
    //             );
    //     }



    //     // insertion opération

    //     $operationModel->insert([

    //         'id_client_source' => $idSource,

    //         'id_client_destinataire' => $destinataire['id'],

    //         'id_type_operation' => $idTransfert,

    //         'montant' => $montant,

    //         'frais' => $frais

    //     ]);



    //     // retirer source

    //     $clientModel->update(

    //         $idSource,

    //         [

    //             'solde' =>
    //             $source['solde']
    //                 -
    //                 ($montant + $frais)

    //         ]

    //     );



    //     // créditer destinataire

    //     $clientModel->update(

    //         $destinataire['id'],

    //         [

    //             'solde' =>
    //             $destinataire['solde']
    //                 +
    //                 $montant

    //         ]

    //     );



    //     $db->transComplete();



    //     if (!$db->transStatus()) {

    //         return redirect()
    //             ->back()
    //             ->with(
    //                 'error',
    //                 'Erreur transfert'
    //             );
    //     }



    //     return redirect()
    //         ->to('/client/solde')
    //         ->with(
    //             'success',
    //             'Transfert effectué'
    //         );
    // }

    public function validerTransfert()
    {

        $numeroDest =
            $this->request->getPost('numero_destinataire');

        if (!preg_match('/^[0-9]{10}$/', $numeroDest)) {
            return redirect()
                ->back()
                ->with('error', 'Le numéro du destinataire doit contenir exactement 10 chiffres.');
        }

        $montant =
            (float)$this->request->getPost('montant');


        if ($montant <= 0) {
            return redirect()
                ->back()
                ->with('error', 'Montant invalide');
        }


        $inclureRetrait =
            $this->request->getPost('inclure_frais_retrait') == 1;


        $db = \Config\Database::connect();

        $db->transStart();


        $clientModel = new Client();

        $operationModel = new Operation();

        $baremeModel = new BaremeFrais();

        $typeModel = new TypeOperation();

        $prefixeModel = new Prefixe();

        $prefixeExterneModel = new PrefixeExterne();


        // client source

        $idSource =
            session()->get('client_id');


        $source =
            $clientModel->find($idSource);


        // code préfixe du numéro saisi

        $codePrefixeDest = substr($numeroDest, 0, 3);


        // on cherche d'abord si ce préfixe appartient à NOTRE opérateur

        $destinataire =
            $clientModel
            ->findByNumero($numeroDest);


        // -----------------------------------------------------
        // CAS 1 : TRANSFERT INTERNE (le numéro existe chez nous)
        // -----------------------------------------------------

        if ($destinataire) {

            if ($destinataire['id'] == $idSource) {

                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'Impossible de se transférer à soi-même'
                    );
            }


            // type transfert

            $idTransfert =
                $typeModel
                ->getIdParLibelle('transfert');


            // frais de transfert

            $frais =
                $baremeModel
                ->getFraisParMontant(
                    $idTransfert,
                    $montant
                );

            if ($frais === null) {
                $frais = 0;
            }


            // frais de retrait (uniquement si option cochée, interne uniquement)

            $fraisRetrait = 0;

            if ($inclureRetrait) {

                $idRetrait =
                    $typeModel
                    ->getIdParLibelle('retrait');

                $fraisRetrait =
                    $baremeModel
                    ->getFraisParMontant(
                        $idRetrait,
                        $montant
                    );

                if ($fraisRetrait === null) {
                    $fraisRetrait = 0;
                }
            }


            $total =
                $montant
                + $frais
                + $fraisRetrait;


            if ($source['solde'] < $total) {

                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'Solde insuffisant'
                    );
            }


            $operationModel->insert([

                'id_client_source' => $idSource,

                'id_client_destinataire' => $destinataire['id'],

                'numero_destinataire_externe' => null,

                'id_prefixe_externe' => null,

                'id_type_operation' => $idTransfert,

                'montant' => $montant,

                'frais' => $frais,

                'frais_retrait_inclus' => $inclureRetrait ? 1 : 0

            ]);


            $clientModel->update(

                $idSource,

                [
                    'solde' => $source['solde'] - $total
                ]

            );


            $clientModel->update(

                $destinataire['id'],

                [
                    'solde' => $destinataire['solde'] + $montant
                ]

            );


            $db->transComplete();


            if (!$db->transStatus()) {

                return redirect()
                    ->back()
                    ->with('error', 'Erreur transfert');
            }


            $message = 'Transfert effectué';

            if ($inclureRetrait) {
                $message .= ' (frais de retrait inclus)';
            }


            return redirect()
                ->to('/client/solde')
                ->with('success', $message);
        }


        // -----------------------------------------------------
        // CAS 2 : TRANSFERT EXTERNE (numéro absent chez nous)
        // -----------------------------------------------------

        $prefixeExterne =
            $prefixeExterneModel
            ->trouverParCode($codePrefixeDest);


        if (!$prefixeExterne) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Ce numéro n\'appartient à aucun opérateur reconnu.'
                );
        }


        // type transfert

        $idTransfert =
            $typeModel
            ->getIdParLibelle('transfert');


        // frais de transfert normal

        $frais =
            $baremeModel
            ->getFraisParMontant(
                $idTransfert,
                $montant
            );

        if ($frais === null) {
            $frais = 0;
        }


        // commission supplémentaire (en %)

        $commission =
            $montant
            * ($prefixeExterne['commission'] / 100);


        // pas d'option frais de retrait pour un autre opérateur

        $total =
            $montant
            + $frais
            + $commission;


        if ($source['solde'] < $total) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Solde insuffisant'
                );
        }


        $operationModel->insert([

            'id_client_source' => $idSource,

            'id_client_destinataire' => null,

            'numero_destinataire_externe' => $numeroDest,

            'id_prefixe_externe' => $prefixeExterne['id'],

            'id_type_operation' => $idTransfert,

            'montant' => $montant,

            'frais' => $frais + $commission,

            'frais_retrait_inclus' => 0

        ]);


        $clientModel->update(

            $idSource,

            [
                'solde' => $source['solde'] - $total
            ]

        );


        // pas de crédit destinataire : il n'est pas géré par notre système


        $db->transComplete();


        if (!$db->transStatus()) {

            return redirect()
                ->back()
                ->with('error', 'Erreur transfert');
        }


        return redirect()
            ->to('/client/solde')
            ->with(
                'success',
                'Transfert vers ' . esc($prefixeExterne['nom_operateur']) . ' effectué'
            );
    }

    public function verifierDestinataire()
    {
        $numero = $this->request->getPost('numero');

        if (!preg_match('/^[0-9]{10}$/', $numero)) {
            return $this->response->setJSON([
                'valide' => false
            ]);
        }

        $clientModel = new Client();
        $prefixeModel = new Prefixe();
        $prefixeExterneModel = new PrefixeExterne();

        $idClient = session()->get('client_id');

        $destinataire = $clientModel->findByNumero($numero);

        // Cas 1 : client interne
        if ($destinataire) {

            if ($destinataire['id'] == $idClient) {
                return $this->response->setJSON([
                    'valide' => false,
                    'erreur' => 'soi-meme'
                ]);
            }

            $source = $clientModel->find($idClient);

            $prefixeSource = $prefixeModel->find($source['id_prefixe']);
            $prefixeDest = $prefixeModel->find($destinataire['id_prefixe']);

            $memeOperateur =
                $prefixeSource['id_operateur'] == $prefixeDest['id_operateur'];

            return $this->response->setJSON([
                'valide' => true,
                'type' => 'interne',
                'meme_operateur' => $memeOperateur
            ]);
        }

        // Cas 2 : externe
        $code = substr($numero, 0, 3);

        $prefixeExterne = $prefixeExterneModel->trouverParCode($code);

        if ($prefixeExterne) {
            return $this->response->setJSON([
                'valide' => true,
                'type' => 'externe',
                'meme_operateur' => false,
                'nom_operateur' => $prefixeExterne['nom_operateur'],
                'commission' => $prefixeExterne['commission']
            ]);
        }

        // Cas 3 : préfixe totalement inconnu
        return $this->response->setJSON([
            'valide' => false,
            'erreur' => 'inconnu'
        ]);
    }

    public function historique()
    {

        $idClient = session()->get('client_id');


        $operationModel = new Operation();


        $historique = $operationModel
            ->getHistoriqueByClient($idClient);



        return view(
            'client/historique',
            [
                'historique' => $historique
            ]
        );
    }
    public function calculFraisAjax()
    {
        $montant = (float)$this->request->getPost('montant');
        $type = $this->request->getPost('type_operation');

        if ($montant <= 0 || !$type) {
            return $this->response->setJSON(['frais' => 0]);
        }

        $typeModel = new TypeOperation();
        $baremeModel = new BaremeFrais();

        $idType = $typeModel->getIdParLibelle($type);
        if (!$idType) {
            return $this->response->setJSON(['frais' => 0]);
        }

        $frais = $baremeModel->getFraisParMontant($idType, $montant);

        return $this->response->setJSON(['frais' => $frais === null ? 0 : $frais]);
    }
}
