@extends('layouts.default')

@section('title', trans('equipmentMinorFuelConsumptions'))

@section('section_img', '../assets/img/components/combustible_de_vehiculos.png')

@section('menu')
    @include('maintenance::layouts.menus.menu_vehicle_fuel')
@endsection

@section('content')

<crud
    name="equipmentMinorFuelConsumptions"
    :resource="{default: 'equipment-minor-fuel-consumptions', get: 'get-equipment-minor-fuel-consumptions?equipment={!! $equipment ?? null !!}&'}"
    :init-values="{mant_minor_equipment_fuel_id: '{!! $equipment ?? null !!}', sentinel:'{{ $sentinel ?? null }}'}"
    :init-values-search="{mant_minor_equipment_fuel_id: '{!! $equipment ?? null !!}'}"
    :crud-avanzado="true"
    inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/home') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('equipmentMinorFuelConsumptions')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('equipmentMinorFuelConsumptions')'}}</h1>
        <!-- end page-header -->

        <!-- begin main buttons -->
        <div class="m-t-20 row">
            <div class="p-5">
            <a href="{{ route('minor-equipment-fuel.index') }}" class="btn btn-primary m-b-10">
                <i class="fa fa-arrow-left mr-3"></i>Atrás
            </a>
            </div>
            <div class="p-5" v-if="dataForm.sentinel==false">
                @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo') || Auth::user()->hasRole('mant Operador Combustible equipos menores'))
                <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-equipmentMinorFuelConsumptions" data-toggle="modal">
                    <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('equipmentMinorFuelConsumptions')
                </button>
                @endif
            </div>
            <div class="p-5">
                <a  :href="'fuel-consumption-history-minors?equipment='+dataForm.mant_minor_equipment_fuel_id" class="btn btn-primary m-b-10"><i class="fas fa-history"></i>  Historial</a>
            </div>
            {{-- <div class="p-5">

                <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-migration" data-toggle="modal">
                    <i class="fa fa-plus mr-2"></i>migrar datos
                </button>
            </div>             --}}

        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('equipmentMinorFuelConsumptions'): ${dataPaginator.total}` | capitalize }}</h5>
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
                            <div class="col-md-4 mb-4">
                                {!! Form::date('name', null, ['v-model' => 'searchFields.created_at', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Fecha de registro')]) ]) !!}
                                <small>Fecha de registro</small>
                            </div>
                            <div class="col-md-4 mb-4">
                                {!! Form::date('name', null, ['v-model' => 'searchFields.supply_date', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Fecha de suministro')]) ]) !!}
                                <small>Fecha del suministro</small>
                            </div>
                            <div class="col-md-4 mb-4">
                                {!! Form::date('name', null, ['v-model' => 'searchFields.created_at', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Fecha de registro')]) ]) !!}
                                <small>Fecha de modificación</small>
                            </div>
                            <div class="col-md-4 mb-4">
                                <select-check
                                css-class="form-control"
                                name-field="dependencias_id"
                                reduce-label="nombre"
                                reduce-key="id"
                                name-resource="get-dependency"
                                :value="searchFields"
                                :is-required="true">
                                </select-check>
                                </select-check>
                                <small>Filtro por proceso</small>
                            </div>
                            <div class="col-md-4 mb-4">
                                <select-check
                                css-class="form-control"
                                name-field="mant_resume_equipment_machinery_id"
                                reduce-label="nombre_inventario"
                                reduce-key="id"
                                name-resource="get-machinary-all"
                                :value="searchFields"
                                :is-required="true">
                                </select-check>
                                </select-check>
                                <small>Filtro por descripción del equipo</small>
                            </div>
                            <div class="col-md-4 mb-2">
                                {!! Form::text('gallons_supplied', null, ['v-model' => 'searchFields.gallons_supplied', 'class' => 'form-control', '@keyup.enter' => 'pageEventActualizado(1)', 'placeholder' => trans('crud.filter_by', ['name' => trans('Galones suministrados')]) ]) !!}
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
                @include('maintenance::equipment_minor_fuel_consumptions.table')
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

        <!-- begin #modal-view-equipmentMinorFuelConsumptions -->
        <div class="modal fade" id="modal-view-equipmentMinorFuelConsumptions">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') @lang('equipmentMinorFuelConsumptions')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        @include('maintenance::equipment_minor_fuel_consumptions.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-equipmentMinorFuelConsumptions -->

        <!-- En este modal se adjunta la resolucion de titulo -->
        <dynamic-modal-form modal-id="modal-delete-provider-contract" size-modal="lg" :data-form="dataForm"
        :is-update="true" :is-delete="true" title="Detalles eliminar registro" endpoint="delete-minor-equipment-fuel-consumption" @saved="
                            if($event.isUpdate) {                                    
                                    _getDataListAvanzado();                             
                            }">
        <template #fields="scope">
            <div>
                <!-- Novedades del contrato -->
                <div class="panel p-2" data-sortable-id="ui-general-1">
                    <div class="row">
                        <div class="col">
                            {!! Form::label('observationDelete', trans('Descripción') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                        </div>
                        <div class="col-md-9">
                            {!! Form::textarea('observationDelete', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observationDelete }", 'v-model' => 'dataForm.observationDelete', 'required' => true]) !!}
                            <small>Ingresar una observación de por qué eliminara el registro.</small>
                            <div class="invalid-feedback" v-if="dataErrors.observationDelete">
                                <p class="m-b-0" v-for="error in dataErrors.observationDelete">
                                @{{ error }}</p>
                            </div>
                        </div>
                    </div>

                </div>
                </div>
            </template>
        </dynamic-modal-form>
        <!-- end #modal-form-titleRegistrations -->


        <dynamic-modal-form
        modal-id="modal-form-migration"
        size-modal="lg"
        :data-form="dataForm"
        title="Migrar combustibles"
        endpoint="migration-modal-consumptions"
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
                        <div class="form-group row m-b-15 mt-4">
                            {!! Form::label('file_import', trans('Adjuntar documento').':', ['class' => 'col-form-label col-md-3 required']) !!}
                            {!! Form::file('file_import', ['accept' => '.xls,.xlsx', '@change' => 'inputFile($event, "file_import")', 'required' => true]) !!}
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </dynamic-modal-form>



        <!-- En este modal se adjunta la resolucion de titulo -->
        <dynamic-modal-form modal-id="modal-delete-budgetassignations" size-modal="lg" :data-form="dataForm"
        :is-update="true" :is-delete="true" title="Detalles eliminar registro" endpoint="get-vehicle-fuels-delete"
        @saved="
                                    if($event.isUpdate) {
                                        if($event.isDelete){                                        
                                            _getDataList();
                                    }else{
                                            assignElementList(dataForm.id, $event.data);
                                        }                                    
                                    } else {
                                        addElementToList($event.data);
                                    }">
        <template #fields="scope">
            <div>
                <!-- Novedades del contrato -->
                <div class="panel p-2" data-sortable-id="ui-general-1">
                    <div class="row">
                        <div class="col">
                        {!! Form::label('observationDelete', trans('Descripción') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                        </div>
                        <div class="col-md-9">
                            {!! Form::textarea('observationDelete', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observationDelete }", 'v-model' => 'dataForm.observationDelete', 'required' => true]) !!}
                            <small>Ingresar una observación de por qué eliminara el registro.</small>
                            <div class="invalid-feedback" v-if="dataErrors.observationDelete">
                                <p class="m-b-0" v-for="error in dataErrors.observationDelete">
                                    @{{ error }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </dynamic-modal-form>
    <!-- end #modal-form-titleRegistrations -->

        <!-- begin #modal-form-equipmentMinorFuelConsumptions -->
        <div class="modal fade" id="modal-form-equipmentMinorFuelConsumptions">
            <div class="modal-dialog modal-lg">
                <form @submit.prevent="save()" id="form-equipmentMinorFuelConsumptions">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of') @lang('equipmentMinorFuelConsumptions')</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('maintenance::equipment_minor_fuel_consumptions.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-equipmentMinorFuelConsumptions -->
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
    $('#modal-form-equipmentMinorFuelConsumptions').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
