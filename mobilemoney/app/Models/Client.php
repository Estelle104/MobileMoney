<?php

namespace App\Models;

use CodeIgniter\Model;

class Client extends Model
{
    protected $table            = 'client';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['numero', 'id_prefixe', 'solde'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    protected $validationRules = [
        'numero'     => 'required|min_length[8]|is_unique[client.numero,id,{id}]',
        'id_prefixe' => 'required|integer',
        'solde'      => 'permit_empty|decimal',
    ];

    /**
     * Recherche un client par numéro, ou le crée automatiquement
     * si le préfixe correspond à un opérateur existant (login auto).
     */
    public function trouverOuCreerParNumero(string $numero): ?array
    {
        $existant = $this->where('numero', $numero)->first();
        if ($existant) {
            return $existant;
        }

        $codePrefixe = substr($numero, 0, 3);
        $prefixeModel = new Prefixe();
        $prefixe = $prefixeModel->where('code', $codePrefixe)->first();

        if (! $prefixe) {
            return null; // préfixe inconnu -> numéro invalide
        }

        $id = $this->insert([
            'numero'     => $numero,
            'id_prefixe' => $prefixe['id'],
            'solde'      => 0.00,
        ]);

        return $this->find($id);
    }

    /**
     * Liste des clients appartenant à un opérateur (via jointure prefixe).
     */
    public function getAllByOperateur(int $idOperateur): array
    {
        return $this->select('client.*')
            ->join('prefixe', 'prefixe.id = client.id_prefixe')
            ->where('prefixe.id_operateur', $idOperateur)
            ->findAll();
    }

    public function getSoldeById(int $idClient): ?float
    {
        $client = $this->find($idClient);
        return $client ? (float) $client['solde'] : null;
    }

    /**
     * Crédite ou débite le solde d'un client (montant négatif = débit).
     */
    public function ajusterSolde(int $idClient, float $montant): bool
    {
        $client = $this->find($idClient);
        if (! $client) {
            return false;
        }

        $nouveauSolde = $client['solde'] + $montant;
        if ($nouveauSolde < 0) {
            return false; // solde insuffisant
        }

        return (bool) $this->update($idClient, ['solde' => $nouveauSolde]);
    }

    public function findByNumero(string $numero): ?array
    {
        return $this->where('numero', $numero)->first();
    }
}
