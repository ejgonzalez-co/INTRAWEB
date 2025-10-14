@extends('layouts.default')

@section('title', 'Comunicaciones por correo')

@section('section_img', '/assets/img/components/bandeja.png')

@section('menu')
    @include('correspondence::layouts.menu')
@endsection

@section('content')

<crud
    name="correo-integrados"
    :resource="{default: 'correo-integrados', get: 'get-correo-integrados', show:'correo-integrados'} "
    inline-template :crud-avanzado="true"
    :actualizar-listado-automatico="true">
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">Comunicaciones por correo</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') comunicaciones por correo'}}</h1>
        <!-- end page-header -->
        <div class="btn_consolidado" style="margin-bottom: 10px;">
            <button type="button" class="btn btn-success m-b-1" @click="$refs.received_ref.verTableroConsolidado();" style="font-size: 15px;">
               <i class="fa fa-table"></i>&nbsp;&nbsp;&nbsp;
               <span id="text_btn_consolidado">Ver consolidado de comunicaciones</span>
            </button>
        </div>
        <!-- begin widget -->
        <div class="row justify-content-center" id="contenedorTableConsolidado" style="display: none;">

            <widget-counter
                icon="fa fa-folder"
                class-css-color="bg-grey"
                :qty="dataWidgets.estados?.total ?? 0"
                status="all"
                title="Total comunicaciones"
                name-field="estado"
                :value="searchFields"
                :limpiar-filtros="['clasificacion', 'filtro_tablero_pqrsd', 'filtro_tablero_correspondencia']"
            ></widget-counter>

            <widget-counter
                icon="fa fa-book"
                class-css-color="bg-warning"
                :qty="dataWidgets.estados?.sin_clasificar ?? 0"
                status="Sin clasificar"
                title="Sin clasificar"
                name-field="estado"
                :value="searchFields"
                :limpiar-filtros="['clasificacion', 'filtro_tablero_pqrsd', 'filtro_tablero_correspondencia']"
            ></widget-counter>

            <widget-counter
                icon="fa fa-book-open"
                class-css-color="bg-blue"
                :qty="dataWidgets.estados?.clasificado_correspondencia ?? 0"
                status="filtro_tablero_correspondencia"
                title="Clasificado como correspondencia"
                name-field="filtro_tablero_correspondencia"
                :value="searchFields"
                :limpiar-filtros="['clasificacion', 'filtro_tablero_pqrsd', 'estado']"
            ></widget-counter>

            <widget-counter
                icon="fa fa-book-open"
                class-css-color="bg-secondary"
                :qty="dataWidgets.estados?.clasificado_pqrsd ?? 0"
                status="filtro_tablero_pqrsd"
                title="Clasificado como PQRS"
                name-field="filtro_tablero_pqrsd"
                :value="searchFields"
                :limpiar-filtros="['clasificacion', 'filtro_tablero_correspondencia', 'estado']"
            ></widget-counter>

            <widget-counter
                icon="fa fa-book-open"
                class-css-color="bg-green"
                :qty="dataWidgets.estados?.comunicacion_no_oficial ?? 0"
                status="Comunicación no oficial"
                title="Comunicación no oficial"
                name-field="clasificacion"
                :value="searchFields"
                :limpiar-filtros="['filtro_tablero_pqrsd', 'filtro_tablero_correspondencia', 'estado']"
            ></widget-counter>

        </div>
        <!-- end widget -->

        <!-- begin main buttons -->
        <div class="m-t-20">
            {{-- <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-configuracion-correo" data-toggle="modal">
                <i class="fa fa-cogs mr-2"></i>Configurar notificaciones
            </button> --}}
            <button onclick="window.location.href = window.location.href.split('?')[0];" class="btn btn-md btn-primary m-b-10"><i class="fa fa-redo-alt mr-2"></i>Cargar página de nuevo</button>
        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') comunicaciones por correo: ${dataPaginator.total}` | capitalize }}</h5>
                </div>
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default btn-recargar-listado" title="Actualizar listado" @click="_getDataListAvanzado(false);"><i class="fa fa-redo-alt"></i></a>
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
                                <select-check
                                    css-class="form-control"
                                    name-field="vigencia"
                                    reduce-label="vigencia"
                                    reduce-key="valor"
                                    name-resource="/get-vigencias/comunicaciones_por_correo/vigencia"
                                    :value="searchFields">
                                </select-check>
                                <small>Filtrar por vigencia</small>
                            </div>
                            <div class="col-md-4">
                                {!! Form::text('consecutivo', null, ['v-model' => 'searchFields.consecutivo', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Consecutivo')]) ]) !!}
                            </div>
                            <div class="col-md-4">
                                {!! Form::text('correo_remitente', null, ['v-model' => 'searchFields.correo_remitente', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('correo del remitente')]) ]) !!}
                            </div>
                            <div class="col-md-4">
                                {!! Form::text('asunto', null, ['v-model' => 'searchFields.asunto', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Asunto')]) ]) !!}
                            </div>
                            <div class="col-md-4">
                                <date-picker
                                    :value="searchFields"
                                    name-field="created_at"
                                    mode="range"
                                    :input-props="{required: true}">
                                </date-picker>
                                <small>Filtrar por fecha de recepción</small>
                            </div>
                            <div class="col-md-4">
                                {!! Form::select('estado',
                                    ["Sin clasificar" => "Sin clasificar",
                                    "Clasificado" => "Clasificado"], null, ['v-model' => 'searchFields.estado', 'class' => 'form-control']) !!}
                                <small>Filtrar por estado</small>
                            </div>
                            <div class="col-md-4">
                                <button @click="pageEventActualizado(1)" class="btn btn-md btn-primary"><i class="fa fa-search mr-2"></i>Buscar</button>
                                <button @click="clearDataSearchAvanzado()" class="btn btn-md btn-primary">@lang('clear_search_fields')</button>
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
                @include('correspondence::correo_integrados.table')
            </div>
            <div class="p-b-15 text-center">
                <!-- Cantidad de elementos a mostrar -->
                <div class="form-group row m-5 col-md-3 text-center d-sm-inline-flex">
                    <label for="show_qty" class="col-form-label col-md-7">Cantidad a mostrar:</label>
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

        <!-- begin #modal-view-correo-integrados -->
        <div class="modal fade" id="modal-view-correo-integrados">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') la comunicación</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body" id="showFields">
                       @include('correspondence::correo_integrados.show_fields')
                    </div>
                    <div class="modal-footer">
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
        <!-- end #modal-view-correo-integrados -->
        <dynamic-modal-form modal-id="modal-configuracion-correo" size-modal="lg" title="Configuración de notificaciones"
            :data-form.sync="dataExtra" endpoint="correo-integrados-configuracion" :is-update="true" :inicializar-data-form="false">
            <template #fields="scope">
                <div>
                    <div class="panel" data-sortable-id="ui-general-1">
                        <!-- begin panel-heading -->
                        <div class="panel-heading ui-sortable-handle">
                            <h4 class="panel-title"><strong>Mensajes de respuesta</strong></h4>
                        </div>
                        <!-- end panel-heading -->
                        <!-- begin panel-body -->
                        <div class="panel-body">
                            <div>
                                <div class="form-group row m-b-15 col-md-12">
                                    {!! Form::label('notificacion_correspondencia', 'Clasificado como correspondencia:', ['class' => 'col-form-label col-md-3 required']) !!}
                                    <div class="col-md-9">
                                        {!! Form::textarea('notificacion_correspondencia', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.notificacion_correspondencia }", 'v-model' => 'dataExtra.notificacion_correspondencia', 'required' => true, 'rows' => '3']) !!}
                                        <small>Ingrese el mensaje para la notificación, cuando la comunicación es clasificada como correspondencia.</small>
                                        <div class="invalid-feedback" v-if="dataErrors.notificacion_correspondencia">
                                            <p class="m-b-0" v-for="error in dataErrors.notificacion_correspondencia">
                                                @{{ error }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row m-b-15 col-md-12">
                                    {!! Form::label('notificacion_pqr', 'Clasificado como PQRS:', ['class' => 'col-form-label col-md-3 required']) !!}
                                    <div class="col-md-9">
                                        {!! Form::textarea('notificacion_pqr', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.notificacion_pqr }", 'v-model' => 'dataExtra.notificacion_pqr', 'required' => true, 'rows' => '3']) !!}
                                        <small>Ingrese el mensaje para la notificación, cuando la comunicación es clasificada como PQRS.</small>
                                        <div class="invalid-feedback" v-if="dataErrors.notificacion_pqr">
                                            <p class="m-b-0" v-for="error in dataErrors.notificacion_pqr">
                                                @{{ error }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row m-b-15 col-md-12">
                                    {!! Form::label('notificacion_no_oficial', 'No era una comunicación oficial:', ['class' => 'col-form-label col-md-3 required']) !!}
                                    <div class="col-md-9">
                                        {!! Form::textarea('notificacion_no_oficial', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.notificacion_no_oficial }", 'v-model' => 'dataExtra.notificacion_no_oficial', 'required' => true, 'rows' => '3']) !!}
                                        <small>Ingrese el mensaje para la notificación, cuando la comunicación es clasificada como no oficial.</small>
                                        <div class="invalid-feedback" v-if="dataErrors.notificacion_no_oficial">
                                            <p class="m-b-0" v-for="error in dataErrors.notificacion_no_oficial">
                                                @{{ error }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel" data-sortable-id="ui-general-1">
                        <!-- begin panel-heading -->
                        <div class="panel-heading ui-sortable-handle">
                            <h4 class="panel-title"><strong>Correo oficial de comunicaciones</strong></h4>
                        </div>
                        <!-- end panel-heading -->
                        <!-- begin panel-body -->
                        <div class="panel-body">
                            <div>
                                <div class="form-group row m-b-15 col-md-12">
                                    {!! Form::label('correo_comunicaciones', 'Correo electrónico:', ['class' => 'col-form-label col-md-3 required']) !!}
                                    <div class="col-md-9">
                                        {!! Form::text('correo_comunicaciones', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.correo_comunicaciones }", 'v-model' => 'dataExtra.correo_comunicaciones', 'required' => true]) !!}
                                        <small>Ingrese el correo oficial de la entidad para obtener las comunicaciones. Ej: admin@dominio.com</small>
                                        <div class="invalid-feedback" v-if="dataErrors.correo_comunicaciones">
                                            <p class="m-b-0" v-for="error in dataErrors.correo_comunicaciones">
                                                @{{ error }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row m-b-15 col-md-12">
                                    {!! Form::label('correo_communicaciones_clave', 'Contraseña:', ['class' => 'col-form-label col-md-3 required']) !!}
                                    <div class="col-md-9">
                                        {!! Form::password('correo_communicaciones_clave', [':class' => "{'form-control':true, 'is-invalid':dataErrors.correo_communicaciones_clave }", 'v-model' => 'dataExtra.correo_communicaciones_clave', 'required' => true]) !!}
                                        <small>Ingrese la contraseña del correo.</small>
                                        <div class="invalid-feedback" v-if="dataErrors.correo_communicaciones_clave">
                                            <p class="m-b-0" v-for="error in dataErrors.correo_communicaciones_clave">
                                                @{{ error }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel" data-sortable-id="ui-general-1">
                        <!-- begin panel-heading -->
                        <div class="panel-heading ui-sortable-handle">
                            <h4 class="panel-title"><strong>Configuración IMAP</strong></h4>
                        </div>
                        <!-- end panel-heading -->
                        <!-- begin panel-body -->
                        <div class="panel-body">
                            <div>
                                <div class="form-group row m-b-15 col-md-12">
                                    {!! Form::label('servidor', 'Servidor:', ['class' => 'col-form-label col-md-3']) !!}
                                    <div class="col-md-9">
                                        {!! Form::text('servidor', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.servidor }", 'v-model' => 'dataExtra.servidor', 'placeholder' => 'imap.gmail.com']) !!}
                                        <small>Ingrese el servidor del correo. Ej: imap.gmail.com. Si no ingresa nada, este será el valor por defecto.</small>
                                        <div class="invalid-feedback" v-if="dataErrors.servidor">
                                            <p class="m-b-0" v-for="error in dataErrors.servidor">
                                                @{{ error }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row m-b-15 col-md-12">
                                    {!! Form::label('seguridad', 'Seguridad:', ['class' => 'col-form-label col-md-3']) !!}
                                    <div class="col-md-9">
                                        {!! Form::select('seguridad', ["SSL" => "SSL", "TLS" => "TLS"], "SSL", [':class' => "{'form-control':true, 'is-invalid':dataErrors.seguridad }", 'v-model' => 'dataExtra.seguridad']) !!}
                                        <small>Seleccione el tipo de seguridad para obtener los correos, si no selecciona nada, tomará el valor SSL por defecto.</small>
                                        <div class="invalid-feedback" v-if="dataErrors.seguridad">
                                            <p class="m-b-0" v-for="error in dataErrors.seguridad">
                                                @{{ error }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row m-b-15 col-md-12">
                                    {!! Form::label('puerto', 'Puerto:', ['class' => 'col-form-label col-md-3']) !!}
                                    <div class="col-md-9">
                                        {!! Form::text('puerto', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.puerto }", 'v-model' => 'dataExtra.puerto', 'placeholder' => '993']) !!}
                                        <small>Ingrese el puerto de comunicación con el servidor de correo. Ej: 993. Si no ingresa nada, este será el valor por defecto.</small>
                                        <div class="invalid-feedback" v-if="dataErrors.puerto">
                                            <p class="m-b-0" v-for="error in dataErrors.puerto">
                                                @{{ error }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row m-b-15 col-md-12">
                                    {!! Form::label('obtener_desde', 'Obtener desde:', ['class' => 'col-form-label col-md-3']) !!}
                                    <div class="col-md-9">
                                        {!! Form::date('obtener_desde', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.obtener_desde }", 'v-model' => 'dataExtra.obtener_desde']) !!}
                                        <small>Seleccione la fecha desde la que se va a empezar a obtener los correos. Si selecciona ninguna fecha, por defecto será la fecha actual.</small>
                                        <div class="invalid-feedback" v-if="dataErrors.obtener_desde">
                                            <p class="m-b-0" v-for="error in dataErrors.obtener_desde">
                                                @{{ error }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </dynamic-modal-form>

        <external-received inline-template ref="received_ref" name="correo-integrados" data-backdrop="static">
            <!-- begin #modal-form-correo-integrados -->
            <div class="modal fade" id="modal-form-correo-integrados">
                <div class="modal-dialog modal-xl">
                    <form @submit.prevent="save()" id="form-correo-integrados">
                        <div class="modal-content border-0">
                            <div class="modal-header bg-blue">
                                <h4 class="modal-title text-white">Creación y edición de correspondencia recibida externa</h4>
                                <button @click="radicatied=false,clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                            </div>
                            <div class="modal-body" id="form-external-received-body" v-if="openForm">
                                @include('correspondence::external_receiveds.fields')
                            </div>
                            <div class="modal-footer">
                                <button @click="radicatied=false,clearDataForm()"  class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                                <button v-if="!radicatied" type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- end #modal-form-correo-integrados -->
        </external-received>
    </div>
</crud>
@endsection

@push('css')
{!!Html::style('assets/plugins/gritter/css/jquery.gritter.css')!!}
@endpush

@push('scripts')
{!!Html::script('assets/plugins/gritter/js/jquery.gritter.js')!!}
<script>

        // Función para imprimir el contenido de un identificador pasado por parámetro
        function printContent(divName) {
        $("#btn-rotule-print, #btn-rotule-print-rotule, #attach, #attach-rotule").hide();

        // Se obtiene el elemento del id recibido por parámetro
        var printContent = document.getElementById(divName);
        // Se guarda en una variable la nueva pestaña
        var printWindow = window.open('', 'PRINT','height=500,width=800');
        // Se obtiene el encabezado de la página actual para no peder estilos
        var headContent = document.getElementsByTagName('head')[0].innerHTML;
        // Se escribe todo el contenido del encabezado de la página actual en la pestaña nueva que se abrirá
        printWindow.document.write(headContent);
        const regex = /font-size: 11px;/ig;
        // Se escribe todo el contenido del id recibido por parámetro en la pestaña nueva que se abrirá
        printWindow.document.write((printContent.innerHTML).replaceAll(regex,"font-size: 11px; font-family: Arial;"));
        printWindow.document.close();
        // Se enfoca en la pestaña nueva
        printWindow.focus();
        // Se abre la ventana para imprimir el contenido de la pestaña nueva
        printWindow.onload = function() {
            printWindow.print();
            printWindow.close();
        };

        $("#btn-rotule-print, #btn-rotule-print-rotule, #attach, #attach-rotule").show();

    }

    // detecta el enter para no cerrar el modal sin enviar el formulario
    $('#modal-form-correo-integrados').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
