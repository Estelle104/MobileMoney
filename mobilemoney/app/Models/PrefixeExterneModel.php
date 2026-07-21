<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeExterneModel extends Model
{
    protected $table            = 'prefixe_externe';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'code',
        'nom_operateur_externe',
        'pourcentage_commission',
        'id_operateur'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'code'                   => 'required|exact_length[3]|numeric|is_unique_prefixe_global[{id},prefixe_externe]',
        'nom_operateur_externe'  => 'required|max_length[100]',
        'pourcentage_commission' => 'required|numeric|greater_than_equal_to[0]',
        'id_operateur'           => 'required|integer',
    ];

    protected $validationMessages = [
        'code' => [
            'exact_length' => 'Le préfixe doit contenir exactement 3 chiffres.',
            'numeric'      => 'Le préfixe ne doit contenir que des chiffres.',
        ],
        'pourcentage_commission' => [
            'greater_than_equal_to' => 'Le pourcentage de commission doit être supérieur ou égal à 0.',
        ]
    ];

    public function findAllByOperateur(int $idOperateur): array
    {
        return $this->where('id_operateur', $idOperateur)->findAll();
    }

    public function estPrefixeExterne(string $code)
    {
        return $this->where('code', $code)->first() ?? false;
    }

    public function appartientAOperateur(int $idPrefixeExterne, int $idOperateur): bool
    {
        return $this->where('id', $idPrefixeExterne)
            ->where('id_operateur', $idOperateur)
            ->first() !== null;
    }

    public function peutEtreSupprime(int $idPrefixeExterne): bool
    {
        return true;
    }
}
