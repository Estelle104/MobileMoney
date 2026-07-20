<?php

namespace App\Controllers\Operateur;

use App\Controllers\BaseController;
use App\Models\Prefixe;
use App\Models\Client;
use App\Models\Operation;
use App\Models\TypeOperation;

class DashboardController extends BaseController
{
    public function index()
    {
        $idOperateur = session()->get('operateur_id');

        $prefixeModel = new Prefixe();
        $clientModel  = new Client();
        $operationModel = new Operation();
        $typeModel    = new TypeOperation();

        // Statistiques rapides
        $nbPrefixes = count($prefixeModel->findAllByOperateur($idOperateur));
        $clients    = $clientModel->getAllByOperateur($idOperateur);
        $nbClients  = count($clients);

        // Solde total détenu par tous les clients (aperçu de la "trésorerie")
        $soldeTotal = array_sum(array_column($clients, 'solde'));

        // Gains du jour uniquement, pour un aperçu rapide
        $aujourdHui = date('Y-m-d');
        $resultatsGains = $operationModel->getTotalFraisParType($idOperateur, $aujourdHui, $aujourdHui);

        $gainsAujourdhui = 0;
        foreach ($resultatsGains as $ligne) {
            $gainsAujourdhui += (float) $ligne['total_frais'];
        }

        // Types d'opérations pour les liens rapides vers les barèmes
        $types = $typeModel->findAll();

        return view('operateur/dashboard/index', [
            'nbPrefixes'      => $nbPrefixes,
            'nbClients'       => $nbClients,
            'soldeTotal'      => $soldeTotal,
            'gainsAujourdhui' => $gainsAujourdhui,
            'types'           => $types,
        ]);
    }
}