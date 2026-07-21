<?php

namespace App\Models;

use CodeIgniter\Model;

class Operation extends Model
{
    protected $table            = 'operation';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'id_client_source',
        'id_client_destinataire',
        'id_type_operation',
        'montant',
        'frais',
        'numero_destinataire_externe',
        'id_prefixe_externe',
        'frais_retrait_inclus',
        'id_groupe_transfert',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'date_transaction';
    protected $updatedField  = '';

    protected $beforeInsert = ['validateDestinataire'];

    protected function validateDestinataire(array $data)
    {
        if (isset($data['data']['id_type_operation'])) {
            $typeModel = new TypeOperation();
            $idTransfert = $typeModel->getIdParLibelle('transfert');

            if ($data['data']['id_type_operation'] == $idTransfert) {
                $hasInterne = !empty($data['data']['id_client_destinataire']);
                $hasExterne = !empty($data['data']['numero_destinataire_externe']) && !empty($data['data']['id_prefixe_externe']);

                if ($hasInterne && $hasExterne) {
                    throw new \Exception("Un transfert ne peut pas avoir un destinataire interne et externe à la fois.");
                }
                if (!$hasInterne && !$hasExterne) {
                    throw new \Exception("Un transfert doit avoir un destinataire (interne ou externe).");
                }
            }
        }
        return $data;
    }

    protected $validationRules = [
        'id_client_source'  => 'required|integer',
        'id_type_operation' => 'required|integer',
        'montant'           => 'required|decimal',
        'frais'             => 'required|decimal',
    ];

    /**
     * Historique complet d'un client (opérations émises + reçues).
     */
    // public function getHistoriqueByClient(int $idClient): array
    // {
    //     return $this->groupStart()
    //             ->where('id_client_source', $idClient)
    //             ->orWhere('id_client_destinataire', $idClient)
    //         ->groupEnd()
    //         ->orderBy('date_transaction', 'DESC')
    //         ->findAll();
    // }

    public function getHistoriqueByClient(int $idClient): array
    {

        return $this
            ->select(
                'operation.*,
             type_operation.libelle as type_operation,
             c1.numero as numero_source,
             c2.numero as numero_destinataire'
            )

            ->join(
                'type_operation',
                'type_operation.id = operation.id_type_operation'
            )

            ->join(
                'client c1',
                'c1.id = operation.id_client_source',
                'left'
            )

            ->join(
                'client c2',
                'c2.id = operation.id_client_destinataire',
                'left'
            )

            ->groupStart()

            ->where(
                'operation.id_client_source',
                $idClient
            )

            ->orWhere(
                'operation.id_client_destinataire',
                $idClient
            )

            ->groupEnd()

            ->orderBy(
                'operation.date_transaction',
                'DESC'
            )

            ->findAll();
    }

    /**
     * Total des frais collectés par type d'opération, pour un opérateur,
     * sur une période optionnelle.
     */
    public function getTotalFraisParType(int $idOperateur, ?string $dateDebut = null, ?string $dateFin = null): array
    {
        $builder = $this->select('type_operation.libelle, SUM(operation.frais) as total_frais')
            ->join('client', 'client.id = operation.id_client_source')
            ->join('prefixe', 'prefixe.id = client.id_prefixe')
            ->join('type_operation', 'type_operation.id = operation.id_type_operation')
            ->where('prefixe.id_operateur', $idOperateur)
            ->groupBy('type_operation.libelle');

        if ($dateDebut) {
            $builder->where('operation.date_transaction >=', $dateDebut . ' 00:00:00');
        }
        if ($dateFin) {
            $builder->where('operation.date_transaction <=', $dateFin . ' 23:59:59');
        }

        return $builder->findAll();
    }

    // Total des frais collectés par type d'opération et par destination (interne/externe), pour un opérateur, sur une période optionnelle.
    public function getTotalFraisParTypeEtDestination(int $idOperateur, ?string $dateDebut = null, ?string $dateFin = null): array
    {
        $builder = $this->select("
                type_operation.libelle, 
                (CASE WHEN operation.id_prefixe_externe IS NULL THEN 'interne' ELSE 'externe' END) as destination,
                SUM(operation.frais) as total_frais
            ")
            ->join('client', 'client.id = operation.id_client_source')
            ->join('prefixe', 'prefixe.id = client.id_prefixe')
            ->join('type_operation', 'type_operation.id = operation.id_type_operation')
            ->where('prefixe.id_operateur', $idOperateur)
            ->groupBy("type_operation.libelle, (CASE WHEN operation.id_prefixe_externe IS NULL THEN 'interne' ELSE 'externe' END)");

        if ($dateDebut) {
            $builder->where('operation.date_transaction >=', $dateDebut . ' 00:00:00');
        }
        if ($dateFin) {
            $builder->where('operation.date_transaction <=', $dateFin . ' 23:59:59');
        }

        return $builder->findAll();
    }

    public function getMontantsParOperateurExterne(int $idOperateur, ?string $dateDebut = null, ?string $dateFin = null): array
    {
        $builder = $this->select('prefixe_externe.nom_operateur_externe, SUM(operation.montant) as total_montant')
            ->join('client as source', 'source.id = operation.id_client_source')
            ->join('prefixe as p_source', 'p_source.id = source.id_prefixe')
            ->join('prefixe_externe', 'prefixe_externe.id = operation.id_prefixe_externe')
            ->where('p_source.id_operateur', $idOperateur)
            ->where('operation.id_prefixe_externe IS NOT NULL')
            ->groupBy('prefixe_externe.nom_operateur_externe');

        if ($dateDebut) {
            $builder->where('operation.date_transaction >=', $dateDebut . ' 00:00:00');
        }
        if ($dateFin) {
            $builder->where('operation.date_transaction <=', $dateFin . ' 23:59:59');
        }

        return $builder->findAll();
    }

    public function getGainsOperateursExternes($operateurId, $dateDebut = null, $dateFin = null)
    {
        $builder = $this->db->table('operation o');

        $builder->select([
            'pe.nom_operateur_externe AS operateur_externe',
            't.libelle',
            'SUM(o.frais) AS total_frais'
        ]);

        $builder->join('prefixe_externe pe', 'pe.id = o.id_prefixe_externe');
        $builder->join('type_operation t', 't.id = o.id_type_operation');

        // seulement les préfixes appartenant à l'opérateur connecté
        $builder->where('pe.id_operateur', $operateurId);

        // uniquement les opérations externes
        $builder->where('o.id_prefixe_externe IS NOT NULL', null, false);

        if ($dateDebut) {
            $builder->where('DATE(o.date_transaction) >=', $dateDebut);
        }

        if ($dateFin) {
            $builder->where('DATE(o.date_transaction) <=', $dateFin);
        }

        $builder->groupBy([
            'pe.nom_operateur_externe',
            't.libelle'
        ]);

        return $builder->get()->getResultArray();
    }
}
