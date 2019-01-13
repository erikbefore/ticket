<?php


namespace App\Helpers;


class ArrayIsoToUtf8
{

    public static function converter($d) {
        if (is_array($d)) {
            foreach ($d as $k => $v) {

                if ($k !== utf8_encode($k)) {
                    $key = $k;
                    $value = $d[$k];
                    unset($d[$k]);
                    $d[utf8_encode($key)] =  self::converter($value);
                } else {
                    $d[$k] = self::converter($v);
                }
            }

        } else if (is_string($d)) {
            return utf8_encode($d);
        }
        return $d;
    }

    public static function decodificar($d) {
        if (is_array($d)) {
            foreach ($d as $k => $v) {

                if ($k !== utf8_decode($k)) {
                    $key = $k;
                    $value = $d[$k];
                    unset($d[$k]);
                    $d[utf8_decode($key)] =  self::decodificar($value);
                } else {
                    $d[$k] = self::decodificar($v);
                }
            }

        } else if (is_string($d)) {
            return utf8_decode($d);
        }
        return $d;
    }

    static function trataUnicode($texto){

        $texto = str_replace("u00e1", "á", $texto);
        $texto = str_replace("u00e0", "à", $texto);
        $texto = str_replace("u00e2", "â", $texto);
        $texto = str_replace("u00e3", "ã", $texto);
        $texto = str_replace("u00e4", "ä", $texto);
        $texto = str_replace("u00c1", "Á", $texto);
        $texto = str_replace("u00c0", "À", $texto);
        $texto = str_replace("u00c2", "Â", $texto);
        $texto = str_replace("u00c3", "Ã", $texto);
        $texto = str_replace("u00c4", "Ä", $texto);
        $texto = str_replace("u00e9", "é", $texto);
        $texto = str_replace("u00e8", "è", $texto);
        $texto = str_replace("u00ea", "ê", $texto);
        $texto = str_replace("u00c9", "É", $texto);
        $texto = str_replace("u00c8", "È", $texto);
        $texto = str_replace("u00ca", "Ê", $texto);
        $texto = str_replace("u00cb", "Ë", $texto);
        $texto = str_replace("u00ed", "í", $texto);
        $texto = str_replace("u00ec", "ì", $texto);
        $texto = str_replace("u00ee", "î", $texto);
        $texto = str_replace("u00ef", "ï", $texto);
        $texto = str_replace("u00cd", "Í", $texto);
        $texto = str_replace("u00cc", "Ì", $texto);
        $texto = str_replace("u00ce", "Î", $texto);
        $texto = str_replace("u00cf", "Ï", $texto);
        $texto = str_replace("u00f3", "ó", $texto);
        $texto = str_replace("u00f2", "ò", $texto);
        $texto = str_replace("u00f4", "ô", $texto);
        $texto = str_replace("u00f5", "õ", $texto);
        $texto = str_replace("u00f6", "ö", $texto);
        $texto = str_replace("u00d3", "Ó", $texto);
        $texto = str_replace("u00d2", "Ò", $texto);
        $texto = str_replace("u00d4", "Ô", $texto);
        $texto = str_replace("u00d5", "Õ", $texto);
        $texto = str_replace("u00d6", "Ö", $texto);
        $texto = str_replace("u00fa", "ú", $texto);
        $texto = str_replace("u00f9", "ù", $texto);
        $texto = str_replace("u00fb", "û", $texto);
        $texto = str_replace("u00fc", "ü", $texto);
        $texto = str_replace("u00da", "Ú", $texto);
        $texto = str_replace("u00d9", "Ù", $texto);
        $texto = str_replace("u00db", "Û", $texto);
        $texto = str_replace("u00e7", "ç", $texto);
        $texto = str_replace("u00c7", "Ç", $texto);
        $texto = str_replace("u00f1", "ñ", $texto);
        $texto = str_replace("u00d1", "Ñ", $texto);
        $texto = str_replace("u0026", "&", $texto);
        $texto = str_replace("u0027", "'", $texto);

        $texto = str_replace("\\", "", $texto);

        return $texto;
    }

}