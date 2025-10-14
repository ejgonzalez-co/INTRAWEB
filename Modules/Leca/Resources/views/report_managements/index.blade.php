@extends('layouts.default')

@section('title', 'Gestión de informes')

@section('section_img', '/assets/img/components/periodico.png')

@section('menu')
    @include('leca::layouts.menu_report_management')
@endsection

@section('content')

    <crud name="reportManagements" :resource="{ default: 'report-managements', get: 'get-report-managements' }"
        inline-template>
        <div>
            <!-- begin breadcrumb -->
            <ol class="breadcrumb m-b-10">
                <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
                <li class="breadcrumb-item active">@lang('reportManagements')</li>
            </ol>
            <!-- end breadcrumb -->

            <!-- begin page-header -->
            <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('reportManagements')' }} </h1>
            <!-- end page-header -->

            <!-- begin main buttons -->
            <div class="m-t-20">
                <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static"
                    data-target="#modal-form-reportManagements" data-toggle="modal">
                    <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('reportManagements')
                </button>

                <button  type="button" class="btn btn-primary m-b-10" 
                    data-target="#daily-report"  data-toggle="modal">
                    <i class="fas fa-file-contract"></i>  Genererar reporte
                </button>
            </div>
            <!-- end main buttons -->

            <!-- begin panel -->
            <div class="panel panel-default">
                <div class="panel-heading border-bottom">
                    <div class="panel-title">
                        <h5 class="text-center"> @{{ `@lang('total_registers') @lang('reportManagements'): ${dataPaginator.total-2}` | capitalize }}</h5>
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
                        <i class="fa fa-search fa-fw mr-2 f-s-12"></i> <b>@{{ (showSearchOptions) ? 'trans.hide_search_options' : 'trans.show_search_options' | trans }}</b>
                    </div>
                    <div id="collapseOne" class="collapse border-bottom p-l-40 p-r-40" data-parent="#accordion">
                        <div class="card-body">
                            <label class="col-form-label"><b>@lang('quick_search')</b></label>
                            <!-- Campos de busqueda -->
                            <div class="row form-group">


                                <!-- filtro de busqueda por cliente -->
                                {{-- <div class="col-md-4 ">
                                <select-check
                                css-class="form-control"
                                name-field="name"
                                reduce-label="name"
                                reduce-key="id"
                                name-resource="get-customer"
                                :value="searchFields"
                                :is-required="false">
                                </select-check>
                                <small>Filtro por cliente</small>
                            </div> --}}

                                <!-- filtro de busqueda por fecha de toma de informe. -->
                                <div class="col-md-4">
                                    {!! Form::date('created_at', null, [
                                        'v-model' => 'searchFields.created_at',
                                        'class' => 'form-control',
                                        'placeholder' => trans('crud.filter_by', ['name' => trans('Created_at')]),
                                    ]) !!}
                                    <small>Filtrar por fecha de informe</small>
                                </div>

                                <div class="col-md-8">
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
                    @include('leca::report_managements.table')
                </div>
                <div class="p-b-15 text-center">
                    <!-- Cantidad de elementos a mostrar -->
                    <div class="form-group row m-5 col-md-3 text-center d-sm-inline-flex">
                        {!! Form::label('show_qty', trans('show_qty') . ':', ['class' => 'col-form-label col-md-7']) !!}
                        <div class="col-md-5">
                            {!! Form::select(
                                trans('show_qty'),
                                [5 => 5, 10 => 10, 15 => 15, 20 => 20, 25 => 25, 30 => 30, 50 => 50, 100 => 100, 200 => 200],
                                '5',
                                ['class' => 'form-control', 'v-model' => 'dataPaginator.pagesItems'],
                            ) !!}
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

            <!-- begin #modal-view-reportManagements -->
            {{-- <div class="modal fade" id="modal-view-reportManagements">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('info_of') @lang('reportManagements')</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                    class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body">
                            @include('leca::report_managements.show_fields')
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-white" data-dismiss="modal"><i
                                    class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                        </div>
                    </div>
                </div>
            </div> --}}
            <!-- end #modal-view-reportManagements -->

            <!-- begin #modal-form-reportManagements -->
            <div class="modal fade" id="modal-form-reportManagements">
                <div class="modal-dialog modal-lg">
                    <form @submit.prevent="save()" id="form-reportManagements">
                        <div class="modal-content border-0">
                            <div class="modal-header bg-blue">
                                <h4 class="modal-title text-white">@lang('form_of') @lang('reportManagements')</h4>
                                <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal"
                                    aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                            </div>
                            <div class="modal-body" v-if="openForm">
                                @include('leca::report_managements.fields')
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


            <!-- begin #modal-view-museum historial -->

            <div class="modal fade" id="modal-history">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">seguimiento y control</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                    class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body">
                            @include('leca::report_managements.show_history')
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-white" data-dismiss="modal"><i
                                    class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- end #modal-view-museum historial -->


            <!-- begin  #modal-form-state dimamyc-modal -->
            <dynamic-modal-form modal-id="modal-form-report-managements" size-modal="lg" 
                title="Estado del informe del cliente" :data-form.sync="dataForm" endpoint="change-status-report"
                :is-update="true" confirmation-message-saved = "Al cambiar el estado del informe, se enviará una notificación al correo del cliente con la credencial para que este pueda consultar el informe final."
                text="Desea continuar con el cambio de estado?"
                @saved="
                        if($event.isUpdate) {
                            assignElementList(dataForm.id, $event.data);
                        } else {
                            addElementToList($event.data);
                        }">

                <template #fields="scope">
                    <div class="panel" data-sortable-id="ui-general-1">
                        <!-- begin panel-body -->
                        <div class="panel-body ">

                            <!-- Status Event Field -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('status', trans('Estado del informe') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                                <div class="col-md-9">
                                    {!! Form::select(
                                        'type_of_request',
                                        ['Informe finalizado.' => 'Informe finalizado.'],
                                        null,
                                        [
                                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.type_of_request }",
                                            'v-model' => 'dataForm.status',
                                            'required' => true,
                                        ],
                                    ) !!}

                                    <small>@lang('Seleccione el') @{{ `@lang('estado del informe')` | lowercase }}</small>
                                    <div class="invalid-feedback" v-if="dataErrors.status">
                                        <p class="m-b-0" v-for="error in dataErrors.status">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end panel-body -->
                    </div>
                </template>
            </dynamic-modal-form>
            <!-- end #modal-form-state dimamyc-modal -->



             <!-- begin  #modal-form-report dimamyc-modal -->
            <create-report-form modal-id="daily-report" size-modal="lg"
                confirmation-message-saved="Generar"
                title="Generar reporte diario" :data-form.sync="dataForm" endpoint="get-daily-report"
                {{-- :is-update="false" --}}
                >

                <template #fields="scope">
                    <div class="panel" data-sortable-id="ui-general-1">
                        <!-- begin panel-body -->
                        <div class="panel-body ">

                            <!-- Status Event Field -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('event_date', trans('Fecha ').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                <div class="col-md-4">
                                    <date-picker
                                    :value="dataForm"
                                    name-field="event_date"
                                    :input-props="{required: true}">
                                    </date-picker>
                                    <small>Seleccione la fecha del reporte </small>
                                <div class="invalid-feedback" v-if="dataErrors.event_date">
                                <p class="m-b-0" v-for="error in dataErrors.event_date">@{{ error }}</p>
                            </div>
                        </div>

                            </div>
                            <!-- Status Event Field -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('status', 'Reporte' . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                                <div class="col-md-4">
                                    {!! Form::select(
                                        'report',
                                        ['14' => 'Informe LECA-R-014', '33' => 'Informe LECA-R-033'],
                                        null,
                                        [
                                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.type_of_request }",
                                            'v-model' => 'dataForm.report',
                                            'required' => true,
                                        ],
                                    ) !!}

                                    <small>@lang('Seleccione el') reporte que desea generar.</small>
                                    <div class="invalid-feedback" v-if="dataErrors.status">
                                        <p class="m-b-0" v-for="error in dataErrors.status">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <!-- end panel-body -->
                    </div>
                </template>
            </create-report-form>
         <!-- end #modal-form-report dimamyc-modal -->



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
        $('#modal-form-reportManagements').on('keypress', ':input:not(textarea):not([type=submit])', function(e) {
            if (e.keyCode === 13) {
                e.preventDefault();
            }
        });
    </script>
@endpush
