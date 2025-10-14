<div class="panel">
    <br>
    <!-- Code Field -->
    <div class="col-md-9">
        <div class="form-group row m-b-15">
            {!! Form::label('code', trans('Code').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <input-random :value="dataForm" name-field="code" prefix="PMI-"></input-random>
            </div>
        </div>
    </div>

    <!-- Name Field -->
    <div class="col-md-9">
        <div class="form-group row m-b-15">
            {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::text('name', null, ['class' => 'form-control', 'v-model' => 'dataForm.name', 'required' => true]) !!}
                <small>Ingrese el nombre del tipo de plan de mejoramiento.</small>
            </div>
        </div>
    </div>

    <!--  State Field -->
    <div class="col-md-9">
        <div class="form-group row m-b-15">
            {!! Form::label('state', trans('State').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <!-- state switcher -->
            {{-- <div class="switcher col-md-9">
                <input type="checkbox" name="state" id="state"  v-model="dataForm.state">
                <label for="state"></label>
            </div> --}}
            <div class="col-md-9">
                {!! Form::select('status',["Activo" => 'Activo', 'Inactivo' => 'Inactivo'  ], null ,['class' => 'form-control', 'v-model' => 'dataForm.status', 'required' => true]) !!}
                <small>Seleccione el estado del tipo de plan de mejoramiento.</small>
            </div>
        </div>
    </div>
    <br>
</div>
