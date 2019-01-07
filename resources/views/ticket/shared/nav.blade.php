{{--<li class="nav-item {!! $n_notices == 0 ? 'disabled' : ($tools->fullUrlIs(route($setting->grab('main_route').'.notices')) ? 'active' : '') !!}" >--}}
	{{--<a class="nav-link" href="{{ $n_notices == 0 ? '#' : route($setting->grab('main_route').'.notices') }}" title="{{ $n_notices == 0 ? trans('panichd::lang.ticket-notices-empty') : trans('panichd::lang.nav-notices-number-title', ['num' => $n_notices]) }}">{{ trans('panichd::lang.ticket-notices-title') }} <span class="badge">{{ $n_notices }}</span></a>--}}
{{--</li>--}}
<li class="nav-item {!! $tools->fullUrlIs(action('\App\Http\Controllers\TicketsController@indexNewest')) || (isset($ticket) && $ticket->isActive()) ? "active" : "" !!}">
	<a class="nav-link" href="{{ action('\App\Http\Controllers\TicketsController@indexNewest') }}" title="{{ trans('panichd::lang.nav-new-tickets-title') }}">
		<span>{{ trans('panichd::lang.nav-new-tickets') }}</span>

		<span class="badge" style="cursor: help">
			{{ App\Model\Ticket::newest()->visible()->count() }}
		</span>
	</a>
</li>

<li class="nav-item {!! $tools->fullUrlIs(action('\App\Http\Controllers\TicketsController@index')) || (isset($ticket) && $ticket->isActive()) ? "active" : "" !!}">
	<a class="nav-link" href="{{ action('\App\Http\Controllers\TicketsController@index') }}" title="{{ trans('panichd::lang.nav-active-tickets-title') }}">
		<span>{{ trans('panichd::lang.active-tickets-adjective') }}</span>

		<span class="badge" style="cursor: help">
			{{ App\Model\Ticket::active()->visible()->count() }}
		</span>
	</a>
</li>
<li class="nav-item {!! $tools->fullUrlIs(action('\App\Http\Controllers\TicketsController@indexComplete')) || (isset($ticket) && $ticket->isComplete()) ? "active" : "" !!}">
	<a class="nav-link" href="{{ action('\App\Http\Controllers\TicketsController@indexComplete') }}" title="{{ trans('panichd::lang.nav-completed-tickets-title') }}">
		<span>{{ trans('panichd::lang.complete-tickets-adjective') }}</span>

		<span class="badge" style="cursor: help">
			{{ App\Model\Ticket::visible()->completedOnYear()->count() }}
		</span>
	</a>
</li>

@if($u->isAdmin())

	<li class="nav-item dropdown {!!
		$tools->fullUrlIs(action('\App\Http\Controllers\DashboardController@index').'*') ||
		$tools->fullUrlIs(action('\App\Http\Controllers\StatusesController@index').'*') ||
		$tools->fullUrlIs(action('\App\Http\Controllers\PrioritiesController@index').'*') ||
		$tools->fullUrlIs(action('\App\Http\Controllers\AgentsController@index').'*') ||
		$tools->fullUrlIs(action('\App\Http\Controllers\MembersController@index').'*') ||
		$tools->fullUrlIs(action('\App\Http\Controllers\CategoriesController@index').'*') ||
		$tools->fullUrlIs(action('\App\Http\Controllers\NoticesController@index').'*') ||
		$tools->fullUrlIs(action('\App\Http\Controllers\ConfigurationsController@index').'*') ||
		$tools->fullUrlIs(action('\App\Http\Controllers\AdministratorsController@index').'*')
		? "active" : "" !!}">

		<a class="nav-link" class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" title="{{ trans('panichd::admin.nav-settings') }}">
			<span>{{ $setting->grab('admin_button_text') }}</span>
		</a>

		@include('panichd::shared.nav_dropdown')
	</li>
@endif