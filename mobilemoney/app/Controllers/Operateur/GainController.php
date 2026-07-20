<?php

namespace App\Controllers\Operateur;

use App\Controllers\BaseController;
use App\Models\Operation;

class GainController extends BaseController
{
    /**
     * Affiche le total des gains (retrait + transfert), sans filtre de date.
     */
    public function index()
    {
        return $this->afficherGains();
    }

    /**
     * Affiche le total des gains filtré par plage de dates (formulaire GET).
     */
    public function filtrer()
    {
        $dateDebut = $this->request->getGet('date_debut');
        $dateFin   = $this->request->getGet('date_fin');

        return $this->afficherGains($dateDebut, $dateFin);
    }

    /**
     * Logique commune : récupère les gains, les structure, envoie à la vue.
     */
    private function afficherGains(?string $dateDebut = null, ?string $dateFin = null)
    {
        $operationModel = new Operation();

        $resultats = $operationModel->getTotalFraisParType(
            session()->get('operateur_id'),
            $dateDebut,
            $dateFin
        );

        // Le model retourne un tableau du type :
        // [ ['libelle' => 'retrait', 'total_frais' => 1500], ['libelle' => 'transfert', 'total_frais' => 800], ... ]
        // On le transforme en tableau indexé par libellé, plus simple à exploiter en vue.
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

        // Dépôt exclu du total si son barème est à 0 (pas de frais collecté dessus)
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