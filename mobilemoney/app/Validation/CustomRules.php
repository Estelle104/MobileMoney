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

    /**
     * Vérifie que le code préfixe est unique parmi les tables `prefixe` et `prefixe_externe`.
     * Utilisation : 'code' => 'is_unique_prefixe_global[id_a_ignorer,table_actuelle]'
     */
    public function is_unique_prefixe_global(string $str, string $fields, array $data, &$error = null): bool
    {
        $params = explode(',', $fields);
        $ignoreId = isset($params[0]) && $params[0] !== '' && $params[0] !== '0' ? (int) $params[0] : null;
        $currentTable = $params[1] ?? 'prefixe_externe'; 

        $db = \Config\Database::connect();
        $idOperateur = session()->get('operateur_id');

        if (!$idOperateur) {
            return false;
        }
        
        // 1. Vérifier si l'opérateur actif possède déjà ce code comme préfixe INTERNE
        // Un opérateur ne peut pas configurer son propre préfixe comme "externe"
        $builderInterne = $db->table('prefixe')
                             ->where('code', $str)
                             ->where('id_operateur', $idOperateur);
                             
        if ($currentTable === 'prefixe' && $ignoreId) {
            $builderInterne->where('id !=', $ignoreId);
        }
        if ($builderInterne->countAllResults() > 0) {
            $error = 'Ce code est déjà votre préfixe interne (vous ne pouvez pas l\'ajouter comme externe).';
            return false;
        }

        // 2. Vérifier si l'opérateur actif a déjà configuré ce code comme préfixe EXTERNE
        // Un opérateur ne peut pas avoir deux fois le même préfixe externe
        $builderExterne = $db->table('prefixe_externe')
                             ->where('code', $str)
                             ->where('id_operateur', $idOperateur);
                             
        if ($currentTable === 'prefixe_externe' && $ignoreId) {
            $builderExterne->where('id !=', $ignoreId);
        }
        if ($builderExterne->countAllResults() > 0) {
            $error = 'Vous avez déjà configuré ce code comme préfixe externe.';
            return false;
        }

        return true;
    }
}