<?php

use Illuminate\Database\Seeder;

class ChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (DB::table('channel')->get()->count() == 0) {

            DB::table('channel')->insert([
                [
                    'id' => 1,
                    'name' => 'SysCor'
                ],
                [
                    'id' => 2,
                    'name' => 'Varejo'
                ],
                [
                    'id' => 3,
                    'name' => 'PaP'
                ],
                [
                    'id' => 4,
                    'name' => 'Vivo Vendas'
                ],
            ]);
        } else {
            echo "\e[31mTabela channel não esta vazia ";
        }
    }
}
