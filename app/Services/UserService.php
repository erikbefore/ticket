<?php


namespace App\Services;

use App\Helpers\Number;
use App\Model\Syscor\User as UserSyscor;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Http\Request;

class UserService
{

    private $userRepository;
    private $userRepositorySyscor;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
        $this->userRepositorySyscor = new UserRepository(new UserSyscor);
      }

    public function findById($id){
          return $this->userRepository->findById($id);
      }


    public function updateOrInsert(int $id){

        //identifica se o usuario existe no bticket
        $userBticket = $this->userRepository->findByIdSystemOrigin($id);

        if($userBticket){
            return $userBticket;
        }

        //Busca as informações do usuário no syscor
        $userSyscor = $this->userRepositorySyscor->findById($id);

        if(!$userSyscor){
            return;
        }

        $user = new User();
        $user->id_system_origin = $userSyscor->us_id;
        $user->name = utf8_encode($userSyscor->us_nome);
        $user->email = $userSyscor->us_email;
        $user->cpf = Number::trataInteiroGrande($userSyscor->us_cpf);
        $user->active =$userSyscor->us_ativo;

        return $this->userRepository->updateOrInsert($user);
    }

    public function findUserByName(Request $request){

        $name = $request->term;
        $return = [];

        if(!$name){
            return $return;
        }

        $users = $this->userRepositorySyscor->findUserByName($name);

        foreach ($users as $user) {
            $return[] = ["id" => $user->us_id, "label" => $user->us_nome];
        }

        return $return;
    }
}