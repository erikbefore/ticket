<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class UF extends Model
{
    protected $table = 'uf';
    protected $primaryKey = "id";
    public $timestamps = false;

    const SIGLAS = [
        null,
        'AC',
        'AL',
        'AM',
        'AP',
        'BA',
        'CE',
        'DF',
        'ES',
        'GO',
        'MA',
        'MG',
        'MS',
        'MT',
        'PA',
        'PB',
        'PE',
        'PI',
        'PR',
        'RJ',
        'RN',
        'RO',
        'RR',
        'RS',
        'SC',
        'SE',
        'SP',
        'TO'
    ];

    const IDs = [
        'AC' => 1,
        'AL' => 2,
        'AM' => 3,
        'AP' => 4,
        'BA' => 5,
        'CE' => 6,
        'DF' => 7,
        'ES' => 8,
        'GO' => 9,
        'MA' => 10,
        'MG' => 11,
        'MS' => 12,
        'MT' => 13,
        'PA' => 14,
        'PB' => 15,
        'PE' => 16,
        'PI' => 17,
        'PR' => 18,
        'RJ' => 19,
        'RN' => 20,
        'RO' => 21,
        'RR' => 22,
        'RS' => 23,
        'SC' => 24,
        'SE' => 25,
        'SP' => 26,
        'TO' => 27
    ];

    public function findWithCache($uf_id, $minutes = 1440)
    {

        return Cache::remember($this->getTable() . ':uf_id', $minutes, function ($uf_id) {
            return $this->find($uf_id);
        });
    }
}
