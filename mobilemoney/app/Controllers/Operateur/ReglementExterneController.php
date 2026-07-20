<?php

namespace App\Controllers\Operateur;

use App\Controllers\BaseController;
use App\Models\Operation;
use App\Models\PrefixeExterneModel;
use App\Models\ReglementExterneModel;

class ReglementExterneController extends BaseController
{
    public function index()
    {
        return $this->filtrer();
    }

    public function filtrer()
    {
        $dateDebut = $this->request->getGet('date_debut');
        $dateFin   = $this->request->getGet('date_fin');
        $idOperateur = session()->get('operateur_id');

        $prefixeModel = new PrefixeExterneModel();
        $prefixesExternes = $prefixeModel->where('id_operateur', $idOperateur)->findAll();
        
        $nomsExternes = [];
        foreach ($prefixesExternes as $p) {
            $nomsExternes[$p['nom_operateur_externe']] = true;
        }
        $nomsExternes = array_keys($nomsExternes);

        $operationModel = new Operation();
        $montantsTransferes = $operationModel->getMontantsParOperateurExterne($idOperateur, $dateDebut, $dateFin);
        
        $mapTransferes = [];
        foreach ($montantsTransferes as $t) {
            $mapTransferes[$t['nom_operateur_externe']] = (float) $t['total_montant'];
        }

        $reglementModel = new ReglementExterneModel();
        
        $situations = [];
        foreach ($nomsExternes as $nom) {
            $totalTransfere = $mapTransferes[$nom] ?? 0.0;
            $totalRegle = $reglementModel->getTotalReglePar($nom, $idOperateur);
            $solde = $totalTransfere - $totalRegle;
            
            $situations[] = [
                'nom_operateur_externe' => $nom,
                'total_transfere'       => $totalTransfere,
                'total_regle'           => $totalRegle,
                'solde'                 => $solde
            ];
        }

        return view('operateur/reglements_externes/index', [
            'situations' => $situations,
            'dateDebut'  => $dateDebut,
            'dateFin'    => $dateFin,
        ]);
    }

    public function creer()
    {
        $idOperateur = session()->get('operateur_id');
        $prefixeModel = new PrefixeExterneModel();
        $prefixesExternes = $prefixeModel->where('id_operateur', $idOperateur)->findAll();
        
        $nomsExternes = [];
        foreach ($prefixesExternes as $p) {
            $nomsExternes[$p['nom_operateur_externe']] = true;
        }
        $nomsExternes = array_keys($nomsExternes);

        return view('operateur/reglements_externes/creer', [
            'nomsExternes' => $nomsExternes
        ]);
    }

    public function enregistrer()
    {
        $reglementModel = new ReglementExterneModel();
        
        $data = [
            'id_operateur'          => session()->get('operateur_id'),
            'nom_operateur_externe' => $this->request->getPost('nom_operateur_externe'),
            'montant'               => $this->request->getPost('montant'),
            'date_reglement'        => date('Y-m-d')
        ];

        if (!$reglementModel->insert($data)) {
            return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement du règlement.');
        }

        return redirect()->to('/operateur/reglements-externes')
            ->with('success', 'Règlement enregistré avec succès.');
    }
}
