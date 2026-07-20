<?php

namespace App\Models;

use CodeIgniter\Model;

class BaremeFrais extends Model
{
    protected $table            = 'bareme_frais';
    protected $allowedFields    = ['id_type_operation', 'montant_min', 'montant_max', 'frais'];

    protected $validationRules = [
        'id_type_operation' => 'required|integer',
        'montant_min'       => 'required|decimal',
        'montant_max'       => 'required|decimal',
        'frais'             => 'required|decimal',
    ];

    /**
     * Retourne toutes les tranches d'un type d'opération, triées.
     */
    public function findAllByType(int $idTypeOperation): array
    {
        return $this->where('id_type_operation', $idTypeOperation)
            ->orderBy('montant_min', 'ASC')
            ->findAll();
    }

    /**
     * Calcule le frais applicable pour un montant et un type d'opération donnés.
     */
    public function getFraisParMontant(int $idTypeOperation, float $montant): ?float
    {
        $tranche = $this->where('id_type_operation', $idTypeOperation)
            ->where('montant_min <=', $montant)
            ->where('montant_max >=', $montant)
            ->first();

        return $tranche ? (float) $tranche['frais'] : null; // null = hors barème
    }

    /**
     * Vérifie qu'une nouvelle tranche ne chevauche pas une tranche existante.
     * $ignoreId permet d'exclure la tranche elle-même en cas de modification.
     */
    public function chevauche(int $idTypeOperation, float $min, float $max, ?int $ignoreId = null): bool
    {
        $builder = $this->where('id_type_operation', $idTypeOperation)
            ->groupStart()
            ->where('montant_min <=', $max)
            ->where('montant_max >=', $min)
            ->groupEnd();

        if ($ignoreId !== null) {
            $builder->where('id !=', $ignoreId);
        }

        return $builder->countAllResults() > 0;
    }

    // public function getFraisParMontant(int $idTypeOperation, float $montant): ?float
    // {
    //     $bareme = $this->where('id_type_operation', $idTypeOperation)
    //         ->where('montant_min <=', $montant)
    //         ->where('montant_max >=', $montant)
    //         ->first();

    //     return $bareme ? (float)$bareme['frais'] : null;
    // }
}
