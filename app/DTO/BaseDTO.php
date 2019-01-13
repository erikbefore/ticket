<?php

namespace App\DTO;

abstract class BaseDTO {

    public function populateFromJson($jsonData) {
        foreach ($jsonData as $key => $value) {
            $this->$key = $value;
        }
    }

    public function populateFromJsonWithEncondingUTF8($jsonData) {
        foreach ($jsonData as $key => $value) {

            if(trim($value) === ""){
                $this->$key = NULL;
            }

            $this->$key = utf8_decode($value);
        }
    }

}