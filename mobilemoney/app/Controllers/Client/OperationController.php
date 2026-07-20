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
            
        $isExterne = false;
        $prefixeExterne = null;

        if (!$destinataire) {
            $prefixeCode = substr($numeroDest, 0, 3);
            $extModel = new \App\Models\PrefixeExterneModel();
            $prefixeExterne = $extModel->estPrefixeExterne($prefixeCode);
            
            if (!$prefixeExterne) {
                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'Opérateur destinataire inconnu ou destinataire introuvable'
                    );
            }
            $isExterne = true;
        }

        if (!$isExterne && $destinataire['id'] == $idSource) {

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
        
        if ($isExterne) {
            $frais += $montant * ($prefixeExterne['pourcentage_commission'] / 100);
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
            'id_client_destinataire' => $isExterne ? null : $destinataire['id'],
            'numero_destinataire_externe' => $isExterne ? $numeroDest : null,
            'id_prefixe_externe' => $isExterne ? $prefixeExterne['id'] : null,
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

        if (!$isExterne) {
            $clientModel->update(
                $destinataire['id'],
                [
                    'solde' =>
                    $destinataire['solde']
                        +
                        $montant
                ]
            );
        }

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
    public function calculFraisAjax()
    {
        $montant = (float)$this->request->getPost('montant');
        $type = $this->request->getPost('type_operation');
        $numero = $this->request->getPost('numero_destinataire');

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
        $frais = $frais === null ? 0 : $frais;

        if ($type === 'transfert' && $numero && strlen($numero) >= 3) {
            $prefixeCode = substr($numero, 0, 3);
            $clientModel = new Client();
            if (!$clientModel->findByNumero($numero)) {
                $extModel = new \App\Models\PrefixeExterneModel();
                $ext = $extModel->estPrefixeExterne($prefixeCode);
                if ($ext) {
                    $frais += $montant * ($ext['pourcentage_commission'] / 100);
                }
            }
        }
        
        return $this->response->setJSON(['frais' => $frais]);
    }
}
