<!-- New Field -->
<div class="form-group row m-b-15">
    {!! Form::label('new', trans('New').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::textarea('new', null, ['class' => 'form-control', 'v-model' => 'dataForm.new', 'required' => true]) !!}
    </div>
</div>

<!-- Type Document Field -->
<div class="form-group row m-b-15">
    {!! Form::label('type_document', trans('Type Document').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('type_document', null, ['class' => 'form-control', 'v-model' => 'dataForm.type_document', 'required' => true]) !!}
    </div>
</div>

<!-- Users Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('users_name', trans('Users Name').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('users_name', null, ['class' => 'form-control', 'v-model' => 'dataForm.users_name', 'required' => true]) !!}
    </div>
</div>

<!-- Users Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('users_id', trans('Users Id').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('users_id', null, ['class' => 'form-control', 'v-model' => 'dataForm.users_id', 'required' => true]) !!}
    </div>
</div>
