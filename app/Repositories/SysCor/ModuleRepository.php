<?php

namespace App\Repositories\SysCor;


use App\Model\Module;

class ModuleRepository
{

    private $module;

    public function __construct(Module $module)
    {
        $this->module = $module;
    }


    public function findDetailsModule(int $idModule){
       return $this->modulo->select(
            [
                "mod_id",
                "mod_nome",
                "sub_nome",
                "me_nome"
            ])
            ->where('mod_id', '=', $idModule)
            ->join('menu_sub AS sub', 'sub.sub_id', '=', 'modulo.sub_id')
            ->join('menu AS me', 'me.me_id', '=', 'sub.me_id')
            ->orderBy(\DB::raw(" me.me_ordem ASC, me.me_nome ASC, sub.sub_nome ASC, mod_nome "))
            ->first();
    }
}