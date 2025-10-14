@extends('layouts.default')

@section('title', 'Rutina de ensayo')

@section('section_img', '/assets/img/components/Recepción_de_muestras.png')

@section('menu')
    @include('leca::layouts.menu_rutina')
@endsection

@section('content')

    <crud name="Recepcion-de-muestras" :resource="{ default: 'list-ensayos-rutina', get: 'get-sample-rutina' }"
        inline-template>
        <div>
            <!-- begin breadcrumb -->
            <ol class="breadcrumb m-b-10">
                <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            </ol>
            <!-- end breadcrumb -->


            <!-- begin widget -->

            <div class="row">

                <widget-counter-funtional icon="fa fa-book-open" bg-color="#17a2b8"
                    :qty="dataList.filter((data) => data.estado_analisis == 'Análisis pendiente').length"
                    title="Análisis pendiente" title-link-see-more="Filtrar" status="Análisis pendiente" :value="searchFields"
                    name-field="estado_analisis" {{-- link-see-more="javascript:checkData('1')" --}}></widget-counter-funtional>

                <widget-counter-funtional icon="fa fa-book-open" bg-color="#ffa500"
                    :qty="dataList.filter((data) => data.estado_analisis == 'Análisis en ejecución').length"
                    title="Análisis en ejecución" title-link-see-more="Filtrar" status="Análisis en ejecución"
                    :value="searchFields" name-field="estado_analisis" {{-- link-see-more="javascript:checkData('1')" --}}></widget-counter-funtional>

                <widget-counter-funtional icon="fa fa-book-open" bg-color="#27C44F"
                    :qty="dataList.filter((data) => data.estado_analisis == 'Análisis finalizado').length"
                    title="Análisis finalizado" title-link-see-more="Filtrar" status="Análisis finalizado"
                    :value="searchFields" name-field="estado_analisis" {{-- link-see-more="javascript:checkData('1')" --}}></widget-counter-funtional>
            </div>

            <div class="mt-3 mb-3">
                @if (!Auth::user()->hasRole('Administrador Leca'))
                    @if (count($ensayos) > 0)
                        <h4 class="text-danger"><b>Rutina:</b></h4>
                        @foreach ($ensayos as $ensayo)
                        @php
                        $fecha = date("d-m-y");
                        @endphp
                        @if($fecha > \Carbon\Carbon::parse($ensayo->lcMonthlyRoutines->routine_end_date)->format('d-m-Y'));
                        @else
                        <br><br>
                        <label style="font-size: 15px"><b>Fecha de inicio:</b>
                            {{ \Carbon\Carbon::parse($ensayo->lcMonthlyRoutines->routine_start_date)->format('d-m-Y') }}</label><br>
                        <label style="font-size: 15px"><b>Fecha de cierre:</b>
                            {{ \Carbon\Carbon::parse($ensayo->lcMonthlyRoutines->routine_end_date)->format('d-m-Y') }}</label><br>
                        <label style="font-size: 15px"><b>Ensayos asignados: &nbsp &nbsp</b></label>

                        @foreach ($ensayo->lcListTrials as $item)
                            <label style="font-size: 15px"> &nbsp - {{ $item->name }}&nbsp </label>
                        @endforeach
                        @endif
                        @endforeach
                    @else
                        <h2 class="text-danger text-center align-items-center justify-content-center"><b>No tiene una rutina
                                asignada</b></h2>
                    @endif
                @endif
            </div>

            <div class="m-t-20 mt-3">

                <button @click="clearDataSearch()" class="btn btn-primary m-b-10">@lang('clear_search_fields')</button>

                <a href="javascript:location.reload()" class="btn btn-primary m-b-10"> <i class="fas fa-sync mr-2"></i>
                    Actualizar página</a>
                @if (!Auth::user()->hasRole('Administrador Leca'))
                    @if (count($ensayos) > 0)
                        <a :href="'{!! url('leca/list-ensayos-relacionados') !!}'" class="btn btn-primary m-b-10" data-toggle="tooltip"
                            data-placement="top" title="Historial"><i class="fa fa-plus mr-3"></i> Empezar rutina de
                            ensayo</a>
                    @endif
                @else
                    <a :href="'{!! url('leca/list-ensayos-relacionados') !!}'" class="btn btn-primary m-b-10" data-toggle="tooltip"
                        data-placement="top" title="Historial"><i class="fa fa-plus mr-3"></i> Ver rutinas de ensayo</a>
                @endif

            </div>
            <!-- begin panel -->
            <div class="panel panel-default">
                <div class="panel-heading border-bottom">
                    <div class="panel-title">
                        <h5 class="text-center"> @{{ `@lang('total_registers') muestras recepcionadas: ${dataPaginator.total}` | capitalize }}</h5>
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
                                    <date-picker :value="searchFields" name-field="created_at"
                                        :input-props="{ required: true }">

                                    </date-picker>
                                    <small>Filtro por fecha toma de la muestra</small>
                                </div>

                                <div class="col-md-4">
                                    <date-picker :value="searchFields" name-field="reception_date"
                                        :input-props="{ required: true }">

                                    </date-picker>
                                    <small>Filtro por fecha de recepción de la muestra</small>
                                </div>

                                <div class="col-md-4">
                                    <select-check css-class="form-control" name-field="lc_sample_points_id"
                                        :reduce-label="['id', 'point_location']" reduce-key="id"
                                        name-resource="get-point-all" :value="searchFields" :is-required="true">
                                    </select-check>
                                    <small>Filtro por punto de la toma</small>
                                </div>


                                <div class="col-md-4">
                                    {!! Form::number('sample_reception_code', null, [
                                        'v-model' => 'searchFields.sample_reception_code',
                                        'class' => 'form-control',
                                        'placeholder' => trans('crud.filter_by', ['name' => 'Identificación de la muestra']),
                                    ]) !!}
                                </div>

                                <div class="col-md-4">
                                    {!! Form::select(
                                        'type_water',
                                        ['Cruda' => 'Cruda', 'Tratada' => 'Tratada', 'De proceso' => 'De proceso'],
                                        null,
                                        ['v-model' => 'searchFields.type_water', 'class' => 'form-control'],
                                    ) !!}
                                    <small>Filtro por tipo agua</small>
                                </div>

                                <div class="col-md-4">
                                    {!! Form::select(
                                        'estado_analisis',
                                        [
                                            'Análisis pendiente' => 'Análisis pendiente',
                                            'Análisis en ejecución' => 'Análisis en ejecución',
                                            'Análisis finalizado' => 'Análisis finalizado',
                                        ],
                                        null,
                                        ['v-model' => 'searchFields.estado_analisis', 'class' => 'form-control'],
                                    ) !!}
                                    <small>Filtro por estado de la muestra</small>
                                </div>

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
                    <div class="float-xl-right m-b-15">
                        <!-- Acciones para exportar datos de tabla-->
                        <div class="btn-group">
                            <a href="javascript:;" data-toggle="dropdown" class="btn btn-primary"><i
                                    class="fa fa-download mr-2"></i> @lang('export_data_table')</a>
                            <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle"
                                aria-expanded="false"><b class="caret"></b></a>
                            <div class="dropdown-menu dropdown-menu-right bg-blue">
                                {{-- <a href="javascript:;" @click="exportDataTable('pdf')" class="dropdown-item text-white no-hover"><i class="fa fa-file-pdf mr-2"></i> PDF</a> --}}
                                <a href="javascript:;" @click="exportDataTable('xlsx')"
                                    class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i>
                                    EXCEL</a>

                                {{-- <a href="javascript:;" @click="exportDataTable('pdf')"
                                    class="dropdown-item text-white no-hover"><i class="fa fa-file-pdf mr-2"></i> PDF</a> --}}
                            </div>
                        </div>
                    </div>
                    <!-- end buttons action table -->
                    @include('leca::rutina_ensayo.table')
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

            <!-- begin #modal-view-Toma-de-muestra -->
            <div class="modal fade" id="modal-view-Toma-de-muestra">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('info_of') @lang('Toma-de-muestra')</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                    class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" id="contentDetalles">
                            @include('leca::rutina_ensayo.show_fields')
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-warning" type="button" onclick="printContent('contentDetalles');"><i
                                    class="fa fa-print mr-2"></i>@lang('print')</button>
                            <button class="btn btn-white" data-dismiss="modal"><i
                                    class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end #modal-view-Toma-de-muestra -->


            <!-- En este modal se ve el historial del registro -->
            <div class="modal fade" id="modal-history-sample-taking">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">Seguimiento y control</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                    class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="row justify-content-center">
                                @include('leca::rutina_ensayo.show_history')
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-white" data-dismiss="modal"><i
                                    class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                        </div>
                    </div>
                </div>
            </div>

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
        $('#modal-form-sampleTakings').on('keypress', ':input:not(textarea):not([type=submit])', function(e) {
            if (e.keyCode === 13) {
                e.preventDefault();
            }
        });

        // Función para imprimir el contenido de un identificador pasado por parámetro
        function printContent(divName) {
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
            // Se esperan 10 milésimas de segundos para imprimir el contenido de la pestaña nueva
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 90);
        }
    </script>
@endpush
