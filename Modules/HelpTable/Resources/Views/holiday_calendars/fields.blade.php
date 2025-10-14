<!-- Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('date', trans('Date').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('date', null, ['class' => 'form-control', 'id' => 'date',
        'placeholder' => 'Select Date', 'v-model' => 'dataForm.date', 'required' => true]) !!}
    </div>
</div>

@push('css')
	{!!Html::style('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css')!!}
@endpush

@push('scripts')
    {!!Html::script('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')!!}
    <script>
        $('#date').datepicker({
            todayHighlight: true
        });
    </script>
@endpush

