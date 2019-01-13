<?php

namespace App\Helpers;

class Number
{

    static function trataInteiroGrande($numero){

        $numero = preg_replace("/[^0-9]/", "", $numero);

        return $numero;
    }

}