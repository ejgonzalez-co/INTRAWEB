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
    name="nonConformingCriterias"
    :resource="{default: 'non-conforming-criterias', get: 'get-non-conforming-criterias?evaluation={!! $decrypt_evaluation_id ?? null !!}'}"
    :init-values="{can_manage: '{!! $can_manage ?? false !!}' , can_generate_reports: '{!! $can_generate_reports ?? false !!}'}"
    inline-template>
    <div>
        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">{{ $text_header }}</h1>
        <!-- end page-header -->
        {{-- <div class="d-flex justify-content-end">
            <div class="d-flex flex-column align-items-center justify-content-center">
                <h4>Porcentaje de cumplimiento aprobado:</h4>
                <h4>{{ $execution_percentage_pm }}%</h4>
            </div>
        </div>
 --}}
                
        <div class="d-flex justify-content-center align-items-center">
            <div class="d-flex flex-column align-items-center justify-content-center">
                <h4>Porcentaje de cumplimiento aprobado del Plan de Mejoramiento:</h4>
                <h4>{{ number_format($execution_percentage_pm, 2) }}%</h4>

                <!-- Barra de progreso -->
                <div class="progress" style="width: 200px; margin-top: 10px;">
                    <div class="progress-bar" role="progressbar" style="width: {{ number_format($execution_percentage_pm, 2) }}%;" aria-valuenow="{{ $execution_percentage_pm }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                
                <p class="text-muted mt-2"><i class="fas fa-info-circle"></i> 
                    Esto debe llegar al 100%. Para seguir aumentando, registra avances en las actividades de las metas.
                </p>
            </div>
        </div>


        <!-- begin main buttons -->
        <div class="m-t-20">
                @if(Auth::user()->hasRole('Registered'))     
                    <a href="approved-improvement-plans">
                @else
                    <a href="{{ route('improvement-plans.index') }}">
                @endif
                
                <button type="button" class="btn btn-light m-b-10">
                    <i class="fas fa-arrow-left mr-2"></i>@lang('back')
                </button>
            </a>
        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('Cantidad de oportunidades de mejora'): ${dataPaginator.total}` | capitalize }}</h5>
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
                                <input type="text" class="form-control" v-model="searchFields.criteria_name">
                                <small>Filtrar por el nombre de la oportunidad de mejora</small>
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
                <!-- begin buttons action table -->
                {{-- <div class="float-xl-right m-b-15">
                    <!-- Acciones para exportar datos de tabla-->
                    <div class="btn-group" v-if="initValues.can_generate_reports">
                        <a href="javascript:;" data-toggle="dropdown" class="btn"><i class="fa fa-download mr-2"></i> @lang('export_data_table')</a>
                        <a href="#" data-toggle="dropdown" class="btn" aria-expanded="false"><b class="caret"></b></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="javascript:;" @click="exportDataTable('xlsx')" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i> EXCEL</a>
                        </div>
                    </div>
                </div> --}}
                <!-- end buttons action table -->
                @include('improvementplans::non_conforming_criterias.table')
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

        <!-- begin #modal-view-nonConformingCriterias -->
        <div class="modal fade" id="modal-view-nonConformingCriterias">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Información de la oportunidad de mejora</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        @include('improvementplans::non_conforming_criterias.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-nonConformingCriterias -->

        <!-- begin #modal-form-nonConformingCriterias -->
        <div class="modal fade" id="modal-form-nonConformingCriterias">
            <div class="modal-dialog modal-lg">
                <form @submit.prevent="save()" id="form-nonConformingCriterias">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">1. Paso: Información general</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('improvementplans::non_conforming_criterias.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-add"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-nonConformingCriterias -->
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
    $('#modal-form-nonConformingCriterias').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
