<?php

namespace App\Controllers\Operateur;

use App\Controllers\BaseController;
use App\Models\PrefixeExterneModel;

class PrefixeExterneController extends BaseController
{
    /**
     * Liste des préfixes externes configurés par l'opérateur connecté.
     */
    public function list()
    {
        $model = new PrefixeExterneModel();
        $prefixes = $model->findAllByOperateur(session()->get('operateur_id'));

        return view('operateur/prefixe_externe/list', ['prefixes' => $prefixes]);
    }

    /**
     * Affiche le formulaire de création.
     */
    public function creer()
    {
        return view('operateur/prefixe_externe/creer');
    }

    /**
     * Traite la soumission du formulaire de création.
     */
    public function enregistrer()
    {
        $model = new PrefixeExterneModel();

        $data = [
            'code'                   => $this->request->getPost('code'),
            'nom_operateur_externe'  => $this->request->getPost('nom_operateur_externe'),
            'pourcentage_commission' => $this->request->getPost('pourcentage_commission'),
            'id_operateur'           => session()->get('operateur_id'),
        ];

        if (! $model->save($data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $model->errors());
        }

        return redirect()->to('/operateur/prefixe-externe/list')
            ->with('success', 'Préfixe externe configuré avec succès.');
    }

    /**
     * Affiche le formulaire de modification.
     */
    public function modifier($id)
    {
        $model = new PrefixeExterneModel();

        if (! $model->appartientAOperateur($id, session()->get('operateur_id'))) {
            return redirect()->to('/operateur/prefixe-externe/list')
                ->with('error', 'Accès refusé à cette configuration.');
        }

        $prefixe = $model->find($id);

        return view('operateur/prefixe_externe/modifier', ['prefixe' => $prefixe]);
    }

    /**
     * Traite la soumission du formulaire de modification.
     */
    public function mettreajour($id)
    {
        $model = new PrefixeExterneModel();

        if (! $model->appartientAOperateur($id, session()->get('operateur_id'))) {
            return redirect()->to('/operateur/prefixe-externe/list')
                ->with('error', 'Accès refusé à cette configuration.');
        }

        $data = [
            'id'                     => $id, 
            'code'                   => $this->request->getPost('code'),
            'nom_operateur_externe'  => $this->request->getPost('nom_operateur_externe'),
            'pourcentage_commission' => $this->request->getPost('pourcentage_commission'),
            'id_operateur'           => session()->get('operateur_id'),
        ];

        if (! $model->save($data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $model->errors());
        }

        return redirect()->to('/operateur/prefixe-externe/list')
            ->with('success', 'Configuration modifiée avec succès.');
    }

    /**
     * Supprime un préfixe externe.
     */
    public function supprimer($id)
    {
        $model = new PrefixeExterneModel();

        if (! $model->appartientAOperateur($id, session()->get('operateur_id'))) {
            return redirect()->to('/operateur/prefixe-externe/list')
                ->with('error', 'Accès refusé à cette configuration.');
        }

        if (! $model->peutEtreSupprime($id)) {
            return redirect()->to('/operateur/prefixe-externe/list')
                ->with('error', 'Impossible de supprimer ce préfixe car des opérations y sont liées.');
        }

        $model->delete($id);

        return redirect()->to('/operateur/prefixe-externe/list')
            ->with('success', 'Préfixe externe supprimé avec succès.');
    }
}
