<?php

namespace App\Helpers;

class Permission
{

    const TICKET_CREATE = 'Criar ticket';
    const TICKET_EDIT = 'Editar Ticket';
    const TICKET_CHANGE_UF = 'Alterar UF do ticket';
    const TICKET_CHANGE_MODULO = 'Alterar Modulo do ticket';
    const TICKET_CHANGE_OWNER = 'Alterar proprietário do ticket';
    const TICKET_CHANGE_CATEGORY = 'Alterar Categoria do ticket';
    const TICKET_CHANGE_ORIGIN = 'Alterar Origem do ticket';
    const TICKET_CHANGE_TYPE = 'Alterar Tipo do ticket';

    public static function getPermissionsExisting(){

        $refl = new \ReflectionClass('\App\Helpers\Permission');
        return $refl->getConstants();
    }
}