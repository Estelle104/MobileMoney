<?php

namespace App\Controllers\Operateur;

use App\Controllers\BaseController;
use App\Models\Prefixe;
use App\Models\Client;

class PrefixeController extends BaseController
{
    public function index()
    {
        return $this->list();
    }

    /**
     * Liste des préfixes de l'opérateur connecté.
     */
    public function list()
    {
        $prefixeModel = new Prefixe();
        $prefixes = $prefixeModel->findAllByOperateur(session()->get('operateur_id'));

        return view('operateur/configuration/list', ['prefixes' => $prefixes]);
    }

    /**
     * Affiche le formulaire de création.
     */
    public function creer()
    {
        return view('operateur/configuration/creer');
    }

    /**
     * Traite la soumission du formulaire de création.
     */
    public function enregistrer()
    {
        $prefixeModel = new Prefixe();

        $data = [
            'code'         => $this->request->getPost('code'),
            'id_operateur' => session()->get('operateur_id'),
        ];

        if (! $prefixeModel->save($data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $prefixeModel->errors());
        }

        return redirect()->to('/operateur/configuration/list')
            ->with('success', 'Préfixe créé avec succès.');
    }

    /**
     * Affiche le formulaire de modification.
     */
    public function modifier($id)
    {
        $prefixeModel = new Prefixe();

        if (! $prefixeModel->appartientAOperateur($id, session()->get('operateur_id'))) {
            return redirect()->to('/operateur/configuration/list')
                ->with('error', 'Accès refusé à ce préfixe.');
        }

        $prefixe = $prefixeModel->find($id);

        return view('operateur/configuration/modifier', ['prefixe' => $prefixe]);
    }

    /**
     * Traite la soumission du formulaire de modification.
     */
    public function mettreajour($id)
    {
        $prefixeModel = new Prefixe();

        if (! $prefixeModel->appartientAOperateur($id, session()->get('operateur_id'))) {
            return redirect()->to('/operateur/configuration/list')
                ->with('error', 'Accès refusé à ce préfixe.');
        }

        $nouveauCode = $this->request->getPost('code');

        // cahngement cote client
        if (! $prefixeModel->modifierCode($id, $nouveauCode)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la modification du préfixe.');
        }

        return redirect()->to('/operateur/configuration/list')
            ->with('success', 'Préfixe modifié avec succès.');
    }

    /**
     * Supprime un préfixe
     */
    public function supprimer($id)
    {
        $prefixeModel = new Prefixe();

        if (! $prefixeModel->appartientAOperateur($id, session()->get('operateur_id'))) {
            return redirect()->to('/operateur/configuration/list')
                ->with('error', 'Accès refusé à ce préfixe.');
        }

        if (! $prefixeModel->peutEtreSupprime($id)) {
            return redirect()->to('/operateur/configuration/list')
                ->with('error', 'Impossible de supprimer : des clients sont rattachés à ce préfixe.');
        }

        $prefixeModel->delete($id);

        return redirect()->to('/operateur/configuration/list')
            ->with('success', 'Préfixe supprimé avec succès.');
    }
}