<?php

namespace App\Providers;

use Cache;
use App\Console\DemoRollback;
use App\Console\WipeOffLists;
use App\Console\WipeOffTickets;
use Collective\Html\FormFacade as CollectiveForm;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Console\Htmlify;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\ToolsController;
use App\Helpers\LaravelVersion;
use App\Model\Setting;
use App\Model\Ticket;

class PanicHDServiceProvider extends ServiceProvider
{
	/**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!Schema::hasTable('migrations') || !Schema::hasTable('panichd_settings')) {
            return;
        }
        
		// Alias for Member model
		$loader = \Illuminate\Foundation\AliasLoader::getInstance();
		$member_model_class = 'App\Model\Member';
		if (Schema::hasTable('panichd_settings') and Setting::where('slug', 'member_model_class')->count() == 1 and Setting::grab('member_model_class') != 'default'){
			// TODO: Check Class existence before using it. Add in cache to avoid this checks in every load
			$member_model_class = Setting::grab('member_model_class');
		}
		
		$loader->alias('PanicHDMember', $member_model_class);		
		
		$this->loadTranslationsFrom(__DIR__.'/../Translations', 'panichd');

		//ok
        $this->loadViewsFrom(__DIR__.'/../../resources/views/ticket', 'panichd');
		
		$authMiddleware = LaravelVersion::authMiddleware();
//
//		Route::get('panichd', 'PanicHD\PanicHD\Controllers\InstallController@index')
//			->middleware($authMiddleware)
//			->name("panichd.install.index");
//
//		if (Request::is('panichd') || Request::is('panichd/*')){
//			Route::post('/panichd/install', [
//                'middleware' => $authMiddleware,
//                'as'         => 'panichd.install.setup',
//                'uses'       => 'PanicHD\PanicHD\Controllers\InstallController@setup',
//            ]);
//
//			Route::get('/panichd/upgrade', [
//                'middleware' => $authMiddleware,
//                'as'         => 'panichd.install.upgrade_menu',
//                'uses'       => 'PanicHD\PanicHD\Controllers\InstallController@upgrade_menu',
//            ]);
//
//			Route::post('/panichd/upgrade', [
//                'middleware' => $authMiddleware,
//                'as'         => 'panichd.install.upgrade',
//                'uses'       => 'PanicHD\PanicHD\Controllers\InstallController@upgrade',
//            ]);
//		}
//
		$this->publishes([__DIR__.'/Translations' => base_path('resources/lang/vendor/panichd')], 'panichd-lang');
		$this->publishes([__DIR__.'/Views' => base_path('resources/views/vendor/panichd')], 'panichd-views');
        $this->publishes([__DIR__.'/Public' => public_path('vendor/panichd')], 'panichd-public');
        $this->publishes([__DIR__.'/Migrations' => base_path('database/migrations')], 'panichd-db');
		
		//$installer = new InstallController();
		
        // if a migration or new setting is missing scape to the installation
        //if ($installer->isUpdated()) {
        if (true) {
            // Send the Agent User model to the view under $u
            // Send settings to views under $setting

            //cache $u
            $u = null;

            view()->composer('panichd::*', function ($view) use (&$u) {
                if (auth()->check()) {
                    if ($u === null) {
                        $u = \PanicHDMember::find(auth()->user()->id);
                    }
                    $view->with('u', $u);
                }
                $setting = new Setting();
                $view->with('setting', $setting);
            });

            // Adding HTML5 color picker to form elements
            CollectiveForm::macro('custom', function ($type, $name, $value = '#000000', $options = []) {
                $field = $this->input($type, $name, $value, $options);

                return $field;
            });

            // Passing to views the master view value from the setting file
            view()->composer('panichd::*', function ($view) {
                $tools = new ToolsController();
                $master = Setting::grab('master_template');
                $email = Setting::grab('email.template');
                $editor_enabled = Setting::grab('editor_enabled');
                $codemirror_enabled = Setting::grab('editor_html_highlighter');
                $codemirror_theme = Setting::grab('codemirror_theme');
                $view->with(compact('master', 'email', 'tools', 'editor_enabled', 'codemirror_enabled', 'codemirror_theme'));
            });

			// Include $n_notices in shared.nav and tickets.createedit templates
			view()->composer(['panichd::shared.nav', 'panichd::tickets.createedit'], function ($view) {
				$n_notices = Setting::grab('departments_notices_feature') ? Ticket::active()->notHidden()->whereIn('user_id', \PanicHDMember::find(auth()->user()->id)->getMyNoticesUsers())->count() : 0;
				$view->with(compact('n_notices'));
			});

            view()->composer(['panichd::tickets.partials.summernote', 'panichd::shared.summernote'], function ($view) {
                $editor_locale = 'pt-BR';

				// User level uses it's own summernote options if specified
				$user_editor = \PanicHDMember::find(auth()->user()->id)->currentLevel() < 2 ? Setting::grab('summernote_options_user') : 0;


				// Load summernote options
				# $editor_options = $user_editor != "0" ? $user_editor : file_get_contents(base_path(Setting::grab('summernote_options_json_file')));
				if($user_editor != "0"){
					$editor_options = $user_editor;
				}elseif(Setting::grab('summernote_options_json_file') == 'default'){
					$editor_options = file_get_contents(realpath(__DIR__).'/../JSON/summernote_init.json');
				}else{
					$editor_options = file_get_contents(base_path(Setting::grab('summernote_options_json_file')));
				}
				
                $view->with(compact('editor_locale', 'editor_options'));
            });
			
			// Notices widget
			view()->composer(['panichd::notices.widget', 'panichd::notices.index'], function ($view) {
				if (Setting::grab('departments_notices_feature')){
                    // Get available notice users list
                    if(is_null(auth()->user())){
                         $all_dept_users = \PanicHDMember::where('ticketit_department','0');
                        if (version_compare(app()->version(), '5.3.0', '>=')) {
                			$a_notice_users = $all_dept_users->pluck('id')->toArray();
                		}else{
                			$a_notice_users = $all_dept_users->lists('id')->toArray();
                		}
                    }else{
                        $a_notice_users = \PanicHDMember::find(auth()->user()->id)->getMyNoticesUsers();
                    }

                    // Get notices
					$a_notices = Ticket::active()->notHidden()->whereIn('user_id', $a_notice_users)
						->join('panichd_priorities', 'priority_id', '=', 'panichd_priorities.id')
						->select('panichd_tickets.*')
						->with('owner.department')
						->with('status')->with('tags')
						->withCount('allAttachments')
						->orderByRaw('CASE when status_id="'.Setting::grab('default_close_status_id').'" then 2 else 1 end')
						->orderByRaw('date(start_date)')
						->orderBy('panichd_priorities.magnitude', 'desc')
						->orderBy('start_date', 'asc')
						->get();
				}else{
					// Don't show notices
					$a_notices = [];
				}
				
				$n_notices = $a_notices ? $a_notices->count() : 0;
				
                $view->with(compact('a_notices', 'n_notices'));
            });
			
			// Send notification when comment is modified
			Event::listen('App\Events\CommentUpdated', function ($event) {
				$original = $event->original;
				$modified = $event->modified;
				
				$notification = new NotificationsController($modified->ticket->category);
                $notification->commentUpdate($original, $modified);
			});
			
            // Send notification when new comment is added
            Event::listen('App\Events\CommentCreated', function ($event) {
                if (Setting::grab('comment_notification')) {
					$comment = $event->comment;
                    $notification = new NotificationsController($comment->ticket->category);
                    $notification->newComment($comment, $event->request);
                }
            });
			
			Ticket::saved(function($ticket){
				Cache::forget('panichd::a_complete_ticket_year_counts');
			});
			Ticket::deleted(function($ticket){
				Cache::forget('panichd::a_complete_ticket_year_counts');
			});
			
            Event::listen('App\Events\TicketUpdated', function ($event) {
                $original = $event->original;
				$modified = $event->modified;
				
				// Send notification when ticket status is modified or ticket is closed
				if (Setting::grab('status_notification')) {
                    if(!strtotime($original->completed_at) and strtotime($modified->completed_at)) {
						
						// Notificate closed ticket
						$notification = new NotificationsController($modified->category);
                        $notification->ticketClosed($original, $modified);
                    }elseif ($original->status_id != $modified->status_id){
						
						// Notificate updated status
						$notification = new NotificationsController($modified->category);
                        $notification->ticketStatusUpdated($original, $modified);
					}
                }
				
				// Send notification when agent is modified
                if (Setting::grab('assigned_notification')) {
                    if ($original->agent->id != $modified->agent->id) {
                        $notification = new NotificationsController($modified->category);
                        $notification->ticketAgentUpdated($original, $modified);
                    }
                }

                return true;
            });

            // Send notification when ticket is created
            Event::listen('App\Events\TicketCreated', function ($event) {
                if (Setting::grab('assigned_notification')) {
                    $notification = new NotificationsController($event->ticket->category);
                    $notification->newTicket($event->ticket);
                }

                return true;
            });

            $main_route = Setting::grab('main_route');
            $main_route_path = Setting::grab('main_route_path');
            $admin_route = Setting::grab('admin_route');
            $admin_route_path = Setting::grab('admin_route_path');

			//include __DIR__.'/../../routes/ticket.php';
			
			if ($this->app->runningInConsole()) {
				$this->commands([
					DemoRollback::class,
					WipeOffLists::class,
					WipeOffTickets::class,
				]);
			}
			
        } else{
			Route::get('/tickets/{params?}', function () {
                return redirect()->route('panichd.install.index');
            })->where('params', '(.*)');
			
			Route::get('/panichd/{menu?}', function () {
                return redirect()->route('panichd.install.index');
            })->where('menu', '(.*)');
		}
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        /*
         * Register the service provider for the dependency.
         */
        $this->app->register(\Collective\Html\HtmlServiceProvider::class);
		$this->app->register(\Intervention\Image\ImageServiceProvider::class);
        $this->app->register(\Jenssegers\Date\DateServiceProvider::class);
		$this->app->register(\Mews\Purifier\PurifierServiceProvider::class);
		$this->app->register(\Yajra\Datatables\DatatablesServiceProvider::class);        
        
        /*
         * Create aliases for the dependency.
         */
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('CollectiveForm', 'Collective\Html\FormFacade');
        $loader->alias('CollectiveHtml', 'Collective\Html\HtmlFacade');
		$loader->alias('Image', 'Intervention\Image\Facades\Image');

        /*
         * Register htmlify command. Need to run this when upgrading from <=0.2.2
         */

        $this->app->singleton('command.panichd.panichd.htmlify', function ($app) {
            return new Htmlify();
        });
        $this->commands('command.panichd.panichd.htmlify');
    }
}
