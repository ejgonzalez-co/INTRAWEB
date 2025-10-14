@extends('layouts.default')

@section('title', trans('listTrials'))

@section('section_img', '/assets/img/components/Análisis_de_muestras.png')

@section('menu')
    @include('leca::layouts.menu_ensayo_relacionado')
@endsection

@section('content')

    <crud name="listTrials"
        :resource="{ default: 'list-ensayos-relacionados', get: 'get-all-ensayos-relacionados', show: 'get-generalities-ensayos' }"
        :update-table="true" inline-template>
        <div>
            <!-- begin breadcrumb -->
            <ol class="breadcrumb m-b-10">
                <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
                <li class="breadcrumb-item active">@lang('listTrials')</li>
            </ol>
            <!-- end breadcrumb -->

            <!-- begin page-header -->
            <h1 class="page-header text-center m-b-35">Vista principal de los ensayos relacionados a su rutina</h1>
            <!-- end page-header -->

            <!-- begin main buttons -->
            {{-- <div class="m-t-20">
            <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-listTrials" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('listTrials')
            </button>
        </div> --}}
            {{-- <button  type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-configurar-general" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>Configurar blanco y patrón
            </button> --}}
            <!-- end main buttons -->
            <a href="javascript:location.reload()" class="btn btn-primary m-b-10"> <i class="fas fa-sync mr-2"></i> Actualizar
                página</a>
            <!-- begin panel -->
            <div class="panel panel-default">
                <div class="panel-heading border-bottom">
                    <div class="panel-title">
                        <h5 class="text-center"> @{{ `@lang('total_registers') @lang('listTrials'): ${dataPaginator.total}` | capitalize }}</h5>
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
                                <div class="col-md-4">
                                    {!! Form::text('name', null, [
                                        'v-model' => 'searchFields.name',
                                        'class' => 'form-control',
                                        'placeholder' => trans('crud.filter_by', ['name' => trans('Nombre')]),
                                    ]) !!}
                                </div>
                                <div class="col-md-4">
                                    {!! Form::text('name', null, [
                                        'v-model' => 'searchFields.code',
                                        'class' => 'form-control',
                                        'placeholder' => trans('crud.filter_by', ['name' => trans('Código')]),
                                    ]) !!}
                                </div>

                                <div class="col-md-4">
                                    {!! Form::text('name', null, [
                                        'v-model' => 'searchFields.type',
                                        'class' => 'form-control',
                                        'placeholder' => trans('crud.filter_by', ['name' => trans('Tipo')]),
                                    ]) !!}
                                </div>
                            </div>
                            <div class="row form-group">

                                <div class="col-md-4">
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
                    @include('leca::EnsayoRelacionado.table')
                </div>
                <!-- ver detalles espectro -->
                <div class="modal fade" id="modal-view-espectro">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content border-0">
                            <div class="modal-header bg-blue">
                                <h4 class="modal-title text-white">@lang('info_of') @lang('listTrials')</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                        class="fa fa-times text-white"></i></button>
                            </div>
                            <div class="modal-body" id="showFields">
                                @include('leca::EnsayoRelacionado.show_fields')
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-warning" type="button" onclick="printContent('showFields');"><i
                                        class="fa fa-print mr-2"></i>@lang('print')</button>
                                <button class="btn btn-white" data-dismiss="modal"><i
                                        class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ver detalles lectura directa -->
                <div class="modal fade" id="modal-view-lectura">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content border-0">
                            <div class="modal-header bg-blue">
                                <h4 class="modal-title text-white">@lang('info_of') @lang('listTrials')</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                        class="fa fa-times text-white"></i></button>
                            </div>
                            <div class="modal-body" id="showFields">
                                @include('leca::EnsayoRelacionado.show_lectura')
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-warning" type="button" onclick="printContent('showFields');"><i
                                        class="fa fa-print mr-2"></i>@lang('print')</button>
                                <button class="btn btn-white" data-dismiss="modal"><i
                                        class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ver detalles Turbidez -->
                <div class="modal fade" id="modal-view-turbiedad">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content border-0">
                            <div class="modal-header bg-blue">
                                <h4 class="modal-title text-white">@lang('info_of') @lang('listTrials')</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                        class="fa fa-times text-white"></i></button>
                            </div>
                            <div class="modal-body" id="showFields">
                                @include('leca::EnsayoRelacionado.show_turbiedad')
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-warning" type="button" onclick="printContent('showFields');"><i
                                        class="fa fa-print mr-2"></i>@lang('print')</button>
                                <button class="btn btn-white" data-dismiss="modal"><i
                                        class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ver detalles Alcalinidad -->
                <div class="modal fade" id="modal-view-alcalinidad">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content border-0">
                            <div class="modal-header bg-blue">
                                <h4 class="modal-title text-white">@lang('info_of') @lang('listTrials')</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                        class="fa fa-times text-white"></i></button>
                            </div>
                            <div class="modal-body" id="showFields">
                                @include('leca::EnsayoRelacionado.show_alcalinidad')
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-warning" type="button" onclick="printContent('showFields');"><i
                                        class="fa fa-print mr-2"></i>@lang('print')</button>
                                <button class="btn btn-white" data-dismiss="modal"><i
                                        class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ver detalles cloruro -->
                <div class="modal fade" id="modal-view-cloruro">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content border-0">
                            <div class="modal-header bg-blue">
                                <h4 class="modal-title text-white">@lang('info_of') @lang('listTrials')</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                        class="fa fa-times text-white"></i></button>
                            </div>
                            <div class="modal-body" id="showFields">
                                @include('leca::EnsayoRelacionado.show_cloruro')
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-warning" type="button" onclick="printContent('showFields');"><i
                                        class="fa fa-print mr-2"></i>@lang('print')</button>
                                <button class="btn btn-white" data-dismiss="modal"><i
                                        class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ver detalles cloruro -->
                <div class="modal fade" id="modal-view-sulfato">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content border-0">
                            <div class="modal-header bg-blue">
                                <h4 class="modal-title text-white">@lang('info_of') @lang('listTrials')</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                        class="fa fa-times text-white"></i></button>
                            </div>
                            <div class="modal-body" id="showFields">
                                @include('leca::EnsayoRelacionado.show_sulfato')
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-warning" type="button" onclick="printContent('showFields');"><i
                                        class="fa fa-print mr-2"></i>@lang('print')</button>
                                <button class="btn btn-white" data-dismiss="modal"><i
                                        class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ver detalles calcio -->
                <div class="modal fade" id="modal-view-calcio">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content border-0">
                            <div class="modal-header bg-blue">
                                <h4 class="modal-title text-white">@lang('info_of') @lang('listTrials')</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                        class="fa fa-times text-white"></i></button>
                            </div>
                            <div class="modal-body" id="showFields">
                                @include('leca::EnsayoRelacionado.show_calcio')
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-warning" type="button" onclick="printContent('showFields');"><i
                                        class="fa fa-print mr-2"></i>@lang('print')</button>
                                <button class="btn btn-white" data-dismiss="modal"><i
                                        class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Ver detalles dureza -->
                <div class="modal fade" id="modal-view-dureza">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content border-0">
                            <div class="modal-header bg-blue">
                                <h4 class="modal-title text-white">@lang('info_of') @lang('listTrials')</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                        class="fa fa-times text-white"></i></button>
                            </div>
                            <div class="modal-body" id="showFields">
                                @include('leca::EnsayoRelacionado.show_dureza')
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-warning" type="button" onclick="printContent('showFields');"><i
                                        class="fa fa-print mr-2"></i>@lang('print')</button>
                                <button class="btn btn-white" data-dismiss="modal"><i
                                        class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ver detalles cloro -->
                <div class="modal fade" id="modal-view-cloro">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content border-0">
                            <div class="modal-header bg-blue">
                                <h4 class="modal-title text-white">@lang('info_of') @lang('listTrials')</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                        class="fa fa-times text-white"></i></button>
                            </div>
                            <div class="modal-body" id="showFields">
                                @include('leca::EnsayoRelacionado.show_cloro')
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-warning" type="button" onclick="printContent('showFields');"><i
                                        class="fa fa-print mr-2"></i>@lang('print')</button>
                                <button class="btn btn-white" data-dismiss="modal"><i
                                        class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ver detalles acidez -->
                <div class="modal fade" id="modal-view-acidez">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content border-0">
                            <div class="modal-header bg-blue">
                                <h4 class="modal-title text-white">@lang('info_of') @lang('listTrials')</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                        class="fa fa-times text-white"></i></button>
                            </div>
                            <div class="modal-body" id="showFields">
                                @include('leca::EnsayoRelacionado.show_acidez')
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-warning" type="button" onclick="printContent('showFields');"><i
                                        class="fa fa-print mr-2"></i>@lang('print')</button>
                                <button class="btn btn-white" data-dismiss="modal"><i
                                        class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ver detalles fluoruro -->
                <div class="modal fade" id="modal-view-fluoruro">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content border-0">
                            <div class="modal-header bg-blue">
                                <h4 class="modal-title text-white">@lang('info_of') @lang('listTrials')</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                        class="fa fa-times text-white"></i></button>
                            </div>
                            <div class="modal-body" id="showFields">
                                @include('leca::EnsayoRelacionado.show_fluoruro')
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-warning" type="button" onclick="printContent('showFields');"><i
                                        class="fa fa-print mr-2"></i>@lang('print')</button>
                                <button class="btn btn-white" data-dismiss="modal"><i
                                        class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ver detalles micro -->
                <div class="modal fade" id="modal-view-micro">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content border-0">
                            <div class="modal-header bg-blue">
                                <h4 class="modal-title text-white">@lang('info_of') @lang('listTrials')</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                        class="fa fa-times text-white"></i></button>
                            </div>
                            <div class="modal-body" id="showFields">
                                @include('leca::EnsayoRelacionado.show_micro')
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-warning" type="button" onclick="printContent('showFields');"><i
                                        class="fa fa-print mr-2"></i>@lang('print')</button>
                                <button class="btn btn-white" data-dismiss="modal"><i
                                        class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ver detalles solido -->
                <div class="modal fade" id="modal-view-solido">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content border-0">
                            <div class="modal-header bg-blue">
                                <h4 class="modal-title text-white">@lang('info_of') @lang('listTrials')</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                        class="fa fa-times text-white"></i></button>
                            </div>
                            <div class="modal-body" id="showFields">
                                @include('leca::EnsayoRelacionado.show_solido')
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-warning" type="button" onclick="printContent('showFields');"><i
                                        class="fa fa-print mr-2"></i>@lang('print')</button>
                                <button class="btn btn-white" data-dismiss="modal"><i
                                        class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ver detalles solido -->
                <div class="modal fade" id="modal-view-solido-secos">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content border-0">
                            <div class="modal-header bg-blue">
                                <h4 class="modal-title text-white">@lang('info_of') @lang('listTrials')</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                        class="fa fa-times text-white"></i></button>
                            </div>
                            <div class="modal-body" id="showFields">
                                @include('leca::EnsayoRelacionado.show_solido_secos')
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-warning" type="button" onclick="printContent('showFields');"><i
                                        class="fa fa-print mr-2"></i>@lang('print')</button>
                                <button class="btn btn-white" data-dismiss="modal"><i
                                        class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ver detalles solido -->
                <div class="modal fade" id="modal-view-lectura">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content border-0">
                            <div class="modal-header bg-blue">
                                <h4 class="modal-title text-white">@lang('info_of') @lang('listTrials')</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                        class="fa fa-times text-white"></i></button>
                            </div>
                            <div class="modal-body" id="showFields">
                                @include('leca::EnsayoRelacionado.show_lectura')
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-warning" type="button" onclick="printContent('showFields');"><i
                                        class="fa fa-print mr-2"></i>@lang('print')</button>
                                <button class="btn btn-white" data-dismiss="modal"><i
                                        class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- end #modal-view-listTrials -->
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


        </div>
    </crud>
@endsection

@push('css')
    <style>
        .fraction {
            display: inline-block;
            vertical-align: middle;
            margin: 0 0.2em 0.4ex;
            text-align: center;
        }

        .fraction>span {
            display: block;
            padding-top: 0.15em;
        }

        .fraction span.fdn {
            border-top: thin solid black;
        }

        .fraction span.bar {
            display: none;
        }

        .tableTH {
            text-align: center;
        }
    </style>
    {{-- {!!Html::style('assets/plugins/gritter/css/jquery.gritter.css')!!} --}}
@endpush

@push('scripts')
    {!! Html::script('assets/plugins/gritter/js/jquery.gritter.js') !!}
    <script>
        // detecta el enter para no cerrar el modal sin enviar el formulario
        $('#modal-form-listTrials').on('keypress', ':input:not(textarea):not([type=submit])', function(e) {
            if (e.keyCode === 13) {
                e.preventDefault();
            }
        });


        function printContent(divName) {

            // Se obtiene el elemento del id recibido por parámetro
            var printContent = document.getElementById(divName);
            // Se guarda en una variable la nueva pestaña
            var printWindow = window.open("");
            // Se obtiene el encabezado de la página actual para no perder estilos
            var headContent = document.getElementsByTagName('head')[0].innerHTML;
            // Se escribe todo el contenido del encabezado de la página actual en la pestaña nueva que se abrirá
            printWindow.document.write(headContent);
            // Se escribe todo el contenido del id recibido por parámetro en la pestaña nueva que se abrirá
            printWindow.document.write(printContent.innerHTML);
            printWindow.document.close();
            // Se enfoca en la pestaña nueva
            printWindow.focus();
            // Se esperan 10 milésimas de segundos para imprimir el contenido de la pestaña nueva
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 30);

        }
    </script>
@endpush
