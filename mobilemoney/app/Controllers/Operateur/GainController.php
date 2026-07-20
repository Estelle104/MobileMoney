<?php

namespace App\Controllers\Operateur;

use App\Controllers\BaseController;
use App\Models\Operation;

class GainController extends BaseController
{
   
    public function index()
    {
        return $this->afficherGains();
    }
    
    public function filtrer()
    {
        $dateDebut = $this->request->getGet('date_debut');
        $dateFin   = $this->request->getGet('date_fin');

        return $this->afficherGains($dateDebut, $dateFin);
    }

   
    private function afficherGains(?string $dateDebut = null, ?string $dateFin = null)
    {
        $operationModel = new Operation();

        $resultats = $operationModel->getTotalFraisParTypeEtDestination(
            session()->get('operateur_id'),
            $dateDebut,
            $dateFin
        );

        $gainsInterne = [
            'retrait'   => 0,
            'transfert' => 0,
            'depot'     => 0,
        ];
        
        $gainsExterne = [
            'transfert' => 0,
        ];

        foreach ($resultats as $ligne) {
            $libelle = strtolower($ligne['libelle']);
            if ($ligne['destination'] === 'interne') {
                if (array_key_exists($libelle, $gainsInterne)) {
                    $gainsInterne[$libelle] = (float) $ligne['total_frais'];
                }
            } else {
                if (array_key_exists($libelle, $gainsExterne)) {
                    $gainsExterne[$libelle] = (float) $ligne['total_frais'];
                }
            }
        }

        $totalGeneral = array_sum($gainsInterne) + array_sum($gainsExterne);

        return view('operateur/gains/index', [
            'gainsInterne' => $gainsInterne,
            'gainsExterne' => $gainsExterne,
            'totalGeneral' => $totalGeneral,
            'dateDebut'    => $dateDebut,
            'dateFin'      => $dateFin,
        ]);
    }
}