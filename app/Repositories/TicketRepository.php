<?php

namespace App\Repositories;

use App\Model\Ticket;

class TicketRepository
{
    private $ticket;

    public function __construct(Ticket $ticket){
        $this->ticket = $ticket;
    }

    public function findActiveByTipoAndCanal($tipo,$canal) {

        $motivos = $this->model
            ->where('ativo', 1)
            ->where('tip', $tipo)
            ->where('can_id', $canal)
            ->get();

        return $motivos;
    }

}