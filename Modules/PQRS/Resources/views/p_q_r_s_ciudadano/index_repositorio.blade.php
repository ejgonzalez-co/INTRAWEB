@extends('layouts.default')

@section('title', trans('PQRS'))

@section('section_img', '/assets/img/components/convocatoria.png')

@section('menu')
    @include('pqrs::layouts.menu_ciudadano')
@endsection

@section('content')
    <crud
    name="p-q-r-s"
    :resource="{default: 'p-q-r-s', get: 'get-p-q-r-s-repository-ciudadano'}"
    :init-values-search="{ vigencia: '' }"
    :crud-avanzado="true"
    inline-template  >
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/home') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">Repositorio de PQRS</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">@{{ 'Repositorio de PQRS'}}</h1>

        <div class="row">
            <div class="col-md-4">
                <select-check
                    css-class="form-control"
                    name-field="vigencia"
                    reduce-label="vigencia"
                    reduce-key="valor"
                    name-resource="/get-vigencias-tablas/pqr"
                    :value="searchFields">
                </select-check>
                <small>Filtrar por vigencia</small>
            </div>
            <div class="col-md-4">
                {{-- <a :href="'?vigencia='+dataForm.vigencia" style="button"> --}}
                <a href="javascript:;" @click="_getDataListAvanzado(true);">
                    <button class="btn btn-primary text-white">Filtrar vigencia</button>
                </a>
            </div>
        </div>
        <br>
        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') PQRS: ${dataPaginator.total}` }}</h5>
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
                            <div class="col-md-4">
                                {!! Form::text('pqr_id', null, ['v-model' => 'searchFields.pqr_id', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('número de radicado')]) ]) !!}
                            </div>
                            <div class="col-md-4">
                                {!! Form::select('estado',
                                    ["Abierto" => "Abierto",
                                    "Asignado" => "Asignado",
                                    "En trámite" => "En trámite",
                                    "Esperando respuesta del ciudadano" => "Esperando respuesta del ciudadano",
                                    "Finalizado" => "Finalizado",
                                    "Finalizado vencido justificado" => "Finalizado vencido justificado",
                                    "Respuesta parcial" => "Respuesta parcial",
                                    "Devuelto" => "Devuelto",
                                    "Cancelado" => "Cancelado"], null, ['v-model' => 'searchFields.estado', 'class' => 'form-control']) !!}
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
                                    name-field="created_at"
                                    mode="range"
                                    :input-props="{required: true}">
                                </date-picker>
                                <small>Filtrar por fecha de recepción</small>
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
                                {!! Form::text('funcionario_destinatario', null, ['v-model' => 'searchFields.funcionario_destinatario', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('funcionario destinatario')]) ]) !!}
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
                            <div class="col-md-4">
                                {!! Form::select('destacado',
                                    [1 => "Si"], null, ['v-model' => 'searchFields.destacado', 'class' => 'form-control']) !!}
                                <small>Filtrar por destacado</small>
                            </div>
                            <div class="col-md-4">
                                <button @click="pageEventActualizado(1)" class="btn btn-md btn-primary"><i class="fa fa-search mr-2"></i>Buscar</button>
                                <button @click="clearDataSearchAvanzado()" class="btn btn-md btn-primary">@lang('clear_search_fields')</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end card search -->
            </div>


            <viewer-attachement type="table" ref="viewerDocuments"></viewer-attachement>

            <!-- end #accordion search -->
            <div class="panel-body">
                <!-- begin buttons action table -->
                <div class="float-xl-right m-b-15" v-if="dataList.length">
                    <!-- Acciones para exportar datos de tabla-->
                    <div class="btn-group">
                        <a href="javascript:;" data-toggle="dropdown" class="btn btn-primary"><i class="fa fa-download mr-2"></i> @lang('export_data_table')</a>
                        <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle" aria-expanded="false"><b class="caret"></b></a>
                        <div class="dropdown-menu dropdown-menu-right bg-blue">
                            <a href="javascript:;" @click="exportRepositoryPqr('xlsx',{!! $vigencia ?? null !!})" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i> EXCEL</a>
                        </div>
                    </div>
                </div>
                <!-- end buttons action table -->
                @include('pqrs::p_q_r_s_ciudadano.table_repository')
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

        <!-- begin #modal-view-p-q-r-s -->
        <div class="modal fade" id="modal-view-p-q-r-s-repository">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of')l PQR</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                       @include('pqrs::p_q_r_s_ciudadano.show_fields_repository')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-p-q-r-s -->
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
    $('#modal-form-p-q-r-s').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
