@extends('layouts.default')

@section('title', trans('Gestión de evaluaciones'))

@section('section_img', '')

@section('menu')

@if(!Auth::user()->hasRole('Registered'))  

    @include('improvementplans::layouts.menu_component_evaluated')

    @else

    @include('improvementplans::layouts.menu_component_evaluations')

@endif
@endsection

@section('content')

<crud
    name="goals"
    :resource="{default: 'goals', get: 'get-goals?improvement-opportunity={!! $decrypt_improvement_opportunity_id ?? null !!}'}"
    :init-values="{ goal_activities:[],goal_activities_cualitativas:[], goal_responsibles:[], goal_activities_cuantitativas:[],goal_dependencies:[], can_manage: '{!! $can_manage ?? false !!}' , can_generate_reports: '{!! $can_generate_reports ?? false !!}', pm_improvement_opportunity_id: '{!! $decrypt_improvement_opportunity_id ?? null !!}'}"
    inline-template>
    <div>
        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">{{ $text_header }}</h1>
        <!-- end page-header -->
        {{-- <div class="d-flex justify-content-end">
            <div class="d-flex flex-column align-items-center justify-content-center">
                <h4>Porcentaje de cumplimiento aprobado:</h4>
                <h4>{{ $execution_percentage }}%</h4>
            </div>
        </div> --}}

        <div class="d-flex justify-content-center align-items-center">
            <div class="d-flex flex-column align-items-center justify-content-center">
                <h4>Porcentaje de cumplimiento aprobado de la oportunidad de mejora:</h4>
                <h4>{{ number_format($execution_percentage, 2) }}%</h4>

                <!-- Barra de progreso -->
                <div class="progress" style="width: 200px; margin-top: 10px;">
                    <div class="progress-bar" role="progressbar" style="width: {{ number_format($execution_percentage, 2) }}%;" aria-valuenow="{{ $execution_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                
                <p class="text-muted mt-2"><i class="fas fa-info-circle"></i> 
                    Esto debe llegar al 100%. Para seguir aumentando, registra avances en las actividades de las metas.
                </p>
            </div>
        </div>
        

        <!-- begin main buttons -->
        <div class="m-t-20">
            <a href="{{ url()->previous() }}">
                <button type="button" class="btn btn-light m-b-10">
                    <i class="fas fa-arrow-left mr-2"></i>@lang('back')
                </button>
            </a>
            @if(!Auth::user()->hasRole('Registered'))     
                <button v-if="{{ $totalPercentageCompliance }} < 100" @click="add()" type="button" class="btn btn-add m-b-10" data-backdrop="static" data-target="#modal-form-goals" data-toggle="modal">
                    2° Paso: Crear metas
                </button>
            @endif
        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('Cantidad de metas'): ${dataPaginator.total}` | capitalize }}</h5>
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
                                <input type="date" class="form-control" v-model="searchFields.commitment_date">
                                <small>Filtrar por fecha de compromiso</small>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control" v-model="searchFields.goal_type">
                                    <option value="Cuantitativa">Cuantitativa</option>
                                    <option value="Cualitativa">Cualitativa</option>
                                </select>
                                <small>Filtrar por el tipo de meta</small>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" v-model="searchFields.goal_name">
                                <small>Filtrar por el nombre de la meta</small>
                            </div>
                            <div class="col-md-4">
                                <button @click="clearDataSearch()" class="btn btn-md btn-light"><i class="fas fa-broom mr-2"></i>@lang('clear_search_fields')</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end card search -->
            </div>
            <!-- end #accordion search -->
            <div class="panel-body">
                <p> <strong>Nombre de la oportunidad de mejora no conforme:</strong> {{ $improvement_opportunity->name_opportunity_improvement }} <br><strong>Descripción del análisis de las causas:</strong> {{ $improvement_opportunity->description_opportunity_improvement }} <br> <strong>Listar posibles causas:</strong> {{ $improvement_opportunity->possible_causes }} <br> <strong>Porcentaje de la oportunidad de mejora en el plan:</strong> {{ $improvement_opportunity->weight }}%
                </p>
                <!-- begin buttons action table -->
                <div class="float-xl-right m-b-15">
                    <!-- Acciones para exportar datos de tabla-->
                    <div class="btn-group" v-if="initValues.can_generate_reports">
                        <a href="javascript:;" data-toggle="dropdown" class="btn btn-light border"><i class="fa fa-download mr-2"></i> @lang('export_data_table')</a>
                        <a href="#" data-toggle="dropdown" class="btn btn-light border dropdown-toggle" aria-expanded="false"><b class="caret"></b></a>
                        <div class="dropdown-menu dropdown-menu-export dropdown-menu-right">
                            <a href="javascript:;" @click="exportDataTable('xlsx')" class="dropdown-item"><i class="fa fa-file-excel mr-2 text-success"></i> EXCEL</a>
                        </div>
                    </div>
                </div>
                <!-- end buttons action table -->
                @include('improvementplans::goals.table')
            </div>
            <div class="p-b-15 text-center">
                <!-- Cantidad de elementos a mostrar -->
                <div class="form-group row m-5 col-md-3 text-center d-sm-inline-flex">
                    {!! Form::label('show_qty', trans('show_qty').':', ['class' => 'col-form-label col-md-7']) !!}
                    <div class="col-md-5">
                        {!! Form::select(trans('show_qty'), [5 => 5, 10 => 10, 15 => 15, 20 => 20, 25 => 25, 30 => 30, 50 => 50, 100 => 100, 200 => 200], '20', ['class' => 'form-control', 'v-model' => 'dataPaginator.pagesItems']) !!}
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

        <!-- begin #modal-form-modification -->
        <dynamic-modal-form
        modal-id="modal-form-assigning-responsible-activity"
        size-modal="lg"
        :data-form="dataForm"
        :is-update="true"
        title="Agregar responsables a las actividades"
        endpoint="assign-responsible-to-activity"
        @saved="
        if($event.isUpdate) {
            assignElementList(dataForm.id, $event.data);
        } else {
            addElementToList($event.data);
        }"
        >
            <template #fields="scope">
                <div v-if="openForm">
                    @include('improvementplans::goals.fields_assign_responsibles_activity')
                </div>
            </template>
        </dynamic-modal-form>
        <!-- end #modal-form-modification -->

        <!-- begin #modal-view-goals -->
        <div class="modal fade" id="modal-view-goals">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') @lang('metas')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        @include('improvementplans::goals.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-goals -->

        <!-- begin #modal-form-goals -->
        <div class="modal fade" id="modal-form-goals">
            <div class="modal-dialog modal-lg">
                <form @submit.prevent="save()" id="form-goals">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">Metas con respecto al plan de mejoramiento</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('improvementplans::goals.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-add"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-goals -->
    </div>
</crud>
@endsection

@push('css')
{!!Html::style('assets/plugins/gritter/css/jquery.gritter.css')!!}

<style>
.custom-tooltip {
    position: relative;
    display: inline-block;
}

.custom-tooltip-content {
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    background-color: #f8f9fa;
    padding: 10px;
    border: 1px solid #ced4da;
    border-radius: 5px;
    display: none;
    width: 300px; /* Ajusta el ancho según tus necesidades */

}

.custom-tooltip:hover .custom-tooltip-content {
    display: block;
}

.custom-tooltip-trigger {
    cursor: pointer;
    color: #007bff;
}

</style>
@endpush

@push('scripts')
{!!Html::script('assets/plugins/gritter/js/jquery.gritter.js')!!}
<script>
    // detecta el enter para no cerrar el modal sin enviar el formulario
    $('#modal-form-goals').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
