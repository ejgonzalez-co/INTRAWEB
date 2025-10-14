@extends('layouts.default')

@section('title', trans('Evaluaciones'))

@section('section_img', '')

@section('menu')
    @include('improvementplans::layouts.menu_component_evaluations')
@endsection

@section('content')

<crud name="evaluations" 
      :resource="{default: 'evaluations', get: 'get-evaluations'}"
      :init-values="{ evaluation_criteria:[],evaluation_dependences:[],can_manage: '{!! $can_manage ?? false !!}' , can_generate_reports: '{!! $can_generate_reports ?? false !!}' }"
      :crud-avanzado="true"
      inline-template>
    <div>
        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">Vista principal de Evaluaciones</h1>
        <!-- end page-header -->

        <!-- begin main buttons -->
        <div class="m-t-20 mb-2">
            <button v-if="initValues.can_manage" @click="add()" type="button" class="btn btn-add m-b-10 mr-2" data-backdrop="static" data-target="#modal-form-evaluations" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>Programar evaluación
            </button>

            <div class="btn-group m-b-10" v-if="initValues.can_generate_reports">
                <a href="javascript:;" data-toggle="dropdown" class="btn btn-light border"><i class="fa fa-download mr-2"></i> @lang('export_data_table')</a>
                <a href="#" data-toggle="dropdown" class="btn btn-light border dropdown-toggle" aria-expanded="false"><b class="caret"></b></a>
                <div class="dropdown-menu dropdown-menu-export dropdown-menu-right">
                    <a href="javascript:;" @click="exportDataTable('xlsx')" class="dropdown-item"><i class="fa fa-file-excel mr-2 text-success"></i> EXCEL</a>
                </div>
            </div>
        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('evaluations'): ${dataPaginator.total}` | capitalize }}</h5>
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
                                <select-check css-class="form-control" name-field="type_evaluation"
                                reduce-label="name" reduce-key="name" name-resource="active-type-evaluations" :value="searchFields">
                                </select-check>
                                <small>Filtrar por tipo de evaluación</small>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" v-model="searchFields.evaluation_name">
                                <small>Filtrar por el nombre de la evaluación</small>
                            </div>
                            <div class="col-md-4">
                                <select-check css-class="form-control" name-field="evaluator_id"
                                    reduce-label="name" reduce-key="id" name-resource="get-users" :value="searchFields">
                                </select-check>
                                <small>Filtrar por funcionario evaluador</small>
                            </div>
                            <div class="col-md-4">
                                <select-check css-class="form-control" name-field="evaluated_id"
                                reduce-label="name" reduce-key="id" name-resource="get-users" :value="searchFields">
                                </select-check>
                                <small>Filtrar por funcionario evaluado</small>
                            </div>
                            <div class="col-md-4">
                                <input type="date" class="form-control" v-model="searchFields.evaluation_start_date">
                                <small>Filtrar por fecha inicial de la evaluación</small>
                            </div>
                            <div class="col-md-4">
                                <input type="date" class="form-control" v-model="searchFields.evaluation_end_date">
                                <small>Filtrar por fecha final de la evaluación</small>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control" v-model="searchFields.status">
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="Cerrada">Cerrada</option>
                                </select>
                                <small>Filtrar por el estado de la evaluación</small>
                            </div>
                            <div class="col-md-4">
                                <button @click="pageEventActualizado(1)" class="btn btn-md btn-add"><i class="fa fa-search mr-2"></i>Buscar</button>
                                <button @click="clearDataSearchAvanzado()" class="btn btn-md btn-light">@lang('clear_search_fields')</button>
                            </div>|
                        </div>
                    </div>
                </div>
                <!-- end card search -->
            </div>
            <!-- end #accordion search -->
            <div class="panel-body" >
                <!-- begin buttons action table -->
                <!-- end buttons action table -->
                @include('improvementplans::evaluations.table')
            </div>
            <div class="p-b-15 text-center">
                <!-- Cantidad de elementos a mostrar -->
                <div class="form-group row m-5 col-md-3 text-center d-sm-inline-flex" >
                    {!! Form::label('show_qty', trans('show_qty').':', ['class' => 'col-form-label col-md-7']) !!}
                    <div class="col-md-5">
                        {!! Form::select(trans('show_qty'), [5 => 5, 10 => 10, 15 => 15, 20 => 20, 25 => 25, 30 => 30, 50 => 50, 100 => 100, 200 => 200], 20, ['class' => 'form-control', 'v-model' => 'dataPaginator.pagesItems', '@change' => 'pageEventActualizado(1)']) !!}                    </div>
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

        <!-- begin #modal-view-evaluations -->
        <div class="modal fade" id="modal-view-evaluations">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') @lang('evaluations')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        @include('improvementplans::evaluations.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-evaluations -->

        <!-- begin #modal-form-execute-evaluation-processes -->
        <dynamic-modal-form
        modal-id="modal-form-execute-evaluation-processes"
        size-modal="lg"
        :data-form="dataForm"
        :is-update="true"
        title="Ejecutar proceso de evaluación"
        endpoint="execute-evaluation-process"
        @saved="
        if($event.isUpdate) {
            assignElementList(dataForm.id, $event.data);
        } else {
            addElementToList($event.data);
        }"
        >
            <template #fields="scope">
                <div>
                    @include('improvementplans::evaluations.execute_evaluation_processes')
                </div>
            </template>
        </dynamic-modal-form>
        <!-- end #modal-form-execute-evaluation-processes -->

        <!-- begin #modal-form-evaluations -->
        <div class="modal fade" id="modal-form-evaluations">
            <div class="modal-dialog modal-xl">
                <form @submit.prevent="save()" id="form-evaluations">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('Programar evaluación')</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('improvementplans::evaluations.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button v-if="!isUpdate" type="submit" class="btn btn-add"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                            <button v-if="dataForm.evaluator_id == {{ Auth::id() }}" type="submit" class="btn btn-add"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-evaluations -->

        <alert-confirmation ref="send-improvement-plan" title="Enviar plan de mejoramiento al evaluado" confirmation-text="Enviar plan" cancellation-text="Cerrar" name-resource="send-improvement-plan" title-successful-shipment="Plan de mejoramiento enviado exitosamente al evaluado"></alert-confirmation>

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
    $('#modal-form-evaluations').on('keypress', ( e ) => {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    } );
</script>
@endpush
