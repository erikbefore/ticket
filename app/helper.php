<?php


use App\Helpers\DBConfiguration;
use App\Model\UF;
use Illuminate\Support\Facades\Session;

if (!function_exists('setConnection')) {
    function setConnection(UF $uf)
    {

        $databaseName = DBConfiguration::PREFIX_DB ."_";
        $databaseName .= strtolower($uf->sigla);

        if(DBConfiguration::DB_VERSION){
            $databaseName .=   "_" . DBConfiguration::DB_VERSION;
        }

        $databaseName = 'syscor_mg';//para testes

        Session::put('database', $databaseName);
    }
}

if (!function_exists('resetConnection')) {
    function resetConnection()
    {
        Session::forget('database');
    }
}
