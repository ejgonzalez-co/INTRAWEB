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
    name="goalProgresses"
    :resource="{default: 'goal-progresses', get: 'get-goal-progresses?goal={!! $goalId ?? null !!}'}"
    :init-values="{goal_id: '{!! $goalId ?? false !!}'}"
    inline-template>
    <div>
        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">Avances de la meta: {{ $goal->goal_name }} </h1>
        <!-- end page-header -->

        <!-- begin main buttons -->
        <div class="m-t-20">
            <a href="{{ url()->previous() }}">
                <button type="button" class="btn btn-light m-b-10">
                    <i class="fas fa-arrow-left mr-2"></i>@lang('back')
                </button>
            </a>
            @if(!Auth::user()->hasRole('Registered') && !Auth::user()->hasRole('Evaluador'))  
                <button @click="add()" type="button" class="btn btn-add m-b-10" data-backdrop="static" data-target="#modal-form-goalProgresses" data-toggle="modal">
                    <i class="fa fa-plus mr-2"></i>Añadir avance
                </button>
            @endif
        </div>
        <!-- end main buttons -->
     


        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('Cantidad de avances'): ${dataPaginator.total}` | capitalize }}</h5>
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
                                {!! Form::text('name', null, ['v-model' => 'searchFields.name', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('ejemplo')]) ]) !!}
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
                    <div class="btn-group">
                        <a href="javascript:;" data-toggle="dropdown" class="btn btn-primary"><i class="fa fa-download mr-2"></i> @lang('export_data_table')</a>
                        <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle" aria-expanded="false"><b class="caret"></b></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="javascript:;" @click="exportDataTable('pdf')" class="dropdown-item text-white no-hover"><i class="fa fa-file-pdf mr-2"></i> PDF</a>
                            <a href="javascript:;" @click="exportDataTable('xlsx')" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i> EXCEL</a>
                        </div>
                    </div>
                </div> --}}
                <!-- end buttons action table -->
                @include('improvementplans::goal_progresses.table')
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

        <!-- begin #modal-view-goalProgresses -->
        <div class="modal fade" id="modal-view-goalProgresses">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Información del avance</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        @include('improvementplans::goal_progresses.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-goalProgresses -->

        <!-- begin #modal-form-goalProgresses -->
        <div class="modal fade" id="modal-form-goalProgresses">
            <div class="modal-dialog modal-lg">
                <form @submit.prevent="save()" id="form-goalProgresses">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">Avances y evidencias - Plan de mejoramiento</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('improvementplans::goal_progresses.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-add"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-goalProgresses -->

        <!-- begin #modal-form-execute-evaluation-processes -->
        <dynamic-modal-form
        modal-id="modal-form-approved-progress"
        size-modal="lg"
        :data-form="dataForm"
        :is-update="true"
        title="Aprobar avances y evidencias"
        endpoint="approved-progress"
        @saved="
        if($event.isUpdate) {
            assignElementList(dataForm.id, $event.data);
        } else {
            addElementToList($event.data);
        }"
        >
            <template #fields="scope">
                <div>
                    <!-- Status Field -->
                    <div class="form-group row m-b-15">
                        {!! Form::label('pm_goal_activities_id', trans('Seleccione el tipo de respuesta') . ':', [
                            'class' => 'col-form-label col-md-3 required',
                        ]) !!}
                        <div class="col-md-9">
                            <select class="form-control" v-model="dataForm.status">
                                <option value="Aprobado">Aprobar avance y evidencias</option>
                                <option value="Devuelto">Devolver avance y evidencias</option>
                            </select>
                            <small>Seleccione que desea hacer con el avance y evidencia.</small>
                            <div class="invalid-feedback" v-if="dataErrors.pm_goal_activities_id">
                                <p class="m-b-0" v-for="error in dataErrors.pm_goal_activities_id">@{{ error }}</p>
                            </div>
                        </div>
                    </div>
                    <!-- Activity Name Field -->
                    <div class="form-group row m-b-15">
                        {!! Form::label('pm_goal_activities_id', trans('Observación') . ':', [
                            'class' => 'col-form-label col-md-3 required',
                        ]) !!}
                        <div class="col-md-9">
                            <textarea cols="30" rows="10" class="form-control" v-model="dataForm.observation"></textarea>
                            <small>Ingrese la observación del avance</small>
                            <div class="invalid-feedback" v-if="dataErrors.pm_goal_activities_id">
                                <p class="m-b-0" v-for="error in dataErrors.pm_goal_activities_id">@{{ error }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </dynamic-modal-form>
        <!-- end #modal-form-execute-evaluation-processes -->

        <alert-confirmation ref="alert-confirmation" title="¿Enviar el avance a revisión del evaluador?" confirmation-text="Enviar a revisión" cancellation-text="Cerrar" name-resource="send-review-goal-progress" title-successful-shipment="Avance enviado exitosamente al evaluador" text-loading="Enviando al evaluador..."></alert-confirmation>
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
    $('#modal-form-goalProgresses').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
