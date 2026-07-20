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

        $resultats = $operationModel->getTotalFraisParType(
            session()->get('operateur_id'),
            $dateDebut,
            $dateFin
        );

        $gains = [
            'retrait'   => 0,
            'transfert' => 0,
            'depot'     => 0,
        ];

        foreach ($resultats as $ligne) {
            $libelle = strtolower($ligne['libelle']);
            if (array_key_exists($libelle, $gains)) {
                $gains[$libelle] = (float) $ligne['total_frais'];
            }
        }

        $totalGeneral = $gains['retrait'] + $gains['transfert'];
        if ($gains['depot'] > 0) {
            $totalGeneral += $gains['depot'];
        }

        return view('operateur/gains/index', [
            'gains'        => $gains,
            'totalGeneral' => $totalGeneral,
            'dateDebut'    => $dateDebut,
            'dateFin'      => $dateFin,
        ]);
    }
}