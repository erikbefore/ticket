<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TicketOrigin extends Model
{
    protected $table = 'ticket_origin';
    public $timestamps = false;

    const ORIGIN_DEFAULT_BTICKET = 5;
}

