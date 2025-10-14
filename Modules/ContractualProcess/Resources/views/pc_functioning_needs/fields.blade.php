<!-- Description Field -->
<div class="form-group row m-b-15">
    {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::textarea('description', null, ['class' => 'form-control', 'v-model' => 'dataForm.description']) !!}
    </div>
</div>

<!-- Estimated Total Value Field -->
<div class="form-group row m-b-15">
    {!! Form::label('estimated_total_value', trans('Estimated Total Value').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        <currency-input
            v-model="dataForm.estimated_total_value"
            required="true"
            :currency="{'prefix': '$ '}"
            locale="es"
            class="form-control"
            :key="keyRefresh"
            >
        </currency-input>
    </div>
</div>

<!-- Observation Field -->
<div class="form-group row m-b-15">
    {!! Form::label('observation', trans('Observation').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::textarea('observation', null, ['class' => 'form-control', 'v-model' => 'dataForm.observation']) !!}
    </div>
</div>