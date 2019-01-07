@extends($master)
@section('page', trans('panichd::lang.create-ticket-title'))

@include('panichd::shared.common')

@section('content')

	<div class="card bg-light">
		<div class="card-body">
      		{!! CollectiveForm::model($ticket, [
				 'route' => [$setting->grab('main_route').'.update', $ticket->id],
				 'method' => 'PATCH',
				 'id' => 'ticket_form',
				 'enctype' => 'multipart/form-data'
			 ]) !!}

            <legend>{!! trans('panichd::lang.edit-ticket') . ' #'.$ticket->id !!}</legend>
			<div class="form-row">

			<!-- UF -->
			@if ($u->canTicketChangeUf())
				<div class="form-group col-md-1">
					<label> *UF:</label>
					<select name="uf" class="form-control">
							<option value="">Escolha a UF</option>
							@foreach ($ufs as $keyUfId => $uf)
								<option value="{{$keyUfId}}" {{ isset($ticket) && $ticket->id_uf == $keyUfId ? 'selected="selected"' : '' }}    >{{ $uf}} </option>
							@endforeach
					</select>
				</div>
			@endif

			<!-- Modulos -->
			@if ($u->canTicketChangeModule())
				<div class="form-group col-md-2" >
					<label> *Módulo:</label>
					<select name="modulo" class=" form-control" >
							<option value="">Escolha a Módulo</option>
							@foreach ($modulos as $keyModId => $modulo)
								<option value="{{$keyModId}}">{{ $modulo}} </option>
							@endforeach
						</select>
				</div>
			@endif

			<div class="form-group col-md-2"><!-- SUBJECT -->
                {!! CollectiveForm::label('subject', '*' . trans('panichd::lang.subject') . trans('panichd::lang.colon')) !!}
                {!! CollectiveForm::text('subject',  $ticket->subject , ['class' => 'form-control', 'required' => 'required', 'placeholder' => trans('panichd::lang.create-ticket-brief-issue')]) !!}
			</div>

			<!-- OWNER -->
			@if ($u->canTicketChangeOwner())
			<div class="form-group col-md-3" >
				<label for="owner_id" title="{{ trans('panichd::lang.create-ticket-owner-help') }}"> *{{trans('panichd::lang.owner')}}{{trans('panichd::lang.colon')}} <span class="fa fa-question-circle" style="color: #bbb"></span></label>
				<select name="owner_id" class="form-control" >
					@foreach ($a_owners as $owner)
						<option value="{{ $owner->id }}" {{ $owner->id == $a_current['owner_id'] ? 'selected="selected"' : '' }}>{{ $owner->name . ($owner->email == "" ? ' ' . trans('panichd::lang.ticket-owner-no-email') : ' - ' . $owner->email) }}
						@if ($setting->grab('departments_notices_feature'))
							@if ($owner->ticketit_department == '0')
								{{ ' - ' . trans('panichd::lang.create-ticket-notices') . ' ' . trans('panichd::lang.all-depts')}}
							@elseif ($owner->ticketit_department != "")
								{{ ' - ' . trans('panichd::lang.create-ticket-notices') . ' ' . $owner->userDepartment->getFullName() }}
							@endif
						@endif
						</option>
					@endforeach
					</select>
            </div>
			@endif

			@if ($u->currentLevel() > 1)

				<div class="form-group col-md-3">
					<label class="tooltip-info" title="{{ trans('panichd::lang.create-ticket-visible-help') }}">{{ trans('panichd::lang.create-ticket-visible') . trans('panichd::lang.colon') }} <span class="fa fa-question-circle" style="color: #bbb"></span></label>
					{{--<div class="form-control">--}}
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" name="hidden" class="custom-control-input" value="false" {{ !$ticket->hidden ? 'checked' : '' }}>
						<label class="custom-control-label" for="customRadioInline1">{{ trans('panichd::lang.yes') }}</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" name="hidden" class="custom-control-input" value="true" {{ ($ticket->hidden) ? 'checked' : ''}}>
						<label class="custom-control-label" for="customRadioInline2">{{ trans('panichd::lang.no') }}</label>
					</div>q
					{{--</div>--}}

					{{--<div class="form-check form-check-inline">--}}
							{{--<label class="form-check-label">--}}
								{{--<input type="radio" name="hidden" value="false" {{ !$ticket->hidden ? 'checked' : '' }}> {{ trans('panichd::lang.yes') }}--}}
							{{--</label>--}}
						{{--</div><div class="form-check form-check-inline">--}}
							{{--<label class="form-check-label">--}}

							{{--</label>--}}
						{{--</div>--}}
				</div>

				<div class="form-group col-md-3" ><!-- TICKET LIST -->
					{!! CollectiveForm::label('complete', trans('panichd::lang.list') . trans('panichd::lang.colon'), [
						'title' => trans('panichd::lang.create-ticket-change-list'),
						'id' => 'last_list',
						'data-last_list_default_status_id' => $a_current['status_id']
					]) !!}
					<div class="col-lg-9">
                        <?php
							if ($setting->grab('use_default_status_id')){
							    $a_lists['newest'] = [
                                    'complete' => 'no',
                                    'default_status_id' => $setting->grab('default_status_id')
                                ];
							}

							$a_lists['active'] = [
								'complete' => 'no',
								'default_status_id' => $setting->grab('default_reopen_status_id')
							];
							$a_lists['complete'] = [
								'complete' => 'yes',
								'default_status_id' => $setting->grab('default_close_status_id')
							];

							if ($a_current['complete'] == "yes"){
								$checked_list = 'complete';
							}elseif($a_current['status_id'] == $setting->grab('default_status_id')){
								if ($setting->grab('use_default_status_id')){
                                    $checked_list = 'newest';
								}else{
                                    $checked_list = 'active';
								}
							}else{
								$checked_list = 'active';
							}
                        ?>

						@foreach ($a_lists as $list => $a_list)
							<div class="form-check form-check-inline">
								<label class="form-check-label">
									<input type="radio" id="radio_{{ $list }}_list" class="jquery_ticket_list form-check-input" name="complete" value="{{ $a_list['complete'] }}" @if($list == $checked_list) {!! 'checked="checked"' !!}@endif data-list="{{ $list }}" data-default_status_id="{{ $a_list['default_status_id'] }}">{{ trans('panichd::lang.' . $list . '-tickets-adjective') }}
								</label>
							</div>
						@endforeach

					</div>
				</div>

				<div class="form-group col-md-3 "><!-- STATUS -->
					{!! CollectiveForm::label('status_id', trans('panichd::lang.status') . trans('panichd::lang.colon')) !!}
					{!! CollectiveForm::select('status_id', $status_lists, $a_current['status_id'], ['id' => 'select_status', 'class' => 'form-control']) !!}

				</div>
				<div class="form-group col-md-3 "><!-- PRIORITY -->
					{!! CollectiveForm::label('priority', trans('panichd::lang.priority') . trans('panichd::lang.colon')) !!}
					{!! CollectiveForm::select('priority_id', $priorities, $a_current['priority_id'], ['class' => 'form-control', 'required' => 'required']) !!}
				</div>

				<div class="form-group col-md-5 ">
					{!! CollectiveForm::label('start_date', trans('panichd::lang.start-date') . trans('panichd::lang.colon'), ['class' => 'col-lg-3 ']) !!}
					<div class="col-lg-9">
						<div class="input-group date" id="start_date">
							<input type="text" class="form-control" name="start_date" value="{{ $a_current['start_date'] }}"/>
							<span class="input-group-addon" style="display: none"></span>
							<span class="input-group-append">
								<button class="btn btn-light btn-default"><span class="fa fa-calendar"></span></button>
							</span>
						</div>
						<div class="jquery_error_text"></div>
					</div>
				</div>
				<div class="form-group col-md-5 " >
					{!! CollectiveForm::label('limit_date', trans('panichd::lang.limit-date') . trans('panichd::lang.colon'), ['class' => 'col-lg-3 ']) !!}
					<div class="col-lg-9">
						<div class="input-group date" id="limit_date">
							<input type="text" class="form-control" name="limit_date"  value="{{ $a_current['limit_date'] }}"/>
							<span class="input-group-addon" style="display: none"></span>
							<span class="input-group-append">
								<button class="btn btn-light btn-default"><span class="fa fa-calendar"></span></button>
							</span>
						</div>
						<div class="jquery_error_text"></div>
					</div>
				</div>
			</div>
			@endif

			<!-- CATEGORY -->
			@if ($u->canTicketChangeCategory())
			<div class="form-group col-md-5 ">
				{!! CollectiveForm::label('category_id', '*' . trans('panichd::lang.category') . trans('panichd::lang.colon')) !!}
				{!! CollectiveForm::select('category_id', $categories, $a_current['cat_id'], ['id'=>($u->currentLevel() > 1 ? 'category_change' : 'category_id'), 'class' => 'form-control', 'required' => 'required']) !!}
			</div>
			@endif

			<!-- ORIGIN -->
			@if ($u->canTicketChangeOrigin())
				<div class="form-group col-md-5">
					<label>*Origem:</label>
						<select name="origin" class=" form-control" >
							<option value="">Escolha a origem</option>
							@foreach ($origins as $origin)
								<option value="{{$origin->id}}" {{ isset($ticket) && $ticket->origin_id == $origin->id ? 'selected="selected"' : '' }}    >{{ $origin->descricao}} </option>
							@endforeach
						</select>
				</div>
			@endif

			<!-- TYPE -->
			@if ($u->canTicketChangeType())
				<div class="form-group col-md-2" >
					<label> *Tipo Suporte:</label>
						<select name="type" class=" form-control" >
							<option value="">Escolha</option>
							@foreach ($types as $type)
								<option value="{{$type->id}}" {{ isset($ticket) && $ticket->type_id == $type->id ? 'selected="selected"' : '' }}    >{{ $type->descricao}} </option>
							@endforeach
						</select>
				</div>
			@endif

			@if ($u->currentLevel() > 1)
				<div class="form-group col-md-3 "><!-- AGENT -->
					{!! CollectiveForm::label('agent_id', trans('panichd::lang.agent') . trans('panichd::lang.colon')) !!}
						{!! CollectiveForm::select(
							'agent_id',
							$agent_lists,
							$a_current['agent_id'],
							['class' => 'form-control']) !!}
				</div>

				@if ($tag_lists->count() > 0)
					<div class="form-group col-md-2"><!-- TAGS -->
						<label>{{ trans('panichd::lang.tags') . trans('panichd::lang.colon') }}</label>
						<div id="jquery_select2_container" class="col-lg-9">
						@include('panichd::tickets.partials.tags_menu')
						</div>
					</div>
				@endif
			@else
				{!! CollectiveForm::hidden('agent_id', 'auto') !!}
			@endif

            <div class="form-group row"><!-- DESCRIPTION -->
                <label for="content" class="col-lg-2  tooltip-info" title="{{ trans('panichd::lang.create-ticket-describe-issue') }}"> *{{trans('panichd::lang.description')}}{{trans('panichd::lang.colon')}} <span class="fa fa-question-circle" style="color: #bbb"></span></label>
                <div class="col-lg-10 summernote-text-wrapper">
                <textarea class="form-control summernote-editor" style="display: none" rows="5" name="content" cols="50">{!! $a_current['description'] !!}</textarea>
				<div class="jquery_error_text"></div>
                </div>
            </div>

			@if ($u->currentLevel() > 1)
				<div >
					<div class="form-group row"><!-- INTERVENTION -->
						<label for="intervention" class="col-lg-2  tooltip-info" title="{{ trans('panichd::lang.create-ticket-intervention-help') }}">{{ trans('panichd::lang.intervention') . trans('panichd::lang.colon') }} <span class="fa fa-question-circle" style="color: #bbb"></span></label>
						<div class="col-lg-10 summernote-text-wrapper">
						<textarea class="form-control summernote-editor" style="display: none" rows="5" name="intervention" cols="50">{!! $a_current['intervention'] !!}</textarea>
						</div>
					</div>
				</div>
			@endif

			@if ($setting->grab('ticket_attachments_feature'))
					<div class="form-group row">
						{!! CollectiveForm::label('attachments', trans('panichd::lang.attachments') . trans('panichd::lang.colon'), [
							'class' => 'col-lg-2 '
						]) !!}
						<div class="col-lg-10">
							@include('panichd::shared.attach_files_button', ['attach_id' => 'ticket_attached'])
							@include('panichd::shared.attach_files_script')
							<div id="ticket_attached" class="panel-group grouped_check_list deletion_list attached_list" data-new-attachment-modal-id="modal-attachment-edit">
							@if (isset($ticket))
								@foreach($ticket->attachments as $attachment)
									@include('panichd::tickets.partials.attachment', ['template'=>'createedit'])
								@endforeach
							@endif
							</div>
						</div>
					</div>
			@endif

            <div class="text-center"><!-- SUBMIT BUTTON -->
                {!! CollectiveForm::submit(isset($ticket) ? trans('panichd::lang.update') : trans('panichd::lang.btn-add'), [
						'class' => 'btn btn-primary ajax_form_submit',
						'data-errors_div' => 'form_errors'
					]) !!}
            </div>
			</div>
        {!! CollectiveForm::close() !!}
    </div></div>
@endsection

@include('panichd::tickets.partials.modal_attachment_edit')
@include('panichd::shared.photoswipe_files')
@include('panichd::shared.datetimepicker')
@include('panichd::shared.jcrop_files')
@include('panichd::tickets.partials.summernote')

@section('footer')
    <script type="text/javascript">
	// PhotoSwipe items array (load before jQuery .pwsp_gallery_link click selector)
	var pswpItems = [
		@if(isset($ticket))
		@foreach($ticket->attachments()->images()->get() as $attachment)
			@if($attachment->image_sizes != "")
				<?php
					$sizes = explode('x', $attachment->image_sizes);
				?>
				{
					src: '{{ URL::route($setting->grab('main_route').'.view-attachment', [$attachment->id]) }}',
					w: {{ $sizes[0] }},
					h: {{ $sizes[1] }},
					pid: {{ $attachment->id }},
					title: '{{ $attachment->new_filename  . ($attachment->description == "" ? '' : trans('panichd::lang.colon').$attachment->description) }}'
				},
			@endif
		@endforeach
		@endif
	];

	var category_id=<?=$a_current['cat_id'];?>;

	$(function(){
        // Change in List affects current status
	    $('.jquery_ticket_list').click(function(){
            var new_status = "";

            if ($(this).data('default_status_id') != ""){
                if ($(this).data('list') == 'newest'){
					new_status = $(this).data('default_status_id');
                }else if($('#last_list').data('last_list_default_status_id') == $('#select_status').val()){
                    new_status = $(this).data('default_status_id');
                }

                if (new_status != ""){
                    $('#select_status').val(new_status).effect('highlight');
				}

                $('#last_list').data('last_list_default_status_id', $(this).data('default_status_id'));
			}
        });

	    @if($setting->grab('use_default_status_id'))
			// Change in status affects the List only changing from or to default_status_id
			$('#select_status').change(function(){
				if ($(this).val() == '{{ $setting->grab('default_status_id') }}'){
					if (!$('#radio_newest_list').is(':checked')) $('#radio_newest_list').prop('checked', true).parent().effect('highlight');
				}else{
					if ($('#radio_newest_list').is(':checked')) $('#radio_active_list').prop('checked', true).parent().effect('highlight');
				}
			});
		@endif

	    // Category select with $u->maxLevel() > 1 only
		$('#category_change').change(function(){
			// Update agent list
			$('#agent_id').prop('disabled',true);
			var loadpage = "{!! route($setting->grab('main_route').'agentselectlist') !!}/" + $(this).val() + "/"+$('#agent_id').val();
			$('#agent_id').load(loadpage, function(){
				$('#agent_id').prop('disabled',false);
			});


			@if ($u->currentLevel() > 1)
				// Get permission level for chosen category
				$.get("{!! route($setting->grab('main_route').'-permissionLevel') !!}/" + $(this).val(),{},function(resp,status){
					if (resp > 1){
						$('.jquery_level2_class').each(function(elem){
							$(this).addClass($(this).attr('data-class'));
						});
						$('.jquery_level2_show').show();

					}else{
						$('.jquery_level2_class').each(function(elem){
							$(this).attr('class','jquery_level2_class');
						});
						$('.jquery_level2_show').hide();
					}

					var other = resp == 1 ? 2 : 1;
					$('.level_class').each(function(){
						$(this).removeClass($(this).attr('data-level-'+other+'-class'));
						$(this).addClass($(this).attr('data-level-'+resp+'-class'));
					});
				});
			@endif

			// Update tag list
			$('#jquery_select2_container .select2-container').hide();
			$('#jquery_tag_category_'+$(this).val()).next().show();
		});

		$('#start_date input[name="start_date"]').val('');
		$('#start_date').datetimepicker({
			locale: '{{App::getLocale()}}',
			format: '{{ trans('panichd::lang.datetimepicker-format') }}',
			@if (isset($ticket) && $a_current['start_date'] != "")
				defaultDate: "{{ $a_current['start_date'] }}",
			@endif
			keyBinds: { 'delete':null, 'left':null, 'right':null }
		});

		$('#limit_date input[name="limit_date"]').val('');
		$('#limit_date').datetimepicker({
			locale: '{{App::getLocale()}}',
			format: '{{ trans('panichd::lang.datetimepicker-format') }}',
			@if (isset($ticket) && $a_current['limit_date'] != "")
				defaultDate: "{{ $a_current['limit_date'] }}",
			@endif
			keyBinds: { 'delete':null, 'left':null, 'right':null },
			useCurrent: false
			@if ($a_current['start_date'] != "")
				, minDate: '{{ $a_current['start_date'] }}'
			@endif
		});

		$('#start_date .btn, #limit_date .btn').click(function(e){
            e.preventDefault();
		    $('#' + $(this).closest('.input-group').prop('id')).data("DateTimePicker").toggle();
		});

        $("#start_date").on("dp.change", function (e) {
            $('#limit_date').data("DateTimePicker").minDate(e.date);
        });
        $("#limit_date").on("dp.change", function (e) {
            $('#start_date').data("DateTimePicker").maxDate(e.date);
        });
	});
	</script>
	@include('panichd::tickets.partials.tags_footer_script')
@append