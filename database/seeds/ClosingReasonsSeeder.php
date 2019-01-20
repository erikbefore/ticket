<?php

use Illuminate\Database\Seeder;

class ClosingReasonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if (DB::table('closingreasons')->get()->count() == 0) {

            DB::table('closingreasons')->insert([

                [
                    'id' => 1,
                    'category_id' => 1,
                    'text' => 'Finalizado',
                    'status_id' => 6
                ],
                [
                    'id' => 2,
                    'category_id' => 2,
                    'text' => 'Finalizado',
                    'status_id' => 6
                ],
                [
                    'id' => 3,
                    'category_id' => 3,
                    'text' => 'Finalizado',
                    'status_id' => 6
                ],
            ]);
        } else {
            echo "\e[31mTabela closingreasons não esta vazia ";
        }
    }
}
