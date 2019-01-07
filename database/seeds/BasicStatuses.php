<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use App\Model\Status;

class BasicStatuses extends Seeder
{
	public $statuses = [		
		'Novo' => '#319df8',
		'Em andamento' => '#ffbc1b',
		'Pendente Cliente' => '#df32f9',
		'Em Desenvolvimento' => '#df32f9',
		'Pendente Parceiro' => '#df32f9',
		'Finalizado' => '4bcd540'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		Model::unguard();

		// Create statuses
        foreach ($this->statuses as $name => $color) {
            $status = Status::firstOrNew(['name'  => $name]);
			$status->color = $color;
			$status->save();
        }
    }
}
