<!-- Identification Number Field -->
<div class="form-group row m-b-15">
    {!! Form::label('identification_number', trans('number_nit_or_identification').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('identification_number', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.identification_number }", 'v-model' => 'dataForm.identification_number', 'required' => true]) !!}
        <small>Ingrese el NIT o la cédula del cliente</small>
        <div class="invalid-feedback" v-if="dataErrors.identification_number">
            <p class="m-b-0" v-for="error in dataErrors.identification_number">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Contract number Field -->
<div class="form-group row m-b-15">
    {!! Form::label('contract_number', trans('Número de contrato').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('contract_number', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.contract_number }", 'v-model' => 'dataForm.contract_number', 'required' => true]) !!}
        <small>Ingrese el número de contrato</small>
        <div class="invalid-feedback" v-if="dataErrors.contract_number">
            <p class="m-b-0" v-for="error in dataErrors.contract_number">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.name }", 'v-model' => 'dataForm.name', 'required' => true]) !!}
        <small>Ingrese el nombre del cliente</small>
        <div class="invalid-feedback" v-if="dataErrors.name">
            <p class="m-b-0" v-for="error in dataErrors.name">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Email Field -->
<div class="form-group row m-b-15">
    {!! Form::label('email', trans('Email').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::email('email', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.email }",'maxlength' => 255,'maxlength' => 255, 'v-model' => 'dataForm.email', 'required' => true]) !!}
        <small>Ingrese el correo del cliente ej: nombreCliente@gmail.com</small>
        <div class="invalid-feedback" v-if="dataErrors.email">
            <p class="m-b-0" v-for="error in dataErrors.email">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Telephone Field -->
<div class="form-group row m-b-15">
    {!! Form::label('telephone', 'Telefono'.':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('telephone', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.telephone }", 'v-model' => 'dataForm.telephone', 'required' => false]) !!}
        <small>Ingrese el teléfono del cliente</small>
        <div class="invalid-feedback" v-if="dataErrors.telephone">
            <p class="m-b-0" v-for="error in dataErrors.telephone">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Extension Field -->
<div class="form-group row m-b-15">
    {!! Form::label('extension', trans('Extension').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('extension', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.extension }", 'v-model' => 'dataForm.extension', 'required' => false]) !!}
        <small>Ingrese la extensión del teléfono si lo requiere</small>
        <div class="invalid-feedback" v-if="dataErrors.extension">
            <p class="m-b-0" v-for="error in dataErrors.extension">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- query_report Field -->
<div class="form-group row m-b-15">
    {!! Form::label('query_report', trans('report_query').':', ['class' => 'col-form-label col-md-3 required' ]) !!}
    <div class="col-md-9 mt-4">
        {!! Form::select(trans('query_report'), ['Email' => 'Email', 'Físico' => 'Físico', 'Email-Físico' => 'Email-Físico'], '5', ['class' => 'form-control', 'v-model' => 'dataForm.query_report', 'required' => true]) !!}
        <div class="invalid-feedback" v-if="dataErrors.query_report">
            <p class="m-b-0" v-for="error in dataErrors.query_report">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Cell Number Field -->
<div class="form-group row m-b-15">
    {!! Form::label('cell_number', trans('Cell Number').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('cell_number', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.cell_number }", 'v-model' => 'dataForm.cell_number', 'required' => false]) !!}
        <small>Ingrese el # celular del cliente</small>
        <div class="invalid-feedback" v-if="dataErrors.cell_number">
            <p class="m-b-0" v-for="error in dataErrors.cell_number">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Direccion Field -->
<div class="form-group row m-b-15">
    {!! Form::label('direction', trans('Direction').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('direction', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.direction }", 'v-model' => 'dataForm.direction', 'required' => true]) !!}
        <small>Ingrese la dirección del cliente</small>
        <div class="invalid-feedback" v-if="dataErrors.direction">
            <p class="m-b-0" v-for="error in dataErrors.direction">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Description Field -->
<div class="form-group row m-b-15">
    {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::textarea('description', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.description }", 'v-model' => 'dataForm.description', 'required' => false]) !!}
        <small>Ingrese una pequeña descripción del cliente.</small>
        <div class="invalid-feedback" v-if="dataErrors.description">
            <p class="m-b-0" v-for="error in dataErrors.description">@{{ error }}</p>
        </div>
    </div>
</div>
<div class="mt-5">
    <select-list-check name-field="point_location" name-resource="get-points" :value="dataForm" label="Seleccione los puntos de muestra" ></select-list-check>
</div>

{{-- <div class="form-group row m-b-15">
    <dynamic-list 
        label-button-add="Agregar Punto de muestra" 
        :data-list.sync="dataForm.point_location" 
        class-table="table-responsive table-bordered" 
        class-container="w-100 p-10"
        :data-list-options="[
            {label:'Punto de muestra', name:'point_location', isShow: true, refList: 'selectPoints'}
        ]">
        <template #fields="scope">

            <!-- Tipo activo Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('point_location', 'Puntos de Muestra'.':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    <select-check 
                    ref-select-check="selectPoints" 
                    css-class="form-control" 
                    name-field="point_location" 
                    reduce-label="point_location" 
                    reduce-key="id" 
                    name-resource="get-points" 
                    :value="scope.dataForm" 
                    :is-required="true">
                    </select-check>
                    <small>Seleccione el ensayo.</small>
                </div>
            </div>
        </template>
    </dynamic-list>
</div> --}}