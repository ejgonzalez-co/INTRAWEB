<!-- Total Value Paa Field -->
<div class="form-group row m-b-15">
    {!! Form::label('total_value_paa', trans('Total Value Paa').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('total_value_paa', null, ['class' => 'form-control', 'v-model' => 'dataForm.total_value_paa', 'required' => true]) !!}
    </div>
</div>

<!-- Total Operating Value Field -->
<div class="form-group row m-b-15">
    {!! Form::label('total_operating_value', trans('Total Operating Value').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('total_operating_value', null, ['class' => 'form-control', 'v-model' => 'dataForm.total_operating_value', 'required' => true]) !!}
    </div>
</div>

<!--  Future Validity Status Field -->
<div class="form-group row m-b-15">
    {!! Form::label('future_validity_status', trans('Future Validity Status').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- future_validity_status switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="future_validity_status" id="future_validity_status"  v-model="dataForm.future_validity_status">
        <label for="future_validity_status"></label>
    </div>
</div>


<!-- Total Investment Value Field -->
<div class="form-group row m-b-15">
    {!! Form::label('total_investment_value', trans('Total Investment Value').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('total_investment_value', null, ['class' => 'form-control', 'v-model' => 'dataForm.total_investment_value', 'required' => true]) !!}
    </div>
</div>


<!--  State Field -->
<div class="form-group row m-b-15">
    {!! Form::label('state', trans('State').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- state switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="state" id="state"  v-model="dataForm.state">
        <label for="state"></label>
    </div>
</div>
