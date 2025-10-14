@extends('layouts.default')

@section('title', 'Gestión de aceites')

@section('section_img', '../assets/img/components/aceite.png')

@section('menu')
    @include('maintenance::layouts.menus.menu_oil')
@endsection

@section('content')

<crud
    name="oil"
    :resource="{default: 'oil', get: 'get-oil'}"
    inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/home') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">Gestión de aceites</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') la gestión de aceites de los vehículos'}}</h1>
        <!-- end page-header -->

        <!-- begin main buttons -->
        <div class="m-t-20">
            {{-- <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-oil" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('oil')
            </button> --}}
            @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo')|| Auth::user()->hasRole('mant Operador Aceites'))
            <button  @click="callFunctionComponent('oils', 'loadOil');" type="button" data-backdrop="static"  class="btn btn-primary m-b-10" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>@lang('crud.create') registro
            </button>
            @endif
            <a  href="oil-histories" class="btn btn-primary m-b-10"><i class="fas fa-history"></i>  Historial</a>
        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') aceites de los vehículos: ${dataPaginator.total}` | capitalize }}</h5>
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
                                {!! Form::text('component', null, ['v-model' => 'searchFields.component', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => 'Consecutivo']) ]) !!}
                            </div>
                            <div class="col-md-4">
                                <date-picker
                                    :value="dataForm"
                                    name-field="register_date"
                                    :input-props="{required: true}"
                                    :key="keyRefresh"
                                >
                                </date-picker>
                                <small>Filtrar por fecha de registro</small>
                            </div>
                            <div class="col-md-4">
                                <auto
                                :is-update="isUpdate"
                                name-prop="plaque"
                                name-field="mant_resume_machinery_vehicles_yellow_id"
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
                           
                        </div>
                        <div class="row form-group">
                            <div class="col-md-4">
                                {!! Form::text('show_type', null, ['v-model' => 'searchFields.show_type', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => 'Tipo de muestra']) ]) !!}
                            </div>
                            <div class="col-md-4">
                                <select name="component_name" id="component_name" v-model="searchFields.component_name" class="form-control">
                                    <option value="Motor" >Motor</option>
                                    <option value="Caja" >Caja</option>
                                    <option value="Transmisión" >Transmisión</option>
                                    <option value="Refrigerante" >Refrigerante</option>
                                    <option value="Otro" >Otro</option>
                                </select>    
                            </div>
                            <div class="col-md-4">
                                {!! Form::text('brand', null, ['v-model' => 'searchFields.brand', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Brand')]) ]) !!}
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-4">
                                {!! Form::text('job_place', null, ['v-model' => 'searchFields.job_place', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => 'Lugar de trabajo']) ]) !!}
                            </div>
                            <div class="col-md-4">
                                {!! Form::text('work_order', null, ['v-model' => 'searchFields.work_order', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => 'Orden de trabajo']) ]) !!}
                            </div>
                            <div class="col-md-4">
                                <button @click="callFunctionComponent('oils', 'cleanOil');"  class="btn btn-md btn-primary">@lang('clear_search_fields')</button>
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
                            <a href="javascript:;" @click="exportDataTable('xlsx')" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i> EXCEL</a>
                        </div>
                    </div>
                </div>
                <!-- end buttons action table -->
                @include('maintenance::oil.table')
            </div>
            <div class="p-b-15 text-center">
                <!-- Cantidad de elementos a mostrar -->
                <div class="form-group row m-5 col-md-3 text-center d-sm-inline-flex">
                    {!! Form::label('show_qty', trans('show_qty').':', ['class' => 'col-form-label col-md-7']) !!}
                    <div class="col-md-5">
                        {!! Form::select(trans('show_qty'), [5 => 5, 10 => 10, 15 => 15, 20 => 20, 25 => 25, 30 => 30, 50 => 50, 100 => 100, 200 => 200], '5', ['class' => 'form-control', 'v-model' => 'dataPaginator.pagesItems']) !!}
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

        <oils-create ref="oils" name = "oils"></oils-create>

        <!-- begin #modal-view-oil -->
        <div class="modal fade" id="modal-view-oil">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Detalle del registro del aceite</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body"  id="showFields">
                        @include('maintenance::oil.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-warning" type="button" onclick="printContent('showFields');"><i class="fa fa-print mr-2"></i>@lang('print')</button>
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-oil -->

            <!-- En este modal se adjunta la resolucion de titulo -->
            <dynamic-modal-form modal-id="modal-delete-provider-contract" size-modal="lg" :data-form="dataForm"
            :is-update="true" :is-delete="true" title="Detalles eliminar registro" endpoint="oil-delete" @saved="
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

        <!-- begin #modal-form-oil -->
        <div class="modal fade" id="modal-form-oil">
            <div class="modal-dialog modal-xl">
                <form @submit.prevent="save()" id="form-oil">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">Formulario para registrar el aceite</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('maintenance::oil.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-oil -->
    </div>
</crud>
@endsection

@push('css')
{!!Html::style('assets/plugins/gritter/css/jquery.gritter.css')!!}
@endpush

@push('scripts')
{!!Html::script('assets/plugins/gritter/js/jquery.gritter.js')!!}
<script>
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

    // detecta el enter para no cerrar el modal sin enviar el formulario
    $('#modal-form-oil').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
