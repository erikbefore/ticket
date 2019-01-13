<?php

namespace App\Services;


use App\Repositories\TicketRepository;

class TicketService
{

    private $ticketRepository;

    public function __construct(TicketRepository $ticketRepository) {
        $this->ticketRepository = $ticketRepository;
    }

    public function getTipoMotivos($filtros){

        return $this->ticketRepository->findActiveByTipoAndCanal($filtros['tipo'],$filtros['canal']);
    }
}