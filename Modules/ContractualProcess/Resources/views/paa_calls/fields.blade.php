<!-- Validity Field -->
<div class="form-group row m-b-15">
    {!! Form::label('validity', trans('Validity').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <select name="validity" id="validity" class="form-control" v-model="dataForm.validity">

            @for ($i = date('Y'); $i < date('Y', strtotime('+5 years')); $i++)
            <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
    </div>
</div>

<!-- Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('name', null, ['class' => 'form-control', 'v-model' => 'dataForm.name', 'required' => true]) !!}
    </div>
</div>

<!-- Start Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('start_date', trans('Start Date').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <date-picker
            :value="dataForm"
            name-field="start_date"
            :max-date="dataForm.closing_date"
            :input-props="{required: true}"
        >
        </date-picker>
    </div>
</div>


<!-- Closing Alert Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('closing_alert_date', trans('Closing Alert Date').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <date-picker
            :value="dataForm"
            name-field="closing_alert_date"
            :min-date="dataForm.start_date"
            :max-date="dataForm.closing_date"
            :input-props="{required: true}"
        >
        </date-picker>
    </div>
</div>

<!-- Closing Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('closing_date', trans('Closing Date').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <date-picker
            :value="dataForm"
            name-field="closing_date"
            :min-date="dataForm.start_date"
            :input-props="{required: true}"
        >
        </date-picker>
    </div>
</div>

<!-- Attached Field -->
<div class="form-group row m-b-15">
    {!! Form::label('attached', trans('Attached').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::file('attached', ['@change' => 'inputFile($event, "attached")', 'required' => false]) !!}
        <small></small>
    </div>
</div>

<!-- Observation Message Field -->
<div class="form-group row m-b-15">
    {!! Form::label('observation_message', trans('Observation Message').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::textarea('observation_message', null, ['class' => 'form-control', 'v-model' => 'dataForm.observation_message']) !!}
    </div>
</div>
