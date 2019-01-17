<?php

namespace App\Http\Controllers;

use App\Model\Syscor\Modulo;
use App\Model\UF;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ModuloController extends Controller
{

    public function search(Request $request)
    {

        if(! $request->uf_id){
            return;
        }

        $UF = UF::find($request->uf_id);

        $term = utf8_decode($request->term);

        $return = [];

        if ($term) {

            setConnection($UF);

            $modulo = new Modulo;
            $modulo->setConnection(Session::get('database'));

            $data = $modulo->select(
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

            resetConnection();
        }

        foreach ($data as $modulo) {
            $return[] = ["id" => $modulo->mod_id, "label" => utf8_encode("{$modulo->me_nome} > $modulo->sub_nome > $modulo->mod_nome")];
        }



        return response()->json($return);
    }
}