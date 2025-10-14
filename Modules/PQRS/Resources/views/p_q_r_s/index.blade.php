@extends('layouts.default')

@section('title', trans('PQRS'))

@section('section_img', '/assets/img/components/convocatoria.png')

@section('menu')
    @include('pqrs::layouts.menu')
@endsection

@section('content')

<crud
    name="p-q-r-s"
    :resource="{default: 'p-q-r-s', get: 'get-p-q-r-s'}"
    :init-values-search="{pqrs_propios: 'rol_superior', tipos_pqrs: 'pendientes_ejecucion'}"
    inline-template
    :crud-avanzado="true"
    :load-data-list="'{{ request('qd') || request('qsb') || request('qder') }}' ? false : true"
    :actualizar-listado-automatico="true">
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">PQRS</li>
        </ol>
        <!-- end breadcrumb -->
        <h1 class="page-header text-center m-0 ">@{{ '@lang('main_view_to_manage') PQRS'}}</h1>


        @if(Auth::user()->hasRole('Administrador de requerimientos') || Auth::user()->hasRole('Operadores') || Auth::user()->hasRole('Consulta de requerimientos'))
            {{-- Valida los roles del usuario en sesion para que muestre las opciones de los pqrs --}}
            <div class="mb-2 row form-group">
                <label class="col-sm-1 col-form-label font-weight-bold" style="font-size: 17px;">Rol:</label>
                <div class="ml-2">
                    <select class="form-control" name="pqrs_propios" v-model="searchFields.pqrs_propios" @change="searchFields = { tipos_pqrs: searchFields.tipos_pqrs, pqrs_propios: searchFields.pqrs_propios };_updateKeyRefresh(); pageEventActualizado(1);">
                        @if(Auth::user()->hasRole('Administrador de requerimientos'))
                        <option value="rol_superior">Administrador de requerimientos</option>
                        @endif
                        @if(Auth::user()->hasRole('Consulta de requerimientos'))
                        <option value="rol_superior">Consulta de requerimientos</option>
                        @endif
                        @if(Auth::user()->hasRole('Operadores'))
                        <option value="rol_superior">Operador de {{ \Modules\Intranet\Models\Dependency::where("id", Auth::user()->id_dependencia)->pluck("nombre")->first(); }}</option>
                        <option value="copias_compartidos">Copias y compartidos</option>
                        @endif
                        <option value="pqrs_personales">PQRS propios</option>
                    </select>
                    <small>Rol con el que está consultando la vista</small>
                </div>
            </div>

        @endif
        {{-- Filtro disponible solo a los funcionarios, permite listar los PQRS categorizados en PQRS en ejecución (ítem por defecto), copias/compartidas y todos --}}
        <div class="mb-2 row form-group">
            <label class="col-sm-1 col-form-label font-weight-bold" style="font-size: 17px;">Filtrar por:</label>
            <div class="ml-2">
                <select class="form-control" name="tipos_pqrs" v-model="searchFields.tipos_pqrs"   @change="searchFields = { tipos_pqrs: searchFields.tipos_pqrs, pqrs_propios: searchFields.pqrs_propios }; _updateKeyRefresh(); pageEventActualizado(1);">
                    <option value="todos">Todos</option>
                    <option value="pendientes_ejecucion">Pendientes de ejecución</option>
                    <option value="copias_compartidos">Copias y compartidos</option>
                </select>
                <small>Filtrar por categoría general de los PQRS</small>
            </div>
        </div>

        {{-- Valida si es el usuario que esta en sesión es un funcionario --}}
        @if(!(Auth::user()->hasRole('Administrador de requerimientos') || Auth::user()->hasRole('Operadores') || Auth::user()->hasRole('Consulta de requerimientos')))
        <div style="font-size: 17px" class="mb-2"><strong>Rol actual: </strong>Funcionario</div>
        @endif
        
        <pqr-component ref="componentePQR"></pqr-component>


        <!-- end page-header -->
        <!-- begin main buttons -->
        <div class="m-t-25">
            {{-- Valida si el usuario en sesión es un administrador --}}
            @if (Auth::user()->hasRole('Administrador de requerimientos'))
                <button @click="add()" type="button" class="btn btn-add m-b-10" data-backdrop="static" data-target="#modal-form-p-q-r-s" data-toggle="modal">
                    <i class="fa fa-plus mr-2"></i>@lang('crud.create') PQR
                </button>
                <button @click="callFunctionComponent('holiday-calendars', 'loadCalendar')" type="button" class="btn btn-light m-b-10">
                    <i class="fas fa-calendar-alt mr-2"></i>@lang('Calendar')
                </button>
                <button @click = "callFunctionComponent('componentePQR', 'updateFechaVencePQRS');" type="button" class="btn btn-light m-b-10">
                    <i class="fa fa-clock mr-2"></i>Actualizar fecha de vencimiento de PQRS
                </button>
                <button @click="add()" type="button" class="btn btn-light m-b-10" data-backdrop="static" data-target="#modal-form-migration" data-toggle="modal">
                    <i class="fa fa-plus mr-2"></i>Contingencia
                </button>
            @endif

                <div class="float-xl-right">
                    <!-- Acciones para exportar datos de tabla-->
                    <div class="btn-group" >
                        <a href="javascript:;" data-toggle="dropdown" class="btn btn-light"><i class="fa fa-download mr-2"></i> @lang('export_data_table')</a>
                        <a href="#" data-toggle="dropdown" class="btn btn-light dropdown-toggle" aria-expanded="false"><b class="caret"></b></a>
                        <div class="dropdown-menu dropdown-menu-export dropdown-menu-right">
                            {{-- <a href="javascript:;" @click="exportDataTable('pdf')" class="dropdown-item"><i class="fa fa-file-pdf mr-2 text-danger"></i>Generar Reporte PDF</a> --}}
                            @if (Auth::user()->hasRole('Administrador de requerimientos'))
                                <a href="javascript:;" @click="exportReportAvanzado('xlsx')" class="dropdown-item"><i class="fa fa-file-excel mr-2 text-success"></i> Reporte Excel avanzado</a>
                                <a href="javascript:;" @click="exportReportAvanzado('xlsx', '', 'export-report-avanzado-tipo-adjunto')" class="dropdown-item"><i class="fa fa-file-excel mr-2 text-success"></i> Reporte Excel por tipos de adjuntos</a>
                                <a href="javascript:;" @click="exportReportAvanzado('xlsx', 'vencidos_pendientes')" class="dropdown-item"><i class="fa fa-file-excel mr-2 text-danger"></i>PQR asignados vencidos</a>
                            @endif
                            <a href="javascript:;" @click="exportDataTableAvanzado('xlsx')" class="dropdown-item"><i class="fa fa-file-excel mr-2 text-success"></i>Generar Reporte Excel</a>
                            @if (Auth::user()->hasRole(['Administrador de requerimientos', 'Operadores']))
                                <a href="javascript:;" @click="exportDataTableAvanzado('pdf')" class="dropdown-item"><i class="fa fa-file-pdf mr-2 text-danger"></i> Reporte PDF</a>
                            @endif
                            
                        </div>
                    </div>
                </div>
            <button onclick="window.location.href = window.location.href.split('?')[0];" class="btn btn-md btn-light m-b-10"><i class="fa fa-redo-alt mr-2"></i>Cargar página de nuevo</button>
        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') PQRS: ${dataPaginator.total}` }}</h5>
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
                            <div class="col-md-4">
                                <select-check
                                    css-class="form-control"
                                    name-field="vigencia"
                                    reduce-label="vigencia"
                                    reduce-key="valor"
                                    name-resource="/get-vigencias/pqr/vigencia"
                                    :value="searchFields">
                                </select-check>
                                <small>Filtrar por vigencia</small>
                            </div>
                            <div class="col-md-4">
                                {!! Form::text('pqr_id', null, ['v-model' => 'searchFields.pqr_id', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('número de radicado')]), '@keyup.enter' => 'pageEventActualizado(1)' ]) !!}
                            </div>
                            <div class="col-md-4">
                                <select-check css-class="form-control" name-field="estado" reduce-label="nombre"
                                :value="searchFields" :is-required="true" :enable-search="true" :is-multiple="true"
                                :options-list-manual="[{ id: 'Abierto', nombre: 'Abierto' },
                                    { id: 'Asignado', nombre: 'Asignado' },
                                    { id: 'En trámite', nombre: 'En trámite' },
                                    { id: 'Esperando respuesta del ciudadano', nombre: 'Esperando respuesta del ciudadano' },
                                    { id: 'Finalizado', nombre: 'Finalizado' },
                                    { id: 'Finalizado vencido justificado', nombre: 'Finalizado vencido justificado' },
                                    { id: 'Respuesta parcial', nombre: 'Respuesta parcial' },
                                    { id: 'Devuelto', nombre: 'Devuelto' },
                                    { id: 'Cancelado', nombre: 'Cancelado' }
                                ]">
                            </select-check>
                                <small>Filtrar por estado</small>
                            </div>
                            <div class="col-md-4">
                                {!! Form::select('linea_tiempo',
                                    ["A tiempo" => "A tiempo",
                                    "Próximo a vencer" => "Próximo a vencer",
                                    "Vencido" => "Vencido"], null, ['v-model' => 'searchFields.linea_tiempo', 'class' => 'form-control']) !!}
                                <small>Filtrar por categoría en tiempo</small>
                            </div>
                            <div class="col-md-4">
                                <date-picker
                                    :value="searchFields"
                                    name-field="pqr.created_at"
                                    mode="range"
                                    :input-props="{required: true}">
                                </date-picker>
                                <small>Filtrar por fecha de recepción</small>
                            </div>
                            <div class="col-md-4">
                                <date-picker
                                    :value="searchFields"
                                    name-field="fecha_fin"
                                    mode="range"
                                    :input-props="{required: true}">
                                </date-picker>
                                <small>Filtrar por fecha de finalización</small>
                            </div>
                            <div class="col-md-4">
                                <date-picker
                                    :value="searchFields"
                                    name-field="fecha_vence"
                                    mode="range"
                                    :input-props="{required: true}">
                                </date-picker>
                                <small>Filtrar por fecha de vencimiento</small>
                            </div>
                            <div class="col-md-4">
                                <input type="text" v-model="searchFields.funcionario_destinatario" class="form-control" placeholder="Filtrar por funcionario destinatario" @keyup.enter="pageEventActualizado(1)" @keyup="!searchFields.funcionario_destinatario ? $delete(searchFields, funcionario_destinatario) : null">
                            </div>
                            <div class="col-md-4">
                                <select-check
                                    css-class="form-control"
                                    name-field="pqr_eje_tematico_id"
                                    reduce-label="nombre"
                                    reduce-key="id"
                                    name-resource="get-p-q-r-eje-tematicos"
                                    :value="searchFields">
                                </select-check>
                                <small>Filtrar por eje temático</small>
                            </div>

                            <div class="col-md-4 mb-2">
                                <select-check css-class="form-control" name-field="dependency_id" reduce-label="nombre"
                                    :value="searchFields" :is-required="true" :enable-search="true" :is-multiple="true" name-resource="/intranet/get-dependencies">
                                </select-check>
                                <small>Filtro por dependencia</small>
                            </div>

                            <div class="col-md-4">
                                {!! Form::select('destacado',
                                    [1 => "Si"], null, ['v-model' => 'searchFields.destacado', 'class' => 'form-control']) !!}
                                <small>Filtrar por destacado</small>
                            </div>

                            <div class="col-md-4">
                                <input type="text" v-model="searchFields.nombre_ciudadano" class="form-control" placeholder="Filtrar por Nombre del ciudadano" @keyup.enter="pageEventActualizado(1)" @keyup="!searchFields.nombre_ciudadano ? $delete(searchFields, nombre_ciudadano) : null">
                                <small>Ingrese un fragemento del nombre del ciudadano</small>
                            </div>

                            <div class="col-md-4">
                                <input type="text" v-model="searchFields.documento_ciudadano" class="form-control" placeholder="Filtrar por Documento del ciudadano" @keyup.enter="pageEventActualizado(1)" @keyup="!searchFields.documento_ciudadano ? $delete(searchFields, documento_ciudadano) : null">
                            </div>

                            <div class="col-md-4">
                                <input type="text" v-model="searchFields.email_ciudadano" class="form-control" placeholder="Filtrar por Email del ciudadano" @keyup.enter="pageEventActualizado(1)" @keyup="!searchFields.email_ciudadano ? $delete(searchFields, email_ciudadano) : null">
                            </div>

                            <div class="col-md-4">
                                {!! Form::select('canal',
                                ["Buzon de sugerencia" => "Buzon de sugerencia",
                                "Correo certificado" => "Correo certificado",
                                "Correo electrónico" => "Correo electrónico",
                                "FAX" => "FAX",
                                "Personal" => "Personal",
                                "Telefónico" => "Telefónico",
                                "Web" => "Web",
                                "Verbal" => "Verbal"], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.canal }", 'v-model' => 'searchFields.canal', 'required' => true]) !!}
                                <small>Filtro por canal</small>

                            </div>

                            <div class="col-md-4 mb-2">
                                <select-check css-class="form-control" name-field="pqr_tipo_solicitud_id" reduce-label="nombre"
                                    :value="searchFields" :is-required="true" :enable-search="true" :is-multiple="true" name-resource="/pqrs/get-p-q-r-tipo-solicituds-radicacion">
                                </select-check>
                                <small>Filtro por tipo de pqr</small>
                            </div>



                            <div class="col-md-4">
                                <button @click="pageEventActualizado(1)" class="btn btn-md btn-add"><i class="fa fa-search mr-2"></i>Buscar</button>
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
                <viewer-attachement :link-file-name="true" type="table" ref="viewerDocuments"></viewer-attachement>
                <!-- end buttons action table -->
                @include('pqrs::p_q_r_s.table')
            </div>
            <div class="p-b-15 text-center">
                <!-- Cantidad de elementos a mostrar -->
                <div class="form-group row m-5 col-md-3 text-center d-sm-inline-flex">
                    <label for="show_qty" class="col-form-label col-md-7">Cantidad a mostrar:</label>
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

        <!-- begin #modal-view-p-q-r-s -->
        <div class="modal fade" id="modal-view-p-q-r-s">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white d-flex justify-content-between align-items-center w-100">
                            <span>@lang('info_of') PQR: @{{dataShow?.pqr_id}}</span>
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body" id="showFields">
                       @include('pqrs::p_q_r_s.show_fields')
                    </div>
                    <div class="modal-footer">
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
        <!-- end #modal-view-p-q-r-s -->

        <!-- begin #modal-form-p-q-r-s -->
        <div class="modal fade" id="modal-form-p-q-r-s">
            <div class="modal-dialog modal-xl" v-if="isUpdate" style="max-width: 94vw;">
                <form @submit.prevent="$refs.componentePQR.validationStatus();" id="form-p-q-r-s" autocomplete="off">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@{{ isUpdate ? 'Formulario de edición del PQR: '+dataForm.pqr_id : '@lang('form_of') PQR' }}</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div style="display: flex;">
                            <div class="modal-body column col-md-6" v-if="openForm">
                                @include('pqrs::p_q_r_s.fields')
                            </div>
                            <div class="modal-body column col-md-6" v-if="openForm" style="background-color:#e0e0e0!important" id="editorDer">
                                @include('pqrs::p_q_r_s.edit_show')
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-dialog modal-xl" v-else>
                <form @submit.prevent="$refs.componentePQR.validationStatus();" id="form-p-q-r-s" autocomplete="off">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@{{ isUpdate ? 'Formulario de edición del PQR: '+dataForm.pqr_id : '@lang('form_of') PQR' }}</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('pqrs::p_q_r_s.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <dynamic-modal-form modal-id="share-PQRS" size-modal="lg" :title="'Compartir PQRS ' + dataForm.pqr_id"
        :data-form.sync="dataForm" endpoint="PQRS-share" :is-update="true"
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
                                name-field-autocomplete="recipient_autocomplete" name-field="copies_users"
                                name-resource="/correspondence/get-only-users" name-options-list="pqr_compartida"
                                :name-labels-display="['fullname']" name-key="users_id"
                                help="Ingrese el nombre del funcionario en la caja y seleccione para agregar a la lista"
                                :key="keyRefresh">
                            </add-list-autocomplete>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </dynamic-modal-form>
        <!-- end #modal-form-p-q-r-s -->

    <!-- modal para la migracion PQR -->
    <dynamic-modal-form
        modal-id="modal-form-migration"
        size-modal="lg"
        :data-form="dataForm"
        title="Migrar PQRS"
        endpoint="migration-modal"
        @saved="
        if($event.isUpdate) {
            assignElementList(dataForm.id, $event.data);
        } else {
            addElementToList($event.data);
        }"
        >
        <template #fields="scope">
            <div>
                <div class="panel mt-2" style="border: 200px; padding: 15px;">
                    <div>
                        <a href="/assets/documents/Formatos/Formato de contingencia PQR.xlsx" target="_blank">Descargar formato para contingencia de PQRS.</a>
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

        <holiday-calendar ref="holiday-calendars" name="holiday-calendars"></holiday-calendar>

        <annotations-general ref="annotations" route="/pqrs/p-q-r-anotacions" name-list="pqr_anotacions" file-path="public/container/pqrs_{{ date('Y') }}/anotaciones"  field-title="Anotaciones de PQRS: " field-title-var="pqr_id"  name-content="anotacion" name-users-name="nombre_usuario"></annotations-general>

        @if(config('app.mod_expedientes'))
            <expedientes-general
                ref="expedientes"
                :campo-consecutivo="'pqr_id'"
                :modulo="'PQRSD'"
                :puede-crear-expedientes="{{ Auth::user()->roles->pluck('name')->intersect(['Operador Expedientes Electrónicos'])->isNotEmpty() ? 'true' : 'false' }}"
                :user-id="{{ Auth::user()->id }}"
            ></expedientes-general>
        @endif
        <!-- begin #modal-form-p-q-r-s-relacionar-adjunto -->
        <div class="modal fade" id="modal-form-p-q-r-s-relacionar-adjunto">
            <div class="modal-dialog modal-lg">
                <form @submit.prevent="callFunctionComponent('componentePQR', 'actualizarAdjuntoRespuesta');" id="form-p-q-r-s-relacionar-adjunto">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@{{ 'Relacionar adjuntos de respuestas al PQRS: '+dataForm.pqr_id }}</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            <p>En en esta vista podrá relacionar los adjuntos de respuesta al PQRS seleccionado.</p>
                            <!-- Adjunto respuesta parcial Field -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('adjunto', trans('Adjuntar archivo').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                <div class="col-md-9">
                                    <input-file :file-name-real="true":value="dataForm" name-field="adjunto" :max-files="11"
                                        :max-filesize="30" file-path="public/container/pqr_{{ date('Y') }}"
                                        message="Arrastre o seleccione los archivos" help-text="Lista de archivos adjuntos."
                                        :is-update="isUpdate"  ruta-delete-update="pqrs/p-q-r-s-delete-file" :id-file-delete="dataForm.id">
                                    </input-file>
                                    <div class="invalid-feedback" v-if="dataErrors.adjunto">
                                        <p class="m-b-0" v-for="error in dataErrors.adjunto">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                            <div>Recuerde que luego de guardar los adjuntos realacionado a la respuesta del PQRS finalizado, no podrá volver a editar. Si así fuera deberá solicitarlo al administrador de PQRS de gestión documental.</div>
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-p-q-r-s-relacionar-adjunto -->

    </div>
</crud>
@endsection

@push('css')
{!!Html::style('assets/plugins/gritter/css/jquery.gritter.css')!!}
<style>
    /* Estilos para pantallas más pequeñas, por ejemplo, dispositivos móviles */
@media (max-width: 768px) {
    /* Oculta el iframe en dispositivos móviles */
    div#editorDer {
        display: none;
    }
}
</style>
@endpush

@push('scripts')
{!!Html::script('assets/plugins/gritter/js/jquery.gritter.js')!!}
<script>
    // detecta el enter para no cerrar el modal sin enviar el formulario
    $('#modal-form-p-q-r-s').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });

    const elementoDiv = document.getElementById('show_table');
        elementoDiv.style.display = 'none';

        // función encargada de mostrar y ocultar el flujo documental
        function toggleDiv(divId) {
            const elementoDiv = document.getElementById(divId);
            var otroDivId = '';
            if (elementoDiv.id === 'show_cards') {
                otroDivId = 'show_table';
            } else if(elementoDiv.id === 'show_table'){
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
        btnCard.addEventListener('mousedown', function() { changeColorOnHold(btnCard); });

        // Evento al soltar el clic
        btnCard.addEventListener('mouseup', function() { restoreColor(btnCard); });

        btnTable.addEventListener('mousedown', function() { changeColorOnHold(btnTable); });
        btnTable.addEventListener('mouseup', function() { restoreColor(btnTable); });

        btnDowLoad.addEventListener('mousedown', function() { changeColorOnHold(btnDowLoad); });
        btnDowLoad.addEventListener('mouseup', function() { restoreColor(btnDowLoad); });

</script>
@endpush
