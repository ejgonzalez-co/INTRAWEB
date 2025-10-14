@extends('layouts.default')

@section('title', trans('Hoja de vida de los equipos'))

@section('section_img', '/assets/img/components/ordenador-personal.png')

@if(Auth::user() != null)
    @if(Auth::user()->hasRole('Administrador TIC'))
        @section('menu')
            @include('help_table::layouts.menus.menu_equipment_resumes')
        @endsection
    @endif
@else
    @section('menu')
        @include('help_table::layouts.menus.menu_provider')
    @endsection
@endif

@section('content')

<crud
    :crud-avanzado="true"
    name="equipmentResumes"
    :resource="{default: 'equipment-resumes', get: 'get-equipment-resumes'}"
    :init-values="{configuration_other_equipments: [], equipment_purchase_details: [], contract:[], provider_fullname: '{!! $provider_fullname ?? null !!}'}"
    inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/home') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('Hoja de vida de los equipos')</li>
        </ol>
        <!-- end breadcrumb -->
        
        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">Vista principal para administrar fichas técnicas de equipos</h1>
        <!-- end page-header -->
        
        <div id="widget" class="row">
            <widget-counter
            icon="fas fa-desktop"
            bg-color="#8bc44a"
            :qty="dataExtra.status_asset_type?.Computador ?? 0" 
            title="Computadores"
            title-link-see-more="Filtrar"
            status="Computador"
            :value="searchFields"
            name-field="asset_type"
            {{-- link-see-more="javascript:checkData('5')" --}}
            ></widget-counter>

            <widget-counter
            icon="fas fa-ff9915"
            bg-color="#ff9915"
            :qty="dataExtra.status_asset_type?.Portátil ?? 0" 
            title="Portátiles"
            title-link-see-more="Filtrar"
            status="Portátil"
            :value="searchFields"
            name-field="asset_type"
            {{-- link-see-more="javascript:checkData('5')" --}}
            ></widget-counter>

            <widget-counter
            icon="fas fa-server"
            bg-color="#f54335"
            :qty="dataExtra.status_asset_type?.Servidor ?? 0" 
            title="Servidores"
            title-link-see-more="Filtrar"
            status="Servidor"
            :value="searchFields"
            name-field="asset_type"
            {{-- link-see-more="javascript:checkData('5')" --}}
            ></widget-counter>

            {{-- <widget-counter-funtional
            icon="fas fa-desktop"
            bg-color="#2496f4"
            :qty="dataList.filter((data) =>  data.asset_type == 'Servidor' || data.asset_type == 'Computador' || data.asset_type == 'Portátil' ).length"
            title="Total equipos"
            title-link-see-more="Filtrar"
            :status="'Servidor'"
            :value="searchFields"
            name-field="asset_type"
            >
            </widget-counter-funtional> --}}

            <widget-counter
            icon="fas fa-desktop"
            bg-color="#2496f4"
            :qty="dataExtra.total_equipos ?? 0" 
            title="Total equipos"
            title-link-see-more="Filtrar"
            status="all"
            :value="searchFields"
            name-field="status_equipment"
            ></widget-counter>


            {{-- Prueba --}}
            <widget-counter
            icon="fas fa-desktop"
            bg-color="#ffeb37"
            :qty="dataExtra.status_equipment?.activo ?? 0" 
            title="Equipos activos"
            title-link-see-more="Filtrar"
            status="Activo"
            :value="searchFields"
            name-field="status_equipment"
            {{-- link-see-more="javascript:checkData('5')" --}}
            ></widget-counter>

            <widget-counter
            icon="fas fa-desktop"
            bg-color="#00b9d2"
            :qty="dataExtra.status_equipment?.desactivado ?? 0" 
            title="Equipos No Activos"
            title-link-see-more="Filtrar"
            status="Desactivado"
            :value="searchFields"
            name-field="status_equipment"
            {{-- link-see-more="javascript:checkData('5')" --}}
            ></widget-counter>

            <widget-counter
            icon="fas fa-desktop"
            bg-color="#cdcdcd"
            :qty="dataExtra.status_equipment?.dado_de_baja ?? 0" 
            title="Equipos dados de baja"
            title-link-see-more="Filtrar"
            status="Dado de Baja"
            :value="searchFields"
            name-field="status_equipment"
            {{-- link-see-more="javascript:checkData('5')" --}}
            ></widget-counter>

            <widget-counter
            icon="fas fa-desktop"
            bg-color="#ff9614"
            :qty="dataExtra.status_equipment?.en_reparacion ?? 0" 
            title="Equipos en reparación"
            title-link-see-more="Filtrar"
            status="En reparación"
            :value="searchFields"
            name-field="status_equipment"
            {{-- link-see-more="javascript:checkData('5')" --}}
            ></widget-counter>

     
        </div>



        <!-- begin main buttons -->
        <div class="m-t-20">
            <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-equipmentResumes" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>Nuevo equipo
            </button>
            <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-import-data" data-toggle="modal">
                <i class="fas fa-file-upload mr-2"></i>Importar datos
            </button>
        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('equipos'): ${dataPaginator.total}` | capitalize }}</h5>
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
                                {!! Form::text('officer', null, ['v-model' => 'searchFields.officer', 'class' => 'form-control','@keyup.enter' => 'pageEventActualizado(1)']) !!}
                                <small>Filtrar por el nombre del funcionario</small>
                            </div>
                            <div class="col-md-4">
                            <select-check css-class="form-control" name-field="dependence" reduce-label="nombre" name-resource="get-dependencies" :value="searchFields" :enable-search="true"></select-check>
                                <small>Filtrar por la dependencia</small>
                            </div>
                            <div class="col-md-4">
                                 <select-check css-class="form-control" name-field="ht_sedes_id" reduce-label="name"
                                    reduce-key="id" name-resource="get-sedes-tics" :value="searchFields" :enable-search="true">
                                </select-check>
                                <small>Filtrar por la sede</small>
                            </div>
                            <div class="col-md-4">
                                {!! Form::text('service_manager', null, ['v-model' => 'searchFields.service_manager', 'class' => 'form-control','@keyup.enter' => 'pageEventActualizado(1)']) !!}
                                <small>Filtrar por el nombre del responsable del inventario</small>
                            </div>
                            {{-- <div class="col-md-4">
                                <select class="form-control" v-model="searchFields.maintenance_type">
                                    <option value="Preventivo">Preventivo</option>
                                    <option value="Predictivo">Predictivo</option>
                                    <option value="Evolutivo">Evolutivo</option>
                                    <option value="Correctivo">Correctivo</option>
                                </select>
                                <small>Filtrar por el tipo de mantenimiento</small>
                            </div> --}}
                            <div class="col-md-4">
                                <date-picker :value="searchFields" name-field="created_at" :input-props="{ required: true }" mode="range">
                                </date-picker>
                                <small>Filtrar por el rango de fecha de creación</small>
                            </div>
                            <div class="col-md-4">
                                {!! Form::text('provider', null, ['v-model' => 'searchFields.provider', 'class' => 'form-control','@keyup.enter' => 'pageEventActualizado(1)']) !!}
                                <small>Filtrar por el nombre del proveedor</small>
                            </div>
                            <div class="col-md-4">
                                <button @click="pageEventActualizado(1)" class="btn btn-md btn-primary"><i class="fa fa-search mr-2"></i>Buscar</button>
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
                <div class="float-xl-right m-b-15">
                    <!-- Acciones para exportar datos de tabla-->
                    <div class="btn-group">
                        <a href="javascript:;" data-toggle="dropdown" class="btn btn-primary"><i class="fa fa-download mr-2"></i> @lang('export_data_table')</a>
                        <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle" aria-expanded="false"><b class="caret"></b></a>
                        <div class="dropdown-menu dropdown-menu-right bg-blue">
                            <a href="javascript:;" @click="exportDataTableAvanzado('xlsx')" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i> EXCEL</a>
                        </div>
                    </div>
                </div>
                <!-- end buttons action table -->
                @include('help_table::equipment_resumes.table')
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

        <!-- begin #modal-view-equipmentResumes -->
        <div class="modal fade" id="modal-view-equipmentResumes">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Detalles de la ficha técnica equipos</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        @include('help_table::equipment_resumes.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-equipmentResumes -->

        <!-- begin #modal-form-equipmentResumes -->
        <div class="modal fade" id="modal-form-equipmentResumes">
            <div class="modal-dialog modal-xl">
                <form @submit.prevent="save()" id="form-equipmentResumes">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">Formulario ficha técnica equipos</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('help_table::equipment_resumes.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-equipmentResumes -->

        <!-- begin #modal-view-equipment-resume-history -->
        <div class="modal fade" id="modal-view-equipment-resume-history">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('Seguimiento y control')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        @include('help_table::equipment_resumes.history')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-equipment-resume-history -->

        <!-- modal para la migracion de la muestra -->
        <dynamic-modal-form
        modal-id="modal-import-data"
        size-modal="lg"
        :data-form="dataForm"
        title="Importar datos"
        endpoint="import-data-equipment-resumes"
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
                            <i class="fas fa-download mr-1"></i>
                            <a href="/assets/documents/Formatos/Formato hoja de vida de los equipos.xlsx" target="_blank">Descargar formato para la importación.</a>
                            <div class="form-group row m-b-15 mt-4">
                                {!! Form::label('import_file', trans('Adjuntar documento').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                {!! Form::file('import_file', ['accept' => 'Application/all*', '@change' => 'inputFile($event, "import_file")', 'required' => true]) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </dynamic-modal-form>
        <!-- modal para la migracion de la muestra -->
        
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
    $('#modal-form-equipmentResumes').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
