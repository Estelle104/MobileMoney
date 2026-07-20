<?php
    namespace App\Models;

use CodeIgniter\Model;

class Operateur extends Model
{
    protected $table            = 'operateur';
    protected $allowedFields    = ['nom', 'email', 'mdp'];

    protected $validationRules = [
        'nom'   => 'required | min_length[2] | max_length[50] | is_unique[operateur.nom,id,{id}]',
        'email' => 'required | valid_email | max_length[100] | is_unique[operateur.email,id,{id}]',
        'mdp'   => 'required | min_length[6]',
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'Cet email est déjà utilisé par un autre opérateur.',
        ],
    ];

    public function creerOperateur(array $data): bool
    {
        if (isset($data['mdp'])) {
            $data['mdp'] = password_hash($data['mdp'], PASSWORD_DEFAULT);
        }

        return (bool) $this->insert($data);
    }

    public function verifierIdentifiants(string $email, string $mdp): ?array
    {
        $operateur = $this->where('email', $email)->first();

        if ($operateur && password_verify($mdp, $operateur['mdp'])) {
            return $operateur;
        }

        return null;
    }
}