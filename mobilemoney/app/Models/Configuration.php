<?php

namespace App\Models;

use CodeIgniter\Model;

class Configuration extends Model
{
    protected $table            = 'configuration';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'pourcentage',
    ];

    public function getPourcentage(){
        return $this->select('pourcentage')->findById('id',1);
    }
}
