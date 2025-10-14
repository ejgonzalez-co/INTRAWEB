@extends('layouts.default')

@section('title', 'Home Page')
<div class="vue">
	<script>

	</script>
</div>

@section('menu')
    @include('layouts.menu')
@endsection

@section('content')

	<!-- #modal-form -->
	<div class="modal fade" id="modal-form">
	<!--<form-crud inline-template>-->
		<div class="modal-dialog modal-lg modal-fluid" role="document">
			<form action="">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Formulario de ...</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					</div>
					<div class="modal-body">
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-3">Example select</label>
							<div class="col-md-9">
								<select class="form-control">
									<option>1</option>
									<option>2</option>
								</select>
							</div>
						</div>
						{!! Form::label('$FIELD_NAME$', '$FIELD_NAME_TITLE$:') !!}
						<dl class="row">
							<dt class="text-inverse text-right col-3 text-truncate">$FIELD_NAME_TITLE$:</dt>
							<dd class="col-7 text-truncate">$FIELD_NAME$.</dd>
							<dt class="text-inverse text-right col-3 text-truncate">Euismod</dt>
							<dd class="col-7 text-truncate">Vestibulum id ligula porta felis euismod semper eget lacinia odio sem nec elit.</dd>
							<dt class="text-inverse text-right col-3 text-truncate">Malesuada porta</dt>
							<dd class="col-7 text-truncate">Etiam porta sem malesuada magna mollis euismod.</dd>
						</dl>

						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-3">Example select</label>
							<div class="col-md-9">
								<input type="text" class="form-control" id="datepicker-default" placeholder="Select Date" />
							</div>
						</div>

						<div class="form-group row m-b-10">
							<label class="col-md-3 col-form-label">Inline Radio</label>
							<div class="col-md-9">
								<div class="radio radio-css radio-inline">
									<input type="radio" name="radio_css_inline" id="inlineCssRadio1" value="option1" checked />
									<label for="inlineCssRadio1">Radio option 1</label>
								</div>
								<div class="radio radio-css radio-inline">
									<input type="radio" name="radio_css_inline" id="inlineCssRadio2" value="option2" />
									<label for="inlineCssRadio2">Radio option 2</label>
								</div>
							</div>
						</div>

						<!-- begin custom-checkbox -->
						<!-- 'Boolean FIELD_NAME_TITLE$ Field' checked by default -->
						<div class="form-group row m-b-15">
							{!! Form::label('$FIELD_NAME$', '$FIELD_NAME_TITLE$:', ['class' => 'col-form-label col-md-3']) !!}
							<div class="col-md-9 pb-2 pt-2">
								<div class="custom-control custom-checkbox ">
									{!! Form::checkbox('$FIELD_NAME$', 1, true, ['class' => 'custom-control-input', 'id' => '$FIELD_NAME_TITLE$']) !!}
									<label class="custom-control-label" for="$FIELD_NAME_TITLE$">Check this custom checkbox</label>
									<!-- remove {, true} to make it unchecked by default -->
								</div>
							</div>
						</div>
						<!-- end custom-checkbox -->
						<!-- $FIELD_NAME_TITLE$ Field -->
						<div class="form-group row m-b-15">
							{!! Form::label('$FIELD_NAME$', '$FIELD_NAME_TITLE$:', ['class' => 'col-form-label col-md-3']) !!}
							<div class="col-md-9">
								{!! Form::email('$FIELD_NAME$', null, ['class' => 'form-control']) !!}
							</div>
						</div>

					</div>
					<div class="modal-footer">
						<button class="btn btn-white" data-dismiss="modal">Cerrar</button>
						<button type="submit" class="btn btn-success">Guardar</button>
					</div>
				</div>
			</form>
		</div>
	<!--</form-crud>-->
	</div>
@endsection


@push('css')
	{!!Html::style('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css')!!}
@endpush


@push('scripts')
	{!!Html::script('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')!!}
    <script>
        $('#datepicker-default').datepicker({
            todayHighlight: true
        });
    </script>
@endpush
