@extends('layouts.default')

@section('title', 'Combustibles')

@section('section_img', '../assets/img/components/gestion_combustibles.png')

@section('menu')
    @include('maintenance::layouts.menus.menu_vehicle_fuel')
@endsection

@section('content')

<crud
    name="vehicle-fuels"
    :resource="{default: 'vehicle-fuels', get: 'get-all-vehicles'}"
    inline-template
    :crud-avanzado="true"
    ref="crud">
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/home') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">Combustibles</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') el @lang('Vehicle Fuels')'}}</h1>
        <!-- end page-header -->

        <!-- begin main buttons -->
        <div class="m-t-20">
            <a type="button" href="vehicle-fuels" class="btn btn-primary m-b-10"><i class="fa fa-arrow-left"></i> Regresar a la vista principal</a>
            <a href="fuel-histories" class="btn btn-primary m-b-10"><i class="fas fa-history"></i>  Historial</a>
        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') combustible de vehículos: ${dataPaginator.total}` | capitalize }}</h5>
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
                            <input type="hidden" v-model="searchFields.typeQuery='mant_resume_machinery_vehicles_yellow_id,performance_by_gallon'">
                            <input type="hidden" v-model="searchFields.todos='todos'">
                            <div class="col-md-4">
                                <auto
                                :is-update="isUpdate"
                                name-prop="plaque"
                                name-field="mant_resume_machinery_vehicles_yellow_id_igual_"
                                :name-labels-display="['plaque']"
                                :value='searchFields'
                                name-resource='get-vehicle'
                                css-class="form-control"
                                reduce-key="id"
                                asignar-al-data=""
                                :key="keyRefresh"
                                :min-text-input="2"
                                :is-required="true"
                                ref = "placa"
                                >
                                </auto>
                                <small>Filtrar por placa</small>
                            </div>

                            <div class="col-md-4 mb-2">
                                {{-- {!! Form::date('created_at', null, ['v-model' => 'searchFields.created_at', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Created_at')]) ]) !!} --}}
                                <date-picker
                                    :value="searchFields"
                                    name-field="date_register_name"
                                    mode="range"
                                    ></date-picker>
                            <small>Filtrar por fecha de creación</small>

                            </div>

                            <div class="col-md-4">
                                {!! Form::date('updated_at', null, ['v-model' => 'searchFields.updated_at', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('')]) ]) !!}
                                <small>Filtrar por fecha de modificación</small>
                            </div>
                            
                        </div>

                        <div class="row form-group">

                            <div class="col-md-4">
                                {!! Form::text('name', null, ['v-model' => 'searchFields.fuel_quantity','@keyup.enter' => 'pageEventActualizado(1)', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Quantity Fuel')]) ]) !!}
                            </div>

                            <div class="col-md-4">
                                {!! Form::text('name', null, ['v-model' => 'searchFields.performance_by_gallon','@keyup.enter' => 'pageEventActualizado(1)', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => 'Rendimiento por galón']) ]) !!}
                            </div>

                            <div class="col-md-4">
                                {!! Form::text('name', null, ['v-model' => 'searchFields.variation_route_hour','@keyup.enter' => 'pageEventActualizado(1)', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => 'Variación en KM por tanqueo']) ]) !!}
                            </div>

                        </div>

                        <div class="row form-group">

                            <div class="col-md-4">
                                {!! Form::text('name', null, ['v-model' => 'searchFields.gallon_price','@keyup.enter' => 'pageEventActualizado(1)', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Gallon Price')]) ]) !!}
                            </div>

                            <div class="col-md-4">
                                {!! Form::text('name', null, ['v-model' => 'searchFields.total_price','@keyup.enter' => 'pageEventActualizado(1)', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Total Price')]) ]) !!}
                            </div>

                            <div class="col-md-4">
                                {!! Form::text('name', null, ['v-model' => 'searchFields.variation_tanking_hour','@keyup.enter' => 'pageEventActualizado(1)', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => 'Variación de HR en los tanqueos']) ]) !!}
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-4">
                                {!! Form::text('name', null, ['v-model' => 'searchFields.current_mileage','@keyup.enter' => 'pageEventActualizado(1)', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => 'Kilometraje']) ]) !!}
                            </div>

                            <div class="col-md-4">
                                {!! Form::text('name', null, ['v-model' => 'searchFields.current_hourmeter','@keyup.enter' => 'pageEventActualizado(1)', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Horómetro')]) ]) !!}
                            </div>

                            {{-- <div class="col-md-4">
                                {!! Form::date('created_migration', null, ['v-model' => 'searchFields.created_migration', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Created_at')]) ]) !!} 
                                <date-picker
                                    :value="searchFields"
                                    name-field="date_register_name"
                                    mode="range"
                                    >
                            </div>
                            <small>Filtrar por fecha de creación del registro migrado</small> --}}

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
                @include('maintenance::vehicle_fuels.table')
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

        <!-- begin #modal-view-vehicleFuels -->
        <div class="modal fade" id="modal-view-vehicleFuels">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Información del @lang('Vehicle Fuel')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body" id="showFields">
                        @include('maintenance::vehicle_fuels.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-warning" type="button" onclick="printContent('showFields');"><i class="fa fa-print mr-2"></i>@lang('print')</button>
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>  
        </div>
        <!-- end #modal-view-vehicleFuels -->

        <!-- En este modal se adjunta la resolucion de titulo -->
        <dynamic-modal-form modal-id="modal-add-documents" size-modal="lg" :data-form="dataForm"
        :is-update="true" title="Adjuntar archivos al registro del tanqueo de combustibles" endpoint="add-documents-by-vehicle-fuels"
        @saved="
        if($event.isUpdate) {
            assignElementList(dataForm.id, $event.data);
        } else {
            addElementToList($event.data);
        }">
            <template #fields="scope">
                <div>
                   <!-- Archive Name Field --> 
                    <div class="form-group row m-b-15">
                        {!! Form::label('observationDelete', trans('Nombre') . ':', ['class' => 'col-form-label col-md-3 required']) !!}

                        <div class="col-md-5">
                            {!! Form::text('name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.document_name }", 'v-model' => 'dataForm.document_name', 'required' => true]) !!}
                            <small>Ingresar el nombre.</small>
                            <div class="invalid-feedback" v-if="dataErrors.document_name">
                                <p class="m-b-0" v-for="error in dataErrors.document_name">
                                    @{{ error }}</p>
                            </div>
                        </div>
                    </div>
                   <!-- Archive Description Field -->
                    <div class="form-group row m-b-15">
                        {!! Form::label('observationDelete', trans('Descripción') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-5">
                            {!! Form::textarea('observationDelete', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.document_description }", 'v-model' => 'dataForm.document_description', 'required' => true]) !!}
                            <small>Ingresar una descripción.</small>
                            <div class="invalid-feedback" v-if="dataErrors.document_description">
                                <p class="m-b-0" v-for="error in dataErrors.document_description">
                                    @{{ error }}</p>
                            </div>
                        </div>
                    </div>

                   <!-- Archive Invoice Field -->
                    <div class="form-group row m-b-15">
                        {!! Form::label('observationDelete', trans('Adjunte aquí la factura') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-5">
                            {!! Form::file('attached_invoce', ['accept' => 'pdf/*', '@change' => 'inputFile($event, "attached_invoce")', 'required' => true]) !!}
                            <div class="invalid-feedback" v-if="dataErrors.attached_invoce">
                                <p class="m-b-0" v-for="error in dataErrors.attached_invoce">
                                    @{{ error }}</p>
                            </div>
                        </div>
                    </div>

                   <!-- Archive Photo Field -->
                    <div class="form-group row m-b-15">
                        {!! Form::label('observationDelete', trans('Adjunte aquí la foto del tacómetro u horómetro') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-5">
                            {!! Form::file('url_image', ['accept' => 'image/*', '@change' => 'inputFile($event, "photo_tachometer_hourmeter")', 'required' => true]) !!}
                            <div class="invalid-feedback" v-if="dataErrors.photo_tachometer_hourmeter">
                                <p class="m-b-0" v-for="error in dataErrors.photo_tachometer_hourmeter">
                                    @{{ error }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </dynamic-modal-form>
        <!-- end #modal-form-titleRegistrations -->


        <vehicle-fuels-create ref="loadVehicleFuel" name = "vehicleFuels"></vehicle-fuels-create>

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
        
        <!-- begin #modal-form-vehicleFuels -->
        <div class="modal fade" id="modal-form-vehicleFuels">
            <div class="modal-dialog modal-lg">
                <form @submit.prevent="save()" id="form-vehicleFuels">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of') registro de combustibles para vehículos</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('maintenance::vehicle_fuels.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-vehicleFuels -->
    </div>
</crud>
@endsection

@push('css')
{!!Html::style('assets/plugins/gritter/css/jquery.gritter.css')!!}
<style>
    @media screen and (max-width: 1000px){
        #buttonTable {
            width: 4rem;
            height: 4rem;
        }
    }
</style>
@endpush

@push('scripts')
{!!Html::script('assets/plugins/gritter/js/jquery.gritter.js')!!}
<script>
   
    // detecta el enter para no cerrar el modal sin enviar el formulario
    $("#modal-form-vehicle-fuels").on("keypress", (e) => {
        if (e.keyCode === 13) {
          e.preventDefault();
        }
    });

    function printContent(divName) {
        
        // Se obtiene el elemento del id recibido por parámetro
        var printContent = document.getElementById(divName);
        // Se guarda en una variable la nueva pestaña
        var printWindow = window.open("");
        // Se obtiene el encabezado de la página actual para no perder estilos
        var headContent = document.getElementsByTagName('head')[0].innerHTML;
        // Se escribe todo el contenido del encabezado de la página actual en la pestaña nueva que se abrirá
        printWindow.document.write(headContent);
        // Se escribe todo el contenido del id recibido por parámetro en la pestaña nueva que se abrirá
        printWindow.document.write(printContent.innerHTML);
        printWindow.document.close();
        // Se enfoca en la pestaña nueva
        printWindow.focus();
        // Se esperan 10 milésimas de segundos para imprimir el contenido de la pestaña nueva
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 30); 
   
    }
</script>
@endpush


