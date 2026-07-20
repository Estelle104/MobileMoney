<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Achat;
use App\Models\AchatProduit;
use App\Models\Produit;

class Home extends BaseController
{
    public function index()
    {
        return redirect()->to('/login');
    }

    public function login(): string
    {
        return view('Login');
    }

    public function achat(): string
    {
        $produitModel = new Produit();
        $db = \Config\Database::connect();

        // 1. Récupérer les produits pour le select HTML
        $listeProduits = $produitModel->findAll(); 

        // 2. Récupérer l'id de l'achat en cours stocké en session par AchatController
        $idAchatEnCours = session()->get('id_achat_en_cours');
        $panier = [];

        // 3. Si un achat est en cours, faire la jointure SQL pour récupérer les lignes du panier
        if ($idAchatEnCours) {
            $builder = $db->table('achat_produit');
            $builder->select('achat_produit.*, produits.designation, produits.prix_unitaire');
            $builder->join('produits', 'produits.id = achat_produit.id_produit');
            $builder->where('id_achat', $idAchatEnCours);
            
            $panier = $builder->get()->getResultArray();
        }

        // 4. Envoyer TOUTES les variables nécessaires à la vue 'Achat'
        return view('Achat', [
            'listeProduits' => $listeProduits,
            'panier'        => $panier // <-- LA VARIABLE PANIER EST MAINTENANT ENVOYÉE !
        ]);
    }
}