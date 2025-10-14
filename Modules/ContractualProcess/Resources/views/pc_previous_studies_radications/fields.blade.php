<!-- Process Field -->
<div class="form-group row m-b-15">
    {!! Form::label('process', trans('Process').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('process', null, ['class' => 'form-control', 'v-model' => 'dataForm.process', 'required' => true]) !!}
    </div>
</div>

<!-- Object Field -->
<div class="form-group row m-b-15">
    {!! Form::label('object', trans('Object').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::textarea('object', null, ['class' => 'form-control', 'v-model' => 'dataForm.object', 'required' => true]) !!}
    </div>
</div>

<!-- Boss Field -->
<div class="form-group row m-b-15">
    {!! Form::label('boss', trans('Boss').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('boss', null, ['class' => 'form-control', 'v-model' => 'dataForm.boss', 'required' => true]) !!}
    </div>
</div>

<!-- Value Field -->
<div class="form-group row m-b-15">
    {!! Form::label('value', trans('Value').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('value', null, ['class' => 'form-control', 'v-model' => 'dataForm.value', 'required' => true]) !!}
    </div>
</div>

<!-- Date Send Field -->
<div class="form-group row m-b-15">
    {!! Form::label('date_send', trans('Date Send').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('date_send', null, ['class' => 'form-control', 'id' => 'date_send',
        'placeholder' => 'Select Date', 'v-model' => 'dataForm.date_send', 'required' => true]) !!}
    </div>
</div>

@push('css')
	{!!Html::style('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css')!!}
@endpush

@push('scripts')
    {!!Html::script('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')!!}
    <script>
        $('#date_send').datepicker({
            todayHighlight: true
        });
    </script>
@endpush


<!-- Notification Field -->
<div class="form-group row m-b-15">
    {!! Form::label('notification', trans('Notification').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('notification', null, ['class' => 'form-control', 'v-model' => 'dataForm.notification', 'required' => true]) !!}
    </div>
</div>

<!-- Pc Previous Studies Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('pc_previous_studies_id', trans('Pc Previous Studies Id').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('pc_previous_studies_id', null, ['class' => 'form-control', 'v-model' => 'dataForm.pc_previous_studies_id', 'required' => true]) !!}
    </div>
</div>
