<!-- Name Process Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name_process', trans('Name Process').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('name_process', null, ['class' => 'form-control', 'v-model' => 'dataForm.name_process', 'required' => true]) !!}
    </div>
</div>

<!-- Users Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('users_id', trans('User').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <select-check
            css-class="form-control"
            name-field="users_id"
            reduce-label="name"
            reduce-key="id"
            name-resource="get-process-lead-users"
            :value="dataForm"
            :is-required="true"
            >
        </select-check>
    </div>
</div>
