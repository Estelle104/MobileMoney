<?php

namespace App\Models;

use CodeIgniter\Model;

class Client extends Model
{
    protected $table = 'clientS';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nom', 'email', 'telephone', 'mdp'];
}