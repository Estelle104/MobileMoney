<?php

// boot CodeIgniter
define('FCPATH', __DIR__ . '/public/');
require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . '/bootstrap.php';
require_once SYSTEMPATH . 'Config/DotEnv.php';
(new CodeIgniter\Config\DotEnv(ROOTPATH))->load();

$db = \Config\Database::connect();
$builder = $db->table('operation')
    ->select('type_operation.libelle, SUM(operation.frais) as total_frais')
    ->join('client', 'client.id = operation.id_client_source')
    ->join('prefixe', 'prefixe.id = client.id_prefixe')
    ->join('type_operation', 'type_operation.id = operation.id_type_operation')
    ->where('prefixe.id_operateur', 1)
    ->groupBy('type_operation.libelle');

$result = $builder->get()->getResultArray();
print_r($result);
