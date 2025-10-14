<!-- New Field -->
<div class="form-group row m-b-15">
    {!! Form::label('new', trans('Observation').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::textarea('new', null, ['class' => 'form-control', 'v-model' => 'dataForm.new', 'required' => true]) !!}
    </div>
</div>
