<?php

namespace App\Controllers\Client;


use App\Controllers\BaseController;
use App\Models\Client;
use App\Models\Operation;
use App\Models\BaremeFrais;
use App\Models\TypeOperation;


class OperationController extends BaseController
{


    public function depot()
    {

        return view('client/depot');
    }




    public function validerDepot()
    {

        $montant = (float)$this->request->getPost('montant');


        if ($montant <= 0) {
            return redirect()
                ->back()
                ->with('error', 'Montant invalide');
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



        // insertion opération

        $operationModel->insert([

            'id_client_source' => $idClient,

            'id_client_destinataire' => null,

            'id_type_operation' => $idDepot,

            'montant' => $montant,

            'frais' => $frais

        ]);



        // mise à jour solde

        $client = $clientModel->find($idClient);


        $clientModel->update(
            $idClient,
            [
                'solde' =>
                $client['solde']
                    +
                    $montant
                    -
                    $frais
            ]
        );


        return redirect()
            ->to('/client/solde')
            ->with(
                'success',
                'Dépôt effectué'
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

    public function validerTransfert()
    {

        $numeroDest =
            $this->request->getPost('numero_destinataire');


        $montant =
            (float)$this->request->getPost('montant');



        if ($montant <= 0) {
            return redirect()
                ->back()
                ->with('error', 'Montant invalide');
        }



        $db = \Config\Database::connect();

        $db->transStart();



        $clientModel = new Client();

        $operationModel = new Operation();

        $baremeModel = new BaremeFrais();

        $typeModel = new TypeOperation();



        // client source

        $idSource =
            session()->get('client_id');



        $source =
            $clientModel->find($idSource);



        // destinataire

        $destinataire =
            $clientModel
            ->findByNumero($numeroDest);



        if (!$destinataire) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Destinataire introuvable'
                );
        }



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



        // frais

        $frais =
            $baremeModel
            ->getFraisParMontant(
                $idTransfert,
                $montant
            );


        if ($frais === null) {
            $frais = 0;
        }



        // solde suffisant

        if ($source['solde'] < ($montant + $frais)) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Solde insuffisant'
                );
        }



        // insertion opération

        $operationModel->insert([

            'id_client_source' => $idSource,

            'id_client_destinataire' => $destinataire['id'],

            'id_type_operation' => $idTransfert,

            'montant' => $montant,

            'frais' => $frais

        ]);



        // retirer source

        $clientModel->update(

            $idSource,

            [

                'solde' =>
                $source['solde']
                    -
                    ($montant + $frais)

            ]

        );



        // créditer destinataire

        $clientModel->update(

            $destinataire['id'],

            [

                'solde' =>
                $destinataire['solde']
                    +
                    $montant

            ]

        );



        $db->transComplete();



        if (!$db->transStatus()) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Erreur transfert'
                );
        }



        return redirect()
            ->to('/client/solde')
            ->with(
                'success',
                'Transfert effectué'
            );
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
}
