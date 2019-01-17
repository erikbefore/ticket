<?php

namespace App\Repositories;

use App\Model\Module;

class ModuleRepository
{
    private $module;

    public function __construct(Module $module)
    {
        $this->module = $module;
    }

    public function findByIdSystemOrigin($id){
        return $this->module
            ->where('id_system_origin', '=', $id)->first();
    }

    public function updateOrCreate(Module $module){

        return $module->updateOrCreate([
            'id_system_origin' => $module->id_system_origin,
        ],[
            'id_system_origin' => $module->id_system_origin,
            'menu' => $module->menu,
            'menu_sub' => $module->menu_sub,
            'name' => $module->name
        ]);
    }
}