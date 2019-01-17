<?php

namespace App\Services;


use App\Model\Module;
use App\Repositories\ModuleRepository;

class ModuleService
{

    private $moduleRepository;

    public function __construct(ModuleRepository $moduleRepository) {
        $this->moduleRepository = $moduleRepository;
    }

    public function updateOrCreate(int $id){

        $moduleBticket = $this->moduleRepository->findByIdSystemOrigin($id);

        if($moduleBticket){
            return $moduleBticket;
        }

        $moduleSyscor = (new \App\Repositories\SysCor\ModuleRepository())->findDetailsModule($id);

        if(!$moduleSyscor){
            return;
        }

        $moduleSyscor = new Module();
        $moduleSyscor->id_system_origin = $moduleSyscor->us_id;
        $moduleSyscor->name = utf8_encode($moduleSyscor->us_nome);
        $moduleSyscor->menu = $moduleSyscor->us_email;
        $moduleSyscor->menu_sub = $moduleSyscor->us_ativo;

        return $this->moduleRepository->updateOrInsert($moduleSyscor);
    }
}