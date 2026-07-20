<?php

namespace App\Controllers\Operateur;

use App\Controllers\BaseController;
use App\Models\Client;
use App\Models\Operation;

class ClientController extends BaseController
{
    /**
     * Liste des clients de l'opérateur connecté, avec leur solde.
     */
    public function list()
    {
        $clientModel = new Client();

        $clients = $clientModel->getAllByOperateur(session()->get('operateur_id'));

        return view('operateur/clients/list', ['clients' => $clients]);
    }

    /**
     * Détail d'un client + historique de ses opérations.
     */
    public function detail($id)
    {
        $clientModel = new Client();

        // Sécurité : le client doit appartenir à l'opérateur connecté
        if (! $clientModel->appartientAOperateur($id, session()->get('operateur_id'))) {
            return redirect()->to('/operateur/clients/list')
                ->with('error', 'Accès refusé à ce client.');
        }

        $client = $clientModel->find($id);

        $operationModel = new Operation();
        $historique = $operationModel->getHistoriqueByClient($id);

        return view('operateur/clients/detail', [
            'client'     => $client,
            'historique' => $historique,
        ]);
    }
}