<?php


namespace App\Model\Syscor;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    protected $table = 'modulo';
    protected $primaryKey = 'mod_id';
    protected $connection = 'syscor_default';
}