@extends('layouts.default')

@section('title', trans('seriesSubSeries'))

@section('section_img', '/assets/img/components/inventario_documents.png')

@section('menu')
    @include('documentaryclassification::layouts.menu')
@endsection

@section('content')

<crud
    name="series-subseries"
    :resource="{default: 'series-subseries', get: 'get-series-subseries'}"
    :init-values="{enable_expediente: 1, types_list: []}"
    inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('seriesSubSeries')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">@{{ '@lang('text_title') @lang('seriesSubSeries')'}}</h1>
        <!-- end page-header -->

        <!-- begin main buttons -->
        <div class="m-t-20">
            <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-series-subseries" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>Crear Serie o Subserie documental
            </button>
            <button onclick="window.location=''" class="btn btn-md btn-primary m-b-10"><i class="fa fa-redo-alt mr-2"></i>Cargar página de nuevo</button>

        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('seriesSubSeries'): ${dataPaginator.total}` | capitalize }}</h5>
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
                        {{-- <label class="col-form-label"><b>@lang('quick_search')</b></label> --}}
                        <!-- Campos de busqueda -->

                        <div class="row form-group">
                            <label class="col-form-label col-md-2" for="name_serie"><b>Serie documental a la que pertenece:</b></label>
                                <div class="col-md-3">
                                    <select-check
                                    css-class="form-control"
                                    name-field="name_serie"
                                    :reduce-label="['no_serie','name_serie']"
                                    reduce-key="name_serie"
                                    name-resource="get-series"
                                    :value="searchFields"
                                    :is-required="true">
                                    </select-check>
                                    <small>Filtro por serie documental</small>
                                </div>

                            <label class="col-form-label col-md-2" for="name_serie"><b>Código:</b></label>
                                <div class="col-md-3">
                                    {!! Form::text('code', null, ['v-model' => 'searchFields.no_serie',':class' => "{'form-control':true, 'is-invalid':dataErrors.folios }" ]) !!}
                                    <small>Filtro por código</small>

                                </div>
                        </div>


                        <div class="row form-group">
                            <label class="col-form-label col-md-2" for="name_serie"><b>Nombre serie:</b></label>
                                <div class="col-md-3">
                                {!! Form::text('name', null, ['v-model' =>'searchFields.name_serie', 'class' => 'form-control' ]) !!}
                                <small>Filtro por nombre de la serie</small>

                                </div>

                            <label class="col-form-label col-md-2" for="name_serie"><b>Nombre subserie:</b></label>
                                <div class="col-md-3">
                                    {!! Form::text('name', null, ['v-model' =>'searchFields.name_subserie', 'class' => 'form-control' ]) !!}
                                <small>Filtro por nombre de la subserie</small>

                                </div>
                        </div>


                        <div class="row form-group">
                            <label class="col-form-label col-md-2" for="enable_expediente"><b>¿Habilitada para expedientes?:</b></label>
                            <div class="col-md-3">
                                <select v-model="searchFields.enable_expediente" class="form-control">
                                    <option value="1">Si</option>
                                    <option value="0">No</option>
                                </select>
                                <small>Filtro por habilitada para expedientes</small>
                            </div>
                        </div>

                        <br>
                        <br>

                        <div class="col-md-4">
                            <button @click="clearDataSearch()" class="btn btn-md btn-primary">@lang('clear_search_fields')</button>
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
                            <a href="javascript:;" @click="exportDataTable('pdf')" class="dropdown-item text-white no-hover"><i class="fa fa-file-pdf mr-2"></i> PDF</a>
                            <a href="javascript:;" @click="exportDataTable('xlsx')" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i> EXCEL</a>
                        </div>
                    </div>
                </div> --}}
                <!-- end buttons action table -->
                @include('documentaryclassification::series_sub_series.table')
            </div>
            <div class="p-b-15 text-center">
                <!-- Cantidad de elementos a mostrar -->
                <div class="form-group row m-5 col-md-3 text-center d-sm-inline-flex">
                    {!! Form::label('show_qty', trans('show_qty').':', ['class' => 'col-form-label col-md-7']) !!}
                    <div class="col-md-5">
                        {!! Form::select(trans('show_qty'), [5 => 5, 10 => 10, 15 => 15, 20 => 20, 25 => 25, 30 => 30, 50 => 50, 75 => 75], '5', ['class' => 'form-control', 'v-model' => 'dataPaginator.pagesItems']) !!}
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

        <!-- begin #modal-view-seriesSubSeries -->
        <div class="modal fade" id="modal-view-seriesSubSeries">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') @lang('seriesSubSeries')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        @include('documentaryclassification::series_sub_series.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-seriesSubSeries -->

        <!-- begin #modal-form-series-subseries -->
        <div class="modal fade" id="modal-form-series-subseries">
            <div class="modal-dialog modal-lg">
                <form @submit.prevent="save()" id="form-series-subseries">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of') @lang('seriesSubSeries')</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('documentaryclassification::series_sub_series.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

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
    $('#modal-form-series-subseries').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
