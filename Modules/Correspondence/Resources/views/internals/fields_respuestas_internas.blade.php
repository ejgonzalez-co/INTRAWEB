<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Otros</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <!-- Require Answer Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('require_answer', '¿Qué requiere hacer con esta correspondencia?:', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">

                        <select class="form-control" id="require_answer" 
                                v-model="dataForm.require_answer" 
                                :disabled="dataForm.estado_respuesta == 'Finalizado'">
                            <option value="No">Ninguna acción</option>
                            <option value="Se requiere que esta correspondencia reciba una respuesta">Se requiere que esta correspondencia reciba una respuesta</option>
                            <option value="Responder a otra correspondencia">Responder a otra correspondencia</option>
                        </select>
                        
                        <small>Seleccione "Responder a otra correspondencia" si está respondiendo a una anterior. Elija "Se requiere una respuesta" si esta correspondencia la necesita. Si no requiere acción, seleccione "Ninguna acción".</small>

                        <div class="invalid-feedback" v-if="dataErrors.require_answer">
                            <p class="m-b-0" v-for="error in dataErrors.require_answer">
                                @{{ error }}</p>
                        </div>
                    </div>
                </div>

                <!-- Answer Consecutive Field -->
                <div class="form-group row m-b-15" v-if="dataForm.require_answer=='Responder a otra correspondencia'">
                    {!! Form::label('answer_consecutive', 'Respuesta a correspondencia:', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">

                        <autocomplete 
                            name-prop="consecutive" 
                            name-field="answer_consecutive" 
                            :value='dataForm'
                            name-resource="/correspondence/get-internals" 
                            css-class="form-control"
                            :is-required="true"
                            :name-labels-display="['type_document', 'consecutive', 'title']" 
                            reduce-key="id" 
                            name-field-edit="answer_consecutive_name"
                            :activar-blur="true"
                            :min-text-input="9"
                            >
                        </autocomplete>
                        <Small>Ingrese el número consecutivo de la correspondencia interna que desea responder.</Small>

                        <div class="invalid-feedback" v-if="dataErrors.answer_consecutive">
                            <p class="m-b-0" v-for="error in dataErrors.answer_consecutive">
                                @{{ error }}</p>
                        </div>
                    </div>
                </div>

                <div class="form-group row m-b-15" v-if="dataForm.require_answer=='Se requiere que esta correspondencia reciba una respuesta'">
                    {!! Form::label('fecha_limite_respuesta', 'Fecha limite para responder la correspondencia:', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">

                        {!! Form::date('fecha_limite_respuesta', null, [
                            ':class' => "{'form-control': true, 'is-invalid': dataErrors.fecha_limite_respuesta }",
                            'v-model' => 'dataForm.fecha_limite_respuesta',
                            'required' => true,
                            'placeholder' => 'YYYY-MM-DD',
                            ':disabled' => "dataForm.estado_respuesta === 'Finalizado'"
                        ]) !!}

                        <small>Seleccione la fecha límite para recibir respuesta a esta correspondencia.</small>

                        <div class="invalid-feedback" v-if="dataErrors.fecha_limite_respuesta">
                            <p class="m-b-0" v-for="error in dataErrors.fecha_limite_respuesta">
                                @{{ error }}</p>
                        </div>
                    </div>

                    {!! Form::label('responsable_respuesta', 'Responsable de la respuesta: ', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9" v-if="dataForm.estado_respuesta === 'Finalizado'" >
                        <select-check 
                            css-class="form-control" 
                            name-field="responsable_respuesta" 
                            reduce-label="fullname"
                            reduce-key="id" 
                            name-resource="get-responsables-respuesta-internal" 
                            :value="dataForm"
                            :is-required="true" 
                            :enable-search="true"
                            :element-disabled="true">
                        </select-check>
                        <small>Seleccione el responsable de la respuesta</small>
                    </div>
                    <div class="col-md-9" v-else>
                        <select-check 
                            css-class="form-control" 
                            name-field="responsable_respuesta" 
                            reduce-label="fullname"
                            reduce-key="id" 
                            name-resource="get-responsables-respuesta-internal" 
                            :value="dataForm"
                            :is-required="true" 
                            :enable-search="true">
                        </select-check>
                        <small>Seleccione el responsable de la respuesta</small>
                    </div>
                </div>

            </div>

            <div class="col-md-12">
                <!--  Other officials Field destination-->
                <div class="form-group row m-b-15">
                    {!! Form::label('url', 'Lista de archivos anexos:', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <input-file :file-name-real="true":value="dataForm" name-field="annexes_digital" :max-files="30"
                            :max-filesize="11" file-path="public/container/internal_{{ date('Y') }}"
                            message="Arrastre o seleccione los archivos" help-text="Lista de archivos anexos. El tamaño máximo permitido es de 10 MB."
                            :is-update="isUpdate"  ruta-delete-update="correspondence/internals-delete-file" :id-file-delete="dataForm.id">
                        </input-file>

                    </div>
                </div>

                <div class="col-md-12">
                    <!-- Annexes Description Field -->
                    <div class="form-group row m-b-15">
                        {!! Form::label('annexes_description', 'Descripción de anexos:', ['class' => 'col-form-label col-md-3']) !!}
                        <div class="col-md-9">
                            {!! Form::textarea('annexes_description', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.annexes_description }", 'v-model' => 'dataForm.annexes_description', 'required' => false, 'rows' => '3']) !!}
                            <small>Ingrese una descripción de los anexos</small>
                            <div class="invalid-feedback" v-if="dataErrors.annexes_description">
                                <p class="m-b-0" v-for="error in dataErrors.annexes_description">
                                    @{{ error }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>

        </div>

    </div>

</div>
