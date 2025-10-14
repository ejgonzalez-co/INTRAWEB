@extends('layouts.default')

@section('title', trans('startSamplings'))

@section('section_img', '/assets/img/components/pc_necesidades.png')

@section('menu')
    @include('leca::layouts.menu_start_samplings')
@endsection

@section('content')

<crud
    name="startSamplings"
    :resource="{default: 'start-samplings', get: 'get-start-samplings'}"
    :init-values="{lc_chlorine_residual_standards: []}"
    inline-template
    :update-table="true">
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('startSamplings')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">@{{ '@lang('general_sampling_information')'}}</h1>
        <!-- end page-header -->

        <!-- begin main buttons -->
        <div class="m-t-20">
            <a  href="javascript:location.reload()"  class="btn btn-primary m-b-10"> <i class="fas fa-sync mr-2"></i> Actualizar página</a>
            <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-startSamplings" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>@lang('start_sampling')
            </button>
            <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-migration" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>@lang('migration')
            </button>
        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('startSamplings'): ${dataPaginator.total}` | capitalize }}</h5>
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
                                <small>Filtar por fecha de la toma</small>
                            </div>
                            <div class="col-md-4">
                                {!! Form::text('name', null, ['v-model' => 'searchFields.user_name', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('responsable de la toma')]) ]) !!}
                            </div>
                            <div class="col-md-4">
                                {!! Form::select('type_customer',['Captacion'=>'Captacion','Distribucion'=>'Distribucion'], null, ['v-model' => 'searchFields.type_customer', 'class' => 'form-control']) !!}
                                <small>Filtro por tipo de cliente</small>
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
                {{-- <div class="float-xl-right m-b-15">
                    <!-- Acciones para exportar datos de tabla-->
                    <div class="btn-group">
                        <a href="javascript:;" data-toggle="dropdown" class="btn btn-primary"><i class="fa fa-download mr-2"></i> @lang('export_data_table')</a>
                        <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle" aria-expanded="false"><b class="caret"></b></a>
                        <div class="dropdown-menu dropdown-menu-right bg-blue">
                            <a href="javascript:;" @click="exportDataTable('pdf')" class="dropdown-item text-white no-hover"><i class="fa fa-file-pdf mr-2"></i> PDF</a>
                            <a href="javascript:;" @click="exportDataTable('xlsx')" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i> EXCEL</a>
                        </div>
                    </div>
                </div> --}}
                <!-- end buttons action table -->
                @include('leca::start_samplings.table')
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

        <!-- begin #modal-view-startSamplings -->
        <div class="modal fade" id="modal-view-startSamplings">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') @lang('startSamplings')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        @include('leca::start_samplings.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-startSamplings -->

        <!-- begin #modal-form-startSamplings -->
        <div class="modal fade" id="modal-form-startSamplings">
            <div class="modal-dialog modal-xl">
                <form @submit.prevent="save()" id="form-startSamplings">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of_startSamplings')</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('leca::start_samplings.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-startSamplings -->

        <!-- modal para la migracion de la muestra -->
        <dynamic-modal-form
        modal-id="modal-form-migration"
        size-modal="lg"
        :data-form="dataForm"
        title="Migrar toma de muestra"
        endpoint="migration-modal"
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
                        <a href="/storage/leca/documents_migration/Formato migracion de la toma de muestra.xlsx" target="_blank">Descargar formato para migración de la toma.</a>
                        <div class="form-group row m-b-15 mt-4">
                            {!! Form::label('file_import', trans('Adjuntar documento').':', ['class' => 'col-form-label col-md-3 required']) !!}
                            {!! Form::file('file_import', ['accept' => 'Application/all*', '@change' => 'inputFile($event, "file_import")', 'required' => true]) !!}
                        </div>
                    </div>
                </div>
            </div>
        </template>
        </dynamic-modal-form>
        <!-- modal para la migracion de la muestra -->


        <!--Aca es donde se ingresan los datos de la informacion final -->
        <dynamic-modal-form
        modal-id="modal-information-finish"
        size-modal="xl"
        :data-form="dataForm"
        :is-update="true"
        title="Formulario información final"
        endpoint="information-finish"
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
                        <h4 class="panel-title"><strong>Informacíón final</strong></h4>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Media y DPR (Diferencia porcentual relativa) -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('media_and_DPR', trans('measurement_and_drp').':', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">

                                    <input-operation tipo-operacion="concatena" :ruta="'get-consecutivo-toma/'+ scope.dataForm.id" name-field="media_and_DPR" :key="keyRefresh" :value="dataForm" operation="consulta" ></input-operation>

                                    <small>Ingrese la medida y DPR</small>
                                    <div class="invalid-feedback" v-if="dataErrors.media_and_DPR">
                                        <p class="m-b-0" v-for="error in dataErrors.media_and_DPR">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Valor de la muestra cloro residual -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('mean_chlorine_value', trans('value_chlorine_residual').':', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    <input-operation prefix=' ' operation="consulta" :cantidad-decimales="3" :ruta="'get-valor-toma/'+ scope.dataForm.id" name-field="mean_chlorine_value" :key="keyRefresh" :value="dataForm"  ></input-operation>
                                    <small>Ingrese el valor de la medida de cloro residual</small>
                                    <div class="invalid-feedback" v-if="dataErrors.mean_chlorine_value">
                                        <p class="m-b-0" v-for="error in dataErrors.mean_chlorine_value">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- (Dpr) Cloro residual -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('DPR_chlorine_residual', trans('dpr_cloro_residual').':', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    
                                    <input-operation suffix=' %' operation="consulta" :cantidad-decimales="2" :ruta="'get-valor-dpr/'+ scope.dataForm.id" name-field="DPR_chlorine_residual" :key="keyRefresh" :value="dataForm"  ></input-operation>
                                    <small>Ingrese (DPR) cloro residual</small>
                                    <div class="invalid-feedback" v-if="dataErrors.DPR_chlorine_residual">
                                        <p class="m-b-0" v-for="error in dataErrors.DPR_chlorine_residual">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>




                        <template v-if="dataForm.type_customer == 'Captacion'" class="">
                            <div class="col-md-6">
                                <!-- Fecha del ultimo ajuste pH -->
                                <div class="form-group row m-b-15">
                                    {!! Form::label('date_last_ph_adjustment', trans('Date_last_ph_adjustment').':', ['class' => 'col-form-label col-md-3']) !!}
                                    <div class="col-md-9">
                                        {{-- <date-picker
                                            :key="keyRefresh"
                                            :value="dataForm"
                                            name-field="date_last_ph_adjustment"
                                            :input-props="{required: flase}"
                                            disabled = 'true'
                                        >
                                        </date-picker> --}}
                                        {!! Form::date('date_last_ph_adjustment', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.date_last_ph_adjustment }", 'v-model' => 'dataForm.date_last_ph_adjustment', 'required' => false, 'disabled' => true]) !!}
                                        <small>Seleccione la fecha del ultimo ajuste de pH</small>
                                        <div class="invalid-feedback" v-if="dataErrors.date_last_ph_adjustment">
                                            <p class="m-b-0" v-for="error in dataErrors.date_last_ph_adjustment">@{{ error }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Pendiente -->
                                <div class="form-group row m-b-15">
                                    {!! Form::label('pending', trans('pending').':', ['class' => 'col-form-label col-md-3']) !!}
                                    <div class="col-md-9">
                                        {{-- {!! Form::text('pending', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.pending }", 'v-model' => 'dataForm.pending', 'required' => false, 'disabled' => true]) !!} --}}
                                        <currency-input
                                            required="false"
                                            v-model="dataForm.pending"
                                            :currency="{'suffix': ''}"
                                            locale="es"
                                            :precision="2"
                                            class="form-control"
                                            :key="keyRefresh"
                                            >
                                        </currency-input>
                                        <small>ingrese pendiente</small>
                                        <div class="invalid-feedback" v-if="dataErrors.pending">
                                            <p class="m-b-0" v-for="error in dataErrors.pending">@{{ error }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Asimetria (Cuando aplique) -->
                                <div class="form-group row m-b-15">
                                    {!! Form::label('asymmetry', trans('asymmetry').':', ['class' => 'col-form-label col-md-3']) !!}
                                   
                                    <div class="col-md-9">
                                        <currency-input
                                            required="false"
                                            v-model="dataForm.asymmetry"
                                            :currency="{'suffix': '°C'}"
                                            locale="es"
                                            :precision="2"
                                            class="form-control"
                                            :key="keyRefresh"
                                            >
                                        </currency-input>
                                        {{-- {!! Form::text('asymmetry', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.asymmetry }", 'v-model' => 'dataForm.asymmetry', 'required' => false, 'disabled' => true]) !!} --}}
                                        <small>ingrese asimetria</small>
                                        <div class="invalid-feedback" v-if="dataErrors.asymmetry">
                                            <p class="m-b-0" v-for="error in dataErrors.asymmetry">@{{ error }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>






                        <template v-else class="">
                            <div class="col-md-6">
                                <!-- Fecha del ultimo ajuste pH -->
                                <div class="form-group row m-b-15">
                                    {!! Form::label('date_last_ph_adjustment', trans('Date_last_ph_adjustment').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                    <div class="col-md-9">
                                        <date-picker
                                            :key="keyRefresh"
                                            :value="dataForm"
                                            name-field="date_last_ph_adjustment"
                                            :input-props="{required: true}"
                                        >
                                        </date-picker>
                                        <small>Seleccione la fecha del ultimo ajuste de pH</small>
                                        <div class="invalid-feedback" v-if="dataErrors.date_last_ph_adjustment">
                                            <p class="m-b-0" v-for="error in dataErrors.date_last_ph_adjustment">@{{ error }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Pendiente -->
                                <div class="form-group row m-b-15">
                                    {!! Form::label('pending', trans('pending').':', ['class' => 'col-form-label col-md-3']) !!}
                                    <div class="col-md-9">
                                        {{-- {!! Form::text('pending', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.pending }", 'v-model' => 'dataForm.pending', 'required' => false]) !!} --}}
                                        <currency-input
                                            required="false"
                                            v-model="dataForm.pending"
                                            :currency="{'suffix': ''}"
                                            locale="es"
                                            :precision="2"
                                            class="form-control"
                                            :key="keyRefresh"
                                            >
                                        </currency-input>
                                        <small>ingrese pendiente</small>
                                        <div class="invalid-feedback" v-if="dataErrors.pending">
                                            <p class="m-b-0" v-for="error in dataErrors.pending">@{{ error }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Asimetria (Cuando aplique) -->
                                <div class="form-group row m-b-15">
                                    {!! Form::label('asymmetry', trans('asymmetry').':', ['class' => 'col-form-label col-md-3']) !!}
                                    <div class="col-md-9">
                                        {{-- {!! Form::text('asymmetry', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.asymmetry }", 'v-model' => 'dataForm.asymmetry', 'required' => false]) !!} --}}
                                        <currency-input
                                            required="false"
                                            v-model="dataForm.asymmetry"
                                            :currency="{'suffix': ''}"
                                            locale="es"
                                            :precision="0"
                                            class="form-control"
                                            :key="keyRefresh"
                                            >
                                    </currency-input>
                                        <small>ingrese asimetria</small>
                                        <div class="invalid-feedback" v-if="dataErrors.asymmetry">
                                            <p class="m-b-0" v-for="error in dataErrors.asymmetry">@{{ error }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        
                    </div>
                    <!-- begin panel-heading -->
                    <div class="panel-heading ui-sortable-handle">
                        <h4 class="panel-title"><strong>Control de temperatura de transporte del agua</strong></h4>
                    </div>
                    <div class="row" style="margin-left: 2px">
                        <div class="col-md-6">
                            <!-- Environmental Conditions Field -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('digital_thermometer', trans('digital_thermometer').':', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    <div class="switcher">
                                        <input type="checkbox" name="digital_thermometer" id="digital_thermometer" v-model="dataForm.digital_thermometer" >
                                        <label for="digital_thermometer"></label>
                                    </div>
                                    <div class="invalid-feedback" v-if="dataErrors.digital_thermometer">
                                        <p class="m-b-0" v-for="error in dataErrors.digital_thermometer">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Cual -->
                            <div class="form-group row m-b-15"  v-if="openForm && dataForm.digital_thermometer">
                                {!! Form::label('which', trans('which').':', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('which', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.which }", 'v-model' => 'dataForm.which', 'required' => false]) !!}
                                    <small>ingrese el código de referencia del equipo ejemplo: 145 </small>
                                    <div class="invalid-feedback" v-if="dataErrors.which">
                                        <p class="m-b-0" v-for="error in dataErrors.which">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- llegada leca -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('arrival_LECA', trans('arrival_leca').':', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    <hour-military
                                    name-field="arrival_LECA"
                                    :value="dataForm">
                                    </hour-military>
                                    <small>Seleccione la llegada a las instalaciones de LECA.</small>
                                    <div class="invalid-feedback" v-if="dataErrors.arrival_LECA">
                                        <p class="m-b-0" v-for="error in dataErrors.arrival_LECA">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5" style="margin-left: 2px">
                        <div class="col-md-6">
                            <!-- llegada leca -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('salida_temperatura', trans('Salida de LECA').':', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    <currency-input  v-if="dataForm.type_customer == 'Captacion'"
                                    v-model="dataForm.salida_temperatura"
                                    required="false"
                                    :currency="{'suffix': ' °'}"
                                    locale="es"
                                    :precision="2"
                                    class="form-control"
                                    :key="keyRefresh"
                                    :disabled="true"
                                    >
                                    </currency-input>
                                    <currency-input  v-else
                                    v-model="dataForm.salida_temperatura"
                                    :currency="{'suffix': ' °'}"
                                    locale="es"
                                    :precision="2"
                                    class="form-control"
                                    :key="keyRefresh"
                                    >
                                    </currency-input>
                                    
                                    <small>Ingrese la temperatura de salida de LECA de la nevera.</small>
                                    <div class="invalid-feedback" v-if="dataErrors.salida_temperatura">
                                        <p class="m-b-0" v-for="error in dataErrors.salida_temperatura">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- llegada leca -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('llegada_temperatura', 'Llegada a LECA:', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    <currency-input  v-if="dataForm.type_customer == 'Distribución' "
                                    v-model="dataForm.llegada_temperatura"
                                    :currency="{'suffix': ' °'}"
                                    locale="es"
                                    :precision="2"
                                    class="form-control"
                                    :key="keyRefresh"
                                    >
                                    </currency-input>
                                    <currency-input  v-else
                                    v-model="dataForm.llegada_temperatura"
                                    required="true"
                                    :currency="{'suffix': ' °'}"
                                    locale="es"
                                    :precision="2"
                                    class="form-control"
                                    :key="keyRefresh"
                                    >
                                    </currency-input>
                                    <small>Ingrese la temperatura de llegada de LECA de la nevera leo.</small>
                                    <div class="invalid-feedback" v-if="dataErrors.llegada_temperatura">
                                        <p class="m-b-0" v-for="error in dataErrors.llegada_temperatura">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- begin panel-heading -->
                    <div class="panel-heading ui-sortable-handle">
                        <h4 class="panel-title"><strong>Notas</strong></h4>
                    </div>
                    <div class="row">
                        <div style="padding: 30px">
                            <div class="form-group row m-b-15">
                                <table border=4 style="margin-left: 300px">
                                    <tr>
                                        <th class="text-center">Notas</th>
                                    </tr>
                                    <tr>
                                        <td><strong>Nota (1):</strong>para la toma de muestras físico y químicas tomar un volumen de 1 <br> Litro por frasco (lleno completo) y microbiológicas 250 ml a 400 ml por <br> frasco, además, debe contener tiosulfato de sodio (agua tratada) y dejar una <br> cámara (espacio) de aire. <br>
                                        <br>
                                        <strong>Nota(2):</strong>SM: Standard Methods for the examination of water and <br> wastewater.
                                        <br>
                                        <br>
                                        <strong>Nota(3):</strong>para el procedimiento de pH la medición de cada ítem de ensayo <br> examinado se realiza por duplicado y la diferencia entre las dos mediciones <br> no puede superar 0.1 unidades de pH y se reportara el valor de la media (x) <br> (1) natural (N), tratada (T), de proceso aguas clarificadas o filtradas (P). 
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- observaciones -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('observations', trans('observations').':', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::textarea('observations', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observations }", 'v-model' => 'dataForm.observations', 'required' => false]) !!}
                                    <small>Ingresar observaciones</small>
                                    <div class="invalid-feedback" v-if="dataErrors.observations">
                                        <p class="m-b-0" v-for="error in dataErrors.observations">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Nombre responsable -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('user_name', trans('name_responsible').':', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    {{-- {!! Form::text('user_name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.user_name }", 'v-model' => 'dataForm.user_name', 'disabled', 'required' => false]) !!}
                                    <div class="invalid-feedback" v-if="dataErrors.user_name">
                                        <p class="m-b-0" v-for="error in dataErrors.user_name">@{{ error }}</p>
                                    </div> --}}
                                    <input class="form-control" type="text" disabled  placeholder="{{ Auth::user()->name }}">
                                    <input type="hidden" name="name_person_responsible" v-model="dataForm.name_person_responsible={{ Auth::user()->id }}" value="{{ Auth::user()->id }}">
                                    <small>Nombre responsable</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Testigo -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('witness', trans('witness').':', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('witness', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.witness }", 'v-model' => 'dataForm.witness', 'required' => false]) !!}
                                    <small>Nombre del testigo</small>
                                    <div class="invalid-feedback" v-if="dataErrors.witness">
                                        <p class="m-b-0" v-for="error in dataErrors.witness">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- begin panel-heading -->
                    <div class="panel-heading ui-sortable-handle">
                        <h4 class="panel-title"><strong>Convención</strong></h4>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row m-b-15">
                                <table border=4 style="margin-left: 300px">
                                    <tr>
                                        <td>
                                            <strong>Convención: F</strong>= físico, Q= químico, B= bacteriológico, MR= muestra regular, S=seguimiento, <br> ME= muestra especial, TM = toma de muestras, DPR (diferencia porcentual relativa). 
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- begin panel-heading -->
                    <div class="panel-heading ui-sortable-handle">
                        <h4 class="panel-title"><strong>Verificación de controles</strong></h4>
                    </div>
                    <!-- begin panel-heading -->
                    <div class="panel-heading ui-sortable-handle">
                        <h4 class="panel-title"><strong>Controles</strong></h4>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Estandar ph -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('standard_ph leo', trans('standard_ph').':', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    <currency-input
                                        required="false"
                                        v-model="dataForm.standard_ph"
                                        :currency="{'suffix': ''}"
                                        locale="es"
                                        :precision="2"
                                        class="form-control"
                                        :key="keyRefresh"
                                        >
                                    </currency-input>
                                    {{-- {!! Form::text('standard_ph', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.standard_ph }", 'v-model' => 'dataForm.standard_ph', 'required' => false]) !!} --}}
                                    <small>Ingrese el estandar de pH</small>
                                    <div class="invalid-feedback" v-if="dataErrors.standard_ph">
                                        <p class="m-b-0" v-for="error in dataErrors.standard_ph">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Inicial -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('initial', trans('initial').':', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    {{-- {!! Form::text('initial', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.initial }", 'v-model' => 'dataForm.initial', 'required' => false]) !!} --}}
                                    <currency-input
                                        required="false"
                                        v-model="dataForm.initial"
                                        :currency="{'suffix': ''}"
                                        locale="es"
                                        :precision="2"
                                        class="form-control"
                                        :key="keyRefresh"
                                        >
                                    </currency-input>
                                    <small>ingrese inicial</small>
                                    <div class="invalid-feedback" v-if="dataErrors.initial">
                                        <p class="m-b-0" v-for="error in dataErrors.initial">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Intermedia -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('intermediate', trans('intermediate').':', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    {{-- {!! Form::text('intermediate', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.intermediate }", 'v-model' => 'dataForm.intermediate', 'required' => false]) !!} --}}
                                    <currency-input
                                        required="false"
                                        v-model="dataForm.intermediate"
                                        :currency="{'suffix': ''}"
                                        locale="es"
                                        :precision="2"
                                        class="form-control"
                                        :key="keyRefresh"
                                        >
                                    </currency-input>
                                    <small>Ingrese la intermedia</small>
                                    <div class="invalid-feedback" v-if="dataErrors.intermediate">
                                        <p class="m-b-0" v-for="error in dataErrors.intermediate">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Final -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('end', trans('end').':', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    {{-- {!! Form::text('end', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.end }", 'v-model' => 'dataForm.end', 'required' => false]) !!} --}}
                                    <currency-input
                                        required="false"
                                        v-model="dataForm.end"
                                        :currency="{'suffix': ''}"
                                        locale="es"
                                        :precision="2"
                                        class="form-control"
                                        :key="keyRefresh"
                                        >
                                    </currency-input>
                                    <small>Ingrese el final</small>
                                    <div class="invalid-feedback" v-if="dataErrors.end">
                                        <p class="m-b-0" v-for="error in dataErrors.end">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Blanco de cloro residual -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('chlorine_residual_target', trans('chlorine_residual_target').':', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    {{-- {!! Form::text('chlorine_residual_target', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.chlorine_residual_target }", 'v-model' => 'dataForm.chlorine_residual_target', 'required' => false]) !!} --}}
                                    <currency-input
                                        required="false"
                                        v-model="dataForm.chlorine_residual_target"
                                        :currency="{'suffix': ''}"
                                        locale="es"
                                        :precision="2"
                                        class="form-control"
                                        :key="keyRefresh"
                                        >
                                    </currency-input>
                                    <small>Ingrese el blanco de cloro resiudal</small>
                                    <div class="invalid-feedback" v-if="dataErrors.chlorine_residual_target">
                                        <p class="m-b-0" v-for="error in dataErrors.chlorine_residual_target">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row m-b-15">
                                <dynamic-list 
                                    label-button-add="Agregar ítem a la lista" 
                                    :data-list.sync="dataForm.lc_chlorine_residual_standards"
                                    class-table="table-responsive table-bordered"
                                    class-container="w-100 p-10"
                                    :data-list-options="[
                                                    {label:'Patrón de cloro residual', name:'chlorine_residual', isShow: true}
                                                ]">
                                    <template #fields="scope">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <!-- Patrón de cloro residual -->
                                                <div class="form-group row m-b-15">
                                                    {!! Form::label('chlorine_residual', trans('residual_clorine').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                                    <div class="col-md-9">
                                                        <currency-input
                                                            required="true"
                                                            v-model="scope.dataForm.chlorine_residual"
                                                            :currency="{'suffix': ''}"
                                                            locale="es"
                                                            :precision="2"
                                                            class="form-control"
                                                            :key="keyRefresh"
                                                            >
                                                        </currency-input>
                                                        {{-- <input class="form-control" required type="text" v-model="scope.dataForm.chlorine_residual"> --}}
                                                        <small>Igrese el patron de cloro residual</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </dynamic-list>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row m-b-15">
                                <dynamic-list 
                                    label-button-add="Agregar ítem a la lista" 
                                    :data-list.sync="dataForm.lc_patron_ntu"
                                    class-table="table-responsive table-bordered"
                                    class-container="w-100 p-10"
                                    :data-list-options="[
                                                    {label:'Patrón (NTU) (TM)', name:'patron', isShow: true}
                                                ]">
                                    <template #fields="scope">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <!-- Patrón de cloro residual -->
                                                <div class="form-group row m-b-15">
                                                    {!! Form::label('patron', trans('Patrón (NTU) (TM)').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                                    <div class="col-md-9">
                                                        {{-- <input class="form-control" required type="text" v-model="scope.dataForm.patron"> --}}
                                                        <currency-input
                                                            required="true"
                                                            v-model="scope.dataForm.patron"
                                                            :currency="{'suffix': ''}"
                                                            locale="es"
                                                            :precision="2"
                                                            class="form-control"
                                                            :key="keyRefresh"
                                                            >
                                                    </currency-input>
                                                        <small>Igrese el patron de Turbidez</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </dynamic-list>
                            </div>
                        </div>
                    </div>

{{-- 
                    <!-- begin panel-heading -->
                    <div class="panel-heading ui-sortable-handle">
                        <h4 class="panel-title"><strong>Patrón (NTU) (TM)</strong></h4>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Patron inicial -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('initial_pattern', trans('initial').':', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('initial_pattern', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.initial_pattern }", 'v-model' => 'dataForm.initial_pattern', 'required' => false]) !!}
                                    <small>Ingrese inicial</small>
                                    <div class="invalid-feedback" v-if="dataErrors.initial_pattern">
                                        <p class="m-b-0" v-for="error in dataErrors.initial_pattern">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <!-- begin panel-heading -->
                    <div class="panel-heading ui-sortable-handle">
                        <h4 class="panel-title"><strong>Entrega de la muestra a conformidad a</strong></h4>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Nombre -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('name_delivery_conformity', trans('name').':', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    
                                    <autocomplete 
                                    :is-update="isUpdate"
                                    :value-default="dataForm.users_a"
                                    name-prop="name" 
                                    name-field="name_delivery_conformity" 
                                    :value="dataForm"
                                    name-resource="get-officials-sampling"
                                    css-class="form-control"
                                    :name-labels-display="['name']" 
                                    reduce-key="id" 
                                    :is-required="false"
                                    :key="keyRefresh">
                                    </autocomplete>
                                    <small>Ingrese el nombre del usuario encargado de recibir las muestras.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        </dynamic-modal-form>
        <!-- end modal informacion final -->
        <!-- En este modal se ve el historial del registro -->
        <div class="modal fade" id="modal-history-start-sampling">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Seguimiento y control</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-center">
                            @include('leca::start_samplings.show_history')
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-history-start-sampling -->
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
    $('#modal-form-startSamplings').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
