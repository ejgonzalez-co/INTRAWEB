@extends('layouts.default')

@section('title', trans('Tic Requests'))

@section('section_img', '/assets/img/components/solicitudes.png')

@section('menu')
    @include('help_table::layouts.menus.menu_requests')
@endsection

@section('content')

@php
    $appUrl = env('APP_URL');
@endphp

<crud
    name="tic-requests"
    :init-values="{generate_maintenance: false}"
    :resource="{default: 'tic-requests', get: 'get-tic-requests', show: 'tic-requests'}"
    :crud-avanzado="true" :actualizar-listado-automatico="true"
    inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/home') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('Tic Requests')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        @if(Auth::user()->hasRole("Proveedor TIC"))
            <h1 class="page-header text-center m-b-35">Vista principal para administrar Solicitudes como Proveedor</h1>
        @else
            <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('Tic Requests')'}}</h1>
        @endif
        <!-- end page-header -->

        @if(Auth::user()->hasRole(['Administrador TIC','Soporte TIC','Usuario TIC']))
        <a href="javascript:hideShow('widget')"><u style="color: black"><strong>Ocultar/Mostrar Consolidado</strong></u></a><br><br>

        <!-- begin widget -->
        <div id="widget" class="row">
            <widget-counter
                icon="fa fa-book-open"
                bg-color="#17a2b8"
                :qty="dataExtra.estados?.abierta ?? 0"
                title="Abierta"
                title-link-see-more="Filtrar"
                :status="1"
                :value="searchFields"
                name-field="ht_tic_request_status_id"
                {{-- link-see-more="javascript:checkData('1')" --}}
            ></widget-counter>
            <widget-counter
                icon="far fa-thumbs-up"
                bg-color="#ffc107"
                :qty="dataExtra.estados?.asignada ?? 0"
                title="Asignada"
                title-link-see-more="Filtrar"
                :status="2"
                :value="searchFields"
                name-field="ht_tic_request_status_id"
                {{-- link-see-more="javascript:checkData('2')" --}}
            ></widget-counter>
            <widget-counter
                icon="fas fa-cogs"
                bg-color="#fd7e14"
                :qty="dataExtra.estados?.en_proceso ?? 0"
                title="En proceso"
                title-link-see-more="Filtrar"
                :status="3"
                :value="searchFields"
                name-field="ht_tic_request_status_id"
                {{-- link-see-more="javascript:checkData('3')" --}}
            ></widget-counter>
            <widget-counter
                icon="fa fa-undo-alt"
                :qty="dataExtra.estados?.devuelta ?? 0" 
                bg-color="rgb(23, 162, 184)"
                title="Devuelta"
                title-link-see-more="Filtrar"
                :status="7"
                :value="searchFields"
                name-field="ht_tic_request_status_id"
                {{-- link-see-more="javascript:checkData('7')" --}}
            ></widget-counter>

            <widget-counter
                icon="fa fa-user-times"
                :qty="dataExtra.estados?.cerrada_sin_encuesta ?? 0" 
                bg-color="#9C9E9D"
                title="Cerrada (Sin encuesta)"
                title-link-see-more="Filtrar"
                :status="6"
                :value="searchFields"
                name-field="ht_tic_request_status_id"
                {{-- link-see-more="javascript:checkData('6')" --}}
            ></widget-counter>
            <widget-counter
                icon="fa fa-user-minus"
                bg-color="#2F5997"
                :qty="dataExtra.estados?.cerrada_encuesta_pendiente ?? 0" 
                title="Cerrada (Encuesta pendiente)"
                title-link-see-more="Filtrar"
                :status="4"
                :value="searchFields"
                name-field="ht_tic_request_status_id"
                {{-- link-see-more="javascript:checkData('4')" --}}
            ></widget-counter>
            <widget-counter
                icon="fa fa-user-check"
                bg-color="rgb(11, 197, 104)"
                :qty="dataExtra.estados?.cerrada_encuesta_realizada ?? 0" 
                title="Cerrada (Encuesta Realizada)"
                title-link-see-more="Filtrar"
                :status="5"
                :value="searchFields"
                name-field="ht_tic_request_status_id"
                {{-- link-see-more="javascript:checkData('5')" --}}
            ></widget-counter>
            <widget-counter
                icon="fas fa-cogs"
                :qty="dataExtra.estados?.precierre ?? 0" 
                bg-color="rgb(151, 227, 159)"
                title="Pre-Cierre"
                title-link-see-more="Filtrar"
                :status="8"
                :value="searchFields"
                name-field="ht_tic_request_status_id"
                {{-- link-see-more="javascript:checkData('8')" --}}
            ></widget-counter>

        </div>
        @endif
        <!-- end widget -->

        <!-- begin main buttons -->
        <div class="m-t-20">
            @if(Auth::user()->hasRole('Administrador TIC'))
            <button @click="callFunctionComponent('holiday-calendars', 'loadCalendar')" type="button" class="btn btn-primary m-b-10">
                <i class="fas fa-calendar-alt mr-2"></i>@lang('Calendar')
            </button>
            @endif

            @if(Auth::user()->hasRole('Administrador TIC') || Auth::user()->hasRole('Usuario TIC') ||  Auth::user()->hasRole('Soporte TIC'))
            <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-tic-requests" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('Tic Requests')
            </button>
            @else
            {{-- <btn-create-request
                btn-title="Registrar solicitud"
                name="tic-requests"
                resource-path="validate-registration-requests"
                >
            </btn-create-request> --}}
            @endif

            <button onclick="window.location.href = window.location.href.split('?')[0];" class="btn btn-md btn-primary m-b-10"><i class="fa fa-redo-alt mr-2"></i>Cargar página de nuevo</button>
        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('Tic Requests'): ${dataPaginator.total}` | capitalize }}</h5>
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
                            <div class="col-md-4 mb-2">
                                <select-check
                                    css-class="form-control"
                                    name-field="ht_tic_type_request_id"
                                    reduce-label="name"
                                    name-resource="get-tic-type-requests"
                                    :value="searchFields"
                                    :enable-search="true"
                                    @keyup.enter = 'pageEventActualizado(1)'
                                >
                                </select-check>
                                <small>Filtro por tipo de solicitud.</small>
                            </div>

                            <div class="col-md-4 mb-2">
                                <select-check
                                    css-class="form-control"
                                    name-field="ht_sedes_tic_request_id"
                                    reduce-label="name"
                                    name-resource="get-sedes-tics"
                                    :value="searchFields"
                                    :enable-search="true"
                                    @keyup.enter = 'pageEventActualizado(1)'
                                >
                                </select-check>
                                <small>Filtro por sede del usuario.</small>
                            </div>
                            <div class="col-md-4 mb-2">
                                <select-check
                                    css-class="form-control"
                                    name-field="ht_dependencias_tic_request_id"
                                    reduce-label="name"
                                    :name-resource="'get-dependencias-tics/'+searchFields.ht_sedes_tic_request_id"
                                    :value="searchFields"
                                    :enable-search="true"
                                    :key="searchFields.ht_sedes_tic_request_id"
                                >
                                </select-check>
                                {{-- <input type="hidden" v-model="searchFields.typeQuery='ht_sedes_tic_request_id','ht_dependencias_tic_request_id'"> --}}
                                <small>Filtro por dependencia del usuario.</small>
                            </div>
                            <div class="col-md-4 mb-2">
                                <select-check
                                    css-class="form-control"
                                    name-field="priority_request"
                                    reduce-label="name"
                                    name-resource="get-constants/priority_request"
                                    :value="searchFields"
                                    :enable-search="true"
                                    @keyup.enter = 'pageEventActualizado(1)'
                                >
                                </select-check>
                                <small>Filtro por prioridad.</small>
                            </div>
                            <div class="col-md-4 mb-2">
                                <select-check
                                    css-class="form-control"
                                    name-field="ht_tic_request_status_id"
                                    reduce-label="name"
                                    name-resource="get-tic-request-statuses"
                                    :value="searchFields"
                                    :enable-search="true"
                                    @keyup.enter = 'pageEventActualizado(1)'
                                >
                                </select-check>
                                <small>Filtro por estado.</small>
                            </div>
                            @if(Auth::user()->hasRole('Administrador TIC'))
                            <div class="col-md-4 mb-2">
                                <select-check
                                    css-class="form-control"
                                    name-field="support_type"
                                    reduce-label="name"
                                    name-resource="get-constants/support_type_tic"
                                    :value="searchFields"
                                    :enable-search="true"
                                    @keyup.enter = 'pageEventActualizado(1)'
                                >
                                </select-check>
                                <small>Filtro por tipo de soporte.</small>
                            </div>
                            <div v-if="searchFields.support_type == 1" class="col-md-4 mb-2">
                                <select-check
                                    css-class="form-control"
                                    name-field="assigned_user_id"
                                    reduce-label="name"
                                    name-resource="get-support-users-tic"
                                    :value="searchFields"
                                    :enable-search="true"
                                    @keyup.enter = 'pageEventActualizado(1)'
                                >
                                </select-check>
                                <small>Filtro por funcionario asignado.</small>
                            </div>
                            <div v-if="searchFields.support_type == 2" class="col-md-4 mb-2">
                                <select-check
                                    css-class="form-control"
                                    name-field="assigned_user_id"
                                    reduce-label="name"
                                    name-resource="get-supplier-users-tic"
                                    :value="searchFields"
                                    :key="searchFields.support_type"
                                    :enable-search="true"
                                    @keyup.enter = 'pageEventActualizado(1)'
                                >
                                </select-check>
                                <small>Filtro por proveedor asignado.</small>
                            </div>
                            <div class="col-md-4 mb-2">
                                <select-check
                                    css-class="form-control"
                                    name-field="users_id"
                                    :reduce-label="['dependency', 'name']"
                                    name-resource="get-users-tic"
                                    :value="searchFields"
                                    :enable-search="true"
                                    @keyup.enter = 'pageEventActualizado(1)'
                                >
                                </select-check>
                                <small>Filtro por usuario solicitante.</small>
                            </div>
                            @endif
                            <div class="col-md-4 mb-2">
                                {!! Form::text('id', null, ['v-model' => 'searchFields.id', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Number')]), '@keyup.enter' => 'pageEventActualizado(1)' ]) !!}
                            </div>
                            <div class="col-md-4 mb-2">
                                {!! Form::text('affair', null, ['v-model' => 'searchFields.affair', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Affair')]), '@keyup.enter' => 'pageEventActualizado(1)' ]) !!}
                            </div>
                            <div class="col-md-4 mb-2">
                                <date-picker
                                :value="searchFields"
                                name-field="created_at"
                                mode="range"
                                >
                                </date-picker>
                                <small>Filtro por fecha de creación.</small>
                            </div>
                            <div class="col-md-4 mb-2">
                                <input type="date" class="form-control" v-model="searchFields.expiration_date" @keyup.enter = 'pageEventActualizado(1)'>
                                <small>Filtro por fecha de vencimiento.</small>
                            </div>
                            <div class="col-md-4 mb-2">
                                <input type="date" class="form-control" v-model="searchFields.date_attention" @keyup.enter = 'pageEventActualizado(1)'>
                                <small>Filtro por fecha de atención.</small>
                            </div>

                            <div class="col-md-4 mb-2">
                                <select-check
                                    css-class="form-control"
                                    name-field="ht_tic_type_tic_categories_id"
                                    reduce-label="name"
                                    name-resource="get-tic-type-tic-categories"
                                    :value="searchFields"
                                    :enable-search="true"
                                    @keyup.enter = 'pageEventActualizado(1)'
                                >
                                </select-check>
                                <small>Filtro por Categoría.</small>
                            </div>

                            <div class="col-md-4 mb-2" v-if="searchFields.ht_tic_type_tic_categories_id">
                                <select-check-depend 
                                    css-class="form-control"
                                    name-field="ht_tic_type_assets_id"
                                    reduce-label="name"
                                    :name-resource="'get-tic-type-assets'"
                                    :value="searchFields"
                                    :enable-search="true"
                                    dependent-id="ht_tic_type_tic_categories_id" 
                                    foreign-key="ht_tic_type_tic_categories_id">
                                    @keyup.enter = 'pageEventActualizado(1)'
                                >
                                </select-check-depend>
                                <small>Filtro por Tipo.</small>
                            </div>

                            <div class="col-md-4 mb-2">
                            <input type="number"
                                class="form-control"
                                v-model="searchFields.year"
                                min="2019"
                                max="2025"
                                placeholder="Año (ej: 2025)">
                            <small>Filtro por año de creación.</small>
                        </div>

                          <div class="col-12 mb-3">

                            <!-- Fila fecha desde -->
                            <div class="row mb-2 align-items-center">
                                <div class="col-md-2 text-end">
                                    <label for="fecha_desde" class="form-label mb-0">Desde:</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="date" id="fecha_desde" v-model="searchFields.fecha_desde" class="form-control">
                                </div>
                            </div>

                            <!-- Fila fecha hasta -->
                            <div class="row mb-2 align-items-center">
                                <div class="col-md-2 text-end">
                                    <label for="fecha_hasta" class="form-label mb-0">Hasta:</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="date" id="fecha_hasta" v-model="searchFields.fecha_hasta" class="form-control">
                                </div>
                            </div>

                            <!-- Texto explicativo -->
                            <div class="row">
                                <div class="col-md-6 offset-md-2">
                                    <small class="text-muted">Filtrar por rango de fechas</small>
                                </div>
                            </div>

                        </div>
                           



                            <div class="col-md-6">
                            <button @click="pageEventActualizado(1)" class="btn btn-md btn-primary"><i class="fa fa-search mr-2"></i>Buscar</button>
                            <button @click="clearDataSearchAvanzado()" class="btn btn-md btn-primary"><i class="fas fa-broom mr-2"></i>@lang('clear_search_fields')</button>
                            </div>
                           
                        </div>
                    </div>
                </div>
                <!-- end card search -->
            </div>
            <!-- end #accordion search -->
            <div class="panel-body">
                <!-- begin buttons action table -->
                <div class="float-xl-right m-b-15">
                    <!-- Acciones para exportar datos de tabla-->
                    <div class="btn-group">
                        <a href="javascript:;" data-toggle="dropdown" class="btn btn-primary"><i class="fa fa-download mr-2"></i> @lang('export_data_table')</a>
                        <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle" aria-expanded="false"><b class="caret"></b></a>
                        <div class="dropdown-menu dropdown-menu-right bg-blue">
                            {{-- <a href="javascript:;" @click="exportDataTable('pdf')" class="dropdown-item text-white no-hover"><i class="fa fa-file-pdf mr-2"></i> PDF</a> --}}
                            <a href="javascript:;" @click="exportDataTableAvanzado('xlsx')" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i> EXCEL</a>
                            <a href="javascript:;" @click="callFunctionComponent('alert-confirmation', 'openConfirmationModal')" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i> Exportar por Power Bi</a>
                        </div>
                    </div>
                </div>
                <!-- end buttons action table -->
                @include('help_table::tic_requests.table')
            </div>
            <div class="p-b-15 text-center">
                <!-- Cantidad de elementos a mostrar -->
                <div class="form-group row m-5 col-md-3 text-center d-sm-inline-flex">
                    {!! Form::label('show_qty', trans('show_qty').':', ['class' => 'col-form-label col-md-7']) !!}
                    <div class="col-md-5">
                        {!! Form::select(trans('show_qty'), [5 => 5, 10 => 10, 15 => 15, 20 => 20, 25 => 25, 30 => 30, 50 => 50, 75 => 75], '5', ['class' => 'form-control', 'v-model' => 'dataPaginator.pagesItems', '@change' => 'pageEventActualizado(1)']) !!}
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

        <!-- begin #modal-view-tic-requests -->
        <div class="modal fade" id="modal-view-tic-requests">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') @lang('Tic Requests')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        @include('help_table::tic_requests.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-tic-requests -->

        <!-- begin #modal-form-tic-requests -->
        <div class="modal fade" id="modal-form-tic-requests" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-xl">
                <form @submit.prevent="save()" id="form-tic-requests">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of') @lang('Tic Requests')</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('help_table::tic_requests.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-tic-requests -->
        <alert-confirmation ref="alert-confirmation" confirmation-text="Copiar url" title="Enlace generado" html="Por favor, copie el enlace que aparece en pantalla y péguelo en Power BI para visualizar la consulta correctamente.<br><br>{{ $appUrl }}api/exportar-usuarios" :powerbi="true" app-url="{{ $appUrl }}api/exportar-usuarios"></alert-confirmation>


        <holiday-calendar ref="holiday-calendars" name="holiday-calendars"></holiday-calendar>

        <ht-tic-satisfaction-poll ref="tic-satisfaction-poll" name="tic-satisfaction-polls"></ht-tic-satisfaction-poll>

        <ht-tic-knowledge-base ref="tic-knowledge-bases"></ht-tic-knowledge-base>

        <!-- begin #tic-requests-request send -->
        <dynamic-modal-form
            modal-id="modal-tic-requests-request"
            size-modal="xl"
            title="Formulario de solicitud"
            :data-form.sync="dataForm"
            :is-update="true"
            endpoint="tic-requests-request"
            @saved="
            if($event.isUpdate) {
                assignElementList(dataForm.id, $event.data);
            } else {
                addElementToList($event.data);
            }">
            <template #fields="scope">
                {{--<div class="panel" data-sortable-id="ui-general-1">
                    <!-- begin panel-heading -->
                    <div class="panel-heading ui-sortable-handle">
                        <h4 class="panel-title"><strong>Actualizar estado:</strong></h4>
                    </div>
                    <!-- end panel-heading -->
                    <!-- begin panel-body -->
                    <div class="panel-body"> --}}
                        <div class="row">

                            @if(Auth::user()->hasRole('Administrador TIC'))
                            <div class="col-md-12">
                                <!-- Tic Request Status Id Field -->
                                <div class="form-group row m-b-15">
                                    {!! Form::label('ht_tic_request_status_id', trans('State').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                    <div class="col-md-9">
                                        <select v-model="dataForm.ht_tic_request_status_id" name="ht_tic_request_status_id" id="ht_tic_request_status_id" class="form-control" required>
                                            <option value="7">Devuelta</option>
                                            <option value="8">Cancelada</option>
                                        </select>
                                        {{-- <select-check
                                            css-class="form-control"
                                            name-field="ht_tic_request_status_id"
                                            reduce-label="name"
                                            reduce-key="id"
                                            name-resource="get-tic-request-statuses"
                                            :value="dataForm"
                                            :is-required="true">
                                        </select-check> --}}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <!-- Tracing Field -->
                                <div class="form-group row m-b-15">
                                    {!! Form::label('tracing', trans('Tracing').':', ['class' => 'col-form-label col-md-3']) !!}
                                    <div class="col-md-9">
                                        <text-area-editor
                                            :value="dataForm"
                                            name-field="tracing"
                                            :hide-modules="{
                                                'bold': true, 
                                                'image': true,
                                                'code': true,
                                                'link': true
                                            }"
                                            placeholder="Ingrese el seguimiento"
                                        >
                                        </text-area-editor>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="col-md-12">
                                <!-- Descripcion Field -->
                                <div class="form-group row m-b-15">
                                    {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                    <div class="col-md-9">
                                        {!! Form::textarea('description', null, ['class' => 'form-control', 'v-model' => 'dataForm.description', 'required' => true]) !!}
                                    </div>
                                </div>
                                </div>
                            @endif

                        </div>
                    {{-- </div>
                    <!-- end panel-body -->
                </div> --}}
            </template>
        </dynamic-modal-form>
        <!-- end #modal-tic-requests-request send -->
    </div>
</crud>
@endsection

@push('css')
{!!Html::style('assets/plugins/gritter/css/jquery.gritter.css')!!}
<style>
    .col-sm-9.col-md-9.col-lg-9 img {
        max-width: 758px; /* Ajusta el valor según tus necesidades */
        max-height: 412px; /* Ajusta el valor según tus necesidades */
    }

    .historial-html img {
        max-width: 550px; /* Ajusta el valor según tus necesidades */
        max-height: 412px; /* Ajusta el valor según tus necesidades */
    }
    </style>
@endpush

@push('scripts')
{!!Html::script('assets/plugins/gritter/js/jquery.gritter.js')!!}

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    // detecta el enter para no cerrar el modal sin enviar el formulario
    $('#modal-form-tic-requests').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });

    function hideShow() {
    var consolidado = document.getElementById("widget");
        if (consolidado.style.display === "none") {
            consolidado.style.display = "flex";
        } else {
            consolidado.style.display = "none";
        }
    }

    const appUrl = import.meta.env.APP_URL;
</script>
@endpush
