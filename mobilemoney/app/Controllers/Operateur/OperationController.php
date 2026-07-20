<?php

namespace App\Controllers\Operateur;

use App\Controllers\BaseController;
use App\Models\TypeOperation;
use App\Models\BaremeFrais;

class OperationController extends BaseController
{
    public function list($idTypeOperation = 1)
    {
        $typeModel   = new TypeOperation();
        $baremeModel = new BaremeFrais();

        $typeOperation = $typeModel->find($idTypeOperation);

        if (! $typeOperation) {
            return redirect()->to('/operateur/dashboard')
                ->with('error', 'Type d\'operation introuvable.');
        }

        $tranches = $baremeModel->findAllByType($idTypeOperation);
        $typesOperations = $typeModel->findAll();

        return view('operateur/operation/list', [
            'typeOperation'   => $typeOperation,
            'typesOperations' => $typesOperations,
            'tranches'        => $tranches,
        ]);
    }

   
    public function ajouter()
    {
        $typeModel = new TypeOperation();
        $types     = $typeModel->findAll(); 
        return view('operateur/operation/ajouter', ['types' => $types]);
    }

    public function enregistrer()
    {
        $baremeModel = new BaremeFrais();

        $idTypeOperation = $this->request->getPost('id_type_operation');
        $montantMin      = $this->request->getPost('montant_min');
        $montantMax      = $this->request->getPost('montant_max');
        $frais           = $this->request->getPost('frais');

        if ($baremeModel->chevauche($idTypeOperation, (float) $montantMin, (float) $montantMax)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Cette tranche chevauche une tranche existante.');
        }

        $data = [
            'id_type_operation' => $idTypeOperation,
            'montant_min'       => $montantMin,
            'montant_max'       => $montantMax,
            'frais'             => $frais,
        ];

        if (! $baremeModel->save($data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $baremeModel->errors());
        }

        return redirect()->to('/operateur/operation/list/' . $idTypeOperation)
            ->with('success', 'Tranche ajoutee avec succes.');
    }

    /**
     * Affiche le formulaire de modification d'une tranche.
     */
    public function modifier($id)
    {
        $baremeModel = new BaremeFrais();
        $typeModel   = new TypeOperation();

        $tranche = $baremeModel->find($id);

        if (! $tranche) {
            return redirect()->to('/operateur/dashboard')
                ->with('error', 'Tranche introuvable.');
        }

        $types = $typeModel->findAll();

        return view('operateur/operation/modifier', [
            'tranche' => $tranche,
            'types'   => $types,
        ]);
    }

    /**
     * Traite la soumission du formulaire de modification.
     */
    public function update($id)
    {
        $baremeModel = new BaremeFrais();

        $tranche = $baremeModel->find($id);
        if (! $tranche) {
            return redirect()->to('/operateur/dashboard')
                ->with('error', 'Tranche introuvable.');
        }

        $idTypeOperation = $this->request->getPost('id_type_operation');
        $montantMin      = $this->request->getPost('montant_min');
        $montantMax      = $this->request->getPost('montant_max');
        $frais           = $this->request->getPost('frais');

        // Chevauchement en excluant la tranche elle-même
        if ($baremeModel->chevauche($idTypeOperation, (float) $montantMin, (float) $montantMax, (int) $id)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Cette tranche chevauche une tranche existante.');
        }

        $data = [
            'id_type_operation' => $idTypeOperation,
            'montant_min'       => $montantMin,
            'montant_max'       => $montantMax,
            'frais'             => $frais,
        ];

        if (! $baremeModel->update($id, $data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $baremeModel->errors());
        }

        return redirect()->to('/operateur/operation/list/' . $idTypeOperation)
            ->with('success', 'Tranche mise a jour avec succes.');
    }

    public function supprimer($id)
    {
        $baremeModel = new BaremeFrais();

        $tranche = $baremeModel->find($id);
        if (! $tranche) {
            return redirect()->to('/operateur/dashboard')
                ->with('error', 'Tranche introuvable.');
        }

        $idTypeOperation = $tranche['id_type_operation'];
        $baremeModel->delete($id);

        return redirect()->to('/operateur/operation/list/' . $idTypeOperation)
            ->with('success', 'Tranche supprimee avec succes.');
    }
}