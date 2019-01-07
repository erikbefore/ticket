<?php

namespace App\Traits;

use Spatie\Permission\Traits\HasRoles;

trait RolePermission
{
    use HasRoles;

    public function getPermissionsExisting(){
        return \App\Helpers\Permission::getPermissionsExisting();
    }

    public function canTicketChangeUf(){
        return $this->hasPermissionTo(\App\Helpers\Permission::TICKET_CHANGE_UF);
    }

    public function canTicketEdit(){
        return $this->hasPermissionTo(\App\Helpers\Permission::TICKET_EDIT);
    }

    public function canTicketCreate(){
        return $this->hasPermissionTo(\App\Helpers\Permission::TICKET_CREATE);
    }

    public function canTicketChangeModule(){
        return $this->hasPermissionTo(\App\Helpers\Permission::TICKET_CHANGE_MODULO);
    }

    public function canTicketChangeOwner(){
        return $this->hasPermissionTo(\App\Helpers\Permission::TICKET_CHANGE_OWNER);
    }

    public function canTicketChangeCategory(){
        return $this->hasPermissionTo(\App\Helpers\Permission::TICKET_CHANGE_CATEGORY);
    }

    public function canTicketChangeOrigin(){
        return $this->hasPermissionTo(\App\Helpers\Permission::TICKET_CHANGE_ORIGIN);
    }

    public function canTicketChangeType(){
        return $this->hasPermissionTo(\App\Helpers\Permission::TICKET_CHANGE_ORIGIN);
    }
}