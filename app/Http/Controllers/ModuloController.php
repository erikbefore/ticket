<?php

namespace App\Http\Controllers;

use App\Model\Syscor\Modulo;
use DB;
use Illuminate\Http\Request;

class ModuloController extends Controller
{

    public function search(Request $request)
    {
        $term = $request->term;
        $return = [];

        if ($term) {

            $data = Modulo::select(
                [
                    "mod_id",
                    "mod_nome",
                    "sub_nome",
                    "me_nome"
                ])
                ->where('mod_nome', 'LIKE', "%{$term}%")
                ->join('menu_sub AS sub', 'sub.sub_id', '=', 'modulo.sub_id')
                ->join('menu AS me', 'me.me_id', '=', 'sub.me_id')
                ->orderBy(\DB::raw(" me.me_ordem ASC, me.me_nome ASC, sub.sub_nome ASC, mod_nome "))
                ->get();
        }

        foreach ($data as $modulo) {
            $return[] = ["id" => $modulo->mod_id, "label" => utf8_encode("{$modulo->me_nome} > $modulo->sub_nome > $modulo->mod_nome")];
        }

        return response()->json($return);
    }
}