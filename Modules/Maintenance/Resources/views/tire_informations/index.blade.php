@extends('layouts.default')

@section('title', trans('tireQuantitites'))

@section('section_img', '/assets/img/components/gestion_llantas.png')

@section('menu')
    @include('maintenance::layouts.menus.menu_tires')
@endsection

@section('content')

<crud
    name="tireInformations"
    :resource="{default: 'tire-informations', get: 'get-tire-informations?machinery={!! $machinery ?? null !!}&'}"
    :init-values="{descripction: '', machinery: '{!! $machinery ?? null !!}', plaque_vehicle: '{!! $plaque_vehicle ?? null !!}', plaque:''}"
    :is-update="true"
    :crud-avanzado="true"
    inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/home') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">Llantas</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        @if (!empty($plaque_vehicle))
            <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage')'}}: {{$plaque_vehicle}}</h1>
        @else
            <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage')'}} la gestión de llantas</h1>
        @endif
        <!-- end page-header -->

        <!-- begin main buttons -->
        <div class="m-t-20">

            @if (!empty($machinery))
            <a href="resume-machinery-vehicles-yellows" class="btn btn-primary m-b-10"
            data-toggle="tooltip" data-placement="top" title="Atras"><i class="fas fa-home"></i> Atras</a>
            @endif
            @if(!Auth::user()->hasRole('mant Consulta general'))
            {{-- <a href="/maintenance/tire-quantitites" class="btn btn-primary m-b-10"><i class="fas fa-home"></i> Atrás</a> --}}
            <button @click="callFunctionComponent('FormTireInformationComponent','showModal',false)" type="button" class="btn btn-primary m-b-10" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>@lang('crud.create') llanta
            </button>
            @endif

            
            @if (!empty($machinery))
                <a :href="'tire-information-histories?machinery={!! $machinery !!}'" class="btn btn-primary m-b-10"
                data-toggle="tooltip" data-placement="top" title="Historial"><i
                    class="fa fa-history mr-3"></i> Historial</a>
            @else
                <a :href="'{!! url('maintenance/tire-information-histories') !!}'" class="btn btn-primary m-b-10"
                data-toggle="tooltip" data-placement="top" title="Historial"><i
                    class="fa fa-history mr-3"></i> Historial</a>
            @endif
            
        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') llantas: ${dataPaginator.total}` | capitalize }}</h5>
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
                            <div class="col-md-3">
                                <date-picker
                                    :value="searchFields"
                                    name-field="created_at"
                                    mode="range"
                                    :input-props="{required: true}">
                                </date-picker>
                                <small>Filtrar por fecha de creación</small>
                            </div>
                            <div class="col-md-3">
                                <date-picker
                                        :value="searchFields"
                                        name-field="date_register"
                                        mode="range"
                                        :input-props="{required: true}">
                                </date-picker>
                                <small>Fecha de ingreso de la llanta</small>
                            </div>
                            <div class="col-md-3">
                                <select-check
                                    css-class="form-control"
                                    name-field="tire_reference"
                                    reduce-label="name"
                                    reduce-key="name"
                                    name-resource="get-tire-reference"
                                    :value="searchFields"
                                    :is-required="true">
                                </select-check>
                                <small>Filtro por dimensión de la llanta</small>
                            </div>
                            <div class="col-md-3">
                                {!! Form::number('position_tire', null, ['v-model' => 'searchFields.position_tire', 'required' => true,'@keyup.enter' => 'pageEventActualizado(1)', 'class' => 'form-control']) !!}
                                <small>Filtro por posición de llanta</small>
                            </div>
                            <div class="col-md-3">
                                {!! Form::select('type_tire', ['Nueva'=>'Nueva','Usada'=>'Usada','Repuesto'=>'Repuesto','Reencauche'=>'Reencauche','Reencauche 2'=>'Reencauche 2','Reencauche 3'=>'Reencauche 3'],null, ['v-model' => 'searchFields.type_tire', 'required' => true, 'class' => 'form-control']) !!}
                                <small>Filtro por tipo de llanta</small>
                            </div>
                            <div class="col-md-3">
                                {!! Form::select('state', ['Instalada'=>'Instalada','En proceso de reencauche'=>'En proceso de reencauche','Dada de baja'=>'Dada de baja'],null, ['v-model' => 'searchFields.state', 'required' => true, 'class' => 'form-control']) !!}
                                <small>Filtro por estado</small>
                            </div>
                            <div class="col-md-3">
                                <select-check
                                    css-class="form-control"
                                    name-field="tire_brand"
                                    reduce-label="brand_name"
                                    reduce-key="id"
                                    name-resource="get-mant-tire-brand"
                                    :value="searchFields"
                                    :is-required="true">
                                </select-check>
                                <small>Filtro por marca de llanta</small>
                            </div>
                            <div class="col-md-3">
                                {!! Form::text('code_tire', null, ['v-model' => 'searchFields.code_tire', 'class' => 'form-control', '@keyup.enter' => 'pageEventActualizado(1)' ,'placeholder' => trans('crud.filter_by', ['name' => trans('codigo de la llanta')]) ]) !!}
                            </div>
                            <div class="col-md-3">
                                {!! Form::select('assignment_type', ['Activo'=>'Activo','Almacén'=>'Almacén'],null, ['v-model' => 'searchFields.assignment_type', 'required' => true, 'class' => 'form-control']) !!}
                                <small>Filtro por tipo de asignación de la llanta</small>
                            </div>
                            <div class="col-md-3">
                                {!! Form::text('plaque', null, ['v-model' => 'searchFields.plaque', 'class' => 'form-control','@keyup.enter' => 'pageEventActualizado(1)', 'placeholder' => trans('crud.filter_by', ['name' => trans('placa')]) ]) !!}
                            </div>

                        </div>
                        <div >
                                <button @click="pageEventActualizado(1)" class="btn btn-md btn-primary"><i class="fa fa-search mr-2"></i>Buscar</button>
                                <button @click="clearDataSearchAvanzado()" class="btn btn-md btn-primary">@lang('clear_search_fields')</button>
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
                            <a href="javascript:;" @click="callFunctionComponent('FormTireInformationComponent', 'exportDataTableAvanzado', 'Activo')" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i> Reporte por llantas activas</a>
                            @if (empty($machinery))
                            <a href="javascript:;" @click="callFunctionComponent('FormTireInformationComponent', 'exportDataTableAvanzado', 'Almacén')" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i> Reporte por llantas en almacén</a>
                            @endif


                        </div>
                    </div>
                </div>
                <!-- end buttons action table -->
                @include('maintenance::tire_informations.table')
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

        <!-- begin #modal-view-tireInformations -->
        <div class="modal fade" id="modal-view-tireInformations">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Detalle general de la llanta</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body" id="tireDetalles">
                        @include('maintenance::tire_informations.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-warning" type="button" onclick="printContent('tireDetalles');"><i class="fa fa-print mr-2"></i>@lang('print')</button>
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-tireInformations -->

        <!-- begin #modal-form-tireInformations -->
        <!-- <div class="modal fade" id="modal-form-tireInformations">
            <div class="modal-dialog modal-xl">
                <form @submit.prevent="save()" id="form-tireInformations">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">Formulario de información general de la llanta</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('maintenance::tire_informations.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div> -->
        <!-- end #modal-form-tireInformations -->

        <!-- begin #modal-view-tireInformations -->
        <div class="modal fade" id="modal-form-historie">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Historial de llantas</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body" >
                        @include('maintenance::tire_informations.history')
                    </div>
                    <div class="modal-footer">
                        
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-tireInformations -->


            <!-- En este modal se adjunta la resolucion de titulo -->
            <dynamic-modal-form modal-id="modal-delete-provider-contract" size-modal="lg" :data-form="dataForm"
            :is-update="true" :is-delete="true" title="Detalles eliminar registro" endpoint="information-delete" @saved="
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

            <form-tire-information ref="FormTireInformationComponent" :form-data="dataForm" machinery="{!! $machinery ?? null !!}"></form-tire-information>
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
    $('#modal-form-tireInformations').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });

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
        // Se esperan 10 milésimas de segundos para imprimir el contenido de la pestaña nueva
        setTimeout(() => {
            printWindow.print();
        }, 10);
    }  
</script>
@endpush
