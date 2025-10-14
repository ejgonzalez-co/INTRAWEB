<div class="">
    <div class="panel">
        <div class="panel-body">
            <!-- Name Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('name', trans('Sistema Operativo') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-3">
                    {!! Form::text('name', null, [
                        ':class' => "{'form-control':true, 'is-invalid':dataErrors.name }",
                        'v-model' => 'dataForm.name',
                        'required' => true,
                    ]) !!}
                    <div class="invalid-feedback" v-if="dataErrors.name">
                        <p class="m-b-0" v-for="error in dataErrors.name">@{{ error }}</p>
                    </div>
                </div>

                <!-- Status Field -->
                {!! Form::label('status', trans('Status') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
                <div class="col-md-3">
                    <select class="form-control" v-model="dataForm.status" required>
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                    <div class="invalid-feedback" v-if="dataErrors.status">
                        <p class="m-b-0" v-for="error in dataErrors.status">@{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>