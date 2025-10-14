@extends('layouts.default')

@section('title', 'Correspondencia Enviada')

@section('section_img', '/assets/img/components/intranet_poll.png')

@section('menu')
    @include('correspondence::layouts.menu')
@endsection

@section('content')
    {{-- <!-- Valida si es un funcionario administrador o funcionario ordinario -->
    @if(Auth::user()->hasRole('Correspondencia Enviada Admin'))
        <iframe src="{{ config("app.url_joomla") }}/index.php?option=com_formasonline&formasonlineform=ExternaAdmin&adm=1&tmpl=component" frameborder="0" style="width: 100%; height: 82vh;"></iframe>
    @else
        <iframe src="{{ config("app.url_joomla") }}/index.php?option=com_formasonline&formasonlineform=ExternaAdmin&func=1&tmpl=component" frameborder="0" style="width: 100%; height: 82vh;"></iframe>
    @endif --}}

    <crud name="externals" :resource="{ default: 'externals', get: 'get-externals-repository?vigencia={!! $vigencia ?? null !!}&'}"
    :crud-avanzado="true"
        inline-template>
        <div>
            <!-- begin breadcrumb -->
            <ol class="breadcrumb m-b-10">
                <li class="breadcrumb-item"><a href="{!! url('/home') !!}">@lang('home')</a></li>
                <li class="breadcrumb-item active">Respositorio @lang('externals')</li>
            </ol>
            <!-- end breadcrumb -->

            <!-- begin page-header -->
            <h1 class="page-header text-center m-b-35"> @{{ '@lang('main_view_to_manage') el repositorio de correspondencias @lang('externals')' }}</h1>
            <!-- end page-header -->
            <div class="row">
                <div class="col-md-4">

                    {{-- {!! Form::text('documento', null, ['v-model' => 'searchFields.documento', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['documento' => trans('ejemplo')]) ]) !!} --}}
                    <select id="vigencia" v-model="dataForm.vigencia" value="2023" class="custom-select" name="vigencia" required>
                        <option value="" selected>Seleccione</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                        <option value="2022">2022</option>
                        <option value="2021">2021</option>
                        <option value="2020">2020</option>
                        <option value="2019">2019</option>
                        <option value="2018">2018</option>
                        <option value="2017">2017</option>
                        <option value="2016">2016</option>
                        <option value="2015">2015</option>
                    </select>
                    <small>Filtro por vigencia</small>
                </div>
                <div class="col-md-4" v-if="dataForm.vigencia">
                    <a :href="'repository-externals?vigencia='+dataForm.vigencia" style="button">
                    <button class="btn btn-primary text-white">filtrar vigencia</button>
                    </a>
                </div>
            </div>
            <br>



            <!-- begin panel -->
            <div class="panel panel-default">
                <div class="panel-heading border-bottom">
                    <div class="panel-title">
                        <h5 class="text-center"> @{{ `@lang('total_registers') @lang('externals'): ${dataPaginator.total}` | capitalize }}</h5>
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
                                        'v-model' => 'searchFields.consecutivo',
                                        'class' => 'form-control',
                                        '@keyup.enter' => 'pageEventActualizado(1)',
                                        'placeholder' => trans('crud.filter_by', ['name' => 'Consecutivo']),
                                    ]) !!}
                                </div>


                                <div class="col-md-4">
                                    {!! Form::text('name', null, [
                                        'v-model' => 'searchFields.asunto',
                                        'class' => 'form-control',
                                        'placeholder' => trans('crud.filter_by', ['name' => 'Título']),
                                        '@keyup.enter' => 'pageEventActualizado(1)'
                                    ]) !!}
                                </div>

                                <div class="col-md-4">
                                    {!! Form::select('estado', ['Elaboración'=>'Elaboración','Revisión'=>'Revisión','Aprobación'=>'Aprobación','Pendiente de firma'=>'Pendiente de firma','Devuelto para modificaciones'=>'Devuelto para modificaciones','Público' => 'Público'], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.estado }", 'v-model' => 'searchFields.estado']) !!}
                                    <small>Filtro por estado</small>
                                </div>

                                <div class="col-md-4">
                                    {!! Form::text('nombre_ciudadano', null, [
                                        'v-model' => 'searchFields.nombre_ciudadano',
                                        'class' => 'form-control',
                                        'placeholder' => 'Filtro por Ciudadano',
                                        '@keyup.enter' => 'pageEventActualizado(1)'
                                    ]) !!}
                                </div>

                                <div class="col-md-4">
                                    {!! Form::text('funcionario_remitente', null, [
                                        'v-model' => 'searchFields.funcionario_remitente',
                                        'class' => 'form-control',
                                        'placeholder' => trans('crud.filter_by', ['name' => 'Remitente']),
                                        '@keyup.enter' => 'pageEventActualizado(1)'
                                    ]) !!}
                                </div>

                                <div class="col-md-4">
                                    <select-check
                                        css-class="form-control"
                                        name-field="tipodoc"
                                        reduce-label="name"
                                        reduce-key="name"
                                        name-resource="get-external-types"
                                        :value="searchFields"
                                        :is-multiple="true"
                                        :enable-search="true">
                                     </select-check>
                                    <small>Filtro por tipo de documento</small>
                                </div>

                                <div class="col-md-4">
                                    <date-picker
                                        :value="searchFields"
                                        name-field="cf_created"
                                        mode="range"
                                        :input-props="{required: true}">
                                    </date-picker>
                                    <small>Filtrar por fecha de recepción</small>
                                </div>

                                <div class="col-md-4">
                                    <select-check css-class="form-control" name-field="dependencia_remitente"
                                    reduce-label="nombre"
                                    reduce-key="nombre"
                                        :value="searchFields" :is-required="true" :enable-search="true" :is-multiple="true" name-resource="/intranet/get-dependencies">
                                    </select-check>
                                    <small>Filtro por dependencias</small>
                                </div>



                                <div class="col-md-4">
                                    {!! Form::text('radicadopor', null, [
                                        'v-model' => 'searchFields.radicadopor',
                                        'class' => 'form-control',
                                        'placeholder' => trans('crud.filter_by', ['name' => 'Radicado por']),
                                        '@keyup.enter' => 'pageEventActualizado(1)'
                                    ]) !!}
                                </div>

                                <div class="col-md-4">

                                    <select name="canal"  class="form-control" v-model="searchFields.canal">
                                        <option value="Correo certificado">Correo certificado</option>
                                        <option value="Correo electrónico">Correo electrónico</option>
                                        <option value="Fax">Fax</option>
                                        <option value="Personal">Personal</option>
                                        <option value="Telefónico">Telefónico</option>
                                        <option value="Web">Web</option>
                                        <option value="Notificación por aviso">Notificación por aviso</option>
                                        <option value="Buzón de sugerencias">Buzón de sugerencias</option>
                                    </select>
                                    <small>Filtrar por canal</small>
                                </div>


                            </div>
                            <button @click="pageEventActualizado(1)" class="btn btn-md btn-primary"><i class="fa fa-search mr-2"></i>Buscar</button>
                            <button @click="clearDataSearchAvanzado()" class="btn btn-md btn-primary">@lang('clear_search_fields')</button>
                        </div>
                    </div>
                    <!-- end card search -->
                </div>
                <!-- end #accordion search -->
                <div class="panel-body">
                    <!-- begin buttons action table -->
                    <div class="float-xl-right m-b-15" v-if="dataList.length">
                    <!-- Acciones para exportar datos de tabla-->
                    <div class="btn-group">
                        <a href="javascript:;" data-toggle="dropdown" class="btn btn-primary"><i class="fa fa-download mr-2"></i> @lang('export_data_table')</a>
                        <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle" aria-expanded="false"><b class="caret"></b></a>
                        <div class="dropdown-menu dropdown-menu-right bg-blue">
                            <!-- {{-- <a href="javascript:;" @click="exportDataTable('pdf')" class="dropdown-item text-white no-hover"><i class="fa fa-file-pdf mr-2"></i> PDF</a> --}} -->
                            <a href="javascript:;" @click="exportRepositoryExternal('xlsx',{!! $vigencia ?? null !!})" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i> EXCEL</a>


                        </div>
                    </div>
                </div>

                <viewer-attachement :link-file-name="true" type="table" ref="viewerDocuments"></viewer-attachement>

                    <!-- end buttons action table -->
                    @include('correspondence::externals.table_repository')
                </div>
                <div class="p-b-15 text-center">
                    <!-- Cantidad de elementos a mostrar -->
                    <div class="form-group row m-5 col-md-3 text-center d-sm-inline-flex">
                        {!! Form::label('show_qty', trans('show_qty').':', ['class' => 'col-form-label col-md-7']) !!}
                        <div class="col-md-5">
                        {!! Form::select(trans('show_qty'), [5 => 5, 10 => 10, 15 => 15, 20 => 20, 25 => 25, 30 => 30, 50 => 50, 75 => 75], 20, ['class' => 'form-control', 'v-model' => 'dataPaginator.pagesItems', '@change' => 'pageEventActualizado(1)']) !!}
                        </div>
                    </div>
                    <!-- Paginador de tabla -->
                    <div class="col-md-12">
                        <paginate
                            v-model="dataPaginator.currentPage"
                            :page-count="dataPaginator.numPages"
                            :click-handler="pageEventActualizado"
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


            <!-- begin #modal-view-externals -->
            <div class="modal fade" id="modal-view-externals">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('info_of') correspondencia @lang('enviada')</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                    class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" id="showFields">
                            @include('correspondence::externals.show_fields_repository')
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-warning" type="button" onclick="printContentDetail('showFields');"><i
                                    class="fa fa-print mr-2"></i>@lang('print')</button>

                            <button class="btn btn-white" data-dismiss="modal"><i
                                    class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end #modal-view-externals -->

        </div>
    </crud>
@endsection

@push('css')
    {!! Html::style('assets/plugins/gritter/css/jquery.gritter.css') !!}
    <style>
        .oculto {
            display: none !important;
        }

        .visible {
            display: inline !important;
        }

        .vdr.active:before {
            outline: none !important;
        }
    </style>
@endpush

@push('scripts')
    {!! Html::script('assets/plugins/gritter/js/jquery.gritter.js') !!}
    <script>
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

        // Función para imprimir el contenido de un identificador pasado por parámetro
        function printContent(divName) {
            $("#btn-rotule-print").removeClass("visible").addClass("oculto");
            $("#btn-rotule-print-rotule").removeClass("visible").addClass("oculto");

            $("#attach").removeClass("visible").addClass("oculto");
            $("#attach-rotule").removeClass("visible").addClass("oculto");

            setTimeout(function() {

                $("#btn-rotule-print").removeClass("oculto").addClass("visible");
                $("#btn-rotule-print-rotule").removeClass("oculto").addClass("visible");

                $("#attach").removeClass("oculto").addClass("visible");
                $("#attach-rotule").removeClass("oculto").addClass("visible");

            }, 600);

            // Se obtiene el elemento del id recibido por parámetro
            var printContent = document.getElementById(divName);
            // Se guarda en una variable la nueva pestaña
            var printWindow = window.open('', 'PRINT', 'height=500,width=800');
            // Se obtiene el encabezado de la página actual para no peder estilos
            var headContent = document.getElementsByTagName('head')[0].innerHTML;
            // Se escribe todo el contenido del encabezado de la página actual en la pestaña nueva que se abrirá
            printWindow.document.write(headContent);
            const regex = /font-size: 11px;/ig;
            // Se escribe todo el contenido del id recibido por parámetro en la pestaña nueva que se abrirá
            printWindow.document.write((printContent.innerHTML).replaceAll(regex, "font-size: 13px;"));
            printWindow.document.close();
            // Se enfoca en la pestaña nueva
            printWindow.focus();
            // Se abre la ventana para imprimir el contenido de la pestaña nueva
            printWindow.onload = function() {
                printWindow.print();
                printWindow.close();
            };
        }
        // detecta el enter para no cerrar el modal sin enviar el formulario
        $('#modal-form-externals, #modal-form-externals-ceropapeles, #modal-form-externa-google').on('keypress', ':input:not(textarea):not([type=submit])', function(e) {
            if (e.keyCode === 13) {
                e.preventDefault();
            }
        });

        $(document).ready(function() {
            $('#content-rotule').draggable();
            // Cuando la tabla se muestra, hace focus en la primera celda
            $("#historial_completo").on("shown.bs.collapse", function() {

                $("#modal-view-externals :last-child").focus();

            });
        });
    </script>
@endpush
