@extends('layouts.default')

@section('title', trans('inventoryDocumentals'))

@section('section_img', '/assets/img/components/inventario_documents.png')

@section('menu')
    @include('documentaryclassification::layouts.menu')
@endsection

@section('content')

<crud
    name="inventoryDocumentals"
    :resource="{default: 'inventory-documentals', get: 'get-inventory-documentals'}"
    inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('inventoryDocumentals')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">@{{ '@lang('text_title') @lang('inventoryDocumentals')'}}
        </h1>
        {{-- <x-message></x-message> --}}
        <!-- end page-header -->



        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <b class="card-title">Filtrar por Oficina Productora, Serie o Subserie Documental</b>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group row m-b-15">
                                {!! Form::label('id_dependencias', trans('Oficina productora').':', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    <select-check
                                        css-class="form-select form-control row col-md-9"
                                        name-field="id_dependencias"
                                        :reduce-label="['nombre','codigo_oficina_productora']"
                                        reduce-key="id"
                                        name-resource="get-dependencias"
                                        :value="searchFields"
                                    >
                                    </select-check>
                                    <small class="text-muted">Selecciona la oficina productora.</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group row m-b-15">
                                {!! Form::label('id_dependencias', 'Serie o Subserie Documental:', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">
                                    <select-check
                                        css-class="form-select form-control row col-md-12"
                                        name-field="id_series_subseries"
                                        :reduce-label="['type','name','no_serieosubserie']"
                                        :name-resource="'get-series-subseries?query=all'"
                                        reduce-key="id_series_subseries"
                                        :value="searchFields"
                                        :is-required="true"
                                    >
                                    </select-check>
                                    <small class="text-muted">Selecciona la serie o subserie documental.</small>
                                </div>
                            </div>
                        </div>

                        <criterios-busqueda-save ref="criterios-busqueda-save" :id-serie="searchFields.id_series_subseries" filter="Si"></criterios-busqueda-save>


                    </div>
                    <div class="col-md-5">
                            <div class="form-group row m-b-15 d-flex align-items-center">
                                <button @click="callFunctionComponent('criterios-busqueda-save','clearDataSearch')" class="btn btn-md btn-primary">Limpiar</button>
                            </div>
                        </div>
                </div>
            </div>
        </div>



        <!-- begin main buttons -->
        <div class="m-t-20">
            @if(Auth::user()->hasRole("Gestión Documental Admin"))
                <button @click="add()" type="button" class="btn btn-primary " data-backdrop="static" data-target="#modal-form-inventoryDocumentals" data-toggle="modal">
                    <i class="fa fa-plus mr-2"></i>@lang('crud.create') un nuevo registro de inventario
                </button>
            @endif
            <button onclick="window.location=''" class="btn btn-md btn-primary"><i class="fa fa-redo-alt mr-2"></i>Cargar página de nuevo</button>

            <div class="float-xl-right m-b-15">
                <!-- Acciones para exportar datos de tabla-->
                <div class="btn-group">
                    <a href="javascript:;" data-toggle="dropdown" class="btn btn-primary"><i class="fa fa-download mr-2"></i> @lang('export_data_table')</a>
                    <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle" aria-expanded="false"><b class="caret"></b></a>
                    <div class="dropdown-menu dropdown-menu-right bg-blue">
                        <a href="javascript:;" @click="exportFullDataTableInventorReport('xlsx')" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i> Reporte de inventario</a>
                        <a href="javascript:;" @click="exportDataTable('xlsx')" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i>Reporte Excel</a>
                        <a href="javascript:;" @click="exportDataTableSecondView('xlsx')" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i>FUID</a>
                        {{-- <a href="export-exaple" class="dropdown-item text-white no-hover" target="_blank">EXCEL</a> --}}
                    </div>
                </div>
            </div>

            <br>
            <br>
        </div>


        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('inventoryDocumentals'): ${dataPaginator.total}` | capitalize }}</h5>
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
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <b class="card-title">Fechas</b>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <label for="date_initial" class="col-form-label"><b>Fecha de extracción (Inicial):</b></label>
                                                    <date-picker
                                                        :value="searchFields"
                                                        name-field="date_initial"
                                                        :input-props="{required: true}"
                                                        mode="single"
                                                    >
                                                    </date-picker>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="date_finish" class="col-form-label"><b>Fecha de extracción (Final):</b></label>
                                                    <date-picker
                                                        :value="searchFields"
                                                        name-field="date_finish"
                                                        :input-props="{required: true}"
                                                        mode="single"
                                                    >
                                                    </date-picker>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <b class="card-title">Clasificación y Soporte</b>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <label for="clasification" class="col-form-label"><b>Clasificación:</b></label>
                                                    {!! Form::select('clasification', ['Público' => 'Público', 'Clasificado' => 'Clasificado', 'Reservado' => 'Reservado'], null, ['class' => "form-control", 'v-model' => 'searchFields.clasification']) !!}
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="soport" class="col-form-label"><b>Soporte:</b></label>
                                                    {!! Form::select('soport', ['Físico' => 'Físico', 'Electrónico' => 'Electrónico', 'Físico y Electrónico' => 'Físico y Electrónico'], null, ['class' => "form-control", 'v-model' => 'searchFields.soport']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-4">
                                <div class="card-header bg-light">
                                    <b class="card-title">Descripción del expediente</b>
                                </div>
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label for="description_final" class="col-form-label"><b>Descripción del expediente:</b></label>
                                            {!! Form::text('description_final', null, ['class' => 'form-control', 'v-model' => 'searchFields.description_final']) !!}
                                        </div>
                                        <div class="col-md-12">
                                            <label for="observation" class="col-form-label"><b>Observación:</b></label>
                                            {!! Form::text('observation', null, ['class' => 'form-control', 'v-model' => 'searchFields.observation']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-4">
                                <div class="card-header bg-light">
                                    <b class="card-title">Ubicación y Metadatos</b>
                                </div>
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <label for="shelving" class="col-form-label"><b>Estantería:</b></label>
                                            {!! Form::text('shelving', null, ['class' => 'form-control', 'v-model' => 'searchFields.shelving', 'placeholder' => trans('crud.filter_by', ['name' => trans('Estantería')])]) !!}
                                        </div>
                                        <div class="col-md-4">
                                            <label for="tray" class="col-form-label"><b>Bandeja:</b></label>
                                            {!! Form::text('tray', null, ['class' => 'form-control', 'v-model' => 'searchFields.tray', 'placeholder' => trans('crud.filter_by', ['name' => trans('Bandeja')])]) !!}
                                        </div>
                                        <div class="col-md-4">
                                            <label for="box" class="col-form-label"><b>Caja:</b></label>
                                            {!! Form::text('box', null, ['class' => 'form-control', 'v-model' => 'searchFields.box', 'placeholder' => trans('crud.filter_by', ['name' => trans('Caja')])]) !!}
                                        </div>
                                    </div>
                                    <div class="form-row mt-3">
                                        <div class="col-md-4">
                                            <label for="file" class="col-form-label"><b>Carpeta:</b></label>
                                            {!! Form::text('file', null, ['class' => 'form-control', 'v-model' => 'searchFields.file', 'placeholder' => trans('crud.filter_by', ['name' => trans('Carpeta')])]) !!}
                                        </div>
                                        <div class="col-md-4">
                                            <label for="book" class="col-form-label"><b>Libro:</b></label>
                                            {!! Form::text('book', null, ['class' => 'form-control', 'v-model' => 'searchFields.book', 'placeholder' => trans('crud.filter_by', ['name' => trans('Libro')])]) !!}
                                        </div>
                                        <div class="col-md-4">
                                            <label for="metadatos" class="col-form-label"><b>Metadatos:</b></label>
                                            {!! Form::text('metadatos', null, ['class' => 'form-control', 'v-model' => 'searchFields.metadatos_text', 'placeholder' => trans('crud.filter_by', ['name' => trans('Metadatos')])]) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="col-md-4">
                            <button @click="clearDataSearch()" class="btn btn-md btn-primary">@lang('clear_search_fields')</button>
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
                @include('documentaryclassification::inventory_documentals.table')
            </div>
            <div class="p-b-15 text-center">
                <!-- Cantidad de elementos a mostrar -->
                <div class="form-group row m-5 col-md-3 text-center d-sm-inline-flex">
                    {!! Form::label('show_qty', trans('show_qty').':', ['class' => 'col-form-label col-md-7']) !!}
                    <div class="col-md-5">
                        {!! Form::select(trans('show_qty'), [5 => 5, 10 => 10, 15 => 15, 20 => 20, 25 => 25, 30 => 30, 50 => 50, 75 => 75], '5', ['class' => 'form-control', 'v-model' => 'dataPaginator.pagesItems']) !!}
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

        <!-- begin #modal-view-inventoryDocumentals -->
        <div class="modal fade" id="modal-view-inventoryDocumentals">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') @lang('inventoryDocumentals')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body" id="showFields">
                        @include('documentaryclassification::inventory_documentals.show_fields')
                    </div>
                    <div class="modal-footer">
                        {{-- <button class="btn btn-warning" type="button" onclick="printContentDetail('showFields');"><i class="fa fa-print mr-2"></i>@lang('print')</button> --}}
                        <button class="btn btn-warning" type="button" v-print="{id: 'showFields', beforeOpenCallback, openCallback, closeCallback}" :disabled="printOpened">
                            <i class="fa fa-print mr-2" v-if="!printOpened"></i>
                            <div class="spinner mr-2" style="position: sticky; float: inline-start; width: 18px; height: 18px; margin: auto;" v-else></div>
                            @lang('print')
                        </button>

                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-inventoryDocumentals -->

        <!-- begin #modal-form-inventoryDocumentals -->
        <div class="modal fade" id="modal-form-inventoryDocumentals">
            <div class="modal-dialog modal-lg">
                <form @submit.prevent="save()" id="form-inventoryDocumentals">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of') @lang('inventoryDocumentals')</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('documentaryclassification::inventory_documentals.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-inventoryDocumentals -->

        <metadatos-component inline-template ref="metadatos_ref">
            <!-- end #modal-form-MetaData -->
            <div class="modal fade" id="modal-form-metadatos">
                <div class="modal-dialog modal-xl">
                    <form @submit.prevent="save()" id="form-metadatos">
                        <div class="modal-content border-0">
                            <div class="modal-header bg-blue">
                                <h4 class="modal-title text-white">@lang('add-meta-data')</h4>
                                <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                            </div>
                            <div class="modal-body" >
                                @include('documentaryclassification::inventory_documentals.add_meta_data')
                            </div>
                            <div class="modal-footer">
                                <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- end #modal-form-MetaData -->
        </metadatos-component>
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
    $('#modal-form-inventoryDocumentals').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });

       // Función para imprimir el contenido de un identificador pasado por parámetro
   function printContentDetail(divName) {

        // Se obtiene el elemento del id recibido por parámetro
        var printContent = document.getElementById(divName);
        // Se guarda en una variable la nueva pestaña
        var printWindow = window.open("");
        // Se obtiene el encabezado de la página actual para no peder estilos
        var headContent = document.getElementsByTagName('head')[0].innerHTML;
        // Se escribe todo el contenido del encabezado de la página actual en la pestaña nueva que se abrirá
        printWindow.document.write(headContent);
        // Se escribe todo el contenido del id recibido por parámetro en la pestaña nueva que se abrirá
        printWindow.document.write(printContent.innerHTML);
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
