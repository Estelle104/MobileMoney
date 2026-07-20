<?php

namespace App\Controllers\Client;


use App\Controllers\BaseController;
use App\Models\Client;
use App\Models\Operation;
use App\Models\BaremeFrais;
use App\Models\TypeOperation;
use App\Models\Prefixe;
use App\Models\PrefixeExterneModel;


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
        $numeroDest = $this->request->getPost('numero_destinataire');

        if (!preg_match('/^[0-9]{10}$/', $numeroDest)) {
            return redirect()->back()->with('error', 'Le numéro du destinataire doit contenir exactement 10 chiffres.');
        }

        $montant = (float)$this->request->getPost('montant');

        if ($montant <= 0) {
            return redirect()->back()->with('error', 'Montant invalide');
        }

        $inclureFraisRetrait = (bool)$this->request->getPost('frais_retrait_inclus');

        $db = \Config\Database::connect();
        $db->transStart();

        $clientModel   = new Client();
        $operationModel = new Operation();
        $baremeModel   = new BaremeFrais();
        $typeModel     = new TypeOperation();
        $prefixeModel  = new Prefixe();

        $idSource = session()->get('client_id');
        $source   = $clientModel->find($idSource);

        // préfixe du client source
        $prefixeSource = $clientModel->getPrefixeClient($idSource);

        // chercher destinataire interne
        $destinataire = $clientModel->findByNumero($numeroDest);

        $isExterne      = false;
        $prefixeExterne = null;
        $isMemeOperateur = false;

        if ($destinataire) {
            // destinataire interne : même ou autre opérateur ?
            $prefixeDest = $prefixeModel->trouverParCode(substr($numeroDest, 0, 3));
            $isMemeOperateur = $prefixeSource && $prefixeDest &&
                               ($prefixeSource['id_operateur'] === $prefixeDest['id_operateur']);
        } else {
            // destinataire inconnu de notre base => chercher dans prefixe_externe
            $prefixeCode = substr($numeroDest, 0, 3);
            // D'abord, est-ce un préfixe interne d'un autre opérateur ?
            $prefixeInterne = $prefixeModel->trouverParCode($prefixeCode);
            if ($prefixeInterne) {
                return redirect()->back()->with('error', 'Destinataire introuvable : ce numéro commence par un préfixe interne mais aucun compte ne correspond.');
            }

            $extModel = new PrefixeExterneModel();
            // chercher dans les prefixes externes configurés par l'opérateur du client source
            $idOperateurSource = $prefixeSource['id_operateur'] ?? null;
            if ($idOperateurSource) {
                $prefixeExterne = $extModel->where('code', $prefixeCode)
                                           ->where('id_operateur', $idOperateurSource)
                                           ->first();
            }

            if (!$prefixeExterne) {
                return redirect()->back()->with('error', 'Opérateur destinataire inconnu. Ce préfixe n\'est pas configuré.');
            }
            $isExterne = true;
        }

        // vérifier auto-transfert
        if (!$isExterne && $destinataire['id'] == $idSource) {
            return redirect()->back()->with('error', 'Impossible de se transférer à soi-même');
        }

        $idTransfert = $typeModel->getIdParLibelle('transfert');
        $idRetrait   = $typeModel->getIdParLibelle('retrait');

        // frais de transfert
        $frais = $baremeModel->getFraisParMontant($idTransfert, $montant) ?? 0;

        // commission si externe
        if ($isExterne) {
            $frais += $montant * ($prefixeExterne['pourcentage_commission'] / 100);
        }

        // frais retrait optionnels (seulement si même opérateur)
        $fraisRetrait = 0;
        if (!$isExterne && $isMemeOperateur && $inclureFraisRetrait) {
            $fraisRetrait = $baremeModel->getFraisParMontant($idRetrait, $montant) ?? 0;
        }

        $totalDebit = $montant + $frais + $fraisRetrait;

        if ($source['solde'] < $totalDebit) {
            $db->transComplete();
            return redirect()->back()->with('error', 'Solde insuffisant (besoin : ' . number_format($totalDebit, 0, ',', ' ') . ' Ar)');
        }

        // insertion opération
        $operationModel->insert([
            'id_client_source'           => $idSource,
            'id_client_destinataire'     => $isExterne ? null : $destinataire['id'],
            'numero_destinataire_externe'=> $isExterne ? $numeroDest : null,
            'id_prefixe_externe'         => $isExterne ? $prefixeExterne['id'] : null,
            'id_type_operation'          => $idTransfert,
            'montant'                    => $montant,
            'frais'                      => $frais + $fraisRetrait,
            'frais_retrait_inclus'       => ($inclureFraisRetrait && !$isExterne && $isMemeOperateur) ? 1 : 0,
        ]);

        // débiter source
        $clientModel->update($idSource, ['solde' => $source['solde'] - $totalDebit]);

        // créditer destinataire (interne seulement)
        if (!$isExterne) {
            $clientModel->update($destinataire['id'], [
                'solde' => $destinataire['solde'] + $montant
            ]);
        }

        $db->transComplete();

        if (!$db->transStatus()) {
            return redirect()->back()->with('error', 'Erreur lors du transfert');
        }

        return redirect()->to('/client/solde')->with('success', 'Transfert effectué avec succès');
    }

    public function transfertMultiple()
    {
        return view('client/transfert_multiple');
    }

    public function validerTransfertMultiple()
    {
        $numeros = $this->request->getPost('numeros'); // tableau
        $montantTotal = (float)$this->request->getPost('montant_total');

        if ($montantTotal <= 0) {
            return redirect()->back()->withInput()->with('error', 'Montant invalide.');
        }

        if (!is_array($numeros)) {
            return redirect()->back()->withInput()->with('error', 'Numéros invalides.');
        }

        // Nettoyer
        $numeros = array_filter(array_map('trim', $numeros), fn($n) => $n !== '');
        $numeros = array_values(array_unique($numeros));

        if (count($numeros) < 2) {
            return redirect()->back()->withInput()->with('error', 'Il faut au moins 2 destinataires.');
        }

        $idSource = session()->get('client_id');
        $clientModel  = new Client();
        $prefixeModel = new Prefixe();
        $typeModel    = new TypeOperation();
        $baremeModel  = new BaremeFrais();
        $operationModel = new Operation();

        $source = $clientModel->find($idSource);
        $prefixeSource = $clientModel->getPrefixeClient($idSource);

        // Vérifications
        $destinataires = [];
        foreach ($numeros as $numero) {
            if (!preg_match('/^[0-9]{10}$/', $numero)) {
                return redirect()->back()->withInput()->with('error', "Numéro invalide : $numero");
            }
            if ($numero === session()->get('numero')) {
                return redirect()->back()->withInput()->with('error', 'Vous ne pouvez pas vous envoyer à vous-même.');
            }
            $dest = $clientModel->findByNumero($numero);
            if (!$dest) {
                return redirect()->back()->withInput()->with('error', "Destinataire introuvable : $numero");
            }
            // vérifier même opérateur
            $prefixeDest = $prefixeModel->trouverParCode(substr($numero, 0, 3));
            if (!$prefixeDest || $prefixeDest['id_operateur'] !== $prefixeSource['id_operateur']) {
                return redirect()->back()->withInput()->with('error', "Le numéro $numero n'appartient pas à votre opérateur.");
            }
            $destinataires[] = $dest;
        }

        $nbDest = count($destinataires);
        $montantIndividuel = round($montantTotal / $nbDest, 2);

        $idTransfert = $typeModel->getIdParLibelle('transfert');
        $fraisUnitaire = $baremeModel->getFraisParMontant($idTransfert, $montantIndividuel) ?? 0;
        $fraisTotal    = $fraisUnitaire * $nbDest;
        $totalDebit    = $montantTotal + $fraisTotal;

        if ($source['solde'] < $totalDebit) {
            return redirect()->back()->withInput()->with('error', 'Solde insuffisant. Total à débiter : ' . number_format($totalDebit, 0, ',', ' ') . ' Ar');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $groupeId = bin2hex(random_bytes(16)); // UUID v4-like

        foreach ($destinataires as $dest) {
            $operationModel->insert([
                'id_client_source'       => $idSource,
                'id_client_destinataire' => $dest['id'],
                'id_type_operation'      => $idTransfert,
                'montant'                => $montantIndividuel,
                'frais'                  => $fraisUnitaire,
                'id_groupe_transfert'    => $groupeId,
            ]);
            $clientModel->update($dest['id'], ['solde' => $dest['solde'] + $montantIndividuel]);
        }

        // Débiter source une seule fois
        $clientModel->update($idSource, ['solde' => $source['solde'] - $totalDebit]);

        $db->transComplete();

        if (!$db->transStatus()) {
            return redirect()->back()->with('error', 'Erreur lors du transfert multiple.');
        }

        return redirect()->to('/client/solde')->with('success', "Transfert de {$montantTotal} Ar vers {$nbDest} destinataires effectué.");
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
        $inclureRetrait = (bool)$this->request->getPost('frais_retrait_inclus');

        if ($montant <= 0 || !$type) {
            return $this->response->setJSON(['frais' => 0, 'frais_retrait' => 0, 'is_externe' => false]);
        }

        $typeModel = new TypeOperation();
        $baremeModel = new BaremeFrais();
        $clientModel = new Client();
        $prefixeModel = new Prefixe();

        $idType = $typeModel->getIdParLibelle($type);
        if (!$idType) {
            return $this->response->setJSON(['frais' => 0, 'frais_retrait' => 0, 'is_externe' => false]);
        }

        $frais = $baremeModel->getFraisParMontant($idType, $montant);
        $frais = $frais === null ? 0 : $frais;
        $fraisRetrait = 0;
        $isExterne = false;
        $isMemeOperateur = false;

        if ($type === 'transfert' && $numero && strlen($numero) >= 3) {
            $idSource = session()->get('client_id');
            $prefixeSource = $clientModel->getPrefixeClient($idSource);
            $prefixeCode = substr($numero, 0, 3);

            $destinataire = $clientModel->findByNumero($numero);
            if ($destinataire) {
                $prefixeDest = $prefixeModel->trouverParCode($prefixeCode);
                $isMemeOperateur = $prefixeSource && $prefixeDest &&
                    ($prefixeSource['id_operateur'] === $prefixeDest['id_operateur']);
            } else {
                $extModel = new PrefixeExterneModel();
                $idOperateurSource = $prefixeSource['id_operateur'] ?? null;
                $ext = null;
                if ($idOperateurSource) {
                    $ext = $extModel->where('code', $prefixeCode)->where('id_operateur', $idOperateurSource)->first();
                }
                if ($ext) {
                    $frais += $montant * ($ext['pourcentage_commission'] / 100);
                    $isExterne = true;
                }
            }

            // frais retrait si même opérateur et case cochée
            if (!$isExterne && $isMemeOperateur && $inclureRetrait) {
                $idRetrait = $typeModel->getIdParLibelle('retrait');
                $fraisRetrait = $baremeModel->getFraisParMontant($idRetrait, $montant) ?? 0;
            }
        }

        return $this->response->setJSON([
            'frais'        => $frais,
            'frais_retrait' => $fraisRetrait,
            'total'        => $frais + $fraisRetrait,
            'is_externe'   => $isExterne,
            'is_meme_operateur' => $isMemeOperateur,
        ]);
    }
}



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
