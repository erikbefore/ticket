<?php

use App\Model\Setting;

if (!Schema::hasTable('panichd_settings')) {
    return;
}

$main_route = Setting::grab('main_route');
$main_route_path = Setting::grab('main_route_path');
$admin_route = Setting::grab('admin_route');
$admin_route_path = Setting::grab('admin_route_path');


//include __DIR__.'/../../routes/ticket.php';
//use App\Helpers\LaravelVersion;/
//use App\Model\Setting;
//use Illuminate\Support\Facades\Route;


Route::group(['middleware' =>  ['web',  'auth']], function () use ($main_route, $main_route_path, $admin_route, $admin_route_path) {

    //Route::group(['middleware' => '', function () use ($main_route) {

    //Ticket public route
    Route::get("$main_route_path/complete", 'TicketsController@indexComplete')
        ->name("$main_route-complete");

    // Get newest tickets list
    Route::get("$main_route_path/newest", 'TicketsController@indexNewest')
        ->name("$main_route-newest")
        ->middleware('App\Http\Middleware\IsAgentMiddleware');

    Route::get("$main_route_path/data/{id?}", 'TicketsController@data')
        ->name("$main_route.data");

    // Notice list
    Route::get("$main_route_path/notices", function(){
        return view('panichd::notices.index');
    })->name("$main_route.notices");

    // Hide or show ticket to user
    Route::get("$main_route_path/hide/{value}/{ticket}", 'TicketsController@hide')->name("$main_route.hide");

    // Open Ticket create page with optional parameters set by URL
    Route::get("$main_route_path/create/{parameters?}", 'TicketsController@create')
        ->where('parameters', '(.*)')
        ->name("$main_route.create");

    // Open Ticket edit page with optional parameters set by URL
    Route::get("$main_route_path/{ticket}/edit/{parameters?}", 'TicketsController@edit')
        ->where('parameters', '(.*)')
        ->name("$main_route.edit");

    $field_name = last(explode('/', $main_route_path));
    Route::resource($main_route_path, 'TicketsController', [
        'names' => [
            'index'   => $main_route.'.index',
            'store'   => $main_route.'.store',
            'update'  => $main_route.'.update',
            'show'    => $main_route.'.show',
            'destroy' => $main_route.'.destroy'
        ],
        'parameters' => [
            $field_name => 'ticket',
        ],
    ]);

    // Attachment routes
    Route::get("$main_route_path/download-attachment/{attachment}", 'TicketsController@downloadAttachment')
        ->name("$main_route.download-attachment");

    Route::get("$main_route_path/view-attachment/{attachment}", 'TicketsController@viewAttachment')
        ->name("$main_route.view-attachment");

    //Ticket Comments public route
    $field_name = last(explode('/', "$main_route_path-comment"));

    Route::resource("$main_route_path-comment", 'CommentsController', [
        'names' => [
            'index'   => "$main_route-comment.index",
            'store'   => "$main_route-comment.store",
            'create'  => "$main_route-comment.create",
            'update'  => "$main_route-comment.update",
            'show'    => "$main_route-comment.show",
            'destroy' => "$main_route-comment.destroy",
            'edit'    => "$main_route-comment.edit",
        ],
        'parameters' => [
            $field_name => 'ticket_comment',
        ],
    ]);

    //Ticket complete route for permitted user.
    Route::patch("$main_route_path/{id}/complete", 'TicketsController@complete')
        ->name("$main_route.complete");

    //Ticket reopen route for permitted user.
    Route::get("$main_route_path/{id}/reopen", 'TicketsController@reopen')
        ->name("$main_route.reopen");
    //});

    // Returns permission_level for category_id
    Route::get("$main_route_path/permissionLevel/{category_id?}", [
        'as'   => $main_route.'-permissionLevel',
        'uses' => 'TicketsController@permissionLevel',
    ]);

    // Ticket list: Change agent for a ticket
    Route::patch("$main_route_path-change.agent", 'TicketsController@changeAgent')
        ->name("$main_route-change.agent");

    // Ticket list: Change priority for a ticket
    Route::patch("$main_route_path-change.priority", 'TicketsController@changePriority')
        ->name("$main_route-change.priority");

    Route::group(['middleware' => 'App\Http\Middleware\IsAgentMiddleware'], function () use ($main_route, $main_route_path) {

        // Send again comment (reply) notification
        Route::post("$main_route_path-notification.resend", 'NotificationsController@notificationResend')
            ->name("$main_route-notification.resend");

        //API return list of agents in particular category
        Route::get("$main_route_path/agents/list/{category_id?}/{ticket_id?}", [
            'as'   => $main_route.'agentselectlist',
            'uses' => 'TicketsController@agentSelectList',
        ]);

        // Remove all filters
        Route::get("$main_route_path/filter/removeall/{list?}", 'FiltersController@removeall')
            ->name("$main_route-filter-removeall");

        // Alter ticket filter
        Route::get("$main_route_path/filter/{filter}/{value}", 'FiltersController@manage');

        // Use single filter in specified list
        Route::get("$main_route_path/filteronly/{filter}/{value}/{list}", 'FiltersController@only')
            ->name("$main_route-filteronly");
    });

    Route::group(['middleware' => 'App\Http\Middleware\IsAdminMiddleware'], function () use ($admin_route, $admin_route_path) {
        //Ticket admin index route (ex. http://url/panichd/)
        Route::get("$admin_route_path/indicator/{indicator_period?}", [
            'as'   => $admin_route.'.dashboard.indicator',
            'uses' => 'DashboardController@index',
        ]);
        Route::get("$admin_route_path/dashboard", 'DashboardController@index')
            ->name('dashboard');

        //Ticket statuses admin routes (ex. http://url/panichd/status)
        Route::resource("$admin_route_path/status", 'StatusesController', [
            'names' => [
                'index'   => "$admin_route.status.index",
                'store'   => "$admin_route.status.store",
                'create'  => "$admin_route.status.create",
                'update'  => "$admin_route.status.update",
                'show'    => "$admin_route.status.show",
                'destroy' => "$admin_route.status.destroy",
                'edit'    => "$admin_route.status.edit",
            ],
        ]);

        //Ticket priorities admin routes (ex. http://url/panichd/priority)
        Route::resource("$admin_route_path/priority", 'PrioritiesController', [
            'names' => [
                'index'   => "$admin_route.priority.index",
                'store'   => "$admin_route.priority.store",
                'create'  => "$admin_route.priority.create",
                'update'  => "$admin_route.priority.update",
                'show'    => "$admin_route.priority.show",
                'destroy' => "$admin_route.priority.destroy",
                'edit'    => "$admin_route.priority.edit",
            ],
        ]);

        Route::post("$admin_route_path/priority/reorder", 'PrioritiesController@reorder')
            ->name("$admin_route.priority.reorder");


        //Agents management routes (ex. http://url/panichd/agent)
        Route::resource("$admin_route_path/agent", 'AgentsController', [
            'names' => [
                'index'   => "$admin_route.agent.index",
                'store'   => "$admin_route.agent.store",
                'create'  => "$admin_route.agent.create",
                'update'  => "$admin_route.agent.update",
                'show'    => "$admin_route.agent.show",
                'destroy' => "$admin_route.agent.destroy",
                'edit'    => "$admin_route.agent.edit",
            ],
        ]);

        //Agents management routes (ex. http://url/panichd/agent)
        Route::resource("$admin_route_path/category", 'CategoriesController', [
            'names' => [
                'index'   => "$admin_route.category.index",
                'store'   => "$admin_route.category.store",
                'create'  => "$admin_route.category.create",
                'update'  => "$admin_route.category.update",
                'show'    => "$admin_route.category.show",
                'destroy' => "$admin_route.category.destroy",
                'edit'    => "$admin_route.category.edit",
            ],
        ]);

        // Members management routes (ex. http://url/panichd/member)
        Route::resource("$admin_route_path/member", 'MembersController', [
            'names' => [
                'index'   => "$admin_route.member.index",
                'store'   => "$admin_route.member.store",
                'update'  => "$admin_route.member.update",
                'destroy' => "$admin_route.member.destroy",
            ],
        ]);

        //Departments management routes (ex. http://url/panichd/agent)
        Route::resource("$admin_route_path/notice", 'NoticesController', [
            'names' => [
                'index'   => "$admin_route.notice.index",
                'store'   => "$admin_route.notice.store",
                'update'  => "$admin_route.notice.update",
                'destroy' => "$admin_route.notice.destroy",
            ],
        ]);

        //Settings configuration routes (ex. http://url/panichd/configuration)
        Route::resource("$admin_route_path/configuration", 'ConfigurationsController', [
            'names' => [
                'index'   => "$admin_route.configuration.index",
                'store'   => "$admin_route.configuration.store",
                'create'  => "$admin_route.configuration.create",
                'update'  => "$admin_route.configuration.update",
                'show'    => "$admin_route.configuration.show",
                'destroy' => "$admin_route.configuration.destroy",
                'edit'    => "$admin_route.configuration.edit",
            ],
        ]);

        //Administrators configuration routes (ex. http://url/panichd/administrators)
        Route::resource("$admin_route_path/administrator", 'AdministratorsController', [
            'names' => [
                'index'   => "$admin_route.administrator.index",
                'store'   => "$admin_route.administrator.store",
                'create'  => "$admin_route.administrator.create",
                'update'  => "$admin_route.administrator.update",
                'show'    => "$admin_route.administrator.show",
                'destroy' => "$admin_route.administrator.destroy",
                'edit'    => "$admin_route.administrator.edit",
            ],
        ]);

        //Tickets demo data route (ex. http://url/panichd/demo-seeds/)
        // Route::get("$admin_route/demo-seeds", 'InstallController@demoDataSeeder');
    });
});
