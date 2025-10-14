@extends('layouts.default')

@section('title', trans('Administración de evaluaciones'))

@section('section_img', '')

@section('menu')
    @include('improvementplans::layouts.menu_component_evaluations')
@endsection

@section('content')

<crud
    name="approvedImprovementPlans"
    :resource="{default: 'approved-improvement-plans', get: 'get-approved-improvement-plans', show: 'approved-improvement-plans'}"
    :init-values="{activities_plans_processing:[], can_manage: '{!! $can_manage ?? false !!}' , can_generate_reports: '{!! $can_generate_reports ?? false !!}'}"
    :crud-avanzado="true"
    inline-template>
    <div>
        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">Vista principal de aprobación planes de mejoramiento</h1>
        <!-- end page-header -->

        <div class="row">
            <widget-counter
            icon="fa fa-user-check"
            class-css-color="bg-green"
            :qty="dataList.filter((data) =>  data.status_improvement_plan == 'Aprobado').length"
            title="Planes aprobados"
            ></widget-counter>
            <widget-counter
            icon="fa fa-user-check"
            class-css-color="bg-warning"
            :qty="dataList.filter((data) =>  data.status_improvement_plan == 'Revisión del plan de mejoramiento').length"
            title="Planes en revisión de metas"
            ></widget-counter>
        </div>

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('planes de mejoramiento'): ${dataPaginator.total}` | capitalize }}</h5>
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
                                <date-picker :value="searchFields" mode="range" :is-inline="false" name-field="created_at">
                                </date-picker>
                                <small>Filtrar por fecha de creación</small>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" v-model="searchFields.type_name_improvement_plan" @keyup.enter = "pageEventActualizado(1);">
                                <small>Filtrar por el nombre del plan de mejoramiento</small>
                            </div>
                            <div class="col-md-4">
                                <select-check 
                                css-class="form-control"
                                name-field="name_improvement_plan"
                                reduce-label="name"
                                reduce-key="name"
                                name-resource="get-active-types-improvement-plans"
                                :enable-search="true"
                                :value="searchFields">
                                </select-check>
                                <small>Filtrar por tipo del plan de mejoramiento</small>
                            </div>
                            <div class="col-md-4">
                                <select-check css-class="form-control" name-field="dependence_id" reduce-label="nombre" reduce-key="id"
                                name-resource="get-dependences" :value="searchFields">
                                </select-check>
                                <small>Filtrar por dependencia del usuario</small>
                            </div>
                            <div class="col-md-4">
                                <select-check css-class="form-control" name-field="evaluated_id"
                                    reduce-label="name" reduce-key="id" name-resource="get-users" :value="searchFields">
                                </select-check>
                                <small>Filtrar por funcionario evaluado</small>
                            </div>
                            <div class="col-md-4">
                                <input type="date" class="form-control" v-model="searchFields.evaluation_end_date">
                                <small>Filtrar por fecha final de la evaluación</small>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control" v-model="searchFields.status_improvement_plan">
                                    <option value="Revisión del plan de mejoramiento">Revisión del plan de mejoramiento</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="Declinado">Declinado</option>
                                    <option value="Aprobado">Aprobado</option>
                                    <option value="Cerrado cumplido">Cerrado Cumplido</option>
                                </select>
                                <small>Filtrar por el estado del plan</small>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control" v-model="searchFields.evaluation_improvement_opportunities" @change="pageEventActualizado(1);">
                                    <option value="Revisión">En Revisión</option>
                                    <option value="Aprobado">Aprobados</option>
                                    <option value="Devuelto">Devuelto</option>
                                    
                                </select>
                                <small>Filtrar por el estado de los avances</small>
                            </div>
                            <div class="col-md-4">
                                <button @click="pageEventActualizado(1)" class="btn btn-md btn-add"><i class="fa fa-search mr-2"></i>Buscar</button>
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
                {{-- <div class="m-t-20 mb-2">
                    <div class="btn-group" v-if="initValues.can_generate_reports">
                        <a href="javascript:;" data-toggle="dropdown" class="btn"><i class="fa fa-download mr-2"></i> @lang('export_data_table')</a>
                        <a href="#" data-toggle="dropdown" class="btn" aria-expanded="false"><b class="caret"></b></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="javascript:;" @click="exportDataTable('xlsx')" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i> EXCEL</a>
                        </div>
                    </div>
                </div> --}}
                <!-- end buttons action table -->
                @include('improvementplans::approved_improvement_plans.table')
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

        <!-- begin #modal-view-approvedImprovementPlans -->
        <div class="modal fade" id="modal-view-approvedImprovementPlans">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') @lang('el plan de mejoramiento')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body" id="showFields">
                        @include('improvementplans::approved_improvement_plans.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-warning" type="button" onclick="printContentDetail('showFields');"><i class="fa fa-print mr-2"></i>Imprimir</button>

                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-approvedImprovementPlans -->

        <!-- begin #modal-form-approvedImprovementPlans -->
        <div class="modal fade" id="modal-form-approvedImprovementPlans">
            <div class="modal-dialog modal-lg">
                <form @submit.prevent="save()" id="form-approvedImprovementPlans">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">Aprobar plan de mejoramiento</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('improvementplans::approved_improvement_plans.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-add"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-approvedImprovementPlans -->

        <!-- begin #modal-form-modification -->
        <dynamic-modal-form
        modal-id="modal-form-modification"
        size-modal="lg"
        :data-form="dataForm"
        :is-update="true"
        title="Procesar modificacion del plan de mejoramiento"
        endpoint="modification-processes"
        @saved="
        if($event.isUpdate) {
            assignElementList(dataForm.id, $event.data);
        } else {
            addElementToList($event.data);
        }"
        >
            <template #fields="scope">
                <div>
                    @include('improvementplans::approved_improvement_plans.modification_processes')
                </div>
            </template>
        </dynamic-modal-form>
        <!-- end #modal-form-modification -->

                <!-- En este modal se ve el historial del registro -->
        <div class="modal fade" id="modal-history-approvedImprovementPlans">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Seguimiento y control del plan de mejoramiento</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        <div>
                        @include('improvementplans::approved_improvement_plans.show_history')
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-titleRegistrations -->
        <alert-confirmation ref="procesar-modificacion" title="Procesar solicitud de modificación del plan de mejoramiento?" confirmation-text="Aprobar solicitud" cancellation-text="Denegar solicitud" name-resource="process-request-improvement-plan" title-successful-shipment="Solicitud de modificación procesada."></alert-confirmation>

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
    $('#modal-form-approvedImprovementPlans').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });

    function printContentDetail(divName) {

        // Se obtiene el elemento del id recibido por parámetro
        var printContent = document.getElementById(divName);
        // Se guarda en una variable la nueva pestaña
        var printWindow = window.open('', 'PRINT','height=500,width=800');
        // Se obtiene el encabezado de la página actual para no peder estilos
        var headContent = document.getElementsByTagName('head')[0].innerHTML;
        // Se escribe todo el contenido del encabezado de la página actual en la pestaña nueva que se abrirá
        printWindow.document.write(headContent);
        const regex = /font-size: 11px;/ig;
        // Se escribe todo el contenido del id recibido por parámetro en la pestaña nueva que se abrirá
        printWindow.document.write((printContent.innerHTML).replaceAll(regex,"font-size: 13px;"));
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
