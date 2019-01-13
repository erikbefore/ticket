<?php

namespace App\Repositories;

use App\DTO\UserDTO;
use App\User;

class UserRepository
{

    private $model;

    public function __construct(User $User)
    {
        $this->model = $User;
    }

    public function findById($id){
        return $this->model->find($id);
    }

    public function findByIdSystemOrigin($id){
        return $this->model
            ->where('id_system_origin', '=', $id)->first();
    }

    public function findUserByName(string $name){

        return $this->model->select(
            [
                "us_id",
                "us_nome"
            ])
            ->where('us_nome', 'LIKE', "%{$name}%")
            ->where('us_ativo', '=', '1')
            ->orderBy(\DB::raw("us_nome"))
            ->get();
    }

    public function updateOrInsert(User $user){

        return $user->updateOrCreate([
            'id_system_origin' => $user->id_system_origin,
        ],[
            'id_system_origin' => $user->id_system_origin,
            'name' => $user->name,
            'active' => $user->active,
            'email' => $user->email,
            'cpf' => $user->cpf,
        ]);
    }
}