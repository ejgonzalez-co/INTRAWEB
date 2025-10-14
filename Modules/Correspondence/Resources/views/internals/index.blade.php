@extends('layouts.default')

@section('title', 'Correspondencia Interna')

@section('section_img', '/assets/img/components/intranet_poll.png')

@section('menu')
@include('correspondence::layouts.menu')
@endsection

@section('content')
{{-- Ejemplo de firma --}}
{{-- <sign-external-component
url-document="http://192.168.1.11:8080/storage/container/internal_2024/ci8Po3xGhwTXDQDTjt09srYYBhjDfMEzYSvZu9Hp.pdf"
execute-url-axios="/guardar-prueba"
></sign-external-component> --}}
<crud
    name="internals"
    :resource="{default: 'internals', get: 'get-internals', edit: 'get-internal-edit'}"
    inline-template :crud-avanzado="true"
    :init-values="{internal_recipients:[],message_confirmation : true, message_confirmation_text : '¿Está seguro de que desea cambiar el estado del documento a --tipo--? Este documento, identificado como --type_document--, --title--, con el consecutivo --consecutive--.' }"
    :load-data-list="'{{ request('qd') || request('qsb') || request('qder') }}' ? false : true"
    :actualizar-listado-automatico="true">
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">Correspondencias Internas</li>
        </ol>
        <!-- end breadcrumb -->

        <div class="d-flex flex-md-row justify-items-center mb-5">

            <!-- begin page-header -->
            <h1 class="page-header text-center m-0"> @{{ 'Correspondencias @lang('internals')'}}</h1>
            <!-- end page-header -->
            <div class="mt-3 mt-md-0 ml-md-4">
                <button type="button" @click="getDataWidgets" data-toggle="collapse" data-target="#contenedor_tablero" class="btn btn-outline-success">
                    <i class="fa fa-table"></i>&nbsp;&nbsp;&nbsp;
                    <span id="text_btn_consolidado">Mostrar/Ocultar Contadores</span>
                </button>
            </div>

        </div>

        <!-- begin widget tablero de control interna -->
        <div class="collapse border-bottom p-l-40 p-r-40 row pt-1" id="contenedor_tablero">

            <!-- Sección 1: Contadores de correspondencias internas por estado -->
            <div class="col-12">
                <h5>Estados de Correspondencia Interna</h5>
                <div class="row">
                    {{-- Total de correspondencias internas --}}
                    <widget-counter
                        icon="fa fa-folder"
                        class-css-color="bg-grey"
                        :qty="dataWidgets?.total_internas ?? 0"
                        status="all"
                        title="Total"
                        name-field="state"
                        :value="searchFields"
                        :limpiar-filtros="['correspondence_internal.id', 'total_respuestas']"
                        :eliminar-parametros-url="['qd','qsb','qder']"></widget-counter>

                    {{-- Total en estado de elaboración --}}
                    <widget-counter
                        icon="fa fa-file-alt"
                        class-css-color="bg-blue"
                        :qty="dataWidgets.estados?.elaboracion ?? 0"
                        status="Elaboración"
                        title="Elaboración"
                        name-field="state"
                        :value="searchFields"
                        :limpiar-filtros="['correspondence_internal.id', 'total_respuestas']"
                        :eliminar-parametros-url="['qd','qsb','qder']"></widget-counter>

                    {{-- Total en estado de revisión --}}
                    <widget-counter
                        icon="fa fa-clone"
                        class-css-color="bg-yellow"
                        :qty="dataWidgets.estados?.revision ?? 0"
                        status="Revisión"
                        title="Revisión"
                        name-field="state"
                        :value="searchFields"
                        :limpiar-filtros="['correspondence_internal.id', 'total_respuestas']"
                        :eliminar-parametros-url="['qd','qsb','qder']"></widget-counter>

                    {{-- Total en estado de aprobación --}}
                    <widget-counter
                        icon="fa fa-thumbs-up"
                        class-css-color="bg-cyan"
                        :qty="dataWidgets.estados?.aprobacion ?? 0"
                        status="Aprobación"
                        title="Aprobación"
                        name-field="state"
                        :value="searchFields"
                        :limpiar-filtros="['correspondence_internal.id', 'total_respuestas']"
                        :eliminar-parametros-url="['qd','qsb','qder']"></widget-counter>

                    {{-- Total en estado pendiente de firmas --}}
                    <widget-counter
                        icon="fa fa-file-contract"
                        class-css-color="bg-orange"
                        :qty="dataWidgets.estados?.firmar_varios ?? 0"
                        status="Pendiente de firma"
                        title="Pendiente de firmas"
                        name-field="state"
                        :value="searchFields"
                        :limpiar-filtros="['correspondence_internal.id', 'total_respuestas']"
                        :eliminar-parametros-url="['qd','qsb','qder']"></widget-counter>

                    {{-- Total en estado devuelto para modificaciones --}}
                    <widget-counter
                        icon="fa fa-reply"
                        class-css-color="bg-red"
                        :qty="dataWidgets.estados?.devuelto_para_modificaciones ?? 0"
                        status="Devuelto"
                        title="Devuelto para modificaciones"
                        name-field="state"
                        :value="searchFields"
                        :limpiar-filtros="['correspondence_internal.id', 'total_respuestas']"
                        :eliminar-parametros-url="['qd','qsb','qder']"></widget-counter>

                    {{-- Total en estado de público --}}
                    <widget-counter
                        icon="fa fa-check-circle"
                        class-css-color="bg-green"
                        :qty="dataWidgets.estados?.publico ?? 0"
                        status="Público"
                        title="Público"
                        name-field="state"
                        :value="searchFields"
                        :limpiar-filtros="['correspondence_internal.id', 'total_respuestas']"
                        :eliminar-parametros-url="['qd','qsb','qder']"
                        :limpiar-filtros="['total_respuestas']"></widget-counter>

                    {{-- Total de correspondencias compartidas --}}
                    <widget-counter
                        icon="fa fa-share-alt"
                        class-css-color="bg-teal"
                        :qty="dataWidgets.total_compartidas ?? 0"
                        status="copias"
                        title="Copias y Compartida"
                        name-field="state"
                        :value="searchFields"
                        :limpiar-filtros="['correspondence_internal.id', 'total_respuestas']"
                        :eliminar-parametros-url="['qd','qsb','qder']"></widget-counter>
                </div>
            </div>

            <!-- Sección 2: Contadores de respuestas a correspondencias -->
            <div class="col-12 mt-3">
                <h5>Correspondencias que requieren mi respuesta</h5>
                <div class="row">
                    <!-- Pendiente de respuesta -->
                    <widget-counter
                        icon="fa fa-clock-o"
                        class-css-color="bg-orange"
                        :qty="dataWidgets?.count_pendiente_respuesta ?? 0"
                        status="pendiente_respuesta"
                        title="Pendiente de respuesta"
                        name-field="total_respuestas"
                        :value="searchFields"
                        :limpiar-filtros="['correspondence_internal.id']"
                        :eliminar-parametros-url="['qd','qsb','qder']"></widget-counter>

                    <!-- Pendiente de respuesta vencidas -->
                    <widget-counter
                        icon="fa fa-exclamation-triangle"
                        class-css-color="bg-red"
                        :qty="dataWidgets?.count_pendiente_respuesta_vencidas ?? 0"
                        status="pendiente_respuesta_vencidas"
                        title="Pendiente de respuesta Vencidas"
                        name-field="total_respuestas"
                        :value="searchFields"
                        :limpiar-filtros="['correspondence_internal.id']"
                        :eliminar-parametros-url="['qd','qsb','qder']"></widget-counter>

                    <!-- Respuestas finalizadas -->
                    <widget-counter
                        icon="fa fa-check-circle"
                        class-css-color="bg-green"
                        :qty="dataWidgets?.count_respuestas_finalizadas ?? 0"
                        status="respuestas_finalizadas"
                        title="Finalizadas"
                        name-field="total_respuestas"
                        :value="searchFields"
                        :limpiar-filtros="['correspondence_internal.id']"
                        :eliminar-parametros-url="['qd','qsb','qder']"></widget-counter>
                </div>
            </div>

        </div>
        <!-- end widget -->


        <!-- begin main buttons -->
        <div class="m-t-20">

            @role('Correspondencia Interna Admin')
            <!-- begin main buttons -->
            <button @click="callFunctionComponent('internal_ref','add')" type="button" class="btn btn-add m-b-10" data-backdrop="static" data-target="#modal-form-internals" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('interna')
            </button>
            <!-- end main buttons -->
            @endrole


            {{-- @if (isset($mostrar) && $mostrar === 'si') --}}
            <button @click="callFunctionComponent('internal_ref', 'loadInterna');" type="button" class="btn btn-add m-b-10" data-backdrop="static">
                <i class="fa fa-plus mr-2"></i>@lang('crud.create') interna cero papel
            </button>
            {{-- @endif --}}

            <button onclick="window.location.href = window.location.href.split('?')[0];" class="btn btn-md btn-light m-b-10"><i class="fa fa-redo-alt mr-2"></i>Cargar página de nuevo</button>

            <div class="float-xl-right">
                <!-- Acciones para exportar datos de tabla-->
                <div class="btn-group">
                    <a href="javascript:;" data-toggle="dropdown" class="btn btn-light"><i class="fa fa-download mr-2"></i> @lang('export_data_table')</a>
                    <a href="#" data-toggle="dropdown" class="btn btn-light dropdown-toggle" aria-expanded="false"><b class="caret"></b></a>
                    <div class="dropdown-menu dropdown-menu-export dropdown-menu-right">

                        <a href="javascript:;" @click="exportDataTableAvanzado('pdf')" class="dropdown-item"><i class="fa fa-file-pdf mr-2 text-danger"></i></i> Reporte PDF</a>
                        <a href="javascript:;" @click="exportDataTableAvanzado('xlsx')" class="dropdown-item"><i class="fa fa-file-excel mr-2 text-success"></i></i>Reporte Excel</a>
                        {{-- <a href="export-exaple" class="dropdown-item text-white no-hover" target="_blank">EXCEL</a> --}}
                    </div>
                </div>
            </div>

        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('internals'): ${dataPaginator.total}` | capitalize }}</h5>
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
                        <div class="row form-group">


                            <div class="col-md-4 p-b-5">
                                <select-check
                                    css-class="form-control"
                                    name-field="year"
                                    reduce-label="year"
                                    reduce-key="valor"
                                    name-resource="/get-vigencias/correspondence_internal/year"
                                    :value="searchFields">
                                </select-check>
                                <small>Filtrar por vigencia</small>
                            </div>

                            <div class="col-md-4 p-b-5">
                                <date-picker
                                    :value="searchFields"
                                    name-field="correspondence_internal.created_at"
                                    mode="range"
                                    :input-props="{required: true}">
                                </date-picker>
                                <small>Filtrar por fecha de creación</small>
                            </div>
                            <div class="col-md-4">
                                <input type="text" v-model="searchFields.consecutive" class="form-control" placeholder="Filtrar por Consecutivo" @keyup.enter="pageEventActualizado(1)" @keyup="!searchFields.consecutive ? $delete(searchFields, consecutive) : null">

                            </div>
                            <div class="col-md-4 p-b-5">
                                <input type="text" v-model="searchFields.title" class="form-control" placeholder="Filtrar por Título" @keyup.enter="pageEventActualizado(1)" @keyup="!searchFields.title ? $delete(searchFields, title) : null">
                                <small>Ingrese un fragmento del título del documento</small>

                            </div>

                            <div class="col-md-4">
                                <input type="text" v-model="searchFields.users_name" class="form-control" placeholder="Filtrar por Radicador" @keyup.enter="pageEventActualizado(1)" @keyup="!searchFields.users_name ? $delete(searchFields, users_name) : null">
                            </div>

                            <div class="col-md-4">
                                <input type="text" v-model="searchFields.from" class="form-control" placeholder="Filtrar por Remitente" @keyup.enter="pageEventActualizado(1)" @keyup="!searchFields.from ? $delete(searchFields, from) : null">
                            </div>


                            <div class="col-md-4 p-b-5">
                                <select-check
                                    css-class="form-control"
                                    name-field="correspondence_internal.type"
                                    reduce-label="name"
                                    reduce-key="id"
                                    name-resource="get-internal-types"
                                    :value="searchFields"
                                    :is-required="true"
                                    :enable-search="true">
                                </select-check>
                                <small>Filtro por tipo de documento</small>
                            </div>

                            <div class="col-md-4">
                                <select-check
                                    css-class="form-control"
                                    name-field="correspondence_internal.dependencias_id"
                                    reduce-label="nombre"
                                    reduce-key="id"
                                    name-resource="/intranet/get-dependencies"
                                    :value="searchFields"
                                    :enable-search="true"
                                    :is-multiple="true">
                                </select-check>
                                <small>Filtro por dependencias</small>
                            </div>

                            <div class="col-md-4">
                                <input type="text" v-model="searchFields.recipients" class="form-control" placeholder="Filtrar por Destinatario" @keyup.enter="pageEventActualizado(1)" @keyup="!searchFields.recipients ? $delete(searchFields, recipients) : null">
                            </div>

                            <div class="col-md-4">
                                <select class="form-control" name="origen" v-model="searchFields.origen">
                                    <option value="DIGITAL">DIGITAL</option>
                                    <option value="FISICO">FISICO</option>
                                </select>
                                <small>Filtro por origen</small>
                            </div>

                            <div class="col-md-4">
                                <input type="text" v-model="searchFields.elaborated_names" class="form-control" placeholder="Filtrar por quién elaboró el documento" @keyup.enter="pageEventActualizado(1)" @keyup="!searchFields.elaborated_names ? $delete(searchFields, elaborated_names) : null">
                                <small>Filtrar por quién elaboró el documento</small>
                            </div>

                            <div class="col-md-4">
                                <input type="text" v-model="searchFields.reviewd_names" class="form-control" placeholder="Filtrar por quién revisó el documento" @keyup.enter="pageEventActualizado(1)" @keyup="!searchFields.reviewd_names ? $delete(searchFields, reviewd_names) : null">
                                <small>Filtrar por quién revisó el documento</small>
                            </div>

                            <div class="col-md-4">
                                <input type="text" v-model="searchFields.approved_names" class="form-control" placeholder="Filtrar por quién aprobó el documento" @keyup.enter="pageEventActualizado(1)" @keyup="!searchFields.approved_names ? $delete(searchFields, approved_names) : null">
                                <small>Filtrar por quién aprobó el documento</small>
                            </div>

                            <div class="col-md-4">
                                <select class="form-control" name="status_permission_check" v-model="searchFields.status_permission_check">
                                    <option value="Si">Si</option>
                                    <option value="No">No</option>
                                </select>
                                <small>Filtro por correspondencia chequeada</small>
                            </div>

                            <div class="col-md-4"><br>
                                <button @click="pageEventActualizado(1)" class="btn btn-add"><i class="fa fa-search mr-2"></i>Buscar</button>
                                <button @click="clearDataSearchAvanzado()" class="btn btn-md btn-light">@lang('clear_search_fields')</button>
                            </div>


                        </div>
                    </div>
                </div>
                <!-- end card search -->
            </div>
            <!-- end #accordion search -->
            <div class="panel-body">
                <!-- begin buttons action table -->
                {{-- <div class="float-xl-right m-b-15" v-if="dataList.length">
                    <!-- Acciones para exportar datos de tabla-->
                    <div class="btn-group">
                        <a href="javascript:;" data-toggle="dropdown" class="btn btn-primary"><i class="fa fa-download mr-2"></i> @lang('export_data_table')</a>
                        <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle" aria-expanded="false"><b class="caret"></b></a>
                        <div class="dropdown-menu dropdown-menu-right bg-blue">
                            <a href="javascript:;" @click="exportDataTable('pdf')" class="dropdown-item text-white no-hover"><i class="fa fa-file-pdf mr-2"></i> PDF</a>
                            <a href="javascript:;" @click="exportDataTable('xlsx')" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i> EXCEL</a>
                        </div>
                    </div>
                </div> --}}
                <viewer-attachement type="table" ref="viewerDocuments"></viewer-attachement>

                @if(config('app.mod_expedientes'))
                    <expedientes-general
                        ref="expedientes"
                        :campo-consecutivo="'consecutive'"
                        :modulo="'Correspondencia interna'"
                        :puede-crear-expedientes="{{ Auth::user()->roles->pluck('name')->intersect(['Operador Expedientes Electrónicos'])->isNotEmpty() ? 'true' : 'false' }}"
                        :user-id="{{ Auth::user()->id }}"
                    ></expedientes-general>
                @endif

                <!-- end buttons action table -->
                @include('correspondence::internals.table')
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

        <!-- begin #modal-view-internals -->
        <div class="modal fade" id="modal-view-rotule">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Rótulo de correspondencia</h4>
                        <button type="button" class="close" data-dismiss="modal"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body" id="view-rotule">
                        @include('correspondence::internals.rotule-show')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-add" type="button" onclick="printContent('view-rotule');"><i class="fa fa-print mr-2"></i>@lang('print')</button>

                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-internals -->

        <!-- begin #modal-view-internals -->
        <div class="modal fade" id="modal-view-internals">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white d-flex justify-content-between align-items-center w-100">
                            <span>@lang('info_of') correspondencia @lang('interna'): @{{dataShow?.consecutive}}</span>
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <i class="fa fa-times text-white"></i>
                        </button>
                    </div>
                    <div class="modal-body" id="showFields">
                        @include('correspondence::internals.show_fields')
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
        <!-- end #modal-view-internals -->

        <dynamic-modal-form
            modal-id="share-internal"
            size-modal="lg"
            :title="'Compartir correspondencia Interna ' + dataForm.consecutive"
            :data-form.sync="dataForm"
            endpoint="internal-share"
            :is-update="true"
            @saved="
                if($event.isUpdate) {
                    assignElementList(dataForm.id, $event.data);
                } else {
                    addElementToList($event.data);
                }">
            <template #fields="scope">
                <div class="row">
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
                                    name-resource="/correspondence/get-only-users"
                                    name-options-list="internal_shares" :name-labels-display="['fullname']" name-key="users_id"
                                    help="Ingrese el nombre del funcionario en la caja y seleccione para agregar a la lista"
                                    :key="keyRefresh">
                                </add-list-autocomplete>
                            </div>
                        </div>
                    </div>
                </div>

            </template>
        </dynamic-modal-form>



        {{-- Modal para firmar documento--}}
        <dynamic-modal-form
            v-if="isUpdate && Array.isArray(dataForm.internal_versions) && dataForm.internal_versions.length > 0"
            modal-id="modal-approve-cancel-sign"
            styles-modal="max-width: 94vw;"
            :route-file="routeFileUrl"
            size-modal="xl"
            :title="'Aprobar firma o devolver para modificaciones el documento: ' + dataForm.consecutive"
            :data-form="dataForm"
            endpoint="sign-internal"
            :is-update="true"
            :confirmation-message-saved="(dataForm.type_send == 'Aprobar Firma' ? '¿Desea aprobar y firmar el documento \''+dataForm.title+'\'' : '¿Desea devolver para modificaciones el documento \''+dataForm.title+'\'')+'?'"
            @saved="
                    if($event.isUpdate) {
                        assignElementList(dataForm.id, $event.data);
                    } else {
                        addElementToList($event.data);
                    }">

            <template #fields="scope">
                <div class="row">

                    <div class="container">
                        <div class="row">
                            <!-- Card para cada versión interna -->
                            <div v-if="dataForm.internal_versions.length > 0" class="col-md-12 mt-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            Documento: @{{ dataForm.title }}<br>
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Versión:</strong> @{{ dataForm.internal_versions[0].number_version }}</p>
                                        <p><strong>Creado por:</strong> @{{ dataForm.internal_versions[0].users_name }}</p>
                                        <p><strong>Observación:</strong> @{{ dataForm.internal_versions[0].observation }}</p>
                                        {{-- <p><strong>Documento:</strong> <a :href="'{{ asset('storage') }}/' + dataForm.internal_versions[0].document_pdf_temp" target="_blank">Ver documento</a></p> --}}
                                        <p><strong>Fecha de creación:</strong> @{{ dataForm.internal_versions[0].created_at }}</p>
                                        <p><strong>Estado:</strong> @{{ dataForm.internal_versions[0].state }}</p>

                                        <!-- Funcionarios para firma -->
                                        <div v-if="dataForm.internal_versions[0].internal_signs.length > 0">
                                            <h6>Funcionarios para firma:</h6>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Fecha</th>
                                                            <th>Funcionario</th>
                                                            <th>Estado</th>
                                                            <th>Observación</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(sign, key) in dataForm.internal_versions[0].internal_signs" :key="key">
                                                            <td>@{{ sign.created_at }}</td>
                                                            <td>@{{ sign.users_name }}</td>
                                                            <td>@{{ sign.state }}</td>
                                                            <td>@{{ sign.observation }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>


                    <div class="col-12 mt-2">
                        <div class="form-group row mb-3">
                            <label for="type_send" class="col-form-label col-md-4 required">¿Qué desea hacer?:</label>
                            <div class="col-md-8">
                                <select class="form-control" v-model="scope.dataForm.type_send" name="type_send" id="type_send" required>
                                    <option value="">Seleccione</option>
                                    <option value="Aprobar Firma">Aprobar Firma</option>
                                    <option value="Devolver para modificaciones">Devolver para modificaciones</option>
                                </select>
                            </div>
                        </div>
                        <div v-if="scope.dataForm.type_send == 'Aprobar Firma'">
                            @include('correspondence::internals.fields_firmas')
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group row mb-3">
                            <label for="danger" class="col-form-label col-md-4 required">Observaciones:</label>
                            <div class="col-md-8">
                                <textarea class="form-control" required type="text" v-model="scope.dataForm.observations" placeholder=""></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </template>


        </dynamic-modal-form>

        {{-- <dynamic-modal-form
                modal-id="modal-approve-cancel-sign"
                size-modal="xl"
                :title="'Revisar el contenido del documento: ' + dataForm.title"
                :data-form="dataForm"
                endpoint="sign-internal"
                :is-update="true"
                :confirmation-message-saved="(dataForm.type_send == 'Aprobar Firma' ? '¿Desea aprobar y firmar el documento \''+dataForm.title+'\'' : '¿Desea devolver para modificaciones el documento \''+dataForm.title+'\'')+'?'"
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
                                    <h1 for="danger" class="col-form-label col-md-12">Aprobar firma o devolver para modificaciones</h1>
                                </div>
                            </div>

                            <!-- Panel versiones-->
                            <div class="panel" data-sortable-id="ui-general-1">
                                <!-- begin panel-heading -->
                                <div class="panel-heading ui-sortable-handle">
                                    <h4 class="panel-title"><strong>Versión</strong></h4>
                                </div>
                                <!-- end panel-heading -->
                                <!-- begin panel-body -->
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table table-hover fix-vertical-table table-responsive">
                                                    <thead>
                                                        <tr>
                                                            <th>Número de versión</th>
                                                            <th>Creado por</th>
                                                            <th>Documento</th>
                                                            <th>@lang('Created_at')</th>
                                                            <th>@lang('State')</th>
                                                            <th>@lang('Observation')</th>
                                                            <th>Funcionarios para firma</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr  v-if='dataForm.internal_versions'>
                                                            <td>@{{ dataForm.internal_versions ? dataForm.internal_versions[0].number_version : '' }}</td>
        <td>@{{ dataForm.internal_versions ? dataForm.internal_versions[0].users_name : '' }}</td>
        <td><a class="col-9 text-truncate" :href="'{{ asset('storage') }}/'+(dataForm.internal_versions ? dataForm.internal_versions[0].document_pdf_temp : '')" target="_blank">Ver documento</a></td>
        <td>@{{ dataForm.internal_versions ? dataForm.internal_versions[0].created_at : '' }}</td>
        <td>@{{ dataForm.internal_versions ? dataForm.internal_versions[0].state : '' }}</td>
        <td>@{{ dataForm.internal_versions ? dataForm.internal_versions[0].observation : '' }}</td>
        <td>

            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Funcionario</th>
                        <th>Estado</th>
                        <th>Observación</th>
                    </tr>
                </thead>

                <body>
                    <tr v-for="(sign, key) in dataForm.internal_versions[0].internal_signs">
                        <td>@{{ sign?.created_at }}</td>
                        <td>@{{ sign?.users_name }}</td>
                        <td>@{{ sign?.state }}</td>
                        <td>@{{ sign?.observation }}</td>
                    </tr>
                </body>
            </table>

        </td>
        </tr>
        </tbody>
        </table>
    </div>
    </div>
    </div>
    </div>
    <!-- end panel-body -->
    </div>



    <div class="col-md-12">
        <div class="form-group row m-b-15">
            <label for="type_send" class="col-form-label col-md-4 required">¿Qué desea hacer?:</label>
            <div class="col-md-8">
                <select class="form-control" v-model="scope.dataForm.type_send" name="type_send" id="type_send" required>
                    <option value="">Seleccione</option>
                    <option value="Aprobar Firma">Aprobar Firma</option>
                    <option value="Devolver para modificaciones">Devolver para modificaciones</option>
                </select>
            </div>
        </div>
        <div v-if="scope.dataForm.type_send=='Aprobar Firma'">
            @include('correspondence::internals.fields_firmas')
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group row m-b-15">
            <label for="danger" class="col-form-label col-md-4 required">Observaciones:</label>
            <div class="col-md-8">
                <textarea class="form-control" required type="text" v-model="scope.dataForm.observations" placeholder=""></textarea>
            </div>
        </div>
    </div>

    </div>

    </template>
    </dynamic-modal-form> --}}


    <correspondence-internal inline-template ref="internal_ref"
        :user-has-signature={{ !empty(Auth::user()->url_digital_signature) ? 'true' : 'false' }}
        :autorizado-firmar={{ Auth::user()->autorizado_firmar == 1 ? 'true' : 'false' }}
        :second-password={{ Auth::user()->enable_second_password == 1 ? 'true' : 'false' }}>

        <div>

            <!-- begin #modal-form-internals -->
            <div class="modal fade" id="modal-form-internals">
                <div class="modal-dialog modal-xl">
                    <form @submit.prevent="save()" id="form-internals" autocomplete="off">
                        <div class="modal-content border-0">
                            <div class="modal-header bg-blue">
                                <h4 class="modal-title text-white">Creación y edición de correspondencia interna</h4>
                                <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal"><i class="fa fa-times text-white"></i></button>
                            </div>
                            <div class="modal-body" id="form-internal-body" v-if="formOpen == 'centralizada'">
                                @include('correspondence::internals.fields')
                            </div>
                            <div class="modal-footer">
                                <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                                <button v-if="!radicatied" type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- end #modal-form-internals -->

            <!-- begin #modal-form-form-interna-google -->
            <div class="modal fade" id="modal-form-interna-google" data-backdrop="static">
                <div class="modal-dialog modal-lg">
                    <form @submit.prevent="createInterna()" id="form-interna-google">
                        <div class="modal-content border-0">
                            <div class="modal-header bg-blue">
                                <h4 class="modal-title text-white">Elaboración de documentos internos oficiales</h4>
                                <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal"><i class="fa fa-times text-white"></i></button>
                            </div>
                            <div class="modal-body" v-if="formOpen == 'ceropapelesCrear'">

                                <div class="panel" data-sortable-id="ui-general-1">
                                    <!-- begin panel-body -->
                                    <div class="panel-body">
                                        <div class="row">

                                            <div class="col-md-12">
                                                <!-- Editor Id Field -->
                                                <input type="hidden" name="editor" value="Google Docs" v-model="dataForm.editor">

                                                {{-- <div class="form-group row m-b-15">
                                                {!! Form::label('editor', trans('¿Cómo desea elaborar su documento?') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                                                <div class="col-md-9">
                                                    <select class="form-control" name="editor" v-model="dataForm.editor" required>
                                                        <option value="Web">Documento rápido - Formulario web</option>
                                                        <option value="Google Docs">Documento avanzado - Google Docs</option>
                                                    </select>
                                                    <small>Seleccione una opción</small>
                                                </div>
                                            </div> --}}

                                                <!-- Title Field -->
                                                <div class="form-group row m-b-15">
                                                    {!! Form::label('title', trans('Título') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                                                    <div class="col-md-9">
                                                        {!! Form::text('title', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.title }", 'v-model' => 'dataForm.title', 'required' => true]) !!}
                                                        <small>@lang('Enter the') el título del documento a crear</small>
                                                        <div class="invalid-feedback" v-if="dataErrors.title">
                                                            <p class="m-b-0" v-for="error in dataErrors.title">
                                                                @{{ error }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Template Id Field -->
                                                <div class="form-group row m-b-15">
                                                    {!! Form::label('type', trans('Plantilla') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                                                    <div class="col-md-9">
                                                        <select-check
                                                            css-class="form-control"
                                                            name-field="type"
                                                            reduce-label="name"
                                                            reduce-key="id"
                                                            name-resource="get-internal-types"
                                                            :value="dataForm"
                                                            :is-required="true"
                                                            :enable-search="true">
                                                        </select-check>
                                                        <small>Seleccione la plantilla del documento interno</small>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <!-- end panel-body -->
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>Cerrar</button>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-arrow-right mr-2"></i>Continuar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- end #modal-form-interna-google -->

            <!-- begin #modal-form-internals-ceropapeles -->
            <div class="modal fade" id="modal-form-internals-ceropapeles" data-backdrop="static">
                <div class="modal-dialog" style="max-width: 94vw;">
                    <form @submit.prevent="validateAndSubmit()" id="form-internals-ceropapeles" autocomplete="off">
                        <div class="modal-content border-0">
                            <div class="modal-header bg-blue">
                                <h4 class="modal-title text-white">Creación y edición de correspondencia interna</h4>
                                {{-- Botón de inicio de sesión en Google. Este botón se habilita para el navegador de Google --}}
                                <button v-if="$parent.navegador == 'google'" id="sessionChromeButton" type="button" onclick="abrirLoginGoogle()" class="btn btn-primary">
                                    <i class="fab fa-google"></i> <!-- Ícono de Google de Font Awesome -->
                                    <span class="tooltip-text">Inicie sesión en Google para habilitar funciones avanzadas sobre el documento</span> <!-- Tooltip -->
                                </button>
                                {{-- Botón para abrir el documento en otra pestaña y editarlo. Este botón solo se habilita para navegadores diferente de Google --}}
                                <button v-if="$parent.navegador !== 'google'" id="sessionButtonOther" type="button" @click="abrirDocumentoGoogle(dataForm.template)" class="btn btn-primary">
                                    <i class="fa fa-file-alt"></i>
                                    <span class="tooltip-text-otro"> Editar el documento en una nueva pestaña</span> <!-- Tooltip -->
                                </button>
                                <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" style="margin-left: inherit;"><i class="fa fa-times text-white"></i></button>
                            </div>
                            <button id="btnmaximizar" type="button" :title="btnResizeEditor" class="btn btn-secondary" style="margin-left: auto; margin-bottom: -36px; z-index: 10; margin-right: 10px; margin-top:5px;" id="btnResizeEditor" @click="resizeEditor($event, this)">
                                <i v-if="btnResizeEditor == 'Maximizar editor'" class="fa fa-arrow-left"></i>
                                <i v-if="btnResizeEditor == 'Minimizar editor'" class="fa fa-arrow-right"></i>
                                @{{ btnResizeEditor }}
                            </button>
                            <button id="btnvistaprevia" type="button" :title="btnvistaprevia === 'Vista previa'
                                ? 'Visualice una vista previa de cómo queda el documento una vez se publique'
                                : 'Regrese al editor de documentos para seguir editando'" class="btn btn-secondary" style=" margin-left: auto; margin-bottom: -40px; z-index: 10; margin-right: 10rem; margin-top:1px; " @click="preview($event, this)">
                                <i v-if="btnvistaprevia == 'Editar documento'" class="fas fa-pencil-alt"></i>
                                <i v-if="btnvistaprevia == 'Vista previa'" class="fa fa-eye"></i>
                                @{{ btnvistaprevia }}
                            </button>
                            <div style="display: flex;">
                                <div class="modal-body column col-md-6" id="formularioIzq" v-if="formOpen == 'ceropapelesEditar'">
                                    @include('correspondence::internals.fields_ceropapeles')
                                </div>

                                {{-- <div class="resizer" id="resizer"></div> --}}
                                <iframe v-if="dataForm.template && btnvistaprevia == 'Vista previa'" class="column" :src="dataForm.template+'?rm=embedded&embedded=true'" id="editorDer" style="border:none; width: 100%; margin-right: 1px; border-right-color: rgb(0 0 0 / 56%); padding-top:3rem;"></iframe>
                                <iframe v-if="dataForm.template_preview && btnvistaprevia == 'Editar documento'" class="column" :src="dataForm.template_preview" id="editorDer" style="border:none; width: 100%; margin-right: 1px; border-right-color: rgb(0 0 0 / 56%); padding-top:3rem;"></iframe>
                            </div>
                            <div class="modal-footer">
                                <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                                <button v-if="dataForm.tipo == 'Publicación'" type="submit" class="btn" style="background-color: #8bc34a; color:#FFFFFF"><i class="fas fa-paper-plane mr-2"></i>Publicar documento</button>
                                <button v-if="dataForm.tipo == 'Firma Conjunta'" type="submit" class="btn btn-warning" style="background-color: #ff9800"><i class="fas fa-paper-plane mr-2"></i>Enviar para firmar</button>
                                <button v-if="dataForm.tipo == 'Revisión'" type="submit" class="btn" style="background-color: #ffeb3b"><i class="fas fa-paper-plane mr-2"></i>Enviar a revisión</button>
                                <button v-if="dataForm.tipo == 'Elaboración'" type="submit" class="btn btn-primary"><i class="fas fa-paper-plane mr-2"></i>Enviar a elaboración</button>
                                <button v-if="dataForm.tipo == 'Aprobación'" type="submit" class="btn btn-cyan"><i class="fas fa-paper-plane mr-2"></i>Enviar para aprobación</button>
                                <button v-if="dataForm.tipo == ''" type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>


                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- end #modal-form-internals-ceropapeles -->
        </div>
    </correspondence-internal>

    <annotations-general ref="annotations" route="/correspondence/internal-annotations" name-list="internal_annotations" file-path="public/container/internal_{{ date('Y') }}/anotaciones" field-title="Anotaciones de correspondencia Interna: " field-title-var="consecutive"></annotations-general>
    </div>
</crud>
@endsection

@push('css')
{!!Html::style('assets/plugins/gritter/css/jquery.gritter.css')!!}
<style>
    .column {
        /* flex: 1; */
        overflow: auto;
        padding: 10px;
        border: 1px solid #ccc;
        box-sizing: border-box;
    }

    .resizer {
        width: 10px;
        cursor: ew-resize;
        background: #333;
        /* height: 100%; */
        position: relative;
        right: -5px;
    }

    .oculto {
        display: none !important;
    }

    .visible {
        display: inline !important;
    }

    .vdr.active:before {
        outline: none !important;
    }

    /* Estilos para pantallas más pequeñas, por ejemplo, dispositivos móviles */
    @media (max-width: 768px) {

        /* Oculta el iframe en dispositivos móviles */
        iframe#editorDer {
            display: none;
        }

        #btnmaximizar {
            display: none;
        }

        #btnvistaprevia {
            display: none;
        }

    }

    /* Estilos para pantallas más grandes */

    @media (min-width: 769px) {
        #botonGoogleDocs {
            display: none;
        }

        #btnmaximizar {
            display: block;
        }
    }

    .selected {
        font-size: 14px;
        /* Tamaño más grande para el botón seleccionado */
        color: #fff;
        /* Cambia el color del texto del botón seleccionado a blanco */
        font-weight: bold;
        /* Añade negrita para hacerlo más resaltado */
    }

    /* Estilos para el tooltip del botón de iniciar sesión en google */

    /* Contenedor del tooltip */
    #sessionChromeButton,
    #sessionButtonOther {
        position: relative;
        border-radius: 5px;
        cursor: pointer;
        background-color: #007bff;
        color: white;
        border: none;
        transition: background-color 0.3s ease;
        margin-left: auto;
    }

    /* Tooltip oculto por defecto */
    .tooltip-text {
        visibility: hidden;
        opacity: 0;
        position: absolute;
        top: 50%;
        left: -1307%;
        /* Lo coloca al lado izquierdo del botón */
        transform: translateY(-50%);
        background-color: #333;
        color: white;
        text-align: center;
        padding: 8px;
        border-radius: 5px;
        z-index: 1;
        transition: opacity 0.3s ease, visibility 0.3s ease;
        white-space: nowrap;
    }

    /* Tooltip oculto por defecto */
    .tooltip-text-otro {
        visibility: hidden;
        opacity: 0;
        position: absolute;
        top: 50%;
        left: -780%;
        /* Lo coloca al lado izquierdo del botón */
        transform: translateY(-50%);
        background-color: #333;
        color: white;
        text-align: center;
        padding: 8px;
        border-radius: 5px;
        z-index: 1;
        transition: opacity 0.3s ease, visibility 0.3s ease;
        white-space: nowrap;
    }

    /* Mostrar el tooltip al pasar el mouse sobre el botón */
    #sessionChromeButton:hover .tooltip-text,
    #sessionButtonOther:hover .tooltip-text-otro {
        visibility: visible;
        opacity: 1;
    }

    /* Efecto hover en el botón */
    #sessionChromeButton:hover,
    #sessionButtonOther:hover {
        background-color: #0056b3;
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
    // detecta el enter para no cerrar el modal sin enviar el formulario
    $('#modal-form-internals, #modal-form-internals-ceropapeles, #modal-form-interna-google').on('keypress', ':input:not(textarea):not([type=submit])', function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
        }
    });

    // detecta el enter para no cerrar el modal sin enviar el formulario
    $('#modal-approve-cancel-sign').on('keypress', ':input:not(textarea):not([type=submit])', function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
        }
    });

    $(document).ready(function() {

        $('#content-rotule').draggable();

        // Cuando la tabla se muestra, hace focus en la primera celda
        $("#historial_completo").on("shown.bs.collapse", function() {

            $("#modal-view-internals :last-child").focus();

        });


    });


    // const resizer = document.getElementById('resizer');
    // const formularioIzq = document.getElementById('formularioIzq');
    // const editorDer = document.getElementById('editorDer');
    // let isResizing = false;

    // resizer.addEventListener('mousedown', (event) => {
    // isResizing = true;
    // document.addEventListener('mousemove', handleMouseMove);
    // document.addEventListener('mouseup', () => {
    //     isResizing = false;
    //     document.removeEventListener('mousemove', handleMouseMove);
    // });
    // });

    // function handleMouseMove(event) {
    // if (isResizing) {
    //     const leftColumnWidth = event.clientX - formularioIzq.getBoundingClientRect().left;
    //     formularioIzq.style.width = `${leftColumnWidth}px`;
    //     editorDer.style.width = `calc(100% - ${leftColumnWidth}px)`;
    // }
    // }



    const elementoDiv = document.getElementById('show_table');
    elementoDiv.style.display = 'none';


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

    const clientId = "{{ env('GOOGLE_CLIENT_ID') }}";
    const redirectUri = "{{ env('GOOGLE_REDIRECT_URI') }}";
    const appUrl = "{{ env('APP_URL') }}";

    // Abrir la ventana de Google Auth
    function abrirLoginGoogle() {
        window.authWindow = window.open(
            `https://accounts.google.com/o/oauth2/v2/auth?client_id=${clientId}&response_type=token&redirect_uri=${redirectUri}&scope=openid%20profile%20email`,
            'google-auth-window',
            'width=900,height=400'
        );
    }

    // Escuchar mensajes de la ventana emergente
    window.addEventListener('message', function(event) {
        // Validar el origen por seguridad
        if (event.origin === appUrl) {
            if (event.data.action === 'refreshIframe') {
                // Refrescar el iframe
                const iframe = document.getElementById('editorDer');
                iframe.src = iframe.src;
            }
        }
    }, false);
</script>
@endpush
