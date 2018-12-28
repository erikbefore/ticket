<?php

namespace PanicHD\PanicHD\Seeds;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use PanicHD\PanicHD\Models\Status;

class BasicStatuses extends Seeder
{
	public $statuses = [		
		'Novo' => '#319df8',
		'Aberto' => '#ffbc1b',
		'Pendente Cliente' => '#df32f9',
		'Pendente Desenvolvimento' => '#df32f9',
		'Pendente Geral' => '#df32f9',
		'Resolvido' => '4bcd540'
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
