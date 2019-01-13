<?php

namespace App\Http\Controllers;


use App\Helpers\ArrayIsoToUtf8;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserSearchController
{

    private $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function search(Request $request)
    {

        try {

            $users = $this->userService->findUserByName($request);

            return response()->json(ArrayIsoToUtf8::converter($users));

        } catch (Exception $e) {
            error_log("Falha ao listar os usuários.");
            error_log($e);
            return response()->json(['codigo' => 500, 'mensagem' => 'Operação não concluída devido a um erro interno inesperado.'], 500);
        }
    }


}