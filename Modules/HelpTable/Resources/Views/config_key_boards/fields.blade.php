<div class="panel">
    <div class="panel-body">
        <div class="form-group row m-b-15">
            <!-- Brand Name Field -->
            {!! Form::label('brand_name', trans('Marca del teclado') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                <input type="text" class="form-control" v-model="dataForm.brand_name" required>
                <div class="invalid-feedback" v-if="dataErrors.brand_name">
                    <p class="m-b-0" v-for="error in dataErrors.brand_name">@{{ error }}</p>
                </div>
            </div>
            <!-- Status Field -->
            {!! Form::label('status', trans('Estado de la marca') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                <select v-model="dataForm.status" class="form-control" required>
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
