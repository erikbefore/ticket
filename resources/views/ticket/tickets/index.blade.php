@extends($master)

@section('page')
    {{ trans('panichd::lang.index-title') }}
@stop

@include('panichd::shared.common')

@include('panichd::tickets.datatable.assets')

@if (App\Model\Ticket::count() == 0)
	@section('content')
		<div class="card bg-light">
			<div class="card-body">
				{{ trans('panichd::lang.no-tickets-yet') }}
			</div>
		</div>
	@stop
@else
	@section('content')
		@include('panichd::tickets.partials.filter_panel')
		<div class="card bg-light">
			<div class="card-body">
				<div id="message"></div>
				@include('panichd::tickets.datatable.header')
			</div>
		</div>
		@include('panichd::tickets.partials.modal_agent')
		@include('panichd::tickets.partials.priority_popover_form')

		{{--@if($ticketList == 'newest')--}}
			{{--@include('panichd::tickets.datatable.modal_page_reload')--}}
		{{--@endif--}}
	@stop

	@section('footer')
		@include('panichd::tickets.datatable.loader')
		@include('panichd::tickets.datatable.events')
	@append
@endif