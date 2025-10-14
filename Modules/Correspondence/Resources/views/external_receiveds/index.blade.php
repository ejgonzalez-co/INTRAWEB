@extends('layouts.default')

@section('title', trans('External Correspondence Received'))

@section('section_img', '/assets/img/components/intranet_poll.png')

@section('menu')
@include('correspondence::layouts.menu')
@endsection

@section('content')

<crud
    name="external-receiveds"
    :resource="{default: 'external-receiveds', get: 'get-external-receiveds', edit: 'get-external-receiveds-edit', show:'external-receiveds'}"
    inline-template :init-values="{state: 3,showCounters:false}"
    :crud-avanzado="true"
    :load-data-list="'{{ request('qd') || request('qsb') || request('qder') }}' ? false : true"
    :actualizar-listado-automatico="true">
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('External Correspondence Received')</li>
        </ol>
        <!-- end breadcrumb -->
        <div class="d-flex flex-column flex-md-row align-items-center mb-5">
            <!-- begin page-header -->
            <h1 class="page-header text-center m-0 ">@{{ '@lang('External Correspondence Received')' }}</h1>
            <!-- end page-header -->

            <!-- begin widget -->
            <div class="mt-3 mt-md-0 ml-md-4">
                <button type="button" @click="getDataWidgets" data-toggle="collapse" data-target="#contenedor_tablero"
                    class="btn btn-outline-success" style="">
                    <i class="fa fa-table"></i>&nbsp;&nbsp;&nbsp;
                    <span id="text_btn_consolidado">Mostrar/Ocultar Contadores</span>
                </button>
            </div>
        </div>



        <div class="collapse border-bottom p-l-40 p-r-40 row justify-content-center pt-1" id="contenedor_tablero">

            <widget-counter
                icon="fa fa-folder"
                class-css-color="bg-grey"
                :qty="dataWidgets?.total_externas ?? 0"
                status="all"
                title="Todas"
                name-field="state"
                :value="searchFields"
                :limpiar-filtros="['external_received.id']"
                :eliminar-parametros-url="['qder']"></widget-counter>

            <widget-counter
                icon="fa fa-book"
                class-css-color="bg-warning"
                :qty="dataWidgets?.total_devueltas ?? 0"
                status="1"
                title="Devuelto"
                name-field="state"
                :value="searchFields"
                :limpiar-filtros="['external_received.id']"
                :eliminar-parametros-url="['qder']"></widget-counter>

            <widget-counter
                icon="fa fa-book-open"
                class-css-color="bg-green"
                :qty="dataWidgets?.total_publicas ?? 0"
                status="3"
                title="Público"
                name-field="state"
                :value="searchFields"
                :limpiar-filtros="['external_received.id']"
                :eliminar-parametros-url="['qder']"></widget-counter>

            <widget-counter
                icon="fa fa-share-alt"
                class-css-color="bg-teal"
                :qty="dataWidgets?.total_compartidas ?? 0"
                status="copias"
                title="Copias y Compartida"
                name-field="state"
                :value="searchFields"
                :limpiar-filtros="['external_received.id']"
                :eliminar-parametros-url="['qder']"></widget-counter>


            {{--
                esto genera un error
                <widget-counter
                icon="fa fa-book-open"
                class-css-color="bg-blue"
                :qty="dataWidgets?.estados?.filter((data) =>  data.users_shares != NULL)[0]?.total ?? 0"
                status="2"
                title="Compartida"
                name-field="state"
                :value="searchFields"
                :limpiar-filtros="['external_received.id']"
                :eliminar-parametros-url="['qder']"
            ></widget-counter> --}}

            {{-- <widget-counter
                icon="fa fa-book-open"
                class-css-color="bg-secondary"
                :qty="dataWidgets?.estados?.filter((data) =>  data.state == '4')[0]?.total ?? 0"
                status="4"
                title="Rechazado"
                name-field="state"
                :value="searchFields"
            ></widget-counter> --}}

        </div>
        @if (Auth::user()->hasRole('Consulta correspondencias'))
        <br>
        <div class="form-group row m-b-5">
            <label for="tipoConsulta" class="col-form-label col-md-1" style="font-size: 1rem;">
                Filtrar por:
            </label>
            <div class="col-md-4">
                <select id="tipoConsulta" class="form-control"
                        v-model="searchFields.typeConsult"
                        @change="_getDataListAvanzado()">
                    <option value="Correspondencia propia">🔍 Correspondencia propia</option>
                    <option value="Consulta de correspondencia">📄 Consulta de correspondencia</option>
                </select>
            </div>
        </div>
        @endif
        <!-- end widget -->

        <!-- begin main buttons -->
        <div class="m-t-20">
            
            @if (!Auth::user()->hasRole('Consulta correspondencias'))
                @role('Correspondencia Recibida Admin')

                <!-- Botón de agregar -->
                <button @click="callFunctionComponent('received_ref','add');" type="button" class="btn btn-add m-b-10" data-backdrop="static" data-target="#modal-form-external-receiveds" data-toggle="modal">
                    <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('External Correspondence Received')
                </button>

                <!-- Botón de contingencia -->
                <button @click="add()" type="button" class="btn btn-light border m-b-10" data-backdrop="static" data-target="#modal-form-migration" data-toggle="modal">
                    Contingencia
                </button>
            @endrole
            @endif

            <!-- Botón de recargar -->
            <button onclick="window.location.href = window.location.href.split('?')[0];" class="btn btn-light border btn-md m-b-10">
                <i class="fa fa-redo-alt mr-2"></i>Cargar página de nuevo
            </button>

            <div class="float-xl-right">
                <!-- Acciones para exportar datos de tabla -->
                <div class="btn-group">
                    <a href="javascript:;" data-toggle="dropdown" class="btn btn-light border">
                        <i class="fa fa-download mr-2"></i> @lang('export_data_table')
                    </a>
                    <a href="#" data-toggle="dropdown" class="btn btn-light border dropdown-toggle" aria-expanded="false">
                        <b class="caret"></b>
                    </a>
                    <div class="dropdown-menu dropdown-menu-export dropdown-menu-right" style="">
                        <a href="javascript:;" @click="exportDataTableAvanzado('pdf')" class="dropdown-item">
                            <i class="fa fa-file-pdf mr-2 text-danger"></i>Generar Reporte PDF
                        </a>
                        <a href="javascript:;" @click="exportDataTableAvanzado('xlsx')" class="dropdown-item">
                            <i class="fa fa-file-excel mr-2 text-success"></i>Generar Reporte Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('External Correspondence Received'): ${dataPaginator.total}` | capitalize }}</h5>
                </div>
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default btn-recargar-listado" title="Actualizar listado" @click="_getDataListAvanzado(false);"><i class="fa fa-redo-alt"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                </div>
            </div>
            <!-- begin #accordion search-->
            <div id="accordion" class="accordion">
                <!-- begin card search -->
                <div @click="toggleAdvanceSearch()" class="cursor-pointer card-header bg-white pointer-cursor d-flex align-items-center" data-toggle="collapse" data-target="#collapseOne">
                    <i class="fa fa-search fa-fw mr-2 f-s-12"></i> <b>@{{ (showSearchOptions)? 'trans.hide_search_options' : 'trans.show_search_options' | trans }}</b>
                </div>
                <div id="collapseOne" class="collapse border-bottom p-l-40 p-r-40" data-parent="#accordion">
                    <div class="card-body">
                        <label class="col-form-label"><b>@lang('quick_search')</b></label>
                        <!-- Campos de busqueda -->
                        <!-- Campos de busqueda -->
                        <div class="row form-group">
                            <div class="col-md-4">
                                <date-picker
                                    :value="searchFields"
                                    name-field="external_received.created_at"
                                    mode="range"
                                    :input-props="{required: true}">
                                </date-picker>
                                <small>Filtrar por fecha de recepción</small>
                            </div>
                            <div class="col-md-4">
                                <select-check
                                    css-class="form-control"
                                    name-field="year"
                                    reduce-label="year"
                                    reduce-key="valor"
                                    name-resource="/get-vigencias/external_received/year"
                                    :value="searchFields">
                                </select-check>
                                <small>Filtrar por vigencia</small>
                            </div>
                            <div class="col-md-4">
                                <input type="text" v-model="searchFields.consecutive" class="form-control" placeholder="Filtrar por consecutivo" @keyup.enter="pageEventActualizado(1)" @keyup="!searchFields.consecutive ? $delete(searchFields, consecutive) : null">

                            </div>
                            <div class="col-md-4">
                                <input type="text" v-model="searchFields.issue" class="form-control" placeholder="Filtrar por asunto" @keyup.enter="pageEventActualizado(1)" @keyup="!searchFields.issue ? $delete(searchFields, issue) : null">
                            </div>

                            <div class="col-md-4 mb-2">
                                <select-check css-class="form-control" name-field="channel" reduce-label="name"
                                    :value="searchFields" :is-required="true" :enable-search="true" :is-multiple="true" name-resource="get-constants/external_received_channels">
                                </select-check>
                                <small>Filtro por canal</small>
                            </div>

                            <div class="col-md-4 mb-2">
                                <select-check css-class="form-control" name-field="dependency_id" reduce-label="nombre"
                                    :value="searchFields" :is-required="true" :enable-search="true" :is-multiple="true" name-resource="/intranet/get-dependencies">
                                </select-check>
                                <small>Filtro por dependencia</small>
                            </div>

                            <div class="col-md-4 mb-2">
                                <input type="text" v-model="searchFields.citizen_name" class="form-control" placeholder="Filtrar por Ciudadano" @keyup.enter="pageEventActualizado(1)" @keyup="!searchFields.citizen_name ? $delete(searchFields, citizen_name) : null">
                            </div>

                            <div class="col-md-4">
                                <input type="text" v-model="searchFields.citizen_document" class="form-control" placeholder="Filtrar por Documento del Ciudadano" @keyup.enter="pageEventActualizado(1)" @keyup="!searchFields.citizen_document ? $delete(searchFields, citizen_document) : null">
                            </div>



                            <div class="col-md-4">
                                <input type="text" v-model="searchFields.functionary_name" class="form-control" placeholder="Filtrar por Destinatario" @keyup.enter="pageEventActualizado(1)" @keyup="!searchFields.functionary_name ? $delete(searchFields, functionary_name) : null">
                            </div>

                            <div class="col-md-4">
                                <input type="text" v-model="searchFields.user_name" class="form-control" placeholder="Filtrar por Radicador" @keyup.enter="pageEventActualizado(1)" @keyup="!searchFields.user_name ? $delete(searchFields, user_name) : null">
                            </div>

                            <div class="col-md-4">
                                <input type="text" v-model="searchFields.citizen_email" class="form-control" placeholder="Filtrar por el correo del ciudadano" @keyup.enter="pageEventActualizado(1)" @keyup="!searchFields.citizen_email ? $delete(searchFields, citizen_email) : null">
                            </div>

                            <div class="col-md-4">
                                <button @click="pageEventActualizado(1)" class="btn btn-md btn-add"><i
                                        class="fa fa-search mr-2"></i>Buscar</button>
                                <button @click="clearDataSearchAvanzado()"
                                    class="btn btn-md btn-light">@lang('clear_search_fields')</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end card search -->
            </div>
            <!-- end #accordion search -->
            <div class="panel-body">
                <!-- begin buttons action table -->
                {{-- <div class="float-xl-right m-b-15">
                    <!-- Acciones para exportar datos de tabla-->
                    <div class="btn-group">
                        <a href="javascript:;" data-toggle="dropdown" class="btn btn-primary"><i class="fa fa-download mr-2"></i> @lang('export_data_table')</a>
                        <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle" aria-expanded="false"><b class="caret"></b></a>
                        <div class="dropdown-menu dropdown-menu-right bg-blue">
                            <a href="javascript:;" @click="exportDataTable('pdf')" class="dropdown-item text-white no-hover"><i class="fa fa-file-pdf mr-2 text-danger" style="color:red"></i> PDF</a>
                            <a href="javascript:;" @click="exportDataTable('xlsx')" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i> EXCEL</a>
                        </div>
                    </div>
                </div> --}}
                <viewer-attachement :link-file-name="true" type="table" ref="viewerDocuments"></viewer-attachement>

                <!-- end buttons action table -->
                @include('correspondence::external_receiveds.table')
            </div>
            <div class="p-b-15 text-center">
                <!-- Cantidad de elementos a mostrar -->
                <div class="form-group row m-5 col-md-3 text-center d-sm-inline-flex">
                    {!! Form::label('show_qty', trans('show_qty').':', ['class' => 'col-form-label col-md-7']) !!}
                    <div class="col-md-5">
                        {!! Form::select(trans('show_qty'), [5 => 5, 10 => 10, 15 => 15, 20 => 20, 25 => 25, 30 => 30, 50 => 50, 75 => 75], 20, ['class' => 'form-control', 'v-model' => 'dataPaginator.pagesItems', '@change' => 'pageEventActualizado(1)']) !!}
                    </div>
                </div>
                <!-- Paginador de tabla -->
                <div class="col-md-12">
                    <paginate
                        v-model="dataPaginator.currentPage"
                        :page-count="dataPaginator.numPages"
                        :click-handler="pageEventActualizado"
                        :prev-text="'Anterior'"
                        :next-text="'Siguiente'"
                        :container-class="'pagination m-10'"
                        :page-class="'page-item'"
                        :page-link-class="'page-link'"
                        :prev-class="'page-item'"
                        :next-class="'page-item'"
                        :prev-link-class="'page-link'"
                        :next-link-class="'page-link'"
                        :disabled-class="'ignore disabled'">
                    </paginate>
                </div>
            </div>
        </div>
        <!-- end panel -->

        <!-- begin #modal-view-external-receiveds -->
        <div class="modal fade" id="modal-view-external-receiveds">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white d-flex justify-content-between align-items-center w-100">
                            <span>@lang('info_of') de la correspondencia externa recibida: @{{dataShow?.consecutive}}</span>
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="spinner" v-if="!Object.keys(dataShow).length" style="position: absolute;top: 20%; z-index: 1;"><span class="spinner-inner"></span></div>

                    <div class="modal-body" :style="{ opacity: !Object.keys(dataShow).length ? 0.2 : 1 }" id="showFields">
                        @include('correspondence::external_receiveds.show_fields')
                    </div>
                    <div class="modal-footer">

                        {{-- <button class="btn btn-warning" type="button" onclick="printContentDetail('showFields');"><i class="fa fa-print mr-2"></i>@lang('print')</button> --}}
                        <button class="btn btn-add" type="button" v-print="{id: 'showFields', beforeOpenCallback, openCallback, closeCallback}" :disabled="printOpened">
                            <i class="fa fa-print mr-2" v-if="!printOpened"></i>
                            <div class="spinner mr-2" style="position: sticky; float: inline-start; width: 18px; height: 18px; margin: auto;" v-else></div>
                            @lang('print')
                        </button>

                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-external-receiveds -->

        <external-received inline-template ref="received_ref">
            <!-- begin #modal-form-external-receiveds -->
            <div class="modal fade" id="modal-form-external-receiveds">
                <div class="modal-dialog modal-xl">
                    <form @submit.prevent="save()" id="form-external-receiveds" autocomplete="off">
                        <div class="modal-content border-0">
                            <div class="modal-header bg-blue">
                                <h4 class="modal-title text-white">Creación y edición de correspondencia recibida externa</h4>
                                <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                            </div>
                            <div class="modal-body" id="form-external-received-body" v-if="openForm">
                                @include('correspondence::external_receiveds.fields')
                            </div>
                            <div class="modal-footer">
                                <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                                <button v-if="!radicatied" type="submit" class="btn btn-add"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- end #modal-form-external-receiveds -->
        </external-received>

        <div class="modal fade" id="modal-view-rotule">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Rótulo de correspondencia</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="callFunctionComponent('rotulo_recibida','limpiarDatos','')"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body" id="view-rotule">
                        {{-- @include('correspondence::external_receiveds.rotule-show') --}}
                        <rotule-component  
                        type-call="rotule_index"
                        :update-props="{{ Auth::user()->hasRole('Correspondencia Recibida Admin') ? 'true' : 'false' }}"
                        name='{{ config('app.name') }}' execute-url-axios-preview="document-preview-external-received/" execute-url-axios-rotular="document-with-rotule-external-received/"
                        ref="rotulo_recibida"></rotule-component>
                    </div>
                    <div class="modal-footer">
                        {{-- <button class="btn btn-warning" type="button" onclick="printContent('view-rotule');"><i class="fa fa-print mr-2"></i>@lang('print')</button> --}}
                        {{-- <button class="btn btn-warning" type="button" v-print="'#page-rotule-show'"><i class="fa fa-print mr-2"></i>@lang('print')</button> --}}

                        <button class="btn btn-white" data-dismiss="modal" @click="callFunctionComponent('rotulo_recibida','limpiarDatos','')"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Invoca el formulario de para compartir --}}
        {{-- <share-correspondence-user ref="share-correspondence-user" name="external-share"></share-correspondence-user> --}}


        <dynamic-modal-form modal-id="share-external" size-modal="lg" :title="'Compartir correspondencia externa ' + dataForm.consecutive"
            :data-form.sync="dataForm" endpoint="external-share-received" :is-update="true"
            @saved="
            if($event.isUpdate) {
                assignElementList(dataForm.id, $event.data);
            } else {
                addElementToList($event.data);
            }">
            <template #fields="scope">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row m-b-15">
                            <h1 for="danger" class="col-form-label col-md-12">Compartir correspondencia</h1>
                        </div>
                    </div>

                    <div class="col-md-12 mt-5">
                        <!--  Other officials Field destination-->
                        <div class="form-group row m-b-15">
                            {!! Form::label('annotation', 'Anotación:', ['class' => 'col-form-label col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::textarea('annotation', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.annotation }", 'v-model' => 'dataForm.annotation', 'required' => false]) !!}
                                <small>@lang('Enter the') el contenido de la anotación.</small>
                                <div class="invalid-feedback" v-if="dataErrors.annotation">
                                    <p class="m-b-0" v-for="error in dataErrors.annotation">@{{ error }}</p>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="col-md-12">
                        <!--  Other officials Field destination-->
                        <div class="form-group row m-b-15">
                            {!! Form::label('users', 'Funcionarios:', ['class' => 'col-form-label col-md-3']) !!}
                            <div class="col-md-9">
                                <add-list-autocomplete :value="dataForm" name-prop="nameFalse"
                                    name-field-autocomplete="shares_autocomplete" name-field="shares_users"
                                    name-resource="/correspondence/get-only-users" name-options-list="external_shares"
                                    :name-labels-display="['fullname']" name-key="users_id"
                                    help="Ingrese el nombre del funcionario en la caja y seleccione para agregar a la lista">
                                </add-list-autocomplete>
                            </div>
                        </div>
                    </div>


                </div>

            </template>
        </dynamic-modal-form>

        <!-- modal para la migracion PQR -->
        <dynamic-modal-form
            modal-id="modal-form-migration"
            size-modal="lg"
            :data-form="dataForm"
            title="Migrar contingencia de correspondencia recibida"
            endpoint="migration-modal-externals"
            @saved="
            if($event.isUpdate) {
                assignElementList(dataForm.id, $event.data);
            } else {
                addElementToList($event.data);
            }">
            <template #fields="scope">
                <div>
                    <div class="panel mt-2" style="border: 200px; padding: 15px;">
                        <div>
                            <a href="/assets/documents/Formatos/Formato de contingencia recibida.xlsx" target="_blank">Descargar formato para contingencia de correspondencia recibida.</a>
                            <div class="form-group row m-b-15 mt-4">
                                {!! Form::label('file_import', trans('Adjuntar documento').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                {!! Form::file('file_import', ['accept' => '.xls,.xlsx', '@change' => 'inputFile($event, "file_import")', 'required' => true]) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </dynamic-modal-form>
        <!-- modal para la migracion de la PQR -->

        <!-- Modal para visualizar un modal de alerta de confirmacion -->
        <alert-confirmation
            ref="alert-confirmation"
            loading-data="Devolviendo correspondencia recibida al administrador..."
            title="¿Está seguro de devolver la correspondencia recibida?"
            confirmation-text="Devolver correspondencia"
            cancellation-text="Cancelar"
            name-resource="return-external-receiveds"
            title-successful-shipment="Correspondencia devuelta"
            :textarea="true"
            secondary-text="Por favor, ingrese el motivo de la devolución de esta correspondencia.">
        </alert-confirmation>


        <annotations-general ref="annotations" route="/correspondence/received-annotations" name-list="external_annotations" file-path="public/container/external_receiveds_{{ date('Y') }}/anotaciones" field-title="Anotaciones de correspondencia Recibida: " field-title-var="consecutive" name-content="annotation"></annotations-general>

        {{-- Invoca el formulario de registro de correspondencia externa recibida --}}
        {{-- <external-received ref="external-received" name="external-receiveds"></external-received> --}}

        @if(config('app.mod_expedientes'))
            <expedientes-general
                ref="expedientes"
                :campo-consecutivo="'consecutive'"
                :modulo="'Correspondencia recibida'"
                :puede-crear-expedientes="{{ Auth::user()->roles->pluck('name')->intersect(['Operador Expedientes Electrónicos'])->isNotEmpty() ? 'true' : 'false' }}"
                :user-id="{{ Auth::user()->id }}"
            ></expedientes-general>
        @endif
    </div>
</crud>
@endsection


@push('css')
{!! Html::style('assets/plugins/gritter/css/jquery.gritter.css') !!}
<style>
    .oculto {
        display: none !important;
    }

    .visible {
        display: inline !important;
    }

    .vdr.active:before {
        outline: none !important;
    }
</style>
@endpush


@push('scripts')
{!!Html::script('assets/plugins/gritter/js/jquery.gritter.js')!!}
<script>
    // Función para imprimir el contenido de un identificador pasado por parámetro
    function printContentDetail(divName) {

        // Se obtiene el elemento del id recibido por parámetro
        var printContent = document.getElementById(divName);
        // Se guarda en una variable la nueva pestaña
        var printWindow = window.open("");
        // Se obtiene el encabezado de la página actual para no peder estilos
        var headContent = document.getElementsByTagName('head')[0].innerHTML;
        // Se escribe todo el contenido del encabezado de la página actual en la pestaña nueva que se abrirá
        printWindow.document.write(headContent);
        // Se escribe todo el contenido del id recibido por parámetro en la pestaña nueva que se abrirá
        printWindow.document.write(printContent.innerHTML);
        printWindow.document.close();
        // Se enfoca en la pestaña nueva
        printWindow.focus();
        // Se abre la ventana para imprimir el contenido de la pestaña nueva
        printWindow.onload = function() {
            printWindow.print();
            printWindow.close();
        };
    }


    // Función para imprimir el contenido de un identificador pasado por parámetro
    function printContent(divName) {
        $("#btn-rotule-print, #btn-rotule-print-rotule, #attach, #attach-rotule").hide();

        // Se obtiene el elemento del id recibido por parámetro
        var printContent = document.getElementById(divName);
        // Se guarda en una variable la nueva pestaña
        var printWindow = window.open('', 'PRINT', 'height=500,width=800');
        // Se obtiene el encabezado de la página actual para no peder estilos
        var headContent = document.getElementsByTagName('head')[0].innerHTML;
        // Se escribe todo el contenido del encabezado de la página actual en la pestaña nueva que se abrirá
        printWindow.document.write(headContent);
        const regex = /font-size: 11px;/ig;
        // Se escribe todo el contenido del id recibido por parámetro en la pestaña nueva que se abrirá
        printWindow.document.write((printContent.innerHTML).replaceAll(regex, "font-size: 11px; font-family: Arial;"));
        printWindow.document.close();
        // Se enfoca en la pestaña nueva
        printWindow.focus();
        // Se abre la ventana para imprimir el contenido de la pestaña nueva
        printWindow.onload = function() {
            printWindow.print();
            printWindow.close();
        };

        $("#btn-rotule-print, #btn-rotule-print-rotule, #attach, #attach-rotule").show();

    }

    const elementoDiv = document.getElementById('show_table');
    elementoDiv.style.display = 'none';

    // función encargada de mostrar y ocultar el flujo documental
    function toggleDiv(divId) {
        const elementoDiv = document.getElementById(divId);
        var otroDivId = '';
        if (elementoDiv.id === 'show_cards') {
            otroDivId = 'show_table';
        } else if (elementoDiv.id === 'show_table') {
            otroDivId = 'show_cards';
        }

        const otroElementoDiv = document.getElementById(otroDivId);

        if (elementoDiv && otroElementoDiv) {


            const estiloActual = elementoDiv.style.display;
            const estiloActual2 = otroElementoDiv.style.display;
            elementoDiv.style.display = estiloActual === 'none' ? 'block' : 'none';
            otroElementoDiv.style.display = otroElementoDiv === 'block' ? 'none' : 'block';

        } else {
            console.error(`El elemento Div con ID ${divId} o ${otroDivId} no se encontró.`);
        }
    }

    const btnCard = document.getElementById('btnCard');
    const btnTable = document.getElementById('btnTable');
    const btnDowLoad = document.getElementById('btnDowLoad');


    // Función para cambiar el color al hacer clic y mantener presionado
    function changeColorOnHold(button) {
        button.style.color = '#4d90fe';
    }

    // Función para restaurar el color al soltar el clic
    function restoreColor(button) {
        button.style.color = '#5f6368';
    }

    // Evento al hacer clic y mantener presionado
    btnCard.addEventListener('mousedown', function() {
        changeColorOnHold(btnCard);
    });

    // Evento al soltar el clic
    btnCard.addEventListener('mouseup', function() {
        restoreColor(btnCard);
    });

    btnTable.addEventListener('mousedown', function() {
        changeColorOnHold(btnTable);
    });
    btnTable.addEventListener('mouseup', function() {
        restoreColor(btnTable);
    });

    btnDowLoad.addEventListener('mousedown', function() {
        changeColorOnHold(btnDowLoad);
    });
    btnDowLoad.addEventListener('mouseup', function() {
        restoreColor(btnDowLoad);
    });




    // detecta el enter para no cerrar el modal sin enviar el formulario
    $('#modal-form-external-receiveds').on('keypress', ':input:not(textarea):not([type=submit])', function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
        }
    });

    $(document).ready(function() {
        $('#content-rotule').draggable();
    });
</script>
<style>
   

    

    /* Íconos con separación */
    .mr-2 {
        margin-right: 8px;
    }
</style>
@endpush