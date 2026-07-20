<?php

namespace App\Models;

use CodeIgniter\Model;

class ReglementExterneModel extends Model
{
    protected $table            = 'reglement_externe';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'id_operateur',
        'nom_operateur_externe',
        'montant',
        'date_reglement'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getTotalReglePar(string $nomOperateurExterne, int $idOperateur): float
    {
        $result = $this->selectSum('montant')
                       ->where('nom_operateur_externe', $nomOperateurExterne)
                       ->where('id_operateur', $idOperateur)
                       ->first();
        
        return $result && $result['montant'] ? (float) $result['montant'] : 0.0;
    }
}
