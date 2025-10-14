<div class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
       <h4 class="panel-title"><strong>Información general</strong></h4>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      {{-- <div class="form-group row m-b-15">
         {!! Form::label(
            'origen_creacion',
            'Origen de creacion' . ':',
            ['class' => 'col-form-label col-md-3 required'],
         ) !!}
         <div class="col-md-9">
            <select class="form-control" name="origen_creacion"
                  id="origen_creacion" v-model="dataForm.origen_creacion" required>
                  <option value="Nuevo documento">Nuevo documento</option>
                  <option value="Módulo de intraweb">Módulo de intraweb</option>
            </select>
            <small>@lang('Select the') el origen del documento</small>
            <div class="invalid-feedback" v-if="dataErrors.origen_creacion">
                  <p class="m-b-0" v-for="error in dataErrors.origen_creacion">
                     @{{ error }}</p>
            </div>
         </div>
      </div> --}}
      <div>
         <div class="form-group row m-b-15">
            {!! Form::label('nombre_documento', 'Nombre del documento'.':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
               {!! Form::text('nombre_documento', null, ['class' => 'form-control', 'v-model' => 'dataForm.nombre_documento', 'required' => true]) !!}
               <small>Ingrese el Nombre del documento.</small>
            </div>
         </div>
         <div class="form-group row m-b-15">
            {!! Form::label('ee_tipos_documentales_id', 'Tipo documental' . ':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
               <select-check 
                  name-field-object="metadatos_tipo_documental"
                  css-class="form-control" name-field="ee_tipos_documentales_id"
                  reduce-label="name"
                  :name-resource="'get-tipos-documentales/'+dataForm.c" :value="dataForm" :is-required="true"
                  ref-select-check="tipos_documentales_ref" :enable-search="true">
               </select-check>
                <small>Seleccione el tipo documental</small>
                <div class="invalid-feedback" v-if="dataErrors.ee_tipos_documentales_id">
                    <p class="m-b-0" v-for="error in dataErrors.ee_tipos_documentales_id">@{{ error }}</p>
                </div>
            </div>
        </div>
        <div class="form-group row m-b-15">
            {!! Form::label('fecha_documento', 'Fecha del documento'.':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
               {!! Form::datetimeLocal('fecha_documento', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.fecha_documento }", 'v-model' => 'dataForm.fecha_documento', 'required' => true]) !!}
               <small>Ingrese al fecha del documento</small>
               <div class="invalid-feedback" v-if="dataErrors.fecha_documento">
                  <p class="m-b-0" v-for="error in dataErrors.fecha_documento">@{{ error }}</p>
               </div>
            </div>
         </div>
         <div class="form-group row m-b-15">
            {!! Form::label('pagina_inicio', 'Página inicio'.':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::number('pagina_inicio', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.pagina_inicio }", 'v-model' => 'dataForm.pagina_inicio', 'required' => true]) !!}
                <small>Ingrese la pagina de inicio</small>
                <div class="invalid-feedback" v-if="dataErrors.pagina_inicio">
                    <p class="m-b-0" v-for="error in dataErrors.pagina_inicio">@{{ error }}</p>
                </div>
            </div>
        </div>
        <div class="form-group row m-b-15">
            {!! Form::label('pagina_fin', 'Página fin'.':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
               {!! Form::number('pagina_fin', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.pagina_fin }", 'v-model' => 'dataForm.pagina_fin', 'required' => true]) !!}
               <small>Ingrese la pagina de fin</small>
               <div class="invalid-feedback" v-if="dataErrors.pagina_fin">
                  <p class="m-b-0" v-for="error in dataErrors.pagina_fin">@{{ error }}</p>
               </div>
            </div>
         </div>
         <div class="form-group row m-b-15">
            {!! Form::label('adjunto', 'Adjuntar documento:', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <input-file :file-name-real="true":value="dataForm" name-field="adjunto" :max-files="10"
                    :max-filesize="10" file-path="public/container/expedientes_electronicos_{{ date('Y') }}"
                    message="Arrastre o seleccione los archivos" help-text="Utilice este campo para adjuntar el documento del expediente. El tamaño máximo permitido es de 10 MB."
                    :is-update="isUpdate" :required="true" ruta-delete-update="expedientes-electronicos/expediente-doc-delete-file" :id-file-delete="dataForm.id">
                </input-file>
            </div>
        </div>
      </div>
      {{-- <div v-if="dataForm.origen_creacion == 'Módulo de intraweb'">
         <div class="form-group row m-b-15">
            {!! Form::label(
               'modulo_intraweb',
               'Módulo de intraweb' . ':',
               ['class' => 'col-form-label col-md-3 required'],
            ) !!}
            <div class="col-md-9">
               <select class="form-control" name="modulo_intraweb"
                     id="modulo_intraweb" v-model="dataForm.modulo_intraweb" required>
                     <option value="Correspondencia interna">Correspondencia interna</option>
                     <option value="Correspondencia recibida">Correspondencia recibida</option>
                     <option value="Correspondencia enviada">Correspondencia enviada</option>
                     <option value="PQRSD">PQRSD</option>
                     <option value="Documentos electrónicos">Documentos electrónicos</option>
               </select>
               <small>@lang('Select the') el modulo de intraweb</small>
               <div class="invalid-feedback" v-if="dataErrors.modulo_intraweb">
                     <p class="m-b-0" v-for="error in dataErrors.modulo_intraweb">
                        @{{ error }}</p>
               </div>
            </div>
         </div>
         <div v-if="dataForm.modulo_intraweb == 'PQRSD'">
            <div class="form-group row m-b-15">
               {!! Form::label('modulo_consecutivo', 'Consecutivo'.':', ['class' => 'col-form-label col-md-3 required']) !!}
               <div class="col-md-9">
                   <autocomplete
                       name-prop="pqr_id"
                       name-field="modulo_consecutivo"
                       :value='dataForm'
                       :is-required="true"
                       name-resource="modulo-pqrs"
                       css-class="form-control"
                       :name-labels-display="['pqr_id']"
                       :fields-change-values="['document_pdf:document_pdf', 'id:id']"
                       reduce-key="pqr_id"
                       name-field-edit="modulo_consecutivo">
                   </autocomplete>
                   <small>Ingrese y seleecione el consecutivo</small>
                   <div class="invalid-feedback" v-if="dataErrors.modulo_consecutivo">
                       <p class="m-b-0" v-for="error in dataErrors.modulo_consecutivo">@{{ error }}</p>
                   </div>
               </div>
           </div>
            <div class="form-group row m-b-15">
               <div class="col-md-12">
                  <viewer-attachement :link-file-name="true" v-if="dataForm.document_pdf" :list="dataForm.document_pdf"></viewer-attachement>
               </div>
            </div>
         </div>
         <div v-else-if="dataForm.modulo_intraweb == 'Correspondencia interna'">
            <div class="form-group row m-b-15">
               {!! Form::label('modulo_consecutivo', 'Consecutivo'.':', ['class' => 'col-form-label col-md-3 required']) !!}
               <div class="col-md-9">
                   <autocomplete
                       name-prop="consecutive"
                       name-field="modulo_consecutivo"
                       :value='dataForm'
                       :is-required="true"
                       name-resource="modulo-interna"
                       css-class="form-control"
                       :name-labels-display="['consecutive']"
                       :fields-change-values="['document_pdf:document_pdf', 'id:id']"
                       reduce-key="consecutive"
                       name-field-edit="modulo_consecutivo">
                   </autocomplete>
                   <small>Ingrese y seleecione el consecutivo</small>
                   <div class="invalid-feedback" v-if="dataErrors.modulo_consecutivo">
                       <p class="m-b-0" v-for="error in dataErrors.modulo_consecutivo">@{{ error }}</p>
                   </div>
               </div>
           </div>
            <div class="form-group row m-b-15">
               <div class="col-md-9">
                  <viewer-attachement :link-file-name="true" v-if="dataForm.document_pdf" :list="dataForm.document_pdf"></viewer-attachement>
               </div>
            </div>
         </div>
         <div v-else-if="dataForm.modulo_intraweb == 'Correspondencia recibida'">
            <div class="form-group row m-b-15">
               {!! Form::label('modulo_consecutivo', 'Consecutivo'.':', ['class' => 'col-form-label col-md-3 required']) !!}
               <div class="col-md-9">
                   <autocomplete
                       name-prop="consecutive"
                       name-field="modulo_consecutivo"
                       :value='dataForm'
                       :is-required="true"
                       name-resource="modulo-recibida"
                       css-class="form-control"
                       :name-labels-display="['consecutive']"
                       :fields-change-values="['document_pdf:document_pdf', 'id:id']"
                       reduce-key="consecutive"
                       name-field-edit="modulo_consecutivo">
                   </autocomplete>
                   <small>Ingrese y seleecione el consecutivo</small>
                   <div class="invalid-feedback" v-if="dataErrors.modulo_consecutivo">
                       <p class="m-b-0" v-for="error in dataErrors.modulo_consecutivo">@{{ error }}</p>
                   </div>
               </div>
           </div>
            <div class="form-group row m-b-15">
               <div class="col-md-12">
                  <viewer-attachement :link-file-name="true" v-if="dataForm.document_pdf" :list="dataForm.document_pdf"></viewer-attachement>
               </div>
            </div>
         </div>
         <div v-else-if="dataForm.modulo_intraweb == 'Correspondencia enviada'">
            <div class="form-group row m-b-15">
               {!! Form::label('modulo_consecutivo', 'Consecutivo'.':', ['class' => 'col-form-label col-md-3 required']) !!}
               <div class="col-md-9">
                   <autocomplete
                       name-prop="consecutive"
                       name-field="modulo_consecutivo"
                       :value='dataForm'
                       :is-required="true"
                       name-resource="modulo-enviada"
                       css-class="form-control"
                       :name-labels-display="['consecutive']"
                       :fields-change-values="['document_pdf:document_pdf', 'id:id']"
                       reduce-key="consecutive"
                       name-field-edit="modulo_consecutivo">
                   </autocomplete>
                   <small>Ingrese y seleecione el consecutivo</small>
                   <div class="invalid-feedback" v-if="dataErrors.modulo_consecutivo">
                       <p class="m-b-0" v-for="error in dataErrors.modulo_consecutivo">@{{ error }}</p>
                   </div>
               </div>
           </div>
            <div class="form-group row m-b-15">
               <div class="col-md-12">
                  <viewer-attachement :link-file-name="true" v-if="dataForm.document_pdf" :list="dataForm.document_pdf"></viewer-attachement>
               </div>
            </div>
         </div>
         <div v-else-if="dataForm.modulo_intraweb == 'Documentos electrónicos'">
            <div class="form-group row m-b-15">
               {!! Form::label('modulo_consecutivo', 'Consecutivo'.':', ['class' => 'col-form-label col-md-3 required']) !!}
               <div class="col-md-9">
                   <autocomplete
                       name-prop="consecutivo"
                       name-field="modulo_consecutivo"
                       :value='dataForm'
                       :is-required="true"
                       name-resource="modulo-documentos-electronicos"
                       css-class="form-control"
                       :name-labels-display="['consecutivo']"
                       :fields-change-values="['document_pdf:document_pdf', 'id:id']"
                       reduce-key="consecutivo"
                       name-field-edit="modulo_consecutivo">
                   </autocomplete>
                   <small>Ingrese y seleecione el consecutivo</small>
                   <div class="invalid-feedback" v-if="dataErrors.modulo_consecutivo">
                       <p class="m-b-0" v-for="error in dataErrors.modulo_consecutivo">@{{ error }}</p>
                   </div>
               </div>
           </div>
            <div class="form-group row m-b-15">
               <div class="col-md-12">
                  <viewer-attachement :link-file-name="true" v-if="dataForm.document_pdf" :list="dataForm.document_pdf"></viewer-attachement>
               </div>
            </div>
         </div>
         <div>
            <div class="form-group row m-b-15">
               {!! Form::label('nombre_documento', 'Nombre del documento'.':', ['class' => 'col-form-label col-md-3 required']) !!}
               <div class="col-md-9">
                  {!! Form::text('nombre_documento', null, ['class' => 'form-control', 'v-model' => 'dataForm.nombre_documento', 'required' => true]) !!}
                  <small>Ingrese el Nombre del documento.</small>
               </div>
            </div>
            <div class="form-group row m-b-15">
               {!! Form::label('ee_tipos_documentales_id', 'Tipo documental' . ':', ['class' => 'col-form-label col-md-3']) !!}
               <div class="col-md-9">
                  <select-check css-class="form-control" name-field="ee_tipos_documentales_id"
                     reduce-label="name"
                     :name-resource="'get-tipos-documentales/'+dataForm.c" :value="dataForm" :is-required="true"
                     ref-select-check="tipos_documentales_ref" :enable-search="true">
                  </select-check>
                   <small>Seleccione el tipo documental</small>
                   <div class="invalid-feedback" v-if="dataErrors.ee_tipos_documentales_id">
                       <p class="m-b-0" v-for="error in dataErrors.ee_tipos_documentales_id">@{{ error }}</p>
                   </div>
               </div>
           </div>
           <div class="form-group row m-b-15">
               {!! Form::label('fecha_documento', 'Fecha del documento'.':', ['class' => 'col-form-label col-md-3 required']) !!}
               <div class="col-md-9">
                  {!! Form::date('fecha_documento', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.fecha_documento }", 'v-model' => 'dataForm.fecha_documento', 'required' => true]) !!}
                  <small>Ingrese al fecha del documento</small>
                  <div class="invalid-feedback" v-if="dataErrors.fecha_documento">
                     <p class="m-b-0" v-for="error in dataErrors.fecha_documento">@{{ error }}</p>
                  </div>
               </div>
            </div>
            <div class="form-group row m-b-15">
               {!! Form::label('descripcion', 'Descripción:', ['class' => 'col-form-label col-md-3 required']) !!}
               <div class="col-md-9">
                   {!! Form::textarea('descripcion	', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.descripcion}", 'v-model' => 'dataForm.descripcion', 'required' => true, 'rows' => '3']) !!}
                   <small>Ingrese una descripción.</small>
                   <div class="invalid-feedback" v-if="dataErrors.descripcion">
                       <p class="m-b-0" v-for="error in dataErrors.descripcion">
                           @{{ error }}</p>
                   </div>
               </div>
           </div>
         </div>
      </div> --}}
   </div>
</div>

{{-- <div class="panel" data-sortable-id="ui-general-1" v-if="dataForm.subserie_clasificacion_documental?.criterios_busqueda">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Metadatos de la Subserie</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="form-group row m-b-15" v-for="metadato in dataForm.subserie_clasificacion_documental?.criterios_busqueda" :key="metadato.id">
            <label for="nombre_metadato" class="col-form-label col-md-3" :class="{'required': metadato.requerido}">@{{ metadato.nombre }}:</label>
            <div class="col-md-9">

                <input
                    v-if="metadato && metadato.tipo_campo !== 'Lista'"
                    :type="metadato.tipo_campo === 'Texto' ? 'text' : (metadato.tipo_campo === 'Número' ? 'number' : 'date')"
                    v-model="dataForm.metadatos[metadato.id]"
                    :name="metadato.id || ''"
                    :id="metadato.id || ''"
                    class="form-control"
                    :required="metadato.requerido">

                <select
                    v-else-if="metadato"
                    v-model="dataForm.metadatos[metadato.id]"
                    :name="metadato.id || ''"
                    :id="metadato.id || ''"
                    class="form-control"
                    :required="metadato.requerido">
                    <option v-for="(value, key) in parseOpciones(metadato.opciones)" :value="key" :key="key">@{{ value }}</option>
                </select>

                <small v-if="metadato">@{{ metadato.texto_ayuda }}</small>
            </div>
        </div>
    </div>
</div> --}}
{{-- 
<div class="panel" data-sortable-id="ui-general-1" v-else-if="dataForm.serie_clasificacion_documental?.series_osubseries?.criterios_busqueda">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Metadatos</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="form-group row m-b-15" v-for="metadato in dataForm.serie_clasificacion_documental?.series_osubseries?.criterios_busqueda" :key="metadato.id">
            <label for="nombre_metadato" class="col-form-label col-md-3" :class="{'required': metadato.requerido}">@{{ metadato.nombre }}:</label>
            <div class="col-md-9">

                <input
                    v-if="metadato && metadato.tipo_campo !== 'Lista'"
                    :type="metadato.tipo_campo === 'Texto' ? 'text' : (metadato.tipo_campo === 'Número' ? 'number' : 'date')"
                    v-model="dataForm.metadatos[metadato.id]"
                    :name="metadato.id || ''"
                    :id="metadato.id || ''"
                    class="form-control"
                    :required="metadato.requerido">

                <select
                    v-else-if="metadato"
                    v-model="dataForm.metadatos[metadato.id]"
                    :name="metadato.id || ''"
                    :id="metadato.id || ''"
                    class="form-control"
                    :required="metadato.requerido">
                    <option v-for="(value, key) in parseOpciones(metadato.opciones)" :value="key" :key="key">@{{ value }}</option>
                </select>

                <small v-if="metadato">@{{ metadato.texto_ayuda }}</small>
            </div>
        </div>
    </div>
</div> --}}
{{-- Metadatos final--}}
<div class="panel" data-sortable-id="ui-general-1" 
    v-if="dataForm.metadatos_tipo_documental?.criterios_busqueda.length > 0">
    
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Metadatos</strong></h4>
    </div>
    <!-- end panel-heading -->

    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="form-group row m-b-15" v-for="criterio in dataForm.metadatos_tipo_documental?.criterios_busqueda" :key="criterio.id">
            <label :for="criterio.id" class="col-form-label col-md-3" :class="{'required': criterio.requerido === 'Si'}">
                @{{ criterio.nombre }}:
            </label>
            <div class="col-md-9">
                
                <!-- Campo de texto, número o fecha -->
                <input
                    v-if="criterio.tipo_campo !== 'Lista'"
                    :type="criterio.tipo_campo === 'Texto' ? 'text' : (criterio.tipo_campo === 'Número' ? 'number' : 'date')"
                    v-model="dataForm.metadatos[criterio.id]"
                    :name="criterio.id || ''"
                    :id="criterio.id || ''"
                    class="form-control"
                    :required="criterio.requerido === 'Si'">

                <!-- Campo de lista desplegable -->
                <select
                    v-else
                    v-model="dataForm.metadatos[criterio.id]"
                    :name="criterio.id || ''"
                    :id="criterio.id || ''"
                    class="form-control"
                    :required="criterio.requerido === 'Si'">
                    <option v-for="(value, key) in parseOpciones(criterio.opciones)" :value="key">@{{ value }}</option>
                </select>

                <!-- Texto de ayuda -->
                <small v-if="criterio.texto_ayuda">@{{ criterio.texto_ayuda }}</small>
            </div>
        </div>
    </div>
</div>
