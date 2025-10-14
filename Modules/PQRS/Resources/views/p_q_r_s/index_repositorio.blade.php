@extends('layouts.default')

@section('title', trans('PQRS'))

@section('section_img', '/assets/img/components/convocatoria.png')

@section('menu')
    @include('pqrs::layouts.menu')
@endsection

@section('content')
    {{-- <!-- Valida si es un funcionario administrador o funcionario ordinario -->
    @if(Auth::user()->hasRole('Administrador de requerimientos'))
        <iframe src="{{ config("app.url_joomla") }}/index.php?option=com_formasonline&formasonlineform=RequerimientosAdmin&tmpl=component" frameborder="0" style="width: 100%; height: 82vh;"></iframe>
    @else
        <iframe src="{{ config("app.url_joomla") }}/index.php?option=com_formasonline&formasonlineform=RequerimientosFunc&tmpl=component" frameborder="0" style="width: 100%; height: 82vh;"></iframe>
    @endif --}}

    <crud
    name="p-q-r-s"
    :resource="{default: 'p-q-r-s', get: 'get-p-q-r-s-repository?vigencia={!! $vigencia ?? null !!}&'}"
    :init-values="{vigencia: '{!! $vigencia ?? '' !!}' }"
    :crud-avanzado="true"
    inline-template  >
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/home') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">repositorio PQRS</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') el repositorio de PQRS'}}</h1>

        <div class="row">
            <div class="col-md-4">

                {{-- {!! Form::text('documento', null, ['v-model' => 'searchFields.documento', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['documento' => trans('ejemplo')]) ]) !!} --}}
                <select id="vigencia" v-model="dataForm.vigencia" value="2023" class="custom-select" name="vigencia" required>
                    <option value="" selected>Seleccione</option>
                    <option value="2024">2024</option>
                    <option value="2023">2023</option>
                    <option value="2022">2022</option>
                    <option value="2021">2021</option>
                    <option value="2020">2020</option>
                    <option value="2019">2019</option>
                    <option value="2018">2018</option>
                    <option value="2017">2017</option>
                    <option value="2016">2016</option>

                </select>
                <small>Filtro por vigencia</small>
            </div>
            <div class="col-md-4">
                <a :href="'repository-pqr?vigencia='+dataForm.vigencia" style="button">
                <button class="btn btn-primary text-white">filtrar vigencia</button>
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
                                {!! Form::text('id', null, ['v-model' => 'searchFields.id', 'class' => 'form-control','@keyup.enter' => 'pageEventActualizado(1)', 'placeholder' => trans('crud.filter_by', ['name' => trans('número de radicado')]) ]) !!}
                            </div>
                            <div class="col-md-4">
                                {!! Form::select('estado',
                                    ["Finalizado" => "Finalizado",
                                    "Cancelado" => "Cancelado"], null, ['v-model' => 'searchFields.estado', 'class' => 'form-control']) !!}
                                <small>Filtrar por estado</small>
                            </div>

                            <div class="col-md-4">
                                <date-picker
                                    :value="searchFields"
                                    name-field="cf_created"
                                    mode="range"
                                    :input-props="{required: true}">
                                </date-picker>
                                <small>Filtrar por fecha de recepción</small>
                            </div>

                            <div class="col-md-4">
                                <date-picker
                                    :value="searchFields"
                                    name-field="fechafin"
                                    mode="range"
                                    :input-props="{required: true}">
                                </date-picker>
                                <small>Filtrar por fecha de finalización</small>
                            </div>

                            <div class="col-md-4">
                                <date-picker
                                    :value="searchFields"
                                    name-field="fechavence"
                                    mode="range"
                                    :input-props="{required: true}">
                                </date-picker>
                                <small>Filtrar por fecha de vencimiento</small>
                            </div>

                            <div class="col-md-4">
                                {!! Form::text('funcionario_asignado', null, ['v-model' => 'searchFields.funcionario_asignado', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('funcionario destinatario')]), '@keyup.enter' => 'pageEventActualizado(1)' ]) !!}
                            </div>

                            <div class="col-md-4">
                                <select-check
                                    css-class="form-control"
                                    name-field="nombre_ejetematico"
                                    reduce-label="nombre"
                                    reduce-key="nombre"
                                    name-resource="get-p-q-r-eje-tematicos"
                                    :value="searchFields">
                                </select-check>
                                <small>Filtrar por eje temático</small>
                            </div>

                            
                            <div class="col-md-4 mb-2">
                                <select-check css-class="form-control" name-field="dependencia" reduce-label="nombre"
                                    :value="searchFields" :is-required="true" :enable-search="true" :is-multiple="true" name-resource="/intranet/get-dependencies">
                                </select-check>
                                <small>Filtro por dependencia</small>
                            </div>

                            <div class="col-md-4">
                                {!! Form::text('nombre_ciudadano', null, ['v-model' => 'searchFields.nombre_ciudadano','@keyup.enter' => 'pageEventActualizado(1)','class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Nombre del ciudadano')]) ]) !!}
                            </div>

                            <div class="col-md-4">
                                {!! Form::text('documento_ciudadano', null, ['v-model' => 'searchFields.documento_ciudadano', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Documento del ciudadano')]), '@keyup.enter' => 'pageEventActualizado(1)' ]) !!}
                            </div>

                            <div class="col-md-4">
                                {!! Form::text('email_ciudadano', null, ['v-model' => 'searchFields.email_ciudadano', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Email del ciudadano')]), '@keyup.enter' => 'pageEventActualizado(1)' ]) !!}
                            </div>

                            <div class="col-md-4">
                                {!! Form::select('canal',
                                ["Buzon de sugerencia" => "Buzon de sugerencia",
                                "Correo certificado" => "Correo certificado",
                                "Correo electrónico" => "Correo electrónico",
                                "FAX" => "FAX",
                                "Personal" => "Personal",
                                "Telefónico" => "Telefónico",
                                "Web" => "Web"], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.canal }", 'v-model' => 'searchFields.canal', 'required' => true]) !!}                            
                                <small>Filtro por canal</small>
                            </div>
                                
                        </div>
                        <button @click="pageEventActualizado(1)" class="btn btn-md btn-primary"><i class="fa fa-search mr-2"></i>Buscar</button>
                        <button @click="clearDataSearchAvanzado()" class="btn btn-md btn-primary">@lang('clear_search_fields')</button>
                    </div>
                </div>
                <!-- end card search -->
            </div>


            <viewer-attachement :link-file-name="true" type="table" ref="viewerDocuments"></viewer-attachement>

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
                @include('pqrs::p_q_r_s.table_repository')
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
                       @include('pqrs::p_q_r_s.show_fields_repository')
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
