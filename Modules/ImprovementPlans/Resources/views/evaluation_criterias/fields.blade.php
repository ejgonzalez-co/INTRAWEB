<div class="panel">
    <br>
    <div class="col-md-9">
        <!-- Name Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::text('name', null, ['class' => 'form-control', 'v-model' => 'dataForm.name', 'required' => true]) !!}
                <small>Ingrese el nombre del criterio de evaluación.</small>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <!--  State Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('state', trans('Estado').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <!-- state switcher -->
            <div class="col-md-9">
                {!! Form::select('status',['Activo' => 'Activo', 'Inactivo' => 'Inactivo'  ], null ,['class' => 'form-control', 'v-model' => 'dataForm.status', 'required' => true]) !!}
                <small>Seleccione el estado del criterio de evaluación.</small>
            </div>
        </div>
    </div>
    <br>
</div>
