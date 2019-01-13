<?php

namespace App\Model\Syscor;

class User extends \App\User
{
    protected $connection = 'syscor';
    protected $table = 'usuario';
    protected $primaryKey = 'us_id';
}