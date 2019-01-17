<?php


use App\Helpers\DBConfiguration;
use App\Model\UF;

$defaultConnectionSyscor = [
    'driver'    => 'mysql',
    'read'      => [
        'host' => env('DB_SYCOR_HOST', '127.0.0.1')
    ],
    'write'     => [
        'host' => env('DB_SYCOR_HOST', '127.0.0.1')
    ],
    'port'      => env('DB_PORT', '3306'),
    'username'  => env('DB_USERNAME', 'root'),
    'password'  => env('DB_PASSWORD', 'root'),
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
    'strict'    => true,
    'engine'    => null,
];


$versionDB = DBConfiguration::DB_VERSION;
$prefixDB = DBConfiguration::PREFIX_DB;
$ufs = UF::IDs;

$dataBaseSyscor = [];
foreach ($ufs as $uf => $uf_id){
    $uf_sigla = strtolower($uf);

    $nomeBanco = "{$prefixDB}_{$uf_sigla}";

    $nomeBanco .= $versionDB ? "_$versionDB" : '';

    $dataBaseSyscor[$nomeBanco] =  array_merge($defaultConnectionSyscor, ['database' => $nomeBanco]);
}