<ul class="nav nav-tabs" data-tabs="tabs">
    <li class="nav-item active" id="tab-radication">
        <button class="nav-link active" id="radication-button" data-toggle="tab" data-target="#radication" @click="radicatied=false" type="button" role="tab">
            1. Nueva Radicación
        </button>
    </li>

    <li class="nav-item" id="tab-rotule">
        <button class="nav-link" id="rotule-button" data-toggle="tab" data-target="#rotule" type="button" role="tab" :disabled="!radicatied" :title="!radicatied ? 'Para generar el rótulo, primero debe radicar' : ''" :style="!radicatied ? 'cursor: no-drop;' : 'cursor: pointer;'">
            2. Rótulo <i class="fa fa-stamp"></i>
        </button>
    </li>
</ul>


<div class="tab-content">
    <div class="tab-pane active" id="radication" v-if="!radicatied">
        <div class="panel" data-sortable-id="ui-general-1">
            <!-- begin panel-heading -->
            <div class="panel-heading ui-sortable-handle">
                <h4 class="panel-title"><strong>Datos generales de la correspondencia</strong></h4>
            </div>
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">

                        <!-- Title Field -->
                        <div class="form-group row m-b-15">
                            {!! Form::label('title', trans('Title') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                            <div class="col-md-9">
                                {!! Form::text('title', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.title }", 'v-model' => 'dataForm.title', 'required' => true]) !!}
                                <small>@lang('Enter the') @{{ `@lang('Title')` | lowercase }}</small>
                                <div class="invalid-feedback" v-if="dataErrors.title">
                                    <p class="m-b-0" v-for="error in dataErrors.title">
                                        @{{ error }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Content Field -->
                        {{-- <div class="form-group row m-b-15">
                        {!! Form::label('content', 'Contenido:', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-9">
                                {!! Form::textarea('content', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.content }", 'v-model' => 'dataForm.content', 'required' => true]) !!}
                                <small>@lang('Enter the') @{{ `@lang('Content')` | lowercase }}</small>
                                <div class="invalid-feedback" v-if="dataErrors.content">
                                    <p class="m-b-0" v-for="error in dataErrors.content">@{{ error }}</p>
                                </div>
                            </div>
                        </div> --}}

                        <!-- Type Document Field -->
                        <div class="form-group row m-b-15">
                            {!! Form::label('type_document', trans('Type Document') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                            <div class="col-md-9">


                                <select-check css-class="form-control" name-field="type" reduce-label="name"
                                    reduce-key="id" name-resource="get-types-internal" :value="dataForm"
                                    :is-required="true" :enable-search="true">
                                </select-check>

                                <small>@lang('Enter the') @{{ `@lang('Type Document')` | lowercase }}</small>
                                <div class="invalid-feedback" v-if="dataErrors.type_document">
                                    <p class="m-b-0" v-for="error in dataErrors.type_document">
                                        @{{ error }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Folios Field -->
                        <div class="form-group row m-b-15">
                            {!! Form::label('folios', trans('Folios') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                            <div class="col-md-4">
                                {!! Form::text('folios', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.folios }", 'v-model' => 'dataForm.folios', 'required' => true]) !!}
                                <small>@lang('Enter the') @{{ `@lang('Folios')` | lowercase }}</small>
                                <div class="invalid-feedback" v-if="dataErrors.folios">
                                    <p class="m-b-0" v-for="error in dataErrors.folios">
                                        @{{ error }}
                                    </p>
                                </div>
                            </div>

                            {!! Form::label('annexes', 'Anexos:', ['class' => 'col-form-label col-md-1']) !!}
                            <div class="col-md-4">
                                {!! Form::text('annexes', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.annexes }", 'v-model' => 'dataForm.annexes', 'required' => false]) !!}
                                <small>@lang('Enter the') @{{ `@lang('Annexes')` | lowercase }}</small>
                                <div class="invalid-feedback" v-if="dataErrors.annexes">
                                    <p class="m-b-0" v-for="error in dataErrors.annexes">
                                        @{{ error }}
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
            <!-- end panel-body -->
        </div>

        <div class="panel" data-sortable-id="ui-general-1">
            <!-- begin panel-heading -->
            <div class="panel-heading ui-sortable-handle">
                <h4 class="panel-title"><strong>Datos de origen</strong></h4>
            </div>
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">

                        <!-- From Field -->
                        <div class="form-group row m-b-15">
                            {!! Form::label('from', 'Funcionario:', ['class' => 'col-form-label col-md-3 required']) !!}
                            <div class="col-md-9">

                                <autocomplete
                                    name-prop="fullname"
                                    name-field="from_id"
                                    :value='dataForm'
                                    name-resource="/correspondence/get-only-users"
                                    css-class="form-control"
                                    :is-required="true"
                                    :name-labels-display="['fullname']"
                                    reduce-key="id"
                                    name-field-edit="from">
                                </autocomplete>
                                <small>Ingrese el nombre del funcionario y luego seleccione. Por ejemplo: Camilo.</small>
                            
                                <div class="invalid-feedback" v-if="dataErrors.from">
                                    <p class="m-b-0" v-for="error in dataErrors.from">
                                        @{{ error }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- end panel-body -->
        </div>


        @include('correspondence::internals.fields_recipients')


        <div class="panel" data-sortable-id="ui-general-1" v-if="false">
            <!-- begin panel-heading -->
            <div class="panel-heading ui-sortable-handle">
                <h4 class="panel-title"><strong>Datos de destino</strong></h4>
            </div>
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <div class="panel-body">
                <div class="row">

                    <!-- Require internal_all Field -->
                    <div class="col-md-12">
                        <div class="form-group row m-b-15">
                            {!! Form::label('internal_all', '¿Correspondencia para todos los funcionarios de la entidad?: ', ['class' => 'col-form-label col-md-3 required']) !!}
                            <div class="col-md-9">

                                <select class="form-control" id="internal_all" v-model="dataForm.internal_all" required >
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                                <Small>Seleccione "Sí" o "No" para indicar si esta correspondencia esta dirigida a toda la entidad.</Small>

                                <div class="invalid-feedback" v-if="dataErrors.internal_all">
                                    <p class="m-b-0" v-for="error in dataErrors.internal_all">
                                        @{{ error }}</p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-12" v-if="dataForm.internal_all!='1'">
                        <!--  Other officials Field destination-->
                        <div class="form-group row m-b-15">
                            {!! Form::label('users', 'Funcionarios destinatarios:', ['class' => 'col-form-label col-md-3 required']) !!}
                            <div class="col-md-9">
                                <add-list-autocomplete required = "true" :value="dataForm" name-prop="nameFalse"
                                    name-field-autocomplete="recipient_autocomplete" name-field="recipients_users"
                                    name-resource="/correspondence/get-recipients-internal"
                                    name-options-list="internal_recipients" :name-labels-display="['name']" name-key="id"
                                    help="Ingrese el nombre del funcionario en la caja y seleccione para agregar a la lista"
                                    
                                    >
                                </add-list-autocomplete>
                            </div>
                        </div>
                    </div>



                    <div class="col-md-12 mt-5">
                        <!--  Other officials Field destination-->
                        <div class="form-group row m-b-15">
                            {!! Form::label('users', 'Funcionarios de copia:', ['class' => 'col-form-label col-md-3']) !!}
                            <div class="col-md-9">
                                <add-list-autocomplete :value="dataForm" name-prop="nameFalse"
                                    name-field-autocomplete="copy_autocomplete" name-field="copies_users"
                                    name-resource="/correspondence/get-only-users"
                                    name-options-list="internal_copy" :name-labels-display="['fullname']" name-key="users_id"
                                    help="Ingrese el nombre del funcionario en la caja y seleccione para agregar a la lista"
                                    >
                                </add-list-autocomplete>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <!-- end panel-body -->
        </div>

        <div class="panel" data-sortable-id="ui-general-1">
            <!-- begin panel-heading -->
            <div class="panel-heading ui-sortable-handle">
                <h4 class="panel-title"><strong>Documento principal de la correspondencia</strong></h4>
            </div>
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <!--  Other officials Field destination-->
                        <div class="form-group row m-b-15">
                            {!! Form::label('url', 'Documento de la correspondencia radicada:', ['class' => 'col-form-label col-md-3']) !!}
                            <div class="col-md-9">
                                <input-file :file-name-real="true":value="dataForm" name-field="document_pdf" :max-files="30"
                                    :max-filesize="11" file-path="public/container/internal_{{ date('Y') }}"
                                    message="Arrastre o seleccione los archivos" help-text="Utilice este campo para cargar una copia electrónica de una carta u otro documento recibido\. El tamaño máximo permitido es de 10 MB."
                                    :is-update="isUpdate" ruta-delete-update="correspondence/internals-delete-file" :id-file-delete="dataForm.id">
                                </input-file>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end panel-body -->
        </div>

        @include('correspondence::internals.fields_respuestas_internas')


    </div>



    <div class="panel" data-sortable-id="ui-general-1" id="clasificacion" v-if="!radicatied">
        @include('correspondence::internals.field_clasificacion_documental')
    </div>

    <div class="tab-pane" id="rotule" v-show="radicatied">
        @include('correspondence::internals.rotule')

    </div>
</div>
