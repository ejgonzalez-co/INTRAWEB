<div class="form-group row m-b-15">
    {!! Form::label('estado', trans('Estado').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <select class="form-control" v-model="dataForm.estado" name="estado">
            <option value="Valida">Válido</option>
            <option value="Inválido">No válido</option>
        </select>
        <small>Seleccione el estado que quiere actualizar el correo</small>
        <div class="invalid-feedback" v-if="dataErrors.estado">
            <p class="m-b-0" v-for="error in dataErrors.estado">@{{ error }}</p>
        </div>
    </div>
</div>