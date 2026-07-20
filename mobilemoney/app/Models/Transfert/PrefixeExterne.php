<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeExterne extends Model
{
    protected $table = 'prefixe_externe';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'code',
        'id_operateur_externe'
    ];

    public function trouverParCode(string $code)
    {
        return $this
            ->select('prefixe_externe.*, operateur_externe.nom as nom_operateur, operateur_externe.commission')
            ->join('operateur_externe', 'operateur_externe.id = prefixe_externe.id_operateur_externe')
            ->where('prefixe_externe.code', $code)
            ->first();
    }
}