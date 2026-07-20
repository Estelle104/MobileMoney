<?php

namespace App\Models;

use CodeIgniter\Model;

class Prefixe extends Model
{
    protected $table            = 'prefixe';
    protected $allowedFields    = ['code', 'id_operateur'];

    protected $validationRules = [
        'code'         => 'required|exact_length[3]|numeric|is_unique[prefixe.code,id,{id}]',
        'id_operateur' => 'required|integer',
    ];

    protected $validationMessages = [
        'code' => [
            'is_unique'    => 'Ce préfixe est déjà utilisé.',
            'exact_length' => 'Le préfixe doit contenir exactement 3 chiffres.',
        ],
    ];

   
    public function findAllByOperateur(int $idOperateur): array
    {
        return $this->where('id_operateur', $idOperateur)->findAll();
    }

    public function appartientAOperateur(int $idPrefixe, int $idOperateur): bool
    {
        return $this->where('id', $idPrefixe)
            ->where('id_operateur', $idOperateur)
            ->first() !== null;
    }

    
    public function modifierCode(int $idPrefixe, string $nouveauCode): bool
    {
        $ancien = $this->find($idPrefixe);
        if (! $ancien) {
            return false;
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Met à jour le préfixe
        $this->update($idPrefixe, ['code' => $nouveauCode]);

        // Répercute sur les numéros clients existants
        $clientModel = new Client();
        $clients     = $clientModel->where('id_prefixe', $idPrefixe)->findAll();

        foreach ($clients as $client) {
            $reste       = substr($client['numero'], 3); // tout sauf les 3 premiers chiffres
            $nouveauNum  = $nouveauCode . $reste;
            $clientModel->update($client['id'], ['numero' => $nouveauNum]);
        }

        $db->transComplete();

        return $db->transStatus();
    }

    public function peutEtreSupprime(int $idPrefixe): bool
    {
        $clientModel = new Client();
        return $clientModel->where('id_prefixe', $idPrefixe)->countAllResults() === 0;
    }

    public function trouverParCode(string $code)
    {
        return $this->where('code', $code)->first();
    }
}