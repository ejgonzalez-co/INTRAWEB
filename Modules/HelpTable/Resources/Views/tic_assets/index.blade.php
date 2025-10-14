@extends('layouts.default')

@section('title', trans('Tic Assets'))

@section('section_img', '/assets/img/components/inventario.png')

@section('menu')
    @include('help_table::layouts.menus.menu_requests')
@endsection

@section('content')

<crud name="tic-assets" :resource="{default: 'tic-assets', get: 'get-tic-assets'}" inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/home') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('Tic Assets')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('Tic Assets')'}}</h1>
        <!-- end page-header -->

        <!-- begin main buttons -->
        <div class="m-t-20">
            <button @click="callFunctionComponent('loadAssets', 'loadAssets');" type="button" class="btn btn-primary m-b-10">
                <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('Tic Assets')
            </button>

            <button @click="openForm = true;" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-migrate" data-toggle="modal">
                <i class="fas fa-file-import mr-2"></i>Importar activos TIC
            </button>
        </div>



        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('Tic Assets'): ${dataPaginator.total}` | capitalize }}</h5>
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
                            {{-- <div class="col-md-4 mb-2">
                                <select-check
                                    css-class="form-control"
                                    name-field="ht_tic_period_validity_id"
                                    reduce-label="name"
                                    name-resource="get-tic-period-validities"
                                    :value="searchFields"
                                >
                                </select-check>
                                <small>Filtro por vigencia</small>
                            </div> --}}
                            <div class="col-md-4 mb-2">
                                <select-check
                                    css-class="form-control"
                                    name-field="dependencias_id"
                                    reduce-label="nombre"
                                    name-resource="/intranet/get-dependencies"
                                    :value="searchFields"
                                >
                                </select-check>
                                <small>Filtro por dependencia</small>
                            </div>
                            <div class="col-md-4 mb-2">
                                <select-check
                                    css-class="form-control"
                                    name-field="ht_tic_type_assets_id"
                                    reduce-label="name"
                                    name-resource="get-tic-type-assets"
                                    :value="searchFields"
                                >
                                </select-check>
                                <small>Filtro por tipo de activo</small>
                            </div>
                            <div class="col-md-4 mb-2">
                                {!! Form::text('name', null, ['v-model' => 'searchFields.consecutive', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Consecutive')]) ]) !!}
                            </div>
                            <div class="col-md-4 mb-2">
                                {!! Form::text('name', null, ['v-model' => 'searchFields.name', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Name')]) ]) !!}
                            </div>
                            <div class="col-md-4 mb-2">
                                {!! Form::text('inventory_plate', null, ['v-model' => 'searchFields.inventory_plate', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Inventory Plate')]) ]) !!}
                            </div>
                            <div class="col-md-4 mb-2">
                                {!! Form::text('serial', null, ['v-model' => 'searchFields.serial', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Serial')]) ]) !!}
                            </div>
                            <div class="col-md-4 mb-2">
                                {!! Form::text('brand', null, ['v-model' => 'searchFields.brand', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Brand')]) ]) !!}
                            </div>
                            <div class="col-md-4 mb-2">
                                {!! Form::text('model', null, ['v-model' => 'searchFields.model', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Model')]) ]) !!}
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
                <div class="float-xl-right m-b-15">
                    <!-- Acciones para exportar datos de tabla-->
                    <div class="btn-group">
                        <a href="javascript:;" data-toggle="dropdown" class="btn btn-primary"><i class="fa fa-download mr-2"></i> @lang('export_data_table')</a>
                        <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle" aria-expanded="false"><b class="caret"></b></a>
                        <div class="dropdown-menu dropdown-menu-right bg-blue">
                            {{--<a href="javascript:;" @click="exportDataTable('pdf')" class="dropdown-item text-white no-hover"><i class="fa fa-file-pdf mr-2"></i> PDF</a>--}}
                            <a href="javascript:;" @click="exportDataTable('xlsx')" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i> EXCEL</a>
                        </div>
                    </div>
                </div>
                <!-- end buttons action table -->
                @include('help_table::tic_assets.table')
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

        <dynamic-modal-form
        modal-id="modal-form-migrate"
        size-modal="lg"
        title="Importación activos TIC"
        :data-form.sync="dataForm"
        endpoint="migrate-tic-assets"
        @saved="addElementToList($event.data);">
            <template #fields="scope">
                <!--  Other officials Field destination-->
                <div class="form-group row m-b-15">
                    {!! Form::label('users', 'Descargar archivo:', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <a href="/assets/formatos/formato-migracion-activos-TIC.xlsx" target="_blank"><i class="fas fa-upload mr-2"></i>Archivo de importación</a>
                    </div>
                </div>
                <div class="form-group row m-b-15">
                    {!! Form::label('file_import', 'Relacione el archivo de importación:', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::file('file_import', ['accept' => '.xls,.xlsx', '@change' => 'inputFile($event, "file_import")', 'required' => true]) !!}
                    </div>
                </div>
            </template>
        </dynamic-modal-form>

        <!-- begin #modal-view-tic-assets -->
        <div class="modal fade" id="modal-view-tic-assets">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') @lang('Tic Assets')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        @include('help_table::tic_assets.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-tic-assets -->

        {{-- Invoca el formulario de la gestion del activo --}}
        <assets-tics ref="loadAssets" name="tic-assets"></assets-tics>
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
    $('#modal-form-tic-assets').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
