@extends('layouts.default')

@section('title', trans('budgetAssignations'))

@section('section_img', '/assets/img/components/configuracion_activos.png')

@section('menu')
    @include('maintenance::layouts.menu')
@endsection

@section('content')

    <crud name="budgetAssignations"
        :resource="{default: 'budget-assignations', get: 'get-budget-assignations?mpc={!! $mpc ?? null !!}', show: 'budget-assignations'}"
        :init-values="{mant_provider_contract_id: '{!! $mpc ?? null !!}', providerContract:{{ $providerContract ?? null }}}"
        inline-template>
        <div>
            <!-- begin breadcrumb -->
            <ol class="breadcrumb m-b-10">
                <li class="breadcrumb-item"><a href="{!! url('/home') !!}">@lang('home')</a></li>
                <li class="breadcrumb-item active">Asignación presupuestal</li>
            </ol>
            <!-- end breadcrumb -->

            <!-- begin page-header -->
            <h1 class="page-header text-center m-b-35">Vista principal para administrar la asignación presupuestal del
                contrato número: {{ $providerContract->contract_number }}, del proveedor:
                {{ $providerContract->providers->name }}
            </h1>
            <!-- end page-header -->

            <!-- begin main buttons -->
            <div class="m-t-20 row">
                <div class="m-4">
                    <a class="btn btn-primary m-b-10 " href="{{ route('provider-contracts.index') }}"><span><i
                                class="fa fa-arrow-left mr-3"></i>Atrás</span></a>
                </div>
                <div class="m-4" v-if="dataPaginator.total == 0">
                    <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static"
                        data-target="#modal-form-budgetAssignations" data-toggle="modal">
                        <i class="fa fa-plus mr-2"></i>Asignar presupuesto
                    </button>
                </div>
                <div class="m-4">
                    <a :href="'{!! url('maintenance/historyBudgetAssignations') !!}?mpc=' +dataForm.mant_provider_contract_id"
                        class="btn btn-primary m-b-10" data-toggle="tooltip" data-placement="top"
                        title="Historial asignación presupuestal"><i class="fa fa-history mr-3"></i>Historial</a>
                </div>
            </div>
            <!-- end main buttons -->

            <!-- begin panel -->
            <div class="panel panel-default">
                <div class="panel-heading border-bottom">
                    <div class="panel-title">
                        <h5 class="text-center">
                            @{{ `@lang('total_registers') asignación presupuestal del contrato del proveedor: ${dataPaginator . total}` | capitalize }}
                        </h5>
                    </div>
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default"
                            data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning"
                            data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    </div>
                </div>
                <!-- begin #accordion search-->
                <div id="accordion" class="accordion">
                    <!-- begin card search -->
                    <div @click="toggleAdvanceSearch()"
                        class="cursor-pointer card-header bg-white pointer-cursor d-flex align-items-center"
                        data-toggle="collapse" data-target="#collapseOne">
                        <i class="fa fa-search fa-fw mr-2 f-s-12"></i>
                        <b>@{{ showSearchOptions ? 'trans.hide_search_options' : 'trans.show_search_options' | trans }}</b>
                    </div>
                    <div id="collapseOne" class="collapse border-bottom p-l-40 p-r-40" data-parent="#accordion">
                        <div class="card-body">
                            <label class="col-form-label"><b>@lang('quick_search')</b></label>
                            <!-- Campos de busqueda -->
                            <div class="row form-group">
                                <div class="col-md-4 mb-2">
                                    {!! Form::text('value_cdp', null, ['v-model' => 'searchFields.value_cdp', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Valor del CDP')])]) !!}
                                </div>
                                <div class="col-md-4 mb-2">
                                    {!! Form::text('consecutive_cdp', null, ['v-model' => 'searchFields.consecutive_cdp', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Consecutivo del CDP')])]) !!}
                                </div>
                                <div class="col-md-4 mb-2">
                                    {!! Form::text('value_contract', null, ['v-model' => 'searchFields.value_contract', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Valor del contrato')])]) !!}
                                </div>
                                <div class="col-md-4 mb-2">
                                    {!! Form::text('cdp_available', null, ['v-model' => 'searchFields.cdp_available', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('CDP disponible')])]) !!}
                                </div>
                                <div class="col-md-4 mb-2">
                                    <button @click="clearDataSearch()"
                                        class="btn btn-md btn-primary">@lang('clear_search_fields')</button>
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
                        <div class="btn-group">
                            <a href="javascript:;" data-toggle="dropdown" class="btn btn-primary"><i
                                    class="fa fa-download mr-2"></i> @lang('export_data_table')</a>
                            <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle"
                                aria-expanded="false"><b class="caret"></b></a>
                            <div class="dropdown-menu dropdown-menu-right bg-blue">

                                <a href="javascript:;" @click="exportDataTable('xlsx')"
                                    class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i>
                                    EXCEL</a>
                            </div>
                        </div>
                    </div>
                    <!-- end buttons action table -->
                    @include('maintenance::budget_assignations.table')
                </div>
                <div class="p-b-15 text-center">
                    <!-- Cantidad de elementos a mostrar -->
                    <div class="form-group row m-5 col-md-3 text-center d-sm-inline-flex">
                        {!! Form::label('show_qty', trans('show_qty') . ':', ['class' => 'col-form-label col-md-7']) !!}
                        <div class="col-md-5">
                            {!! Form::select(trans('show_qty'), [5 => 5, 10 => 10, 15 => 15, 20 => 20, 25 => 25, 30 => 30, 50 => 50, 100 => 100, 200 => 200], '5', ['class' => 'form-control', 'v-model' => 'dataPaginator.pagesItems']) !!}
                        </div>
                    </div>
                    <!-- Paginador de tabla -->
                    <div class="col-md-12">
                        <paginate v-model="dataPaginator.currentPage" :page-count="dataPaginator.numPages"
                            :click-handler="pageEvent" :prev-text="'Anterior'" :next-text="'Siguiente'"
                            :container-class="'pagination m-10'" :page-class="'page-item'" :page-link-class="'page-link'"
                            :prev-class="'page-item'" :next-class="'page-item'" :prev-link-class="'page-link'"
                            :next-link-class="'page-link'" :disabled-class="'ignore disabled'">
                        </paginate>
                    </div>
                </div>
            </div>
            <!-- end panel -->

            <!-- begin #modal-view-budgetAssignations -->
            <div class="modal fade" id="modal-view-budgetAssignations">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('info_of') @lang('budgetAssignations')</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                    class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" id="contentDetalles">
                            @include('maintenance::budget_assignations.show_fields')
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-warning" type="button" onclick="printContent('contentDetalles');"><i
                                    class="fa fa-print mr-2"></i>@lang('print')</button>
                            <button class="btn btn-white" data-dismiss="modal"><i
                                    class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end #modal-view-budgetAssignations -->

            <!-- begin #modal-form-budgetAssignations -->
            <div class="modal fade" id="modal-form-budgetAssignations">
                <div class="modal-dialog modal-lg">
                    <form @submit.prevent="save()" id="form-budgetAssignations">
                        <div class="modal-content border-0">
                            <div class="modal-header bg-blue">
                                <h4 class="modal-title text-white">@lang('form_of') @lang('budgetAssignations')</h4>
                                <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal"
                                    aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                            </div>
                            <div class="modal-body" v-if="openForm">
                                @include('maintenance::budget_assignations.fields')
                            </div>
                            <div class="modal-footer">
                                <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i
                                        class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                                <button type="submit" class="btn btn-primary"><i
                                        class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- end #modal-form-budgetAssignations -->

            <!-- En este modal se adjunta la resolucion de titulo -->
            <dynamic-modal-form modal-id="modal-new-contract" size-modal="lg" :data-form="dataForm" :is-update="true"
                title="Novedades del contrato" endpoint="save-new-contract" @saved="
                                                if($event.isUpdate) {
                                                    assignElementList(dataForm.id, $event.data);
                                                } else {
                                                    addElementToList($event.data);
                                                }">
                <template #fields="scope">
                    <div>
                        <!-- Novedades del contrato -->
                        <div class="panel p-2" data-sortable-id="ui-general-1">
                            <!-- begin panel-heading -->
                            <div class="panel-heading ui-sortable-handle">
                                <h4 class="panel-title"><strong>Novedades del contrato</strong></h4>
                            </div>
                            <!-- end panel-heading -->
                            <!-- begin panel-body -->
                            <div class="panel-body">
                                <div class="form-group row m-b-15">
                                    {!! Form::label('type_new', trans('Tipo de novedad') . ':', ['class' => 'col-form-label col-md-2']) !!}
                                    <div class="col-md-4">
                                        {!! Form::select('type_new', ['Prorroga' => 'Prorroga', 'Adición al CDP' => 'Adición al CDP', 'Adición al contrato' => 'Adición al contrato', 'Suspensiones' => 'Suspensiones'], null, ['class' => 'form-control', 'v-model' => 'scope.dataForm.type_new', 'required' => true]) !!}
                                        <small>Seleccione el tipo de novedad.</small>
                                    </div>
                                    {!! Form::label('date_new', trans('Fecha de la novedad') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
                                    <div class="col-md-4">
                                        {!! Form::date('date_new', null, ['class' => 'form-control', 'id' => 'date_new', 'placeholder' => 'Select Date', 'v-model' => 'scope.dataForm.date_new', 'required' => true]) !!}
                                        <small>Seleccione la fecha de la novedad.</small>
                                    </div>
                                </div>

                                <div class="form-group row m-b-8">
                                    <div style="margin-left: 6px" class="row"
                                        v-if="scope.dataForm.type_new == 'Adición al CDP'">
                                        {!! Form::label('cdp_modify', trans('Valor de la adición del CDP') . ':', ['class' => 'col-form-label col-md-4 required']) !!}
                                        <div class="col-md-5">
                                            <currency-input v-model="scope.dataForm.cdp_modify" required="true"
                                                :currency="{'prefix': '$ '}" locale="es" :precision="2"
                                                class="form-control" :key="keyRefresh">
                                            </currency-input>
                                            <small>Ingrese el valor de la adición del CDP.</small>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-left: 6px"
                                        v-if="scope.dataForm.type_new == 'Adición al contrato'">
                                        {!! Form::label('contract_modify', trans('Valor de la adición del contrato') . ':', ['class' => 'col-form-label col-md-4 required']) !!}
                                        <div class="col-md-5">
                                            <currency-input v-model="scope.dataForm.contract_modify" required="true"
                                                :currency="{'prefix': '$ '}" locale="es" :precision="2"
                                                class="form-control" :key="keyRefresh">
                                            </currency-input>
                                            <small>Ingrese el valor de la adición del contrato.</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    {!! Form::label('observationNews', trans('Observación') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
                                    <div class="col-md-8">
                                        {!! Form::textarea('observationNews', null, ['class' => 'form-control', 'v-model' => 'scope.dataForm.observationNews', 'required' => true]) !!}
                                        <small>Ingrese una observación.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </dynamic-modal-form>
            <!-- end #modal-form-titleRegistrations -->

            <!-- En este modal se adjunta la resolucion de titulo -->
            <dynamic-modal-form modal-id="modal-delete-budgetassignations" size-modal="lg" :data-form="dataForm"
                :is-update="true" :is-delete="true" title="Detalles eliminar registro" endpoint="delete-budget-assignation"
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
        </div>
    </crud>
@endsection

@push('css')
    {!! Html::style('assets/plugins/gritter/css/jquery.gritter.css') !!}
@endpush

@push('scripts')
    {!! Html::script('assets/plugins/gritter/js/jquery.gritter.js') !!}
    <script>
        // detecta el enter para no cerrar el modal sin enviar el formulario
        $('#modal-form-budgetAssignations').on('keypress', ':input:not(textarea):not([type=submit])', function(e) {
            if (e.keyCode === 13) {
                e.preventDefault();
            }
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
                printWindow.close();
            }, 90);
        }
    </script>
@endpush
