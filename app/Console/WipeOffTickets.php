<?php

namespace App\Console;

use App\Model\Attachment;
use App\Model\Comment;
use App\Model\Ticket;
use Illuminate\Console\Command;

class WipeOffTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'panichd:wipe-off-tickets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Erase all tickets and linked content from PanicHD';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tickets = Ticket::all();
		
		if ($tickets->count() == 0){
			$this->info(trans('panichd::console.wipe-off-no-tickets-message'));
			return false;
		}
		
		$this->info('');
        $this->info('*');
		$this->info('* '.trans('panichd::console.wipe-off-tickets'));
		$this->info('*');
		$this->info('');
		
		$this->info(trans('panichd::console.wipe-off-tickets-description'));
		
		$options = [
			trans('panichd::console.continue-question-yes'),
			trans('panichd::console.continue-question-no')
		];
		
		$answer = $this->choice(trans('panichd::console.continue-question'), $options, 1);
		
		if ($answer != trans('panichd::console.continue-question-yes')){
			$this->info(trans('panichd::console.command-aborted'));
			return false;
		}
		
		$this->info(trans('panichd::console.wipe-off-tickets-start'));

        foreach ($tickets as $ticket) {
			$ticket->tags()->detach();
			$ticket->delete();
		}
		
		Attachment::truncate();
		Comment::truncate();
		Ticket::truncate();
		

		$this->info(trans('panichd::console.done'));
    }
} ?>