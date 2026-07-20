<?php

namespace App\Models;

use CodeIgniter\Model;

class BaremeFrais extends Model
{
    protected $table            = 'bareme_frais';
    protected $allowedFields    = ['id_type_operation', 'montant_min', 'montant_max', 'frais'];

    protected $validationRules = [
        'id_type_operation' => 'required|integer',
        'montant_min'       => 'required|decimal|greater_than_equal_to[0]',
        'montant_max'       => 'required|decimal|valid_montant_max[montant_min]',
        'frais'             => 'required|decimal|greater_than_equal_to[0]',
    ];

    public function findAllByType(int $idTypeOperation): array
    {
        return $this->where('id_type_operation', $idTypeOperation)
            ->orderBy('montant_min', 'ASC')
            ->findAll();
    }

    /**
     * Retourne le montant des frais pour un type d'opération et un montant donné.
     * Retourne null si le montant est hors barème.
     */
    public function getFraisParMontant(int $idTypeOperation, float $montant): ?float
    {
        $tranche = $this->where('id_type_operation', $idTypeOperation)
            ->where('montant_min <=', $montant)
            ->where('montant_max >=', $montant)
            ->first();

        return $tranche ? (float) $tranche['frais'] : null; // null = hors barème
    }


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
