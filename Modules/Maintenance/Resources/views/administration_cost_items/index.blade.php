@extends('layouts.default')

@section('title', trans('administrationCostItems'))

@section('section_img', '/assets/img/components/configuracion_activos.png')

@section('menu')
    @include('maintenance::layouts.menu')
@endsection

@section('content')

    <crud name="administrationCostItems"
        :resource="{default: 'administration-cost-items', get: 'get-administration-cost-items?mpc={!! $mpc ?? null !!}', show: 'administration-cost-items'}"
        :init-values="{mant_budget_assignation_id: '{!! $mpc ?? null !!}', budgetAssignation:{{ $budgetAssignation ?? null }}}"
        inline-template>
        <div>
            <!-- begin breadcrumb -->
            <ol class="breadcrumb m-b-10">
                <li class="breadcrumb-item"><a href="{!! url('/home') !!}">@lang('home')</a></li>
                <li class="breadcrumb-item active">@lang('administrationCostItems')</li>
            </ol>
            <!-- end breadcrumb -->

            <!-- begin page-header -->
            <h1 class="page-header text-center m-b-35">Vista principal para administrar Rubros, valor del contrato:
                ${{ $budgetAssignation->value_contract }}. CDP disponible: ${{ $budgetAssignation->cdp_available }}
            </h1>
            <!-- end page-header -->

            <!-- begin main buttons -->
            <div class="m-t-20">
                <a :href="'{!! url('maintenance/budget-assignations') !!}?mpc={!! $budgetAssignation->mant_provider_contract_id !!}'"
                    class="btn btn-primary m-b-10" data-toggle="tooltip" data-placement="top"
                    title="Atrás"><i class="fa fa-arrow-left mr-3"></i>Atrás</a>

                <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static"
                    data-target="#modal-form-administrationCostItems" data-toggle="modal">
                    <i class="fa fa-plus mr-2"></i>@lang('crud.create') rubro
                </button>

                <a :href="'{!! url('maintenance/historyCostItems') !!}?mpc=' +dataForm.mant_budget_assignation_id" class="btn btn-primary m-b-10"
                    data-toggle="tooltip" data-placement="top" title="Historial"><i
                        class="fa fa-history mr-3"></i> Historial</a>
            </div>
            <!-- end main buttons -->

            <!-- begin panel -->
            <div class="panel panel-default">
                <div class="panel-heading border-bottom">
                    <div class="panel-title">
                        <h5 class="text-center"> Total de registros de rubros del contrato del proveedor:
                            @{{ ` ${dataPaginator . total}` | capitalize }}</h5>
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
                                    {!! Form::text('code_cost', null, ['v-model' => 'searchFields.code_cost', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('código del rubro presupuestal')])]) !!}
                                </div>

                                <div class="col-md-4 mb-2">
                                    {!! Form::text('name', null, ['v-model' => 'searchFields.name', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('nombre del rubro')])]) !!}
                                </div>

                                <div class="col-md-4 mb-2">
                                    {!! Form::text('cost_center', null, ['v-model' => 'searchFields.cost_center', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('código de centro de costo')])]) !!}
                                </div>

                                <div class="col-md-4 mb-2">
                                    {!! Form::text('cost_center_name', null, ['v-model' => 'searchFields.cost_center_name', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('nombre del centro de costo')])]) !!}
                                </div>

                                <div class="col-md-4 mb-2">
                                    {!! Form::text('value_item', null, ['v-model' => 'searchFields.value_item', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('valor del rubro')])]) !!}
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
                    @include('maintenance::administration_cost_items.table')
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

            <!-- begin #modal-view-administrationCostItems -->
            <div class="modal fade" id="modal-view-administrationCostItems">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('info_of') @lang('administrationCostItems')</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                    class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" id="contentDetalles">
                            @include('maintenance::administration_cost_items.show_fields')
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
            <!-- end #modal-view-administrationCostItems -->

            <!-- begin #modal-form-administrationCostItems -->
            <div class="modal fade" id="modal-form-administrationCostItems">
                <div class="modal-dialog modal-lg">
                    <form @submit.prevent="save()" id="form-administrationCostItems">
                        <div class="modal-content border-0">
                            <div class="modal-header bg-blue">
                                <h4 class="modal-title text-white">@lang('form_of') @lang('administrationCostItems')</h4>
                                <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal"
                                    aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                            </div>
                            <div class="modal-body" v-if="openForm">
                                @include('maintenance::administration_cost_items.fields')
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
            <!-- end #modal-form-administrationCostItems -->

            <!-- En este modal se adjunta la resolucion de titulo -->
            <dynamic-modal-form modal-id="modal-delete-administrationCostItems" size-modal="lg" :data-form="dataForm"
                :is-update="true" :is-delete="true" title="Detalles eliminar registro" endpoint="delete-cost-item" @saved="
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
        $('#modal-form-administrationCostItems').on('keypress', ':input:not(textarea):not([type=submit])', function(e) {
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
