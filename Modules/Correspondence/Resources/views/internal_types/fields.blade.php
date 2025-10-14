<!-- Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.name }", 'v-model' => 'dataForm.name', 'required' => true]) !!}
        <small>@lang('Enter the') el @{{ `@lang('Name')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.name">
            <p class="m-b-0" v-for="error in dataErrors.name">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Template Field -->
<div class="form-group row m-b-15">
    {!! Form::label('template', trans('Template').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::file('template', ['accept' => '.docx', '@change' => 'inputFile($event, "template")', 'required' => false]) !!}
        <small>@lang('Select the') la @{{ `@lang('Template')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.template">
            <p class="m-b-0" v-for="error in dataErrors.template">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Prefix Field -->
<div class="form-group row m-b-15">
    {!! Form::label('prefix', trans('Prefix').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('prefix', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.prefix }", 'v-model' => 'dataForm.prefix', 'required' => true]) !!}
        <small>@lang('Enter the') el @{{ `@lang('Prefix')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.prefix">
            <p class="m-b-0" v-for="error in dataErrors.prefix">@{{ error }}</p>
        </div>
    </div>
</div>
<hr />
<!-- Variables Field -->
<div class="form-group row m-b-15">
    <h6 class="col-form-label">Variables de la plantilla</h6>
    <dynamic-list label-button-add="Agregar variable" :data-list.sync="dataForm.variables_documento" :is-remove="true"
        :data-list-options="[
            { label: 'Variable - Descripción', name: 'variable', isShow: true, refList: 'variables' }
        ]"
        class-container="col-md-12" class-table="table table-bordered" :is-remove="false">
        <template #fields="scope">
            <!-- Variables Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('variable', trans('Variable') . ':', ['class' => 'col-form-label col-md-3',]) !!}
                <div class="col-md-9">
                    <select name="variable" v-model="scope.dataForm.variable" class="form-control" ref="variables" required>
                       
                        <option value="#consecutivo">#consecutivo - Consecutivo del documento</option>
                        <option value="#titulo">#titulo - Título del documento</option>
                        <option value="#remitente">#remitente - Remitente del documento</option>
                        <option value="#dependencia_remitente">#dependencia_remitente - Dependencia remitente del documento</option>
                        <option value="#destinatarios">#destinatarios - Destinatarios del documento</option>
                        <option value="#copias">#copias - Copias del documento</option>
                        <option value="#anexos">#anexos - Anexos del documento</option>
                        <option value="#tipo_documento">#tipo_documento - Tipo de documento</option>
                        <option value="#elaborado">#elaborado - Pérsona que elaboró el documento</option>
                        <option value="#revisado">#revisado - Persona que revisó el documento</option>
                        <option value="#aprobado">#aprobado - Persona que aprobó el documento</option>
                        <option value="#proyecto">#proyecto - Persona que proyectó el documento</option>
                        <option value="#respuesta_correspondencia">#respuesta_correspondencia - Correspondencia a la que hace respuesta</option>
                        <option value="#codigo_dependencia">#codigo_dependencia - Código de la dependencia</option>
                        <option value="#fecha">#fecha - Fecha de publicación del documento</option>
                        <option value="#codigo_validacion">#codigo_validacion - Código de validación del documento</option>
                        <option value="#firmas">#firmas - Firmas de los remitentes del documento</option>
                        <option value="#direccion">#direccion - Dirección de la dependencia</option>
                        <option value="#dep_piso">#dep_piso - Piso de la dependencia</option>
                        <option value="#codigo_postal">#codigo_postal - codigo_postal de la dependencia</option>
                        <option value="#telefono">#telefono - Telefono de la dependencia</option>
                        <option value="#dep_ext">#dep_ext - Extensión de la dependencia</option>
                        <option value="#dep_correo">#dep_correo - Correo de la dependencia</option>
                        <option value="#logo">#logo - Logo de la dependencia</option>


                    </select>
                    <div class="invalid-feedback" v-if="dataErrors.variable">
                        <p class="m-b-0" v-for="error in dataErrors.variable">@{{ error }}</p>
                    </div>
                </div>
            </div>
        </template>
    </dynamic-list>
</div>