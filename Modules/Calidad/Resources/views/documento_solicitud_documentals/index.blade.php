@extends('layouts.default')

@section('title', trans('Solicitudes de documentos'))

@section('section_img', '')

@section('menu')
    @include('calidad::layouts.menu')
@endsection

@section('content')

<crud
    name="documento-solicitud-documentals"
    :resource="{default: 'documento-solicitud-documentals', get: 'get-documento-solicitud-documentals'}"
    inline-template
    :init-values-search="{vigencia: ''}">
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('Solicitudes de documentos')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('solicitudes de elaboración, modificación, eliminación de documentos')'}}</h1>
        <!-- end page-header -->

        <!-- begin main buttons -->
        <div class="m-t-20">
            <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-documento-solicitud-documentals" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('solicitud')
            </button>
            <button onclick="window.location.href = window.location.href.split('?')[0];" class="btn btn-md btn-primary m-b-10">
                <i class="fa fa-redo-alt mr-2"></i>Cargar página de nuevo
            </button>
        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('solicitudes'): ${dataPaginator.total}` | capitalize }}</h5>
                </div>
                <div class="panel-heading-btn">
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
                                    name-field="vigencia"
                                    reduce-label="vigencia"
                                    reduce-key="valor"
                                    name-resource="/get-vigencias/calidad_documento/vigencia"
                                    :value="searchFields">
                                </select-check>
                                <small>Filtrar por vigencia</small>
                            </div>

                            <div class="col-md-4">
                                {!! Form::text('codigo', null, ['v-model' => 'searchFields.codigo', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => "código"])]) !!}
                            </div>

                            <div class="col-md-4 p-b-5">
                                {!! Form::text('nombre_documento', null, ['v-model' => 'searchFields.nombre_documento', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => "nombre del documento"])]) !!}
                                <small>Ingrese el nombre del documento</small>
                            </div>

                            <div class="col-md-4 p-b-5">
                                <select class="form-control" v-model="searchFields.tipo_solicitud">
                                    <option value="Elaboración">Elaboración</option>
                                    <option value="Modificación">Modificación</option>
                                    <option value="Eliminación">Eliminación</option>
                                    <option value="Actualización normograma">Actualización normograma</option>
                                </select>
                                <small>Filtrar por tipo de solicitud</small>
                            </div>

                            <div class="col-md-4 p-b-5">
                                <select class="form-control" v-model="searchFields.estado">
                                    <option value="Solicitud en revisión">Solicitud en revisión</option>
                                    <option value="Solicitud aprobada">Solicitud aprobada</option>
                                    <option value="Solicitud rechazada">Solicitud rechazada</option>
                                </select>
                                <small>Filtrar por estado</small>
                            </div>

                            <div class="col-md-4 p-b-5">
                                <select-check
                                    css-class="form-control"
                                    name-field="tipo_documento"
                                    reduce-label="nombre"
                                    name-resource="get-documento-tipo-documentos"
                                    :value="searchFields"
                                    :is-required="true"
                                    :enable-search="true"
                                    name-field-object="tipo_documentos"
                                    :is-filtro="true">
                                 </select-check>
                                <small>Filtro por tipo de documento</small>
                            </div>

                            <div class="col-md-4 p-b-5">
                                <date-picker
                                    :value="searchFields"
                                    name-field="de_documento.created_at"
                                    mode="range"
                                    :input-props="{required: true}">
                                </date-picker>
                                <small>Filtrar por fecha de creación</small>
                            </div>

                            <div class="col-md-4 mb-3">
                                {!! Form::text('nombre_solicitante', null, ['v-model' => 'searchFields.nombre_solicitante', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => "solicitante"])]) !!}
                            </div>

                            <div class="col-md-4 mb-3">
                                {!! Form::text('funcionario_responsable', null, ['v-model' => 'searchFields.funcionario_responsable', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => "responsable"])]) !!}
                            </div>

                            <div class="col-md-4">
                                <button @click="clearDataSearch()" class="btn btn-md btn-primary">@lang('clear_search_fields')</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end card search -->
            </div>
            <!-- end #accordion search -->
            <div class="panel-body">
                <!-- begin buttons action table -->

                <!-- end buttons action table -->
                <div class="float-xl-right m-b-15">
                    <!-- Acciones para exportar datos de tabla-->
                    <div class="btn-group">
                        <a href="javascript:;" data-toggle="dropdown" class="btn btn-primary"><i class="fa fa-download mr-2"></i> @lang('export_data_table')</a>
                        <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle" aria-expanded="false"><b class="caret"></b></a>
                        <div class="dropdown-menu dropdown-menu-right bg-blue">
                            {{-- <a href="javascript:;" @click="exportDataTable('pdf')" class="dropdown-item text-white no-hover"><i class="fa fa-file-pdf mr-2"></i> PDF</a> --}}
                            <a href="javascript:;" @click="exportDataTableAvanzado('xlsx')" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i> EXCEL</a>
                        </div>
                    </div>
                </div>
                @include('calidad::documento_solicitud_documentals.table')
            </div>
            <div class="p-b-15 text-center">
                <!-- Cantidad de elementos a mostrar -->
                <div class="form-group row m-5 col-md-3 text-center d-sm-inline-flex">
                    <label for="show_qty" class="col-form-label col-md-7">Cantidad a mostrar:</label>
                    <div class="col-md-5">
                        <select class="form-control" v-model="dataPaginator.pagesItems" name="Cantidad a mostrar"><option value="5" selected="selected">5</option><option value="10">10</option><option value="15">15</option><option value="20">20</option><option value="25">25</option><option value="30">30</option><option value="50">50</option><option value="100">100</option><option value="200">200</option></select>
                    </div>
                </div>
                <!-- Paginador de tabla -->
                <div class="col-md-12">
                    <paginate
                        v-model="dataPaginator.currentPage"
                        :page-count="dataPaginator.numPages"
                        :click-handler="pageEvent"
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

        <!-- begin #modal-view-documento-solicitud-documentals -->
        <div class="modal fade" id="modal-view-documento-solicitud-documentals">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') la @lang('solicitud documental')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                       @include('calidad::documento_solicitud_documentals.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-documento-solicitud-documentals -->

        <!-- begin #modal-form-documento-solicitud-documentals -->
        <div class="modal fade" id="modal-form-documento-solicitud-documentals">
            <div class="modal-dialog modal-xl">
                <form @submit.prevent="save()" id="form-documento-solicitud-documentals">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">Formato de solicitud documental <br />(creación, modificación, eliminación de documento o incluisión de documento normativo)</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('calidad::documento_solicitud_documentals.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-documento-solicitud-documentals -->

        <!-- begin #modal-form-documento-solicitud-documentals -->
        <dynamic-modal-form modal-id="modal-gestion-solicitud-documental" size-modal="lg" title="Gestión de solicitudes documentales"
            :data-form.sync="dataForm" endpoint="gestionar-solicitud-documental" :is-update="true" :inicializar-data-form="false"
            @saved="assignElementList(dataForm.id, $event.data);">
            <template #fields="scope">
                <div>
                    <div class="form-group row m-b-15 col-md-12">
                        {!! Form::label('estado_solicitud', '¿Qué desea hacer con la solicitud?:', ['class' => 'col-form-label col-md-4 required']) !!}
                        <div class="col-md-8">
                            <select class="form-control" v-model="dataForm.estado_solicitud" required>
                                <option value="Solicitud aprobada">Aprobar solicitud</option>
                                <option value="Solicitud rechazada">Rechazar solicitud</option>
                            </select>
                            <small>Seleccione una de las opciones del listado.</small>
                            <div class="invalid-feedback" v-if="dataErrors.estado_solicitud">
                                <p class="m-b-0" v-for="error in dataErrors.estado_solicitud">
                                    @{{ error }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row m-b-15 col-md-12">
                        {!! Form::label('observaciones', 'Observaciones:', ['class' => 'col-form-label col-md-4 required']) !!}
                        <div class="col-md-8">
                            {!! Form::textarea('observaciones', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observaciones }", 'v-model' => 'dataForm.observaciones', 'required' => true, 'rows' => '3']) !!}
                            <small>Ingrese el motivo por el cual seleccionó la opción anterior.</small>
                            <div class="invalid-feedback" v-if="dataErrors.observaciones">
                                <p class="m-b-0" v-for="error in dataErrors.observaciones">
                                    @{{ error }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </dynamic-modal-form>
        <!-- end #modal-form-documento-solicitud-documentals -->

    </div>
</crud>
@endsection

@push('css')
{!!Html::style('assets/plugins/gritter/css/jquery.gritter.css')!!}
@endpush

@push('scripts')
{!!Html::script('assets/plugins/gritter/js/jquery.gritter.js')!!}
<script>
    // detecta el enter para no cerrar el modal sin enviar el formulario
    $('#modal-form-documento-solicitud-documentals').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
