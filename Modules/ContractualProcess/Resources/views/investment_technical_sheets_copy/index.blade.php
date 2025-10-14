@extends('layouts.default')

@section('title', trans('Investment Technical Sheets'))

@section('section_img', '')

@section('menu')
    @include('contractual_process::layouts.menus.menu_investment_needs')
@endsection

@section('content')
<crud
    name="investment-technical-sheets"
    :resource="{default: 'investment-technical-sheets', get: 'get-investment-technical-sheets{!! !empty($need) ? '?pc_needs_id='.$need : null !!}'}"
    :init-values="{
        pc_needs_id: '{!! $need ?? null !!}',
        other_planning_documents: [],
        direct_causes_problems: [],
        indirect_causes_problems: [],
        direct_effects_problems: [],
        indirect_effects_problems: [],
        project_areas_influences: [],
        specific_objectives: [],
        monitoring_indicators: [],
        information_tariff_harmonizations: [],
        environmental_impacts: [],
        supporting_study_data: [],
        selection_alternatives: [],
        alternative_budgets: [],
        resource_schedule_current_terms: [],
        schedule_resources_previous_periods: [],
        replacement: false,
        expansion: false,
        rehabilitation: false,
        process_licenses_guadua: false,
        coverage: false,
        continuity: false,
        irca_water_quality_risk_index: false,
        micrometer: false,
        ianc_unaccounted_water_index: false,
        ipufi_loss_index_billed_user: false,
        icufi_index_water_consumed_user: false,
        isufi_supply_index_billed_user: false,
        ccpi_consumption_corrected_losses: false,
        pressure: false,
        discharge_treatment_index: false,
        tons_bbo_removed: false,
        tons_sst_removed: false,
        operational_claim_index: false,
        commercial_claim_index: false,
        efficiency_collection: false,
        via_aqueduct_sewerage_rates: false,
        cleaning_fee_resources: false,
        regalias: false,
        general_participation_system: false,
        decentralized_entity: false,
        capital_contributed: false,
        contributed_capital_official: false,
        capital_contributions: false,
        third_party_contributions: false,
        national_debt: false,
        foreign_debt: false,
        week: []
    }"

    :resource="{default: 'investment-technical-sheets', get: 'get-investment-technical-sheets'}"
    inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/home') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('Investment Technical Sheets')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">@{{ `@lang('main_view_to_manage') @lang('Investment Technical Sheets')` | capitalize }}</h1>
        <!-- end page-header -->

        <!-- begin main buttons -->
        <div class="m-t-20">
            @if(!Auth::user()->hasRole('PC Gestor de recursos'))
            <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-investment-technical-sheets" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>@{{ `@lang('crud.create') @lang('Investment Technical Sheet')` | capitalize }}
            </button>
            @endif
        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('Investment Technical Sheets'): ${dataPaginator.total}` | capitalize }}</h5>
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
                            <button @click="clearDataSearch()" class="btn btn-md btn-primary">@lang('clear_search_fields')</button>
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
                            <a href="javascript:;" @click="exportDataTable('pdf')" class="dropdown-item text-white no-hover"><i class="fa fa-file-pdf mr-2"></i> PDF</a>
                            <a href="javascript:;" @click="exportDataTable('xlsx')" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i> EXCEL</a>
                        </div>
                    </div>
                </div>
                <!-- end buttons action table -->
                @include('contractual_process::investment_technical_sheets.table')
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

        <!-- begin #modal-view-investment-technical-sheets -->
        <div class="modal fade" id="modal-view-investment-technical-sheets">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') @lang('Investment Technical Sheets')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        @include('contractual_process::investment_technical_sheets.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-investment-technical-sheets -->

        <!-- begin #modal-form-investment-technical-sheets -->
        <div class="modal fade" id="modal-form-investment-technical-sheets">
            <div class="modal-dialog modal-xl">
                <form @submit.prevent="save()" id="form-investment-technical-sheets">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@{{ `@lang('form_of') @lang('Investment Technical Sheet')` | capitalize }}</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('contractual_process::investment_technical_sheets.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-investment-technical-sheets -->


        <!-- begin #modal-form-goals-indicators send -->
        <dynamic-modal-form
            modal-id="goals-indicators"
            size-modal="xl"
            title="Objetivos e indicadores"
            :data-form.sync="dataForm"
            endpoint="obectives-indicators"
            @saved="dataList.unshift($event); $forceUpdate()"
            >
            <template #fields="scope">
                <div class="panel" data-sortable-id="ui-general-1">
                    <!-- begin panel-heading -->
                    <div class="panel-heading ui-sortable-handle">
                        <h4 class="panel-title"><strong>Objetivos del Proyecto:</strong></h4>
                    </div>
                    <!-- end panel-heading -->
                    <!-- begin panel-body -->
                    <div class="panel-body">
                        <div class="row">
                    
                            <div class="col-md-12">
                                <!-- General objective Field -->
                                <div class="form-group row m-b-15">
                                    {!! Form::label('general_objective', trans('General objective').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                    <div class="col-md-9">
                                        {!! Form::textarea('general_objective', null, ['class' => 'form-control', 'rows' => '5', 'v-model' => 'scope.dataForm.general_objective', 'required' => true]) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <!-- Overall Goal Field -->
                                <div class="form-group row m-b-15">
                                    {!! Form::label('overall_goal', trans('Overall goal').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                    <div class="col-md-9">
                                        {!! Form::textarea('overall_goal', null, ['class' => 'form-control', 'rows' => '5', 'v-model' => 'scope.dataForm.overall_goal', 'required' => true]) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="panel" data-sortable-id="ui-general-1">
                                    <!-- begin panel-heading -->
                                    <div class="panel-heading ui-sortable-handle">
                                        <h4 class="panel-title"><strong>Objetivos específicos:</strong></h4>
                                    </div>
                                    <!-- end panel-heading -->
                                    <!-- begin panel-body -->
                                    <div class="panel-body">
                                        <div class="row">

                                            <dynamic-list
                                                label-button-add="Agregar ítem a la lista"
                                                :data-list.sync="scope.dataForm.specific_objectives"
                                                :data-list-options="[
                                                    {label:'Descripción', name:'description', isShow: true},
                                                ]"
                                                class-container="col-md-12"
                                                class-table="table table-bordered"
                                                >
                                                <template #fields="scope">
                                                    
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <!-- Specific objectives Field -->
                                                            <div class="form-group row m-b-15">
                                                                {!! Form::label('description', trans('Specific objectives').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                                                <div class="col-md-9">
                                                                    {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '3', 'v-model' => 'scope.dataForm.description', 'required' => true]) !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                </template>
                                            </dynamic-list>

                                        </div>
                                    </div>
                                    <!-- end panel-body -->
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- end panel-body -->
                </div>

                <div class="panel" data-sortable-id="ui-general-1">
                    <!-- begin panel-heading -->
                    <div class="panel-heading ui-sortable-handle">
                        <h4 class="panel-title">
                            <strong>Nota:</strong><br><i><b>Indicadores de Gestión:</b> Cantidad de Acciones, Procesos, Procedimientos y/o operaciones realizadas.<br><b>Indicador de Producto y Servicio:</b> Bienes y Servicios, (intermedios y finales) Producidos y/o provisionados.<br><b>Indicador Resultados:</b> Efectos generados en el Bienestar de la población como consecuencia de la entrega del Producto o Servicio.<br>Todo proyecto debe contar como mínimo con un indicador de (Gestión, Producto o Servicio)  y un indicador de Resultado.</i>
                            <hr>
                            <strong>Indicadores de seguimiento:</strong>
                        </h4>
                    </div>
                    <!-- end panel-heading -->
                    <!-- begin panel-body -->
                    <div class="panel-body">
                        <div class="row">
                            
                            <dynamic-list
                                label-button-add="Agregar ítem a la lista"
                                :data-list.sync="scope.dataForm.monitoring_indicators"
                                :data-list-options="[
                                    {label:'Tipo', name:'indicator_type', isShow: true, refList: 'indicator_typeRef'},
                                    {label:'Descripción', name:'description', isShow: true},
                                    {label:'Formula', name:'formula', isShow: true},
                                ]"
                                class-table="table table-bordered"
                                >
                                <template #fields="scope">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <!-- Indicator Type Field -->
                                            <div class="form-group row m-b-15">
                                                {!! Form::label('indicator_type', trans('Indicator type').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                                <div class="col-md-9">
                                                    <select-check
                                                        css-class="form-control"
                                                        name-field="indicator_type"
                                                        reduce-label="name"
                                                        reduce-key="id"
                                                        name-resource="get-constants-active/indicator_type_monitoring_indicators"
                                                        :value="scope.dataForm"
                                                        ref-select-check="indicator_typeRef"
                                                        :is-required="true">
                                                    </select-check>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <!-- Indicator Type Field -->
                                            <div class="form-group row m-b-15">
                                                {!! Form::label('description', trans('Monitoring indicators').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                                <div class="col-md-9">
                                                    {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '5', 'v-model' => 'scope.dataForm.description', 'required' => true]) !!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <!-- Formula Field -->
                                            <div class="form-group row m-b-15">
                                                {!! Form::label('formula', trans('Formula').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                                <div class="col-md-9">
                                                    {!! Form::text('formula', null, ['class' => 'form-control', 'v-model' => 'scope.dataForm.formula', 'required' => true]) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </dynamic-list>
                               
                        </div>
                    </div>
                    <!-- end panel-body -->
                </div>
            </template>
        </dynamic-modal-form>
        <!-- end #modal-form-goals-indicators send -->

        <!-- begin #modal-form-information-tariff-harmonization send -->
        <dynamic-modal-form
            modal-id="information-tariff-harmonization"
            size-modal="xl"
            title="Información armonización tarifaria"
            :data-form.sync="dataForm"
            endpoint="information-tariff-harmonization"
            @saved="dataList.unshift($event); $forceUpdate()"
            >
            <template #fields="scope">

                <div class="panel" data-sortable-id="ui-general-1">
                    <!-- begin panel-heading -->
                    <div class="panel-heading ui-sortable-handle">
                        <h4 class="panel-title"><strong>Info. Armonización tarifaria:</strong></h4>
                    </div>
                    <!-- end panel-heading -->
                    <!-- begin panel-body -->
                    <div class="panel-body">
                        <div class="row">
                            <dynamic-list
                                label-button-add="Agregar ítem a la lista"
                                :data-list.sync="scope.dataForm.information_tariff_harmonizations"
                                :data-list-options="[
                                    {label:'Item', name:'item', isShow: true, refList: 'itemRef'},
                                    {label:'Actividad', name:'activity', isShow: true, refList: 'activityRef'},
                                    {label:'Unidad', name:'unit', isShow: true},
                                ]"
                                class-container="col-md-12"
                                class-table="table table-bordered"
                                >
                                <template #fields="scope">
                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Item Field -->
                                        <div class="form-group row m-b-15">
                                            {!! Form::label('item', trans('Item').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                            <div class="col-md-9">
                                                <select-check
                                                    css-class="form-control"
                                                    name-field="item"
                                                    reduce-label="name"
                                                    reduce-key="id"
                                                    name-resource="get-constants-active/item_tariff_harmonization"
                                                    :value="scope.dataForm"
                                                    ref-select-check="itemRef"
                                                    :is-required="true">
                                                </select-check>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Activity Field -->
                                        <div class="form-group row m-b-15">
                                            {!! Form::label('item', trans('Activity').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                            <div class="col-md-9">
                                                <select-check
                                                    css-class="form-control"
                                                    name-field="activity"
                                                    reduce-label="name"
                                                    reduce-key="id"
                                                    :name-resource="'get-activity-tariff-harmonization-by-item/'+scope.dataForm.item"
                                                    :value="scope.dataForm"
                                                    ref-select-check="activityRef"
                                                    :key="scope.dataForm.item"
                                                    :is-required="true">
                                                </select-check>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Unit Field -->
                                        <div class="form-group row m-b-15">
                                            {!! Form::label('unit', trans('Unit').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                            <div class="col-md-9">
                                                {!! Form::number('unit', null, ['class' => 'form-control', 'v-model' => 'scope.dataForm.unit', 'required' => true]) !!}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                </template>
                            </dynamic-list>

                        </div>
                    </div>
                    <!-- end panel-body -->
                </div>

                <div class="panel" data-sortable-id="ui-general-1">
                    <!-- begin panel-heading -->
                    <div class="panel-heading ui-sortable-handle">
                        <h4 class="panel-title"><strong>Tipo de inversión (Aplica Acueducto y Alcantarillado):</strong></h4>
                    </div>
                    <!-- end panel-heading -->
                    <!-- begin panel-body -->
                    <div class="panel-body">
                        <div class="row">

                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td>Tipo</td>
                                            <td colspan="2">Si / No</td>
                                        </tr>
                                        <tr>
                                            <td>Reposición</td>
                                            <td colspan="2"> 
                                                <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="replacement" id="replacement" v-model="scope.dataForm.replacement">
                                                <label for="replacement"></label>
                                                <small></small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Expansión</td>
                                            <td colspan="2"> 
                                                <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="expansion" id="expansion" v-model="scope.dataForm.expansion">
                                                <label for="expansion"></label>
                                                <small></small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Rehabilitación</td>
                                            <td colspan="2"> 
                                                <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="rehabilitation" id="rehabilitation" v-model="scope.dataForm.rehabilitation">
                                                <label for="rehabilitation"></label>
                                                <small></small>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <!-- end panel-body -->
                </div>

                <div class="panel" data-sortable-id="ui-general-1">
                    <!-- begin panel-heading -->
                    <div class="panel-heading ui-sortable-handle">
                        <h4 class="panel-title"><strong>Meta (Aplica Acueducto y Alcantarillado):</strong></h4>
                    </div>
                    <!-- end panel-heading -->
                    <!-- begin panel-body -->
                    <div class="panel-body">
                        <div class="row">

                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td>Meta</td>
                                            <td colspan="2">Si / No</td>
                                        </tr>
                                        <tr>
                                            <td>Cobertura</td>
                                            <td colspan="2"> 
                                                <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="coverage" id="coverage" v-model="scope.dataForm.coverage">
                                                <label for="coverage"></label>
                                                <small></small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Continuidad</td>
                                            <td colspan="2"> 
                                                <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="continuity" id="continuity" v-model="scope.dataForm.continuity">
                                                <label for="continuity"></label>
                                                <small></small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Índice de Riesgo de Calidad de Agua IRCA</td>
                                            <td colspan="2"> 
                                                <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="irca_water_quality_risk_index" id="irca_water_quality_risk_index" v-model="scope.dataForm.irca_water_quality_risk_index">
                                                <label for="irca_water_quality_risk_index"></label>
                                                <small></small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Micromedición</td>
                                            <td colspan="2"> 
                                                <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="micrometer" id="micrometer" v-model="scope.dataForm.micrometer">
                                                <label for="micrometer"></label>
                                                <small></small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Índice de Agua No Contabilizada IANC</td>
                                            <td colspan="2"> 
                                                <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="ianc_unaccounted_water_index" id="ianc_unaccounted_water_index" v-model="scope.dataForm.ianc_unaccounted_water_index">
                                                <label for="ianc_unaccounted_water_index"></label>
                                                <small></small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>IPUFi - Índice de pérdidas por Usuario Facturado</td>
                                            <td colspan="2"> 
                                                <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="ipufi_loss_index_billed_user" id="ipufi_loss_index_billed_user" v-model="scope.dataForm.ipufi_loss_index_billed_user">
                                                <label for="ipufi_loss_index_billed_user"></label>
                                                <small></small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>ICUFi - Índice de Agua Consumida por Usuario Facturado</td>
                                            <td colspan="2"> 
                                                <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="icufi_index_water_consumed_user" id="icufi_index_water_consumed_user" v-model="scope.dataForm.icufi_index_water_consumed_user">
                                                <label for="icufi_index_water_consumed_user"></label>
                                                <small></small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>ISUFi - Índice de Suministro por Usuario Facturado</td>
                                            <td colspan="2"> 
                                                <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="isufi_supply_index_billed_user" id="isufi_supply_index_billed_user" v-model="scope.dataForm.isufi_supply_index_billed_user">
                                                <label for="isufi_supply_index_billed_user"></label>
                                                <small></small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>CCPi - Consumo corregido por pérdidas</td>
                                            <td colspan="2"> 
                                                <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="ccpi_consumption_corrected_losses" id="ccpi_consumption_corrected_losses" v-model="scope.dataForm.ccpi_consumption_corrected_losses">
                                                <label for="ccpi_consumption_corrected_losses"></label>
                                                <small></small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Presión</td>
                                            <td colspan="2"> 
                                                <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="pressure" id="pressure" v-model="scope.dataForm.pressure">
                                                <label for="pressure"></label>
                                                <small></small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Índice de tratamiento de Vertimientos</td>
                                            <td colspan="2"> 
                                                <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="discharge_treatment_index" id="discharge_treatment_index" v-model="scope.dataForm.discharge_treatment_index">
                                                <label for="discharge_treatment_index"></label>
                                                <small></small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Toneladas DBO Removidas</td>
                                            <td colspan="2"> 
                                                <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="tons_bbo_removed" id="tons_bbo_removed" v-model="scope.dataForm.tons_bbo_removed">
                                                <label for="tons_bbo_removed"></label>
                                                <small></small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Toneladas SST Removidas</td>
                                            <td colspan="2"> 
                                                <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="tons_sst_removed" id="tons_sst_removed" v-model="scope.dataForm.tons_sst_removed">
                                                <label for="tons_sst_removed"></label>
                                                <small></small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Índice de Reclamación Operativa</td>
                                            <td colspan="2"> 
                                                <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="operational_claim_index" id="operational_claim_index" v-model="scope.dataForm.operational_claim_index">
                                                <label for="operational_claim_index"></label>
                                                <small></small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Índice de Reclamación Comercial</td>
                                            <td colspan="2"> 
                                                <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="commercial_claim_index" id="commercial_claim_index" v-model="scope.dataForm.commercial_claim_index">
                                                <label for="commercial_claim_index"></label>
                                                <small></small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Eficiencia en el recaudo</td>
                                            <td colspan="2"> 
                                                <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="efficiency_collection" id="efficiency_collection" v-model="scope.dataForm.efficiency_collection">
                                                <label for="efficiency_collection"></label>
                                                <small></small>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <!-- end panel-body -->
                </div>

                <div class="panel" data-sortable-id="ui-general-1">
                    <!-- begin panel-heading -->
                    <div class="panel-heading ui-sortable-handle">
                        <h4 class="panel-title"><strong>Fuentes de Inversión:</strong></h4>
                    </div>
                    <!-- end panel-heading -->
                    <!-- begin panel-body -->
                    <div class="panel-body">
                        <div class="row">

                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td>Fuente</td>
                                            <td colspan="2">Si / No</td>
                                        </tr>
                                        <tr>
                                            <td>Vía Tarifas de Acueducto y Alcantarillado</td>
                                            <td colspan="2"> 
                                                <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="via_aqueduct_sewerage_rates" id="via_aqueduct_sewerage_rates" v-model="scope.dataForm.via_aqueduct_sewerage_rates">
                                                <label for="via_aqueduct_sewerage_rates"></label>
                                                <small></small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Recursos tarifa de Aseo</td>
                                            <td colspan="2"> 
                                                <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="cleaning_fee_resources" id="cleaning_fee_resources" v-model="scope.dataForm.cleaning_fee_resources">
                                                <label for="cleaning_fee_resources"></label>
                                                <small></small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Regalías</td>
                                            <td colspan="2"> 
                                                <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="regalias" id="regalias" v-model="scope.dataForm.regalias">
                                                <label for="regalias"></label>
                                                <small></small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Sistema General de Participación</td>
                                            <td colspan="2"> 
                                                <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="general_participation_system" id="general_participation_system" v-model="scope.dataForm.general_participation_system">
                                                <label for="general_participation_system"></label>
                                                <small></small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Entidad descentralizada</td>
                                            <td colspan="2"> 
                                                <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="decentralized_entity" id="decentralized_entity" v-model="scope.dataForm.decentralized_entity">
                                                <label for="decentralized_entity"></label>
                                                <small></small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Capital aportado Bajo Condición</td>
                                            <td colspan="2"> 
                                                <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="capital_contributed" id="capital_contributed" v-model="scope.dataForm.capital_contributed">
                                                <label for="capital_contributed"></label>
                                                <small></small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Capital aportado Entidades Oficiales o Territoriales </td>
                                            <td colspan="2"> 
                                                <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="contributed_capital_official" id="contributed_capital_official" v-model="scope.dataForm.contributed_capital_official">
                                                <label for="contributed_capital_official"></label>
                                                <small></small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Aportes de Capital</td>
                                            <td colspan="2"> 
                                                <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="capital_contributions" id="capital_contributions" v-model="scope.dataForm.capital_contributions">
                                                <label for="capital_contributions"></label>
                                                <small></small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Aportes de Terceros</td>
                                            <td colspan="2"> 
                                                <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="third_party_contributions" id="third_party_contributions" v-model="scope.dataForm.third_party_contributions">
                                                <label for="third_party_contributions"></label>
                                                <small></small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Deuda a nivel Nacional</td>
                                            <td colspan="2"> 
                                                <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="national_debt" id="national_debt" v-model="scope.dataForm.national_debt">
                                                <label for="national_debt"></label>
                                                <small></small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Deuda a nivel Extranjero</td>
                                            <td colspan="2"> 
                                                <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="foreign_debt" id="foreign_debt" v-model="scope.dataForm.foreign_debt">
                                                <label for="foreign_debt"></label>
                                                <small></small>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <!-- end panel-body -->
                </div>

                <div class="panel" data-sortable-id="ui-general-1">
                    <!-- begin panel-heading -->
                    <div class="panel-heading ui-sortable-handle">
                        <h4 class="panel-title"><strong>Datos del estudio soporte:</strong></h4>
                    </div>
                    <!-- end panel-heading -->
                    <!-- begin panel-body -->
                    <div class="panel-body">
                        <div class="row">

                            <dynamic-list
                                label-button-add="Agregar ítem a la lista"
                                :data-list.sync="scope.dataForm.supporting_study_data"
                                :data-list-options="[
                                    {label:'Nombre', name:'name', isShow: true},
                                    {label:'Fecha del estudio', name:'study_date', isShow: true},
                                    {label:'Autor', name:'author', isShow: true},
                                    {label:'Estado', name:'state', isShow: true, refList: 'stateRef'},
                                    {label:'Lugar de almacenamiento', name:'storage_place', isShow: true},
                                    {label:'Tipo de estudio soporte', name:'support_study_type', isShow: true, refList: 'support_study_typeRef'},
                                    {label:'Tipo de estudio soporte', name:'product_consultancy', isShow: true, refList: 'product_consultancyRef'},
                                ]"
                                class-container="col-md-12"
                                class-table="table table-bordered"
                                >
                                <template #fields="scope">
                                <div class="row">

                                    <div class="col-md-6">
                                        <!-- Name Field -->
                                        <div class="form-group row m-b-15">
                                            {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                            <div class="col-md-9">
                                                {!! Form::text('name', null, ['class' => 'form-control', 'v-model' => 'scope.dataForm.name', 'required' => true]) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Study Date Field -->
                                        <div class="form-group row m-b-15">
                                            {!! Form::label('study_date', trans('Study date').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                            <div class="col-md-9">
                                                <date-picker
                                                    :value="scope.dataForm"
                                                    name-field="study_date"
                                                    :input-props="{required: true}"
                                                >
                                                </date-picker>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Author Field -->
                                        <div class="form-group row m-b-15">
                                            {!! Form::label('author', trans('Author').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                            <div class="col-md-9">
                                                {!! Form::text('author', null, ['class' => 'form-control', 'v-model' => 'scope.dataForm.author', 'required' => true]) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- State Field -->
                                        <div class="form-group row m-b-15">
                                            {!! Form::label('state', trans('State').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                            <div class="col-md-9">
                                            
                                                <select-check
                                                    css-class="form-control"
                                                    name-field="state"
                                                    reduce-label="name"
                                                    reduce-key="id"
                                                    name-resource="get-constants-active/state_type_investment_tariff_harmonization"
                                                    :value="scope.dataForm"
                                                    ref-select-check="stateRef"
                                                    :is-required="true">
                                                </select-check>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Storage Place Field -->
                                        <div class="form-group row m-b-15">
                                            {!! Form::label('storage_place', trans('Storage place').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                            <div class="col-md-9">
                                                {!! Form::text('storage_place', null, ['class' => 'form-control', 'v-model' => 'scope.dataForm.storage_place', 'required' => true]) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Support Study Type Field -->
                                        <div class="form-group row m-b-15">
                                            {!! Form::label('support_study_type', trans('Support study type').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                            <div class="col-md-9">
                                            
                                                <select-check
                                                    css-class="form-control"
                                                    name-field="support_study_type"
                                                    reduce-label="name"
                                                    reduce-key="id"
                                                    name-resource="get-constants-active/support_study_type_tariff_harmonization"
                                                    :value="scope.dataForm"
                                                    ref-select-check="support_study_typeRef"
                                                    :is-required="true">
                                                </select-check>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Product of a consultancy Field -->
                                        <div class="form-group row m-b-15">
                                            {!! Form::label('product_consultancy', trans('Product of a consultancy').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                            <div class="switcher col-md-9 m-t-5">
                                                <select v-model="scope.dataForm.product_consultancy" name="product_consultancy" id="product_consultancy" class="form-control" ref="product_consultancyRef" required>
                                                    <option value="Sí">Sí</option>
                                                    <option value="No">No</option>
                                                </select>
                                                <!-- <input type="checkbox" name="product_consultancy" id="product_consultancy" :value="false" v-model="scope.dataForm.product_consultancy" >
                                                <label for="product_consultancy"></label> -->
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                </template>
                            </dynamic-list>
                        </div>
                    </div>
                    <!-- end panel-body -->
                </div>

                <div class="panel" data-sortable-id="ui-general-1">
                    <!-- begin panel-heading -->
                    <div class="panel-heading ui-sortable-handle">
                        <h4 class="panel-title"><strong>Selección de alternativas:</strong></h4>
                    </div>
                    <!-- end panel-heading -->
                    <!-- begin panel-body -->
                    <div class="panel-body">
                        <div class="row">

                            <dynamic-list
                                label-button-add="Agregar ítem a la lista"
                                :data-list.sync="scope.dataForm.selection_alternatives"
                                :data-list-options="[
                                    {label:'Nombre de la alternativa', name:'alternative_name', isShow: true},
                                    {label:'Descripción', name:'description', isShow: true},
                                    {label:'Seleccionada', name:'selected', isShow: true, refList: 'selectedRef'},
                                ]"
                                class-container="col-md-12"
                                class-table="table table-bordered"
                                >
                                <template #fields="scope">
                                <div class="row">

                                    <div class="col-md-6">
                                        <!-- Alternative Name Field -->
                                        <div class="form-group row m-b-15">
                                            {!! Form::label('alternative_name', trans('Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                            <div class="col-md-9">
                                                {!! Form::text('alternative_name', null, ['class' => 'form-control', 'v-model' => 'scope.dataForm.alternative_name', 'required' => true]) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Description  Field -->
                                        <div class="form-group row m-b-15">
                                            {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                            <div class="col-md-9">
                                                {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '5', 'v-model' => 'scope.dataForm.description', 'required' => true]) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Selected Field -->
                                        <div class="form-group row m-b-15">
                                            {!! Form::label('selected', trans('Selected').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                            <div class="switcher col-md-9 m-t-5">
                                                <select v-model="scope.dataForm.selected" name="selected" id="selected" class="form-control" ref="selectedRef" required>
                                                    <option value="Sí">Sí</option>
                                                    <option value="No">No</option>
                                                </select>
                                                <!-- <input type="checkbox" name="selected" id="selected" :value="false" v-model="scope.dataForm.selected" >
                                                <label for="selected"></label> -->
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                </template>
                            </dynamic-list>

                        </div>
                    </div>
                    <!-- end panel-body -->
                </div>

                <div class="panel" data-sortable-id="ui-general-1">
                    <!-- begin panel-heading -->
                    <div class="panel-heading ui-sortable-handle">
                        <h4 class="panel-title"><strong>Beneficios del proyecto:</strong></h4>
                    </div>
                    <!-- end panel-heading -->
                    <!-- begin panel-body -->
                    <div class="panel-body">
                        <div class="row">

                            <div class="col-md-6">
                                <!-- social  Field -->
                                <div class="form-group row m-b-15">
                                    {!! Form::label('social', trans('Social').':', ['class' => 'col-form-label col-md-3']) !!}
                                    <div class="col-md-9">
                                        {!! Form::textarea('social', null, ['class' => 'form-control', 'rows' => '5', 'v-model' => 'scope.dataForm.social']) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Environmental  Field -->
                                <div class="form-group row m-b-15">
                                    {!! Form::label('environmental', trans('Environmental').':', ['class' => 'col-form-label col-md-3']) !!}
                                    <div class="col-md-9">
                                        {!! Form::textarea('environmental', null, ['class' => 'form-control', 'rows' => '5', 'v-model' => 'scope.dataForm.environmental']) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Economical  Field -->
                                <div class="form-group row m-b-15">
                                    {!! Form::label('economical', trans('Economical').':', ['class' => 'col-form-label col-md-3']) !!}
                                    <div class="col-md-9">
                                        {!! Form::textarea('economical', null, ['class' => 'form-control', 'rows' => '5', 'v-model' => 'scope.dataForm.economical']) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Jobs To Generate Field -->
                                <div class="form-group row m-b-15">
                                    {!! Form::label('jobs_to_generate', trans('Jobs to generate').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                    <div class="col-md-9">
                                        {!! Form::textarea('jobs_to_generate', null, ['class' => 'form-control', 'rows' => '5', 'v-model' => 'scope.dataForm.jobs_to_generate', 'required' => true]) !!}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- end panel-body -->
                </div>

            </template>
        </dynamic-modal-form>
        <!-- end #modal-form-information-tariff-harmonization send -->

        <!-- begin #modal-form-environmental-impacts send -->
        <dynamic-modal-form
            modal-id="environmental-impacts"
            size-modal="lg"
            title="Impactos ambientales"
            :data-form.sync="dataForm"
            endpoint="environmental-impacts"
            @saved="dataList.unshift($event); $forceUpdate()"
            >
            <template #fields="scope">

                <div class="panel" data-sortable-id="ui-general-1">
                    <!-- begin panel-heading -->
                    <div class="panel-heading ui-sortable-handle">
                        <h4 class="panel-title"><strong>Impactos Ambientales generados por el desarrollo del proyecto:</strong></h4>
                    </div>
                    <!-- end panel-heading -->
                    <!-- begin panel-body -->
                    <div class="panel-body">
                        <div class="row">
                            <dynamic-list
                                label-button-add="Agregar ítem a la lista"
                                :data-list.sync="scope.dataForm.environmental_impacts"
                                :data-list-options="[
                                    {label:'Componente ambiental', name:'environmental_component', isShow: true, refList: 'environmental_componentRef'},
                                    {label:'Descripción', name:'impact_description', isShow: true},
                                ]"
                                class-container="col-md-12"
                                class-table="table table-bordered"
                                >
                                <template #fields="scope">
                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Environmental component Field -->
                                        <div class="form-group row m-b-15">
                                            {!! Form::label('environmental_component', trans('Environmental component').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                            <div class="col-md-9">
                                                <select-check
                                                    css-class="form-control"
                                                    name-field="environmental_component"
                                                    reduce-label="name"
                                                    reduce-key="id"
                                                    name-resource="get-constants-active/environmental_component"
                                                    :value="scope.dataForm"
                                                    ref-select-check="environmental_componentRef"
                                                    :is-required="true">
                                                </select-check>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Impact Description  Field -->
                                        <div class="form-group row m-b-15">
                                            {!! Form::label('impact_description', trans('Description').':', ['class' => 'col-form-label col-md-3']) !!}
                                            <div class="col-md-9">
                                                {!! Form::textarea('impact_description', null, ['class' => 'form-control', 'rows' => '5', 'v-model' => 'scope.dataForm.impact_description']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </template>
                            </dynamic-list>

                            <div class="col-md-6">
                                <!-- Requires Environmental License Field -->
                                <div class="form-group row m-b-15">
                                    {!! Form::label('requires_environmental_license', trans('Requires environmental license').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                    <div class="switcher col-md-9 m-t-5">
                                        <select v-model="scope.dataForm.requires_environmental_license" name="requires_environmental_license" id="requires_environmental_license" class="form-control" required>
                                            <option value="Sí">Sí</option>
                                            <option value="No">No</option>
                                        </select>
                                        <small></small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- License Number Field -->
                                <div class="form-group row m-b-15">
                                    {!! Form::label('license_number', trans('License number').':', ['class' => 'col-form-label col-md-3']) !!}
                                    <div class="col-md-9">
                                        {!! Form::text('license_number', null, ['class' => 'form-control', 'v-model' => 'scope.dataForm.license_number']) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Expedition Date Field -->
                                <div class="form-group row m-b-15">
                                    {!! Form::label('expedition_date', trans('Expedition date').':', ['class' => 'col-form-label col-md-3']) !!}
                                    <div class="col-md-9">
                                        <date-picker
                                            :value="scope.dataForm"
                                            name-field="expedition_date"
                                            :input-props="{required: false}"
                                        >
                                        </date-picker>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- end panel-body -->
                </div>

            </template>
        </dynamic-modal-form>
        <!-- end #modal-form-environmental-impacts send -->

        <alternative-investment-budget ref="alternative-investment"></alternative-investment-budget>

        <!-- begin #modal-form-alternative-budget send -->
        <dynamic-modal-form
            modal-id="alternative-budget1"
            size-modal="lg"
            title="Presupuesto alternativo"
            :data-form.sync="dataForm"
            endpoint="alternative-budget"
            @saved="dataList.unshift($event); $forceUpdate()"
            >
            <template #fields="scope">
                <div class="panel" data-sortable-id="ui-general-1">
                    <!-- begin panel-heading -->
                    <div class="panel-heading ui-sortable-handle">
                        <h4 class="panel-title"><strong>Presupuesto Alternativa Seleccionada para la vigencia actual:</strong></h4>
                    </div>
                    <!-- end panel-heading -->
                    <!-- begin panel-body -->
                    <div class="panel-body">
                        <div class="row">
                            <dynamic-list
                                label-button-add="Agregar ítem a la lista"
                                :data-list.sync="scope.dataForm.alternative_budgets"
                                :data-list-options="[
                                    {label:'Descripción', name:'description', isShow: true},
                                    {label:'Unidad', name:'unit', isShow: true},
                                    {label:'Cantidad', name:'quantity', isShow: true},
                                    {label:'V/r Unitario', name:'unit_value', isShow: true},
                                    {label:'V/r Total', name:'total_value', isShow: true},
                                ]"
                                class-container="col-md-12"
                                class-table="table table-bordered"
                                >
                                <template #fields="scope">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <!-- Impact Description Field -->
                                            <div class="form-group row m-b-15">
                                                {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-3']) !!}
                                                <div class="col-md-9">
                                                    {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '5', 'v-model' => 'scope.dataForm.description']) !!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <!-- Unit Field -->
                                            <div class="form-group row m-b-15">
                                                {!! Form::label('unit', trans('Unit').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                                <div class="col-md-9">
                                                    {!! Form::number('unit', null, ['class' => 'form-control', 'v-model' => 'scope.dataForm.unit', 'required' => true]) !!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <!-- Quantity Field -->
                                            <div class="form-group row m-b-15">
                                                {!! Form::label('quantity', trans('Quantity').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                                <div class="col-md-9">
                                                    {!! Form::number('quantity', null, ['class' => 'form-control', 'v-model' => 'scope.dataForm.quantity', 'required' => true]) !!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <!-- Unit value Field -->
                                            <div class="form-group row m-b-15">
                                                {!! Form::label('unit_value', trans('Unit value').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                                <div class="col-md-9">
                                                    <currency-input
                                                        v-model="scope.dataForm.unit_value"
                                                        required="true"
                                                        :currency="{'prefix': '$ '}"
                                                        locale="es"
                                                        class="form-control"
                                                        :key="keyRefresh"
                                                        >
                                                    </currency-input>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <!-- Total value Field -->
                                            <div class="form-group row m-b-15">
                                                {!! Form::label('total_value', trans('Total value').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                                <div class="col-md-9">
                                                    <currency-input
                                                        v-model="scope.dataForm.total_value"
                                                        required="true"
                                                        :currency="{'prefix': '$ '}"
                                                        locale="es"
                                                        class="form-control"
                                                        :key="keyRefresh"
                                                        >
                                                    </currency-input>
                                                </div>
                                            </div>
                                        </div>

                                        

                                    </div>
                                </template>
                            </dynamic-list>

                            <div class="col-md-6">
                                <!-- Administration Field -->
                                <div class="form-group row m-b-15">
                                    {!! Form::label('administration', trans('Administration').':', ['class' => 'col-form-label col-md-3']) !!}
                                    <div class="col-md-9">
                                        <currency-input
                                            v-model="scope.dataForm.administration"
                                            required="true"
                                            :currency="{'prefix': '$ '}"
                                            locale="es"
                                            class="form-control"
                                            :key="keyRefresh"
                                            >
                                        </currency-input>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Unforeseen Field -->
                                <div class="form-group row m-b-15">
                                    {!! Form::label('unforeseen', trans('Unforeseen').':', ['class' => 'col-form-label col-md-3']) !!}
                                    <div class="col-md-9">
                                        <currency-input
                                            v-model="scope.dataForm.unforeseen"
                                            required="true"
                                            :currency="{'prefix': '$ '}"
                                            locale="es"
                                            class="form-control"
                                            :key="keyRefresh"
                                            >
                                        </currency-input>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Utilities Field -->
                                <div class="form-group row m-b-15">
                                    {!! Form::label('utilities', trans('Utilities').':', ['class' => 'col-form-label col-md-3']) !!}
                                    <div class="col-md-9">
                                        <currency-input
                                            v-model="scope.dataForm.utilities"
                                            required="true"
                                            :currency="{'prefix': '$ '}"
                                            locale="es"
                                            class="form-control"
                                            :key="keyRefresh"
                                            >
                                        </currency-input>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- VAT Field -->
                                <div class="form-group row m-b-15">
                                    {!! Form::label('vat', trans('VAT').':', ['class' => 'col-form-label col-md-3']) !!}
                                    <div class="col-md-9">
                                        <currency-input
                                            v-model="scope.dataForm.vat"
                                            required="true"
                                            :currency="{'prefix': '$ '}"
                                            locale="es"
                                            class="form-control"
                                            :key="keyRefresh"
                                            >
                                        </currency-input>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Others Field -->
                                <div class="form-group row m-b-15">
                                    {!! Form::label('others', trans('Others').':', ['class' => 'col-form-label col-md-3']) !!}
                                    <div class="col-md-9">
                                        <currency-input
                                            v-model="scope.dataForm.others"
                                            required="true"
                                            :currency="{'prefix': '$ '}"
                                            locale="es"
                                            class="form-control"
                                            :key="keyRefresh"
                                            >
                                        </currency-input>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- end panel-body -->
                </div>
            </template>
        </dynamic-modal-form>
        <!-- end #modal-form-lternative-budget send -->


        <!-- begin #modal-form-chronograms send -->
        <dynamic-modal-form
            modal-id="chronograms"
            size-modal="xl"
            title="Cronogramas"
            :data-form.sync="dataForm"
            endpoint="chronograms"
            :key="keyRefresh"
            @saved="dataList.unshift($event); $forceUpdate()"
            >
            <template #fields="scope">

                @include('contractual_process::investment_technical_sheets.fields_cronograms')

            </template>
        </dynamic-modal-form>
        <!-- end #modal-form-chronograms send -->

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
    $('#modal-form-investment-technical-sheets').on('keypress', ( e ) => {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    } );
</script>
@endpush
