<ul class="dropdown-menu">
	<a class="dropdown-item {!! $tools->fullUrlIs(action('\App\Http\Controllers\DashboardController@index')) || Request::is($setting->grab('admin_route').'/indicator*') ? 'active' : '' !!}" href="{{ action('\App\Http\Controllers\DashboardController@index') }}" title="{{ trans('panichd::admin.nav-dashboard-title') }}">
		{{ trans('panichd::admin.nav-dashboard') }}
	</a>
	
	<li class="dropdown-divider"></li>
	
	<a class="dropdown-item {!! $tools->fullUrlIs(action('\App\Http\Controllers\CategoriesController@index').'*') ? "active" : "" !!}" href="{{ action('\App\Http\Controllers\CategoriesController@index') }}">{{ trans('panichd::admin.nav-categories') }}</a>
	<a class="dropdown-item {!! $tools->fullUrlIs(action('\App\Http\Controllers\PrioritiesController@index').'*') ? "active" : "" !!}" href="{{ action('\App\Http\Controllers\PrioritiesController@index') }}">{{ trans('panichd::admin.nav-priorities') }}</a>
	<a class="dropdown-item {!! $tools->fullUrlIs(action('\App\Http\Controllers\StatusesController@index').'*') ? "active" : "" !!}" href="{{ action('\App\Http\Controllers\StatusesController@index') }}">{{ trans('panichd::admin.nav-statuses') }}</a>

	<li class="dropdown-divider"></li>
	
	<a class="dropdown-item {!! $tools->fullUrlIs(action('\App\Http\Controllers\MembersController@index').'*') ? "active" : "" !!}" href="{{ action('\App\Http\Controllers\MembersController@index') }}">{{ trans('panichd::admin.nav-members') }}</a>
	<a class="dropdown-item {!! $tools->fullUrlIs(action('\App\Http\Controllers\AgentsController@index').'*') ? "active" : "" !!}" href="{{ action('\App\Http\Controllers\AgentsController@index') }}">{{ trans('panichd::admin.nav-agents') }}</a>
	<a class="dropdown-item {!! $tools->fullUrlIs(action('\App\Http\Controllers\AdministratorsController@index').'*') ? "active" : "" !!}" href="{{ action('\App\Http\Controllers\AdministratorsController@index')}}">{{ trans('panichd::admin.nav-administrators') }}</a>

	<li class="dropdown-divider"></li>
	
	@if ($setting->grab('departments_notices_feature'))
		<a class="dropdown-item {!! $tools->fullUrlIs(action('\App\Http\Controllers\NoticesController@index').'*') ? "active" : "" !!}" href="{{ action('\App\Http\Controllers\NoticesController@index') }}">{{ trans('panichd::admin.nav-notices') }}</a>
	@endif
	<a class="dropdown-item {!! $tools->fullUrlIs(action('\App\Http\Controllers\ConfigurationsController@index').'*') ? "active" : "" !!}" href="{{ action('\App\Http\Controllers\ConfigurationsController@index') }}">{{ trans('panichd::admin.nav-configuration') }}</a>
</ul>