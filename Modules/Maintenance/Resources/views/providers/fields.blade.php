<div class="panel" data-sortable-id="ui-general-1">
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Información general</strong></h4>
    </div>
    <div class="panel-body">
        <div class="form-group row m-b-15">
            {!! Form::label('type_provider', 'Tipo de Proveedor:', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                <select class="form-control" v-model="dataForm.type_provider" name="type_provider" required>
                    <option value="" disabled>Seleccione</option>
                    <option value="Interno">Interno</option>
                    <option value="Externo">Externo</option>
                </select>
                <small>Seleccione si el proveedor es interno o externo</small>
            </div>
        </div>
        
        <div class="form-group row m-b-15">
            {!! Form::label('type_person', trans('Type Person').':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                <select class="form-control" v-model="dataForm.type_person" name="type_person" id="type_person" required>
                    <option value="">Seleccione</option>
                    <option value="Natural">Natural</option>
                    <option value="Jurídica">Jurídica</option>
                </select>
                <small>Seleccione el tipo de persona</small>
            </div>

            {!! Form::label('document_type', trans('Document Type').':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                <select class="form-control" v-model="dataForm.document_type" name="document_type" id="document_type" required>
                    <option value="">Seleccione</option>
                    <option value="Cédula de ciudadanía">Cédula de ciudadanía</option>
                    <option value="Cédula de extranjería">Cédula de extranjería</option>
                    <option value="NIT">NIT</option>
                </select>
                <small>Seleccione el tipo de documento</small>
            </div>
        </div>

        <div class="form-group row m-b-15">
            {!! Form::label('identification', trans('Identification').':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::text('identification', null, ['class' => 'form-control', 'v-model' => 'dataForm.identification', 'required' => true]) !!}
                <small>Ingrese el número de identificación del proveedor</small>
            </div>

            {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::text('name', null, ['class' => 'form-control', 'v-model' => 'dataForm.name', 'required' => true]) !!}
                <small>Ingrese el nombre del proveedor</small>
            </div>
        </div>

        <div class="form-group row m-b-15">
            {!! Form::label('mail', trans('Mail').':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::email('mail', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.mail }", 'v-model' => 'dataForm.mail', 'required' => true]) !!}
                <small>Ingrese un correo electrónico válido, ej: xxxxx@gmail.com</small>
                <div class="invalid-feedback" v-if="dataErrors.mail">
                    <p class="m-b-0" v-for="error in dataErrors.mail">@{{ error }}</p>
                </div>
            </div>

            {!! Form::label('regime', trans('Regime').':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                <select class="form-control" v-model="dataForm.regime" name="regime" id="regime" required>
                    <option value="">Seleccione</option>
                    <option value="Simplificado">Simplificado</option>
                    <option value="Común">Común</option>
                    <option value="Ordinario">Ordinario</option>
                </select>
                <small>Seleccione el régimen del proveedor</small>
            </div>
        </div>

        <div class="form-group row m-b-15">
            {!! Form::label('phone', trans('Phone').':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                {!! Form::text('phone', null, ['class' => 'form-control', 'v-model' => 'dataForm.phone', 'required' => false]) !!}
                <small>Ingrese el teléfono del proveedor</small>
            </div>
            
            {!! Form::label('address', trans('Address').':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                {!! Form::text('address', null, ['class' => 'form-control', 'v-model' => 'dataForm.address', 'required' => false]) !!}
                <small>Ingrese la dirección del proveedor</small>
            </div>
        </div>

        <div class="form-group row m-b-15">
            {!! Form::label('municipality', trans('Municipality').':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                {!! Form::text('municipality', null, ['class' => 'form-control', 'v-model' => 'dataForm.municipality', 'required' => false]) !!}
                <small>Ingrese el municipio del proveedor</small>
            </div>

            {!! Form::label('department', trans('Department').':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                {!! Form::text('department', null, ['class' => 'form-control', 'v-model' => 'dataForm.department', 'required' => false]) !!}
                <small>Ingrese el departamento del proveedor</small>
            </div>
        </div>

        <div class="form-group row m-b-15">
            {!! Form::label('Types activity', trans('Types activity').':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                <select-check ref-select-check="selectRefActivity" css-class="form-control" name-field="mant_types_activity_id" reduce-label="name" reduce-key="id" name-resource="/maintenance/get-types-activities" :value="dataForm"></select-check>
                <small>Seleccione el tipo de actividad del proveedor</small>
            </div>
        </div>
    </div>
</div>

<div class="panel" data-sortable-id="ui-general-1">
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Información representante legal</strong></h4>
    </div>
    <div class="panel-body">
        <div class="form-group row m-b-15">
            {!! Form::label('name_rep', trans('Name').':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                {!! Form::text('name_rep', null, ['class' => 'form-control', 'v-model' => 'dataForm.name_rep']) !!}
                <small>Ingrese el nombre del proveedor</small>
            </div>

            {!! Form::label('document_type_rep', trans('Document Type').':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                <select class="form-control" v-model="dataForm.document_type_rep" name="document_type_rep" id="document_type_rep">
                    <option value="">Seleccione</option>
                    <option value="Cédula de ciudadanía">Cédula de ciudadanía</option>
                    <option value="Cédula de extranjería">Cédula de extranjería</option>
                    <option value="NIT">NIT</option>
                </select>
                <small>Seleccione el tipo de documento</small>
            </div>
        </div>

        <div class="form-group row m-b-15">
            {!! Form::label('identification_rep', trans('identification').':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                {!! Form::text('identification_rep', null, ['class' => 'form-control', 'v-model' => 'dataForm.identification_rep']) !!}
                <small>Ingrese el número de identificación del proveedor</small>
            </div>
            {!! Form::label('phone_rep', trans('phone').':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                {!! Form::text('phone_rep', null, ['class' => 'form-control', 'v-model' => 'dataForm.phone_rep']) !!}
                <small>Ingrese el teléfono del proveedor</small>
            </div>
        </div>

        <div class="form-group row m-b-15">
            {!! Form::label('mail_rep', trans('Mail').':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                {!! Form::email('mail_rep', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.mail_rep }", 'v-model' => 'dataForm.mail_rep']) !!}
                <small>Ingrese un correo electrónico válido, ej: xxxxx@gmail.com</small>
                <div class="invalid-feedback" v-if="dataErrors.mail_rep">
                    <p class="m-b-0" v-for="error in dataErrors.mail_rep">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel" data-sortable-id="ui-general-1">
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Correos de contacto opcional</strong></h4>
    </div>
    <div class="panel-body">
        <dynamic-list 
            label-button-add="Agregar ítem a la lista" 
            :data-list.sync="dataForm.optional_contact_emails"
            class-table="table-responsive table-bordered" 
            class-container="w-100 p-10"
            :data-list-options="[
                                {label:'Nombre', name:'name', isShow: true},
                                {label:'Correo', name:'mail', isShow: true},
                                {label:'Teléfono', name:'phone', isShow: true},
                                {label:'Observación', name:'observation', isShow: true}
                            ]">
            <template #fields="scope">

                <div class="form-group row m-b-15">
                    {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-2 required']) !!}
                    <div class="col-md-4">
                        {!! Form::text('name', null, ['class' => 'form-control', 'v-model' => 'scope.dataForm.name', 'required' => true]) !!}
                        <small>Ingrese el nombre del contacto</small>
                    </div>
                    {!! Form::label('mail', trans('Mail').':', ['class' => 'col-form-label col-md-2 required']) !!}
                    <div class="col-md-4">
                        {!! Form::email('mail', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.mail }", 'v-model' => 'scope.dataForm.mail', 'required' => true]) !!}
                        <small>Ingrese el correo del contacto</small>
                        <div class="invalid-feedback" v-if="dataErrors.mail">
                            <p class="m-b-0" v-for="error in dataErrors.mail">@{{ error }}</p>
                        </div>
                    </div>
                </div>

                <div class="form-group row m-b-15">                     
                    {!! Form::label('phone', trans('Phone').':', ['class' => 'col-form-label col-md-2 required']) !!}
                    <div class="col-md-4">
                        {!! Form::text('phone', null, ['class' => 'form-control', 'v-model' => 'scope.dataForm.phone', 'required' => true]) !!}
                        <small>Ingrese el teléfono del contacto</small>
                    </div>
                    {!! Form::label('observation', trans('Observation').':', ['class' => 'col-form-label col-md-2 required']) !!}
                    <div class="col-md-4">
                        {!! Form::text('observation', null, ['class' => 'form-control', 'v-model' => 'scope.dataForm.observation', 'required' => true]) !!}
                        <small>Ingrese la obervación del contacto</small>
                    </div>
                </div>

            </template>
        </dynamic-list>
    </div>
</div>

<div class="panel" data-sortable-id="ui-general-1">
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Otros datos</strong></h4>
    </div>
    <div class="panel-body">
        <div class="form-group row m-b-15">
            {!! Form::label('firma_proveedor', 'Firma Proveedor:', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-10">
                <input-file
                    :value="dataForm"
                    name-field="firma_proveedor"
                    :max-files="1"
                    :max-filesize="2"
                    file-path="public/providers/signatures"
                    message="Arrastre o seleccione la imagen de la firma"
                    help-text="Adjunte la imagen de la firma del proveedor (Opcional). Tamaño máximo 2MB."
                    :is-update="isUpdate"
                    ruta-delete-update="mant/providers/delete-signature"
                    :id-file-delete="dataForm.id">
                </input-file>
            </div>
        </div>
        
        <div class="form-group row m-b-15">
            {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-10">
                {!! Form::textarea('description', null, ['class' => 'form-control', 'v-model' => 'dataForm.description', 'rows' => 3]) !!}
                <small>Ingrese alguna descripción</small>
            </div>
        </div>

        <div class="form-group row m-b-15">
            {!! Form::label('state', trans('State').':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                <select class="form-control" v-model="dataForm.state" name="state" id="state" required>
                    <option value="">Seleccione</option>
                    <option value="Activo">Activo</option>
                    <option value="Inactivo">Inactivo</option>
                </select>
                <small>Seleccione el estado del proveedor</small>
            </div>
        </div>
    </div>
</div>