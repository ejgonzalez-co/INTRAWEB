<!-- Nombre Activo Field -->
<div class="form-group row m-b-15">
    {!! Form::label('nombre_activo', trans('Nombre Activo').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('nombre_activo', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.nombre_activo }", 'v-model' => 'dataForm.nombre_activo', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Nombre Activo')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.nombre_activo">
            <p class="m-b-0" v-for="error in dataErrors.nombre_activo">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Tipo Mantenimiento Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tipo_mantenimiento', trans('Tipo Mantenimiento').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('tipo_mantenimiento', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tipo_mantenimiento }", 'v-model' => 'dataForm.tipo_mantenimiento', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Tipo Mantenimiento')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.tipo_mantenimiento">
            <p class="m-b-0" v-for="error in dataErrors.tipo_mantenimiento">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Kilometraje Actual Field -->
<div class="form-group row m-b-15">
    {!! Form::label('kilometraje_actual', trans('Kilometraje Actual').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('kilometraje_actual', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.kilometraje_actual }", 'v-model' => 'dataForm.kilometraje_actual', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Kilometraje Actual')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.kilometraje_actual">
            <p class="m-b-0" v-for="error in dataErrors.kilometraje_actual">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Kilometraje Recibido Proveedor Field -->
<div class="form-group row m-b-15">
    {!! Form::label('kilometraje_recibido_proveedor', trans('Kilometraje Recibido Proveedor').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('kilometraje_recibido_proveedor', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.kilometraje_recibido_proveedor }", 'v-model' => 'dataForm.kilometraje_recibido_proveedor', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Kilometraje Recibido Proveedor')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.kilometraje_recibido_proveedor">
            <p class="m-b-0" v-for="error in dataErrors.kilometraje_recibido_proveedor">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Nombre Proveedor Field -->
<div class="form-group row m-b-15">
    {!! Form::label('nombre_proveedor', trans('Nombre Proveedor').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('nombre_proveedor', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.nombre_proveedor }", 'v-model' => 'dataForm.nombre_proveedor', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Nombre Proveedor')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.nombre_proveedor">
            <p class="m-b-0" v-for="error in dataErrors.nombre_proveedor">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- No Salida Almacen Field -->
<div class="form-group row m-b-15">
    {!! Form::label('no_salida_almacen', trans('No Salida Almacen').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('no_salida_almacen', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.no_salida_almacen }", 'v-model' => 'dataForm.no_salida_almacen', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('No Salida Almacen')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.no_salida_almacen">
            <p class="m-b-0" v-for="error in dataErrors.no_salida_almacen">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- No Factura Field -->
<div class="form-group row m-b-15">
    {!! Form::label('no_factura', trans('No Factura').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('no_factura', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.no_factura }", 'v-model' => 'dataForm.no_factura', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('No Factura')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.no_factura">
            <p class="m-b-0" v-for="error in dataErrors.no_factura">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- No Solicitud Field -->
<div class="form-group row m-b-15">
    {!! Form::label('no_solicitud', trans('No Solicitud').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('no_solicitud', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.no_solicitud }", 'v-model' => 'dataForm.no_solicitud', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('No Solicitud')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.no_solicitud">
            <p class="m-b-0" v-for="error in dataErrors.no_solicitud">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Actividad Field -->
<div class="form-group row m-b-15">
    {!! Form::label('actividad', trans('Actividad').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('actividad', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.actividad }", 'v-model' => 'dataForm.actividad', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Actividad')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.actividad">
            <p class="m-b-0" v-for="error in dataErrors.actividad">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Repuesto Field -->
<div class="form-group row m-b-15">
    {!! Form::label('repuesto', trans('Repuesto').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('repuesto', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.repuesto }", 'v-model' => 'dataForm.repuesto', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Repuesto')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.repuesto">
            <p class="m-b-0" v-for="error in dataErrors.repuesto">@{{ error }}</p>
        </div>
    </div>
</div>
