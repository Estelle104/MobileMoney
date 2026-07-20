<?php

namespace App\Models;

use CodeIgniter\Model;

class Client extends Model
{
    protected $table = 'client';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'numero',
        'id_prefixe',
        'solde'
    ];

    //--------------------------------------------------

    public function trouverParNumero(string $numero)
    {
        return $this->where('numero', $numero)->first();
    }

    //--------------------------------------------------

    protected function creerClient(string $numero, int $idPrefixe)
    {
        $this->insert([
            'numero'=>$numero,
            'id_prefixe'=>$idPrefixe,
            'solde'=>0.00
        ]);

        return $this->find($this->getInsertID());
    }

    //--------------------------------------------------

    public function trouverOuCreerParNumero(string $numero)
    {
        $numero = trim($numero);

        /*
         * Validation
         */

        if (!preg_match('/^[0-9]{10}$/', $numero)) {
            throw new \Exception("Format du numéro invalide.");
        }

        /*
         * Préfixe
         */

        $code = substr($numero,0,3);

        $prefixeModel = new Prefixe();

        $prefixe = $prefixeModel->trouverParCode($code);

        if (!$prefixe) {
            throw new \Exception("Préfixe inconnu.");
        }

        /*
         * Client
         */

        $client = $this->trouverParNumero($numero);

        if ($client) {
            return $client;
        }

        /*
         * Création automatique
         */

        return $this->creerClient(
            $numero,
            $prefixe['id']
        );
    }

}