@extends('layouts.default')

@section('title', trans('Presupuesto'))

@section('section_img', '/assets/img/components/presupuesto.png')

@section('menu')
    @include('maintenance::layouts.menus.menu_budget')
@endsection

@section('content')

<crud name="provider-contracts" 
:init-values="{maintenance_contract_news:[]}" 
:resource="{default: 'budget-provider', get: 'get-provider-dependencia', show: 'provider-contracts'}" inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/home') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">Presupuesto</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">Vista principal para la consulta de los contratos de los proveedores</h1>
        <!-- end page-header -->
        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') los contratos de los proveedores: ${dataPaginator.total}` | capitalize }}</h5>
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
                         
                            <div class="col-md-4 mb-4">
                                <select-check css-class="form-control" name-field="mant_providers_id" reduce-label="name" name-resource="/maintenance/get-providers" :value="searchFields" :is-required="true"></select-check>
                                <small>Filtrar por proveedor.</small>
                            </div>
                            <div class="col-md-4 mb-4">
                                {!! Form::text('object', null, ['v-model' => 'searchFields.object', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('objeto')]) ]) !!}
                            </div>
                            <div class="col-md-4 mb-4">
                                {!! Form::text('type_contract', null, ['v-model' => 'searchFields.type_contract', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('tipo de contrato')]) ]) !!}
                            </div>
                            <div class="col-md-4 mb-4">
                                {!! Form::select('condition',['Activo'=>'Activo','Inactivo'=>'Inactivo'] ,null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.condition }", 'v-model' => 'searchFields.condition', 'required' => true]) !!}
                                <small>Seleccione su estado.</small>
                            </div>
                            <div class="col-md-4 mb-4">
                                {!! Form::text('contract_number', null, ['v-model' => 'searchFields.contract_number', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('número de contrato')]) ]) !!}
                            </div>
                            <div class="col-md-4 mb-4">
                                {!! Form::date('start_date', null, ['v-model' => 'searchFields.start_date', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Fecha de acta de inicio')]) ]) !!}
                                <small>Fecha de acta de inicio</small>
                            </div>
                            <div class="col-md-4 mb-4">
                                {!! Form::text('CDP_approved', null, ['v-model' => 'searchFields.CDP_approved', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('CDP aprobado')]) ]) !!}
                            </div>
                            <div class="col-md-4 mb-4">
                                {!! Form::text('CDP_available', null, ['v-model' => 'searchFields.CDP_available', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('CDP disponible')]) ]) !!}
                            </div>
                            <div class="col-md-4 mb-4">
                                {!! Form::text('contract_value', null, ['v-model' => 'searchFields.contract_value', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('valor de contrato')]) ]) !!}
                            </div>
                            <div class="col-md-4 mb-4">
                                {!! Form::date('closing_date', null, ['v-model' => 'searchFields.closing_date', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('fecha de cierre')]) ]) !!}
                                <small>Fecha de cierre</small>
                            </div>
                            <div class="col-md-4 mb-4">
                                {!! Form::text('last_minute', null, ['v-model' => 'searchFields.last_minute', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('última acta')]) ]) !!}
                            </div>
                            <div class="col-md-4 mb-4">
                                {!! Form::text('executed_value', null, ['v-model' => 'searchFields.executed_value', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('valor ejecutado')]) ]) !!}
                            </div>
                            <div class="col-md-4 mb-4">
                                {!! Form::text('balance_value', null, ['v-model' => 'searchFields.balance_value', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('valor saldo')]) ]) !!}
                            </div>
                            <div class="col-md-4 mb-4">
                                {!! Form::text('execution_percentage', null, ['v-model' => 'searchFields.execution_percentage', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('porcentaje de ejecución')]) ]) !!}
                            </div>
                            <div class="col-md-4 mb-4">
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
                            {{-- <a href="javascript:;" @click="exportDataTable('pdf')" class="dropdown-item text-white no-hover"><i class="fa fa-file-pdf mr-2"></i> PDF</a> --}}
                            <a href="javascript:;" @click="exportDataTable('xlsx')" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i> EXCEL</a>
                        </div>
                    </div>
                </div>
                <!-- end buttons action table -->
                @include('maintenance::budget.table')
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
        
                <!-- begin #modal-view-provider-contracts -->
                <div class="modal fade" id="modal-view-provider-contracts">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content border-0">
                            <div class="modal-header bg-blue">
                                <h4 class="modal-title text-white">@lang('info_of') documentos del contrato del proveedor</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                            </div>
                            <div class="modal-body" id="contentDetalles">
                                <div class="row">
                                    @include('maintenance::budget.show_fields')
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-warning" type="button" onclick="printContent('contentDetalles');"><i class="fa fa-print mr-2"></i>@lang('print')</button>
                                <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end #modal-view-provider-contracts -->

        <!-- En este modal se adjunta la resolucion de titulo -->
        <dynamic-modal-form modal-id="modal-delete-provider-contract" size-modal="lg" :data-form="dataForm"
        :is-update="true" :is-delete="true" title="Detalles eliminar registro" endpoint="delete-provider-contracts" @saved="
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

    <!-- En este modal se adjunta la resolucion de titulo -->
    <dynamic-modal-form modal-id="modal-new-condition" size-modal="lg" :data-form="dataForm"
    :is-update="true" title="Detalles cambio de estado" endpoint="new-condition" @saved="
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
                        <small>Ingresar una observación de por qué cambiará el estado.</small>
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
    $('#modal-form-provider-contracts').on('keypress', ( e ) => {
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
        // Se esperan 10 milésimas de segundos para imprimir el contenido de la pestaña nueva
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 90);
    }
</script>
@endpush
