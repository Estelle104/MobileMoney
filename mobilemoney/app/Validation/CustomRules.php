<?php

namespace App\Validation;

class CustomRules
{
    /**
     * Vérifie que montant_max est bien supérieur à montant_min.
     * Utilisation : 'montant_max' => 'valid_montant_max[montant_min]'
     */
    public function valid_montant_max(string $str, string $field, array $data): bool
    {
        return (float) $str > (float) $data[$field];
    }
}