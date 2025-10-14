@extends('layouts.default')

@section('title', trans('Resume-machinery-vehicles-yellows'))

@section('section_img', '/assets/img/components/gestion_activos.png')

@section('menu')
    @include('maintenance::layouts.menus.menu_resume')
@endsection

@section('content')

    
<crud name="resume-machinery-vehicles-yellows" :crud-avanzado="true" :resource="{default: 'resume-machinery-vehicles-yellows', get: 'get-resume-machinery-vehicles-yellows'}" inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/home') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('resume-machinery-vehicles-yellows')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('resume-machinery-vehicles-yellows')'}}</h1>
        <!-- end page-header -->
        
        <!-- begin widget -->
        
        <div class="row">
            @foreach ($consolidatedRequestBoard as $key => $requestBoard)
                <widget-counter-filter-maitenance
                    class-css-col="col-md-3"
                    icon="fa fa-wrench"
                    bg-color="#6fb154"
                    title-link-see-more="Filtrar"
                    status="{!! $requestBoard['id'] !!}"
                    class-css-color="bg-green"
                    :qty="{!! $requestBoard['total_registro'] !!}"
                    :value="searchFields"
                    name-field="mant_asset_type_id"
                    title="{!! $requestBoard['name'] !!}">
                </widget-counter-filter-maitenance>
            @endforeach

            <button  @click="clearDataSearch()" style="height: 40px; margin-left:10px;" class="btn btn-md btn-primary">Limpiar datos filtrados</button>
        </div>
        <!-- end widget -->

        <!-- begin main buttons -->
        <div class="m-t-20">
            @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo') || Auth::user()->hasRole('mant Operador líder'))
            <button @click="callFunctionComponent('loadAssets', 'loadAssets');" type="button" class="btn btn-primary m-b-10">
                <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('resume-machinery-vehicles-yellow')
            </button>
            @endif
        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('resume-machinery-vehicles-yellows'): ${dataPaginator.total}` | capitalize }}</h5>
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
                                {!! Form::date('created_at', null, ['v-model' => 'searchFields.created_at', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Fecha de creación')]) ]) !!}
                            </div>

                            <div class="col-md-4">
                                <select-check css-class="form-control" name-field="mant_asset_type_id" reduce-label="name" name-resource="/maintenance/get-asset-types" :value="searchFields"></select-check>
                                <small>Filtrar por tipo de activo</small>
                            </div>

                            <div class="col-md-4">
                                {!! Form::text('name_vehicle_machinery', null, ['v-model' => 'searchFields.name_vehicle_machinery','@keyup.enter' => 'pageEventActualizado(1)', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('nombre de activo')]) ]) !!}
                            </div>

                            <div class="col-md-4" v-if="searchFields.mant_asset_type_id=='10'">
                                {!! Form::text('consecutive', null, ['v-model' => 'searchFields.consecutive', 'class' => 'form-control','@keyup.enter' => 'pageEventActualizado(1)', 'placeholder' => trans('crud.filter_by', ['name' => trans('consecutivo')]) ]) !!}
                            </div>

                            <div class="col-md-4">
                                <select-check css-class="form-control" name-field="dependencias_id_igual_" reduce-label="nombre" name-resource="/intranet/get-dependencies" :value="searchFields"></select-check>
                                <small>Filtrar por dependencia autorizada </small>
                                <input type="hidden" v-model="searchFields.typeQuery='dependencias_id,mant_asset_type_id,mant_category_id'">
                            </div>

                           

                            
                            <div class="col-md-4" >
                                {!! Form::text('no_inventory', null, ['v-model' => 'searchFields.no_inventory','@keyup.enter' => 'pageEventActualizado(1)', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Nº de inventario')]) ]) !!}
                            </div>

                            <div class="col-md-4">
                                {!! Form::text('mark', null, ['v-model' => 'searchFields.mark','@keyup.enter' => 'pageEventActualizado(1)', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('marca')]) ]) !!}
                            </div>

                            <div class="col-md-4">
                                {!! Form::text('model', null, ['v-model' => 'searchFields.model','@keyup.enter' => 'pageEventActualizado(1)', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('modelo')]) ]) !!}
                            </div>
                            

                            <div class="col-md-4">
                                <select-check css-class="form-control" name-field="mant_category_id" reduce-label="name" name-resource="/maintenance/get-categories" :value="searchFields"></select-check>
                                <small>Filtrar por tipo de categoría</small>
                            </div>
                            
                            <br />
                            <div class="col-md-4">
                                {!! Form::text('plaque', null, ['v-model' => 'searchFields.plaque','@keyup.enter' => 'pageEventActualizado(1)', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('placa de vehículo')]) ]) !!}
                            </div>

                            <!-- filtro de busqueda por Tipo combustible. -->
                           <div class="col-md-4" >
                                {!! Form::select('Tipo',['Gasolina'=>'Gasolina','ACPM'=>'ACPM','No aplica'=>'No aplica'], null, ['v-model' => 'searchFields.fuel_type', 'class' => 'form-control' ]) !!} 
                                <small>Filtro por tipo de combustible</small> 
                            </div>

                            <div class="col-md-4" v-if="searchFields.mant_asset_type_id">
                                {!! Form::text('serie', null, ['v-model' => 'searchFields.serie','@keyup.enter' => 'pageEventActualizado(1)', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Serie')]) ]) !!}
                            </div>
                            
                            <br />
                        </div>
                        <div class="row form-group">
                            <div class="col-md-4">
                                <button @click="pageEventActualizado(1)" class="btn btn-md btn-primary"><i class="fa fa-search mr-2"></i>Buscar</button>
                                <button @click="clearDataSearchAvanzado()" class="btn btn-md btn-primary">@lang('clear_search_fields')</button>
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
                        </div>
                    </div>
                </div>
                <!-- end buttons action table -->
                @include('maintenance::resume_machinery_vehicles_yellows.table')
            </div>
            <div class="p-b-15 text-center">
                <!-- Cantidad de elementos a mostrar -->
                <div class="form-group row m-5 col-md-3 text-center d-sm-inline-flex">
                    {!! Form::label('show_qty', trans('show_qty').':', ['class' => 'col-form-label col-md-7']) !!}
                    <div class="col-md-5">
                        {!! Form::select(trans('show_qty'), [5 => 5, 10 => 10, 15 => 15, 20 => 20, 25 => 25, 30 => 30, 50 => 50, 100 => 100, 200 => 200], 20, ['class' => 'form-control', 'v-model' => 'dataPaginator.pagesItems', '@change' => 'pageEventActualizado(1)']) !!}                    
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

        <!-- begin #modal-view-resume-machinery-vehicles-yellows -->
        <div class="modal fade" id="modal-view-resume-machinery-vehicles-yellows">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white" v-if="Object.keys(dataShow).length && dataShow.mant_category.mant_asset_type.form_type == 1">@lang('info_of') hoja de vida de los vehículos y maquinaria amarilla</h4>
                        <h4 class="modal-title text-white" v-if="Object.keys(dataShow).length && dataShow.mant_category.mant_asset_type.form_type == 2">@lang('info_of') hoja de vida de los equipos menores</h4>
                        <h4 class="modal-title text-white" v-if="Object.keys(dataShow).length && dataShow.mant_category.mant_asset_type.form_type == 3">@lang('info_of') hoja de vida plantas y medidores</h4>
                        <h4 class="modal-title text-white" v-if="Object.keys(dataShow).length && dataShow.mant_category.mant_asset_type.form_type == 4">@lang('info_of') hoja de vida del equipamiento (LECA)</h4>
                        <h4 class="modal-title text-white" v-if="Object.keys(dataShow).length && dataShow.mant_category.mant_asset_type.form_type == 5">@lang('info_of') inventario y cronograma del aseguramiento metrológico</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="spinner" v-if="!Object.keys(dataShow).length" style="position: absolute;top: 20%; z-index: 1;"><span class="spinner-inner"></span></div>
                    <div class="modal-body" :style="{ opacity: !Object.keys(dataShow).length ? 0.2 : 1 }" id="showFields">
                        <div class="row" v-if="Object.keys(dataShow).length && dataShow.mant_category.mant_asset_type.form_type == 1">
                            @include('maintenance::resume_machinery_vehicles_yellows.show_fields')
                        </div>

                        <div class="row" v-if="Object.keys(dataShow).length && dataShow.mant_category.mant_asset_type.form_type == 2">
                            @include('maintenance::resume_equipment_machineries.show_fields')
                        </div>

                        <div class="row" v-if="Object.keys(dataShow).length && dataShow.mant_category.mant_asset_type.form_type == 3">
                            @include('maintenance::resume_equipment_machinery_lecas.show_fields')
                        </div>

                        <div class="row" v-if="Object.keys(dataShow).length && dataShow.mant_category.mant_asset_type.form_type == 4">
                            @include('maintenance::resume_equipment_lecas.show_fields')
                        </div>

                        <div class="row" v-if="Object.keys(dataShow).length && dataShow.mant_category.mant_asset_type.form_type == 5">
                            @include('maintenance::resume_inventory_lecas.show_fields')
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{-- <button class="btn btn-warning" type="button" onclick="printContent('contentDetalles');"><i class="fa fa-print mr-2"></i>@lang('print')</button> --}}
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
        <!-- end #modal-view-resume-machinery-vehicles-yellows -->

        <!-- Componente para importar el formulario y las funcionalidades de la creación de un activo -->
        <assets-create ref="loadAssets" name="resume-machinery-vehicles-yellows"></assets-create>
        <send-petition-message ref="send-petition" title-alert="Hoja de vida de activos" text-alert="¿Desea exportar la hoja de vida del activo?" request-type="post" resource-alert="export-hoja-de-vida-activo" :loading-alert="true" :download-export-data="true" name-export="hoja de vida equipos menores" type-fyle-export="xlsx" title-confirmation-swal="Exportación exitosa" text-confirmation-swal="El reporte de la hoja de vida se ha exportado correctamente."></send-petition-message>

        <send-petition-message ref="send-petition-machinery" title-alert="Hoja de vida de activos" text-alert="¿Desea exportar la hoja de vida del activo?" request-type="post" resource-alert="export-hoja-de-vida-activo-machinery" :loading-alert="true" :download-export-data="true" name-export="hoja de vida equipos menores" type-fyle-export="xlsx" title-confirmation-swal="Exportación exitosa" text-confirmation-swal="El reporte de la hoja de vida se ha exportado correctamente."></send-petition-message>

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
    $('#modal-form-resume-machinery-vehicles-yellows').on('keypress', ( e ) => {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    } );

    // Función para imprimir el contenido de un identificador pasado por parámetro
    function printContent(divName) {
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
</script>
@endpush
