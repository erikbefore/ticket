<?php

namespace App\Http\Controllers;


use App\Helpers\ArrayIsoToUtf8;
use App\Model\UF;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UserSearchController
{

    private $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function search(Request $request)
    {

        try {

            if(! $request->uf_id){
                return;
            }

            $UF =  UF::find($request->uf_id);

            setConnection($UF);

            $users = $this->userService->findUserByName($request);

            resetConnection();

            return response()->json(ArrayIsoToUtf8::converter($users));

        } catch (Exception $e) {
            error_log("Falha ao listar os usuários.");
            error_log($e);
            return response()->json(['codigo' => 500, 'mensagem' => 'Operação não concluída devido a um erro interno inesperado.'], 500);
        }
    }


}