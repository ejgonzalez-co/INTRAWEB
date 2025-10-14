@extends('layouts.default')

@section('title', trans('Toma-de-muestra'))

@section('section_img', '/assets/img/components/Toma_de_muestras.png')

@section('menu')
    @include('leca::layouts.menu_sample_takings')
@endsection

@section('content')

<crud
    name="Toma-de-muestra"
    :resource="{default: 'sample-takings', get: 'get-sample-takings?lc_start_sampling_id={!! $lc_start_sampling_id !!}'}"
    :init-values="{lc_start_sampling_id: '{!! $lc_start_sampling_id ?? null !!}',
    lc_dynamic_ph_one_lists: [],
    lc_dynamic_ph_two_lists: [],
    lc_dynamic_ph_lists: [],
    lc_residual_chlorine_lists: []
    }"
    :update-table="true"
    inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('Toma-de-muestra')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('Toma-de-muestra')'}} </h1>
        <!-- end page-header -->

        <!-- begin main buttons -->
        <div class="m-t-20">
            <a  href="javascript:location.reload()"  class="btn btn-primary m-b-10"> <i class="fas fa-sync mr-2"></i> Actualizar página</a>

            <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-Toma-de-muestra" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>@lang('create_sample')
            </button>
            <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-migration-sample" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>@lang('migration')
            </button>
        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('Toma-de-muestra'): ${dataPaginator.total}` | capitalize }}</h5>
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
                                <date-picker
                                :value="searchFields"
                                name-field="created_at"
                                :input-props="{required: true}"
                            >
                            </date-picker>
                                <small>Filtro por fecha de la muestra</small>
                            </div>
                            <div class="col-md4">
                                <select-check 
                                css-class="form-control" 
                                name-field="lc_sample_points_id" 
                                :reduce-label="['id', 'point_location']"
                                reduce-key="id"
                                name-resource="get-point-all" 
                                :value="searchFields" 
                                :is-required="true">
                                </select-check>
                                <small>Filtro por punto de la toma</small>
                            </div>
                            <div class="col-md-4">
                                {!! Form::text('name', null, ['v-model' => 'searchFields.user_name', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('funcionario')]) ]) !!}
                            </div>
                            <div class="col-md-4">
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
                @include('leca::sample_takings.table')
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

        <!-- begin #modal-view-Toma-de-muestra -->
        <div class="modal fade" id="modal-view-Toma-de-muestra">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') @lang('Toma-de-muestra')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        @include('leca::sample_takings.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-Toma-de-muestra -->

        <!-- begin ##modal-view-qr -->
        <div class="modal fade" id="modal-view-qr">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Código QR</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body" id="showFields">
                        @include('leca::sample_takings.show_qr')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-warning" type="button" onclick="printContent('showFields');"><i class="fa fa-print mr-2"></i>@lang('print')</button>
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end ##modal-view-qr -->

        <!-- begin #modal-form-Toma-de-muestra -->
        <div class="modal fade" id="modal-form-Toma-de-muestra">
            <div class="modal-dialog modal-xl">
                <form @submit.prevent="save()" id="form-Toma-de-muestra">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of') @lang('startSamplings')</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('leca::sample_takings.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-Toma-de-muestra -->

        <!--Aca es donde se ingresan los datos extras que van en el codigo qr -->
        <dynamic-modal-form
        modal-id="modal-informatio-code-qr"
        size-modal="xl"
        :data-form="dataForm"
        :is-update="true"
        title="Formulario de identificación de la muestra"
        endpoint="information-qr"
        @saved="
        if($event.isUpdate) {
            assignElementList(dataForm.id, $event.data);
        } else {
            addElementToList($event.data);
        }"
        >
        <template #fields="scope">
            <div class="panel" data-sortable-id="ui-general-1">
                <div class="panel-body">
                    <!-- begin panel-heading -->
                    <div class="panel-heading ui-sortable-handle">
                        <h4 class="panel-title"><strong>Información de la muestra</strong></h4>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Fecha de la toma -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('created_at', trans('Lc Start Sampling Id').':', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('created_at', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.created_at }", 'v-model' => 'dataForm.created_at', 'disabled', 'required' => false]) !!}
                                    <small>Fecha de la muestra</small>
                                    <div class="invalid-feedback" v-if="dataErrors.created_at">
                                        <p class="m-b-0" v-for="error in dataErrors.created_at">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Hora de la toma -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('hour', trans('Hour').':', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('hour', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.hour }", 'v-model' => 'dataForm.hour', 'disabled', 'required' => false]) !!}
                                    <small>Hora</small>
                                    <div class="invalid-feedback" v-if="dataErrors.hour">
                                        <p class="m-b-0" v-for="error in dataErrors.hour">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Tipo de la toma -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('type_water', trans('Type Water').':', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('type_water', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.type_water }", 'v-model' => 'dataForm.type_water', 'disabled', 'required' => false]) !!}
                                    <small>Tipo de agua</small>
                                    <div class="invalid-feedback" v-if="dataErrors.type_water">
                                        <p class="m-b-0" v-for="error in dataErrors.type_water">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="panel">
                            <div class="panel-heading ui-sortable-handle">
                                <h4 class="panel-title"><strong>Ensayos</strong></h4>
                            </div>
                            <div class="panel-body">
                                <!-- Checks Roles -->
                                <select-check-leca
                                    css-class="custom-control-input"
                                    name-field="lc_list_trials" reduce-label="name"
                                    name-resource="get-list-trials-physicists" :value="dataForm"
                                    :is-check="true" :key="keyRefresh">
                                </select-check-leca>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-md-6">
                        <div class="panel">
                            <div class="panel-heading ui-sortable-handle">
                                <h4 class="panel-title"><strong>Quimicos</strong></h4>
                            </div>
                            <div class="panel-body">
                                <!-- Checks Roles -->
                                <select-check
                                    css-class="custom-control-input"
                                    name-field="lc_list_trials_two" reduce-label="name"
                                    name-resource="get-list-trials-chemists" :value="dataForm"
                                    :is-check="true" :key="keyRefresh">
                                </select-check>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>

            {{-- <div class="panel" data-sortable-id="ui-general-1">
                <div class="panel-body">
                    <!-- begin panel-heading -->
                    <div class="panel-heading ui-sortable-handle">
                        <h4 class="panel-title"><strong>Grupo de parámetros</strong></h4>
                    </div>
                    <div class="col-md-6">
                        <div class="panel">
                            <div class="panel-heading ui-sortable-handle">
                                <h4 class="panel-title"><strong>Fisicos</strong></h4>
                            </div>
                            <div class="panel-body">
                                <!-- Checks Ensayos fisicos -->
                                <select-check
                                    css-class="custom-control-input"
                                    name-field="lc_list_trials" reduce-label="name"
                                    name-resource="get-list-trials-physicists" :value="dataForm"
                                    :is-check="true" :key="keyRefresh">
                                </select-check>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="panel" data-sortable-id="ui-general-1">
                <div class="panel-body">
                    <!-- begin panel-heading -->
                    <div class="panel-heading ui-sortable-handle">
                        <h4 class="panel-title"><strong>Preservación</strong></h4>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Environmental Conditions Field -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('refrigeration', trans('refrigeration').':', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    <div class="switcher">
                                        <input type="checkbox" name="refrigeration" id="refrigeration" v-model="dataForm.refrigeration" >
                                        <label for="refrigeration"></label>
                                    </div>
                                    <div class="invalid-feedback" v-if="dataErrors.refrigeration">
                                        <p class="m-b-0" v-for="error in dataErrors.refrigeration">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row m-b-15">
                                {!! Form::label('hci', trans('5. HCI').':', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    <div class="switcher">
                                        <input type="checkbox" name="hci" id="hci" v-model="dataForm.hci" >
                                        <label for="hci"></label>
                                    </div>
                                    <div class="invalid-feedback" v-if="dataErrors.hci">
                                        <p class="m-b-0" v-for="error in dataErrors.hci">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Environmental Conditions Field -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('filtered_sample', trans('filtered_sample_text').':', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    <div class="switcher">
                                        <input type="checkbox" name="filtered_sample" id="filtered_sample" v-model="dataForm.filtered_sample" >
                                        <label for="filtered_sample"></label>
                                    </div>
                                    <div class="invalid-feedback" v-if="dataErrors.filtered_sample">
                                        <p class="m-b-0" v-for="error in dataErrors.filtered_sample">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Environmental Conditions Field -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('naoh', trans('6. NaOH').':', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    <div class="switcher">
                                        <input type="checkbox" name="naoh" id="naoh" v-model="dataForm.naoh" >
                                        <label for="naoh"></label>
                                    </div>
                                    <div class="invalid-feedback" v-if="dataErrors.naoh">
                                        <p class="m-b-0" v-for="error in dataErrors.naoh">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Environmental Conditions Field -->
                            <div class="form-group row m-b-15">
                                <label class="col-form-label col-md-3">3. HNO<sub>3</sub></label>
                                <div class="col-md-9">
                                    <div class="switcher">
                                        <input type="checkbox" name="hno3" id="hno3" v-model="dataForm.hno3" >
                                        <label for="hno3"></label>
                                    </div>
                                    <div class="invalid-feedback" v-if="dataErrors.hno3">
                                        <p class="m-b-0" v-for="error in dataErrors.hno3">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Environmental Conditions Field -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('acetate', trans('Acetate').':', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    <div class="switcher">
                                        <input type="checkbox" name="acetate" id="acetate" v-model="dataForm.acetate" >
                                        <label for="acetate"></label>
                                    </div>
                                    <div class="invalid-feedback" v-if="dataErrors.acetate">
                                        <p class="m-b-0" v-for="error in dataErrors.acetate">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Environmental Conditions Field -->
                            <div class="form-group row m-b-15">
                                <label class="col-form-label col-md-3">4. H<sub>2</sub>SO<sub>4</sub></label>
                                <div class="col-md-9">
                                    <div class="switcher">
                                        <input type="checkbox" name="h2so4" id="h2so4" v-model="dataForm.h2so4" >
                                        <label for="h2so4"></label>
                                    </div>
                                    <div class="invalid-feedback" v-if="dataErrors.h2so4">
                                        <p class="m-b-0" v-for="error in dataErrors.h2so4">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Environmental Conditions Field -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('ascorbic_acid', trans('ascorbic_acid_text').':', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    <div class="switcher">
                                        <input type="checkbox" name="ascorbic_acid" id="ascorbic_acid" v-model="dataForm.ascorbic_acid" >
                                        <label for="ascorbic_acid"></label>
                                    </div>
                                    <div class="invalid-feedback" v-if="dataErrors.ascorbic_acid">
                                        <p class="m-b-0" v-for="error in dataErrors.ascorbic_acid">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel" data-sortable-id="ui-general-1">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Codigo de la toma -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('sample_reception_code', trans('sample_code').':', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('sample_reception_code', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.sample_reception_code }", 'v-model' => 'dataForm.sample_reception_code', 'disabled', 'required' => false]) !!}
                                    <small>Código de muestra</small>
                                    <div class="invalid-feedback" v-if="dataErrors.sample_reception_code">
                                        <p class="m-b-0" v-for="error in dataErrors.sample_reception_code">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Nombre del responsable de la toma -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('user_name', trans('name_person_responsible').':', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('user_name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.user_name }", 'v-model' => 'dataForm.user_name', 'disabled', 'required' => false]) !!}
                                    <small>Nombre del responsable de la toma de muestra</small>
                                    <div class="invalid-feedback" v-if="dataErrors.user_name">
                                        <p class="m-b-0" v-for="error in dataErrors.user_name">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Cargo -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('charge', trans('Charge').':', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('charge', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.charge }", 'v-model' => 'dataForm.charge', 'disabled', 'required' => false]) !!}
                                    <small>Cargo</small>
                                    <div class="invalid-feedback" v-if="dataErrors.charge">
                                        <p class="m-b-0" v-for="error in dataErrors.charge">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Proceso -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('process', trans('Process').':', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('process', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.process }", 'v-model' => 'dataForm.process', 'disabled', 'required' => false]) !!}
                                    <small>Proceso</small>
                                    <div class="invalid-feedback" v-if="dataErrors.process">
                                        <p class="m-b-0" v-for="error in dataErrors.process">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        </dynamic-modal-form>
        <!-- end #modal-informatio-code-qr -->

        <!-- modal para la migracion de la muestra -->
        <dynamic-modal-form
        modal-id="modal-form-migration-sample"
        size-modal="lg"
        :data-form="dataForm"
        title="Migrar muestras de la toma"
        endpoint="migration-modal-sample"
        @saved="
        if($event.isUpdate) {
            assignElementList(dataForm.id, $event.data);
        } else {
            addElementToList($event.data);
        }"
        >
        <template #fields="scope">
            <div>
                <div class="panel mt-2" style="border: 200px; padding: 15px;">
                    <div>
                        <a href="/storage/leca/documents_migration/Formato migracion muestras de la toma.xlsx" target="_blank">Descargar formato para migración de las muestras de la toma.</a>
                        <div class="form-group row m-b-15 mt-4">
                            {!! Form::label('file_import_sample', trans('Adjuntar documento').':', ['class' => 'col-form-label col-md-3 required']) !!}
                            {!! Form::file('file_import_sample', ['accept' => 'Application/all*', '@change' => 'inputFile($event, "file_import_sample")', 'required' => true]) !!}
                        </div>
                    </div>
                </div>
            </div>
        </template>
        </dynamic-modal-form>
        <!-- modal para la migracion de la muestra -->

        <!-- En este modal se ve el historial del registro -->
        <div class="modal fade" id="modal-history-sample-taking">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Seguimiento y control</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-center">
                            @include('leca::sample_takings.show_history')
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-histori-sample-taking -->
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
    $('#modal-form-Toma-de-muestra').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });

    function printContent(divName) {

        //Crea Iframe para imprimir
        const iframe = document.createElement('iframe');

        // Proporciones del iframe
        iframe.style.height = 0;
        iframe.style.visibility = 'hidden';
        iframe.style.width = 0;

        //url de la imagen a imprimir
        var urlImagen = $("#image_qr").attr('src');
        // envia el contenido del iframe
        iframe.setAttribute('srcdoc', '<html><body><center><img src="'+urlImagen+'" width="140" height="130"></center></body></html>');
        document.body.appendChild(iframe);
        iframe.contentWindow.print();
        
        // Se obtiene el elemento del id recibido por parámetro
        // var printContent = document.getElementById(divName);
        // // Se guarda en una variable la nueva pestaña
        // var printWindow = window.open("");
        // // Se obtiene el encabezado de la página actual para no perder estilos
        // var headContent = document.getElementsByTagName('head')[0].innerHTML;
        // // Se escribe todo el contenido del encabezado de la página actual en la pestaña nueva que se abrirá
        // printWindow.document.write(headContent);
        // // Se escribe todo el contenido del id recibido por parámetro en la pestaña nueva que se abrirá
        // printWindow.document.write(printContent.innerHTML);
        // printWindow.document.close();
        // // Se enfoca en la pestaña nueva
        // printWindow.focus();
        // // Se esperan 10 milésimas de segundos para imprimir el contenido de la pestaña nueva
        // setTimeout(() => {
        //     printWindow.print();
        //     printWindow.close();
        // }, 5000);
    }
</script>
@endpush
