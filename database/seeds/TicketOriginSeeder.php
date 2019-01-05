<?php

use Illuminate\Database\Seeder;

class TicketOriginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (DB::table('ticket_origin')->get()->count() == 0) {

            DB::table('ticket_origin')->insert([

                [
                    'id' => 1,
                    'descricao' => 'Chat'
                ],
                [
                    'id' => 2,
                    'descricao' => 'E-mail'
                ],
                [
                    'id' => 3,
                    'descricao' => 'Telefone'
                ],
                [
                    'id' => 4,
                    'descricao' => 'Whatsapp'
                ],
                [
                    'id' => 5,
                    'descricao' => 'Bticket'
                ],
                [
                    'id' => 6,
                    'descricao' => 'Outros'
                ]
            ]);
        } else {
            echo "\e[31mTabela ticket_origin não esta vazia ";
        }
    }
}
