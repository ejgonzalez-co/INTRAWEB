@extends('layouts.default')

@section('title', trans('Tipos de planes de mejoramiento'))

@section('section_img', '')

@section('menu')
    @include('improvementplans::layouts.menu')
@endsection

@section('content')

    <crud name="typeImprovementPlans" :resource="{default: 'type-improvement-plans', get: 'get-type-improvement-plans'}"     :init-values="{can_manage: '{!! $can_manage ?? false !!}' , can_generate_reports: '{!! $can_generate_reports ?? false !!}'}" inline-template>
    <div>
        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">Administración de tipos de planes de mejoramiento</h1>
        <!-- end page-header -->

        <!-- begin main buttons -->
        <div class="m-t-20" style="margin-bottom: 10px;">
            <button v-if="initValues.can_manage" @click="add()" type="button" class="btn btn-add m-b-10" data-backdrop="static" data-target="#modal-form-typeImprovementPlans" data-toggle="modal" 
            style="margin-right: 20px">
                <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('tipo de plan de mejoramiento')
            </button>

            <button v-if="initValues.can_manage" @click="callFunctionComponent('form-time-messages', 'loadCalendar')" type="button" class="btn btn-light border m-b-10" style="margin-right: 20px">
                <i class="fas fa-bell mr-2"></i>Configurar tiempos de alerta y mensajes
            </button>

            <!-- Acciones para exportar datos de tabla-->
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
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('tipos de planes de mejoramiento'): ${advancedSearchFilterPaginate().length}` | capitalize }}</h5>
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
                                {!! Form::text('name', null, ['v-model' => 'searchFields.name', 'class' => 'form-control']) !!}
                                <small>Filtrar por el nombre</small>
                            </div>
                            <div class="col-md-4">
                                {!! Form::text('code', null, ['v-model' => 'searchFields.code', 'class' => 'form-control']) !!}
                                <small>Filtrar por el código</small>
                            </div>
                            <div class="col-md-4">
                                {!! Form::select('state',["Activo" => 'Activo', "Inactivo" => 'Inactivo'], null, ['class' => 'form-control', 'v-model' => 'searchFields.state']) !!}
                                <small>Filtrar por el estado</small>
                            </div>
                            <div class="col-md-4 mt-2">
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
                <!-- end buttons action table -->
                @include('improvementplans::type_improvement_plans.table')
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

        <!-- begin #modal-view-typeImprovementPlans -->
        <div class="modal fade" id="modal-view-typeImprovementPlans">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') @lang('tipo de plan de mejoramiento')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            @include('improvementplans::type_improvement_plans.show_fields')
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-typeImprovementPlans -->

        <!-- begin #modal-form-typeImprovementPlans -->
        <div class="modal fade" id="modal-form-typeImprovementPlans">
            <div class="modal-dialog modal-lg">
                <form @submit.prevent="save()" id="form-typeImprovementPlans">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of') @lang('tipo de plan de mejoramiento')</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('improvementplans::type_improvement_plans.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-add"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-typeImprovementPlans -->
        <!--  -->
        {{-- <div class="modal fade" id="modal-form-time_massages">
            <div class="modal-dialog modal-lg">
                <form @submit.prevent="save()" id="form-time_massages">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('configure-time-alert-massages') para planes de acción proximós a vencer</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('improvementplans::type_improvement_plans.time_massages_fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div> --}}
        <!--  -->

        <form-time-messages ref="form-time-messages" name="time-messages"></form-time-messages>

        {{-- <dynamic-modal-form
            modal-id="modal-form-time_massages"
            size-modal = "lg"
            title="Configuración de tiempos de alerta y mensajes para planes de acción próximos a vencer"
            :data-form="dataForm"
            :is-update="true"
            endpoint="update-time-massages"
            @saved="
            if($event.isUpdate) {
                _getDataList();
            }
            "
            >
            <template #fields="scope">
                @include('improvementplans::type_improvement_plans.time_messages')
            </template>
        </dynamic-modal-form> --}}
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
    $('#modal-form-typeImprovementPlans').on('keypress', ( e ) => {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    } );
</script>
@endpush
