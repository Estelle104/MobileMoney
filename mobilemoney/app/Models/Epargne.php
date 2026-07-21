<?php

namespace App\Models;

use CodeIgniter\Model;

class Epargne extends Model
{
    protected $table            = 'epargne';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'id_client',
        'pourcentage',
        'created_at',
    ];

    public function pct_client($id)
    {
        return $this->where('id', $id)->first();
    }
}