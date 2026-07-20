<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeOperation extends Model
{
    protected $table            = 'type_operation';
    protected $allowedFields    = ['libelle'];

    protected $validationRules = [
        'libelle' => 'required|max_length[50]|is_unique[type_operation.libelle,id,{id}]',
    ];

    public function findByLibelle(string $libelle): ?array
    {
        return $this->where('libelle', $libelle)->first();
    }

    public function getIdParLibelle(string $libelle): ?int
    {
        $type = $this
            ->where('libelle', $libelle)
            ->first();

        return $type ? $type['id'] : null;
    }
}
