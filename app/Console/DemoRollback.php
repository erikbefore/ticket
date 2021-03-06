<?php

namespace App\Console;

use App\Model\Attachment;
use App\Model\Category;
use App\Model\Closingreason;
use App\Model\Comment;
use App\Model\Tag;
use App\Model\Ticket;
use Illuminate\Console\Command;

class DemoRollback extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'panichd:demo-rollback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Undo all the elements created by DemoDataSeeder';

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
		$this->info('');
        $this->info('*');
		$this->info('* '.trans('panichd::console.demo-rollback'));
		$this->info('*');
		$this->info('');

		$this->info(trans('panichd::console.demo-rollback-description'));
		$this->info(' - '.trans('panichd::console.demo-rollback-info-categories'));
		$this->info(' - '.trans('panichd::console.demo-rollback-info-users'));
		$this->info('');
		$this->info(trans('panichd::console.demo-rollback-kept'));
		$this->info(' - '.trans('panichd::console.demo-rollback-priorities'));
		$this->info(' - '.trans('panichd::console.demo-rollback-statuses'));
		
		$options = [
			trans('panichd::console.continue-question-yes'),
			trans('panichd::console.continue-question-no')
		];
		
		$answer = $this->choice(trans('panichd::console.continue-question'), $options, 1);
		
		if ($answer != trans('panichd::console.continue-question-yes')){
			$this->info(trans('panichd::console.command-aborted'));
			return false;
		}
		
		// Delete demo categories and tickets
		$o_categories = Category::where('name', 'like', '_Demo_%');
		if ($o_categories->count() == 0){
			$this->info(trans('panichd::console.demo-categories-not-found'));
		}else{
			foreach ($o_categories->get() as $category){
				$category->delete();
			}
			
			if (Category::count() == 0){
				Category::truncate();
				Closingreason::truncate();
				Tag::truncate();
				
				// Active agents deletion
				foreach ( \PanicHDMember::agents()->get() as $member){
					$member->panichd_agent = 0;
					$member->save();
				}
			}

			if (Ticket::count() == 0){
				Attachment::truncate();
				Comment::truncate();
				Ticket::truncate();
			}
		}
		
		// Delete demo users
		$o_members = \PanicHDMember::where('email', 'like', '%@demodataseeder.com');
		if ($o_members->count() == 0){
			$this->info(trans('panichd::console.demo-users-not-found'));
		}else{
			foreach ($o_members->get() as $member){
				$member->delete();
			}
			
			if ( \PanicHDMember::count() == 0){
				PanicHDMember::truncate();
			}
		}

		$this->info(trans('panichd::console.done'));
    }
} ?>