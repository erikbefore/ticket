<?php


namespace App\Model;

use Illuminate\Database\Eloquent\Model;


class Channel extends Model
{
    protected $table = 'channel';

    const  SYSCOR   = ['id' => 1, 'name' => 'SysCor'];

}