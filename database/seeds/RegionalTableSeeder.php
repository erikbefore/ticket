<?php

namespace Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionalTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if (DB::table('regional')->get()->count() == 0) {

            DB::table('regional')->insert([

                [
                    'id' => 1,
                    'descricao' => 'BASE',
                    'ordem' => '3'
                ],
                [
                    'id' => 2,
                    'descricao' => 'CO',
                    'ordem' => '6'
                ],
                [
                    'id' => 3,
                    'descricao' => 'MG',
                    'ordem' => '8'
                ],
                [
                    'id' => 4,
                    'descricao' => 'NE',
                    'ordem' => '9'
                ],
                [
                    'id' => 5,
                    'descricao' => 'PRSC',
                    'ordem' => '5'
                ],
                [
                    'id' => 6,
                    'descricao' => 'RJES',
                    'ordem' => '2'
                ],
                [
                    'id' => 7,
                    'descricao' => 'SP',
                    'ordem' => '1'
                ],
                [
                    'id' => 8,
                    'descricao' => 'RS',
                    'ordem' => '4'
                ],
                [
                    'id' => 9,
                    'descricao' => 'N',
                    'ordem' => '7'
                ],
            ]);

        } else {
            echo "\e[31mTabela regional não esta vazia ";
        }
    }
}
