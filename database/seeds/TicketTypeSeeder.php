<?php

use Illuminate\Database\Seeder;

class TicketTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (DB::table('ticket_type')->get()->count() == 0) {

            DB::table('ticket_type')->insert([

                [
                    'id' => 1,
                    'descricao' => 'Dúvida sistêmica'
                ],
                [
                    'id' => 2,
                    'descricao' => 'Erro sistêmico'
                ],
                [
                    'id' => 3,
                    'descricao' => 'Intermitência parceiro'
                ],
                [
                    'id' => 4,
                    'descricao' => 'Solicitações Vivo'
                ],
                [
                    'id' => 5,
                    'descricao' => 'Outros'
                ],
            ]);
        } else {
            echo "\e[31mTabela ticket_type não esta vazia ";
        }
    }
}
