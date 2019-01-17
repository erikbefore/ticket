<?php

use Illuminate\Database\Seeder;

class UfTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (DB::table('uf')->get()->count() == 0) {

            DB::table('uf')->insert([

                [
                    'id' => 1,
                    'nome' => 'Acre',
                    'sigla' => 'AC',
                    'codigo_ibge' => '12',
                    'id_regional' => 2
                ],
                [
                    'id' => 2,
                    'nome' => 'Alagoas',
                    'sigla' => 'AL',
                    'codigo_ibge' => '27',
                    'id_regional' => 4
                ],
                [
                    'id' => 3,
                    'nome' => 'Amazonas',
                    'sigla' => 'AM',
                    'codigo_ibge' => '13',
                    'id_regional' => 9
                ],
                [
                    'id' => 4,
                    'nome' => 'Amapá',
                    'sigla' => 'AP',
                    'codigo_ibge' => '16',
                    'id_regional' => 9
                ],
                [
                    'id' => 5,
                    'nome' => 'Bahia',
                    'sigla' => 'BA',
                    'codigo_ibge' => '29',
                    'id_regional' => 1
                ],
                [
                    'id' => 6,
                    'nome' => 'Ceará',
                    'sigla' => 'CE',
                    'codigo_ibge' => '23',
                    'id_regional' => 4
                ],
                [
                    'id' => 7,
                    'nome' => 'Distrito Dederal',
                    'sigla' => 'DF',
                    'codigo_ibge' => '53',
                    'id_regional' => 2
                ],
                [
                    'id' => 8,
                    'nome' => 'Espírito Santo',
                    'sigla' => 'ES',
                    'codigo_ibge' => '32',
                    'id_regional' => 6
                ],
                [
                    'id' => 9,
                    'nome' => 'Goiás',
                    'sigla' => 'GO',
                    'codigo_ibge' => '52',
                    'id_regional' => 2
                ],

                [
                    'id' => 10,
                    'nome' => 'Maranhão',
                    'sigla' => 'MA',
                    'codigo_ibge' => '21',
                    'id_regional' => 9
                ],

                [
                    'id' => 11,
                    'nome' => 'Minas Gerais',
                    'sigla' => 'MG',
                    'codigo_ibge' => '31',
                    'id_regional' => 3
                ],
                [
                    'id' => 12,
                    'nome' => 'Mato Grosso do Sul',
                    'sigla' => 'MS',
                    'codigo_ibge' => '50',
                    'id_regional' => 2
                ],
                [
                    'id' => 13,
                    'nome' => 'Mato Grosso',
                    'sigla' => 'MT',
                    'codigo_ibge' => '51',
                    'id_regional' => 2
                ],
                [
                    'id' => 14,
                    'nome' => 'Pará',
                    'sigla' => 'PA',
                    'codigo_ibge' => '15',
                    'id_regional' => 9
                ],
                [
                    'id' => 15,
                    'nome' => 'Paraíba',
                    'sigla' => 'PB',
                    'codigo_ibge' => '25',
                    'id_regional' => 4
                ],
                [
                    'id' => 16,
                    'nome' => 'Pernanbuco',
                    'sigla' => 'PE',
                    'codigo_ibge' => '26',
                    'id_regional' => 4
                ],
                [
                    'id' => 17,
                    'nome' => 'Piauí',
                    'sigla' => 'PI',
                    'codigo_ibge' => '22',
                    'id_regional' => 4
                ],
                [
                    'id' => 18,
                    'nome' => 'Paraná',
                    'sigla' => 'PR',
                    'codigo_ibge' => '41',
                    'id_regional' => 5
                ],
                [
                    'id' => 19,
                    'nome' => 'Rio De Janeiro',
                    'sigla' => 'Rj',
                    'codigo_ibge' => '33',
                    'id_regional' => 6
                ],
                [
                    'id' => 20,
                    'nome' => 'Rio Grande do Norte',
                    'sigla' => 'RN',
                    'codigo_ibge' => '24',
                    'id_regional' => 4
                ],
                [
                    'id' => 21,
                    'nome' => 'Rondônia',
                    'sigla' => 'RO',
                    'codigo_ibge' => '11',
                    'id_regional' => 2
                ],
                [
                    'id' => 22,
                    'nome' => 'Roraima',
                    'sigla' => 'RR',
                    'codigo_ibge' => '14',
                    'id_regional' => 9
                ],
                [
                    'id' => 23,
                    'nome' => 'Rio Grande do Sul',
                    'sigla' => 'RS',
                    'codigo_ibge' => '43',
                    'id_regional' => 8
                ],
                [
                    'id' => 24,
                    'nome' => 'Santa Catarina',
                    'sigla' => 'SC',
                    'codigo_ibge' => '42',
                    'id_regional' => 5
                ],
                [
                    'id' => 25,
                    'nome' => 'Sergipe',
                    'sigla' => 'SE',
                    'codigo_ibge' => '28',
                    'id_regional' => 1
                ],
                [
                    'id' => 26,
                    'nome' => 'São Paulo',
                    'sigla' => 'SP',
                    'codigo_ibge' => '35',
                    'id_regional' => 7
                ],
                [
                    'id' => 27,
                    'nome' => 'Tocantins',
                    'sigla' => 'To',
                    'codigo_ibge' => '17',
                    'id_regional' => 2
                ],


            ]);

        }   else {
            echo "\e[31mTabela uf não esta vazia ";
        }
    }
}
