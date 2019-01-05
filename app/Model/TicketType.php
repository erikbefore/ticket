<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    protected $table = 'ticket_type';
    public $timestamps = false;

    const TYPE_DEFAULT_BTICKET = 1;
}

