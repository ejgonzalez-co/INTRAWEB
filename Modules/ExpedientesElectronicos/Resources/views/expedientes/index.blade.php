@extends('layouts.default')

@section('title', trans('Expedientes'))

@section('section_img', '')

@section('menu')
@include('expedienteselectronicos::layouts.menu')
@endsection

@section('content')

<crud
    name="expedientes"
    :resource="{default: 'expedientes', get: 'get-expedientes'}"
    :init-values="{ee_permiso_usuarios_expedientes: [], metadatos:{}, permiso_general_expediente: ''}"
    inline-template
    :init-values-search='{_obj_llave_valor_: {}, rol_consulta_expedientes: @json(session('rol_consulta_expedientes') ? session('rol_consulta_expedientes') : (Auth::user()->hasRole('Operador Expedientes Electrónicos') ? 'operador' : (Auth::user()->hasRole('Consulta Expedientes Electrónicos') ? 'consulta_expedientes' : 'funcionario')))}'
    :crud-avanzado="true"
    @if(Auth::user()->hasRole('Operador Expedientes Electrónicos'))
    titulo-swal="Enviar expediente electrónico para aprobación y firma."
    texto-swal="Una vez que la apertura del expediente sea aprobada por el responsable, se generará la carátula, lo que permitirá continuar alimentando el expediente por parte de este."
    :swal-confirmacion="searchFields.rol_consulta_expedientes == 'operador'"
    @endif
    >
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('expedientes')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <div class="d-flex flex-md-row justify-items-center mb-5">

            <!-- begin page-header -->
            <h1 class="page-header text-center m-0">@{{ '@lang('main_view_to_manage') @lang('expedientes')'}}</h1>
            <!-- end page-header -->
            <div class="mt-3 mt-md-0 ml-md-4">
                <button type="button" @click="getDataWidgets" data-toggle="collapse" data-target="#contenedor_tablero" class="btn btn-outline-success">
                    <i class="fa fa-table"></i>&nbsp;&nbsp;&nbsp;
                    <span id="text_btn_consolidado">Mostrar/Ocultar Contadores</span>
                </button>
            </div>

        </div>

        {{-- Lista los roles que posiblemente pueda tener un usuario --}}
        <div class="mb-2 row form-group">
            <label class="col-sm-1 col-form-label font-weight-bold" style="font-size: 17px;">Rol:</label>
            <div class="ml-2">
                <select class="form-control" name="rol_consulta_expedientes" v-model="searchFields.rol_consulta_expedientes" @change="_updateKeyRefresh(); pageEventActualizado(1); $refs.contenedorTablero.classList.contains('show') && (isTableroVisible = false, getDataWidgets())">
                    @if(Auth::user()->hasRole('Operador Expedientes Electrónicos'))
                    <option value="operador">Operador de {{ \Modules\Intranet\Models\Dependency::where("id", Auth::user()->id_dependencia)->pluck("nombre")->first(); }}</option>
                    @endif
                    @if(Auth::user()->hasRole('Consulta Expedientes Electrónicos'))
                        <option value="consulta_expedientes">Consulta de expendientes</option>
                    @endif
                    <option value="responsable_expedientes">Responsable</option>
                    <option value="funcionario">Funcionario</option>
                </select>
                <small>Rol con el que está consultando la vista</small>
            </div>
        </div>
        <!-- end page-header -->

        <!-- begin widget -->
        <div class="collapse border-bottom p-l-40 p-r-40 row pt-1" id="contenedor_tablero" ref="contenedorTablero">

            <widget-counter
                icon="fa fa-folder"
                bg-color="#2196f3"
                :qty="dataWidgets.estados?.abierto ?? 0"
                status="Abierto"
                title="Abiertos"
                name-field="estado"
                :value="searchFields"
                :eliminar-parametros-url="['qd','qsb','qder']"></widget-counter>

            {{-- <widget-counter
                icon="fa fa-folder"
                bg-color="#fd7e14"
                :qty="dataWidgets.estados?.pendiente_aprobacion_cierre ?? 0"
                status="Pendiente de aprobación de cierre"
                title="Pendiente de aprobación de cierre"
                name-field="estado"
                :value="searchFields"
                :eliminar-parametros-url="['qd','qsb','qder']"
            ></widget-counter> --}}

            <widget-counter
                icon="fa fa-folder"
                bg-color="#ff9800"
                :qty="dataWidgets.estados?.pendiente_de_firma ?? 0"
                status="Pendiente de firma"
                title="Pendiente de firma"
                name-field="estado"
                :value="searchFields"
                :eliminar-parametros-url="['qd','qsb','qder']"></widget-counter>

            <widget-counter
                icon="fa fa-folder"
                bg-color="#f44336"
                :qty="dataWidgets.estados?.devuelto_para_modificaciones ?? 0"
                status="Devuelto para modificaciones"
                title="Devuelto para modificaciones"
                name-field="estado"
                :value="searchFields"
                :eliminar-parametros-url="['qd','qsb','qder']"></widget-counter>

            <widget-counter
                icon="fa fa-folder"
                bg-color="#8bc34a"
                :qty="dataWidgets.estados?.cerrado ?? 0"
                status="Cerrado"
                title="Cerrados"
                name-field="estado"
                :value="searchFields"
                :eliminar-parametros-url="['qd','qsb','qder']"></widget-counter>
        </div>

        <!-- begin main buttons -->
        <div class="m-t-20">
            {{-- Valida si el usuario en sesión es operador de expedientes --}}
            @if (Auth::user()->hasRole('Operador Expedientes Electrónicos'))
            <button @click="add()" type="button" class="btn btn-add m-b-10" data-backdrop="static" data-target="#modal-form-expedientes" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('expedientes')
            </button>
            @endif
            <button onclick="window.location.href = window.location.href.split('?')[0];" class="btn btn-light m-b-10"><i class="fa fa-redo-alt mr-2"></i>Cargar página de nuevo</button>
        </div>
        <!-- end main buttons -->


        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('expedientes'): ${dataPaginator.total}` | capitalize }}</h5>
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
                            <div class="col-md-4 p-b-5">
                                <input type="text" v-model="searchFields.consecutivo" class="form-control" placeholder="Filtrar por consecutivo" @keyup.enter="pageEventActualizado(1)" @keyup="!searchFields.consecutivo ? $delete(searchFields, 'consecutivo') : null">
                                <small>Filtrar por consecutivo</small>
                            </div>
                            <div class="col-md-4 p-b-5">
                                {!! Form::text('nombre_expediente', null, ['v-model' => 'searchFields.nombre_expediente', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => "nombre del expediente"]) ]) !!}
                                <small>Filtrar por nombre del expediente</small>
                            </div>
                            <div class="col-md-4 p-b-5">
                                <date-picker
                                    :value="searchFields"
                                    name-field="ee_expediente.created_at"
                                    mode="range"
                                    :input-props="{required: true}">
                                </date-picker>
                                <small>Filtrar por fecha de creación</small>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control" name="existe_fisicamente" id="existe_fisicamente" v-model="searchFields.existe_fisicamente">
                                    <option value="Si">Si</option>
                                    <option value="No">No</option>
                                </select>
                                <small>¿Existe físicamente?</small>
                            </div>
                            <div class="col-md-4">
                                <select-check css-class="form-control" name-field="id_responsable" reduce-label="fullname" name-resource="/expedientes-electronicos/get-users-responsable-expediente-filtro" :value="searchFields" :is-required="true" :enable-search="true"></select-check>
                                <small>Filtrar por responsable</small>
                            </div>
                            <div class="col-md-4">
                                <input type="text" v-model="searchFields.descripcion" class="form-control" placeholder="Filtrar por descripcion" @keyup.enter="pageEventActualizado(1)" @keyup="!searchFields.descripcion ? $delete(searchFields, 'descripcion') : null">
                                <small>Filtrar por descripción</small>
                            </div>
                            <div class="col-md-4 p-b-5">
                                <select class="form-control" name="estado" id="estado" v-model="searchFields.estado" required>
                                    <option value="Abierto">Abierto</option>
                                    <option value="Cerrado">Cerrado</option>
                                </select>
                                <small>Filtrar por estado</small>
                            </div>

                            {{-- dataForm.subserie_clasificacion_documental?.criterios_busqueda.length > 0 && dataForm.subserie_clasificacion_documental --}}

                            <div class="col-md-4 p-b-5">
                                <select-check css-class="form-control" name-field="classification_production_office" reduce-label="nombre" name-resource="/intranet/get-dependencies" :value="searchFields" :enable-search="true" :ids-to-empty="['classification_serie','classification_subserie']"></select-check>
                                <small>Seleccione la oficina productora.</small>
                            </div>

                            <!-- classification_serie Field -->
                            <div class="col-md-4 p-b-5">
                                <select-check css-class="form-control" name-field="classification_serie" reduce-label="name" reduce-key="id_series_subseries" :name-resource="'/documentary-classification/get-inventory-documentals-serie-dependency/'+ searchFields.classification_production_office"
                                    :key="searchFields.classification_production_office"
                                    :value="searchFields"
                                    :value-aux="searchFieldsAux"
                                    :is-required="true"
                                    :enable-search="true"
                                    name-field-object="serie_clasificacion_documental"
                                    :ids-to-empty="['_obj_llave_valor_']"
                                    :is-filtro="true"></select-check>
                                <small>Seleccione una serie documental, ejemplo: Contratos.</small>
                            </div>

                            <!-- classification_subserie Field -->
                            <div class="col-md-4 p-b-5">
                                <select-check css-class="form-control" name-field="classification_subserie" reduce-label="name_subserie"
                                    :name-resource="'/documentary-classification/get-subseries-clasificacion?serie='+searchFields.classification_serie"
                                    :key="searchFields.classification_serie"
                                    :value="searchFields"
                                    :value-aux="searchFieldsAux"
                                    :is-required="true"
                                    :enable-search="true"
                                    name-field-object="subserie_clasificacion_documental"
                                    :ids-to-empty="['_obj_llave_valor_']"
                                    :is-filtro="true"></select-check>
                                <small>Seleccione una sub-serie documental, ejemplo: Contratos de trabajo.</small>
                            </div>

                            {{-- metadatos --}}
                            <div
                                v-if="(searchFieldsAux.serie_clasificacion_documental?.series_osubseries?.criterios_busqueda.length > 0 && searchFields.classification_serie) || (searchFieldsAux.subserie_clasificacion_documental?.criterios_busqueda.length > 0 && searchFields.classification_subserie)" class="col-md-12 m-l-10 m-b-10 p-t-10 p-b-10" style="border: 1px solid #6c757d57;">
                                <strong>Metadatos de la serie ó subserie</strong>
                                <span class="row">
                                    <div class="col-md-4 p-t-5" v-for="metadato in searchFieldsAux.subserie_clasificacion_documental?.criterios_busqueda ?? searchFieldsAux.serie_clasificacion_documental?.series_osubseries?.criterios_busqueda">
                                        <input v-if="metadato.tipo_campo != 'Lista'"
                                        :type="metadato.tipo_campo == 'Texto' ? 'text' : (metadato.tipo_campo == 'Número' ? 'number' : ('date'))"
                                        v-model="searchFields._obj_llave_valor_[metadato.id]" :name="''+metadato.id" :id="''+metadato.id" class="form-control" :required="metadato.requerido" :placeholder="'Filtrar por '+metadato.nombre" @keyup.enter='pageEventActualizado(1)' @keyup="!searchFields._obj_llave_valor_[metadato.id] ? $delete(searchFields._obj_llave_valor_, metadato.id) : null">
                                        <span v-else>
                                            <select v-model="searchFields._obj_llave_valor_[metadato.id]" :name="''+metadato.id" :id="''+metadato.id" class="form-control" :required="metadato.requerido">
                                                <option v-for="(value, key) in parseOpciones(metadato.opciones)" :value="key">@{{ value }}</option>
                                            </select>
                                            <small>@{{ metadato.texto_ayuda }}</small>
                                        </span>
                                    </div>
                                </span>
                            </div>

                            <div class="col-md-4">
                                <button @click="pageEventActualizado(1)" class="btn btn-add"><i class="fa fa-search mr-2"></i>Buscar</button>
                                <button @click="clearDataSearchAvanzado()" class="btn btn-light">@lang('clear_search_fields')</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end card search -->
            </div>

            <!-- Modal para visualizar un modal de alerta de confirmacion -->
            <alert-confirmation ref="alert-confirmation" secondary-text="Una vez firmado, el expediente se cerrará automáticamente. No podrá volver a abrirlo ni agregar más documentos." loading-data="Enviado expediente a firma" title="¿Está seguro de firmar y cerrar el expediente?" confirmation-text="Aceptar" cancellation-text="Cancelar" :textarea="false" name-resource="cerrar-expediente" title-successful-shipment="Expediente enviado"></alert-confirmation>

            @if(config('app.mod_expedientes'))
                <expedientes-general
                    ref="expedientes"
                    :campo-consecutivo="'consecutivo'"
                    :modulo="'Expedientes'"
                    :puede-crear-expedientes="{{ Auth::user()->roles->pluck('name')->intersect(['Operador Expedientes Electrónicos'])->isNotEmpty() ? 'true' : 'false' }}"
                    :user-id="{{ Auth::user()->id }}"
                ></expedientes-general>
            @endif

            <!-- end #accordion search -->
            <div class="panel-body">
                <!-- begin buttons action table -->

                <!-- end buttons action table -->
                @include('expedienteselectronicos::expedientes.table')
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

        <!-- begin #modal-view-expedientes -->
        <div class="modal fade" id="modal-view-expedientes">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') @lang('expedientes')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body" id="showFields">
                        @include('expedienteselectronicos::expedientes.show_fields')
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
        <!-- end #modal-view-expedientes -->

        <!-- begin #modal-form-expedientes -->
        <div class="modal fade" id="modal-form-expedientes">
            <div class="modal-dialog modal-xl">
                <form @submit.prevent="save()" id="form-expedientes">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of') @lang('expedientes')</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('expedienteselectronicos::expedientes.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-expedientes -->

        <dynamic-modal-form modal-id="aprobar-firmar-expediente" size-modal="completo" title="Aprobar firmar o devolver para modificaciones"
            :data-form.sync="dataForm" endpoint="aprobar-firmar-expedientes" :is-update="true"
            @saved="
            if($event.isUpdate) {
                assignElementList(dataForm.id, $event.data);
            } else {
                addElementToList($event.data);
            }">
            <template #fields="scope">
                <div style="display: flex;">
                    <div class="modal-body column col-md-6">
                        <div class="panel" data-sortable-id="ui-general-1">
                            <!-- begin panel-heading -->
                            <div class="panel-heading ui-sortable-handle">
                                <h3 class="panel-title"><strong>Acción sobre el documento</strong></h3>
                            </div>
                            <!-- end panel-heading -->
                            <!-- begin panel-body -->
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <div class="form-group row m-b-15">
                                        <label for="type_send" class="col-form-label col-md-4 required">¿Qué desea hacer?:</label>
                                        <div class="col-md-8">
                                            <select class="form-control" v-model="scope.dataForm.type_send" name="type_send" id="type_send" required>
                                                <option value="">Seleccione</option>
                                                <option value="Aprobar Firma">Aprobar Firma</option>
                                                <option value="Devolver para modificaciones">Devolver para modificaciones</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group row m-b-15">
                                        <label for="danger" class="col-form-label col-md-4 required">Observaciones:</label>
                                        <div class="col-md-8">
                                            <textarea class="form-control" required type="text" v-model="scope.dataForm.observacion_accion" placeholder=""></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel" data-sortable-id="ui-general-1">
                            <!-- begin panel-heading -->
                            <div class="panel-heading ui-sortable-handle">
                                <h3 class="panel-title"><strong>Funcionario enviador para firma</strong></h3>
                            </div>
                            <!-- end panel-heading -->
                            <!-- begin panel-body -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th scope="col">Fecha</th>
                                                <th scope="col">Funcionario</th>
                                                <th scope="col">Tipo de Usuario</th>
                                                <th scope="col">Estado</th>
                                                <th scope="col">Observación</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>@{{ dataForm.created_at}}</td>
                                                <td>@{{ dataForm.nombre_usuario_enviador}}</td>
                                                <td>@{{ dataForm.tipo_usuario_enviador}}</td>
                                                <td>@{{ dataForm.estado}}</td>
                                                <td>@{{ dataForm.observacion}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body column col-md-6">
                        <viewer-attachement v-if="dataForm.ee_documentos_expedientes?.length" :link-file-name="true" :open-default="true" :list="dataForm.ee_documentos_expedientes[0].adjunto" :key="dataForm.ee_documentos_expedientes[0].adjunto"></viewer-attachement>
                    </div>
                </div>
            </template>
        </dynamic-modal-form>

        <annotations-general ref="annotations" route="/expedientes-electronicos/expedientes-anotaciones" name-list="expediente_anotaciones" file-path="public/container/expedientes_electronicos_{{ date('Y') }}/expediente_anotaciones"  field-title="Anotaciones del expediente electrónico: " field-title-var="consecutivo" name-content="anotacion" name-users-name="nombre_usuario"></annotations-general>
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
    $('#modal-form-expedientes').on('keypress', ':input:not(textarea):not([type=submit])', function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
        }
    });

    $(document).ready(function() {
        // Cuando la tabla se muestra, hace focus en la primera celda
        $("#historial_completo").on("shown.bs.collapse", function() {
            $("#modal-view-documentos :last-child").focus();
        });
    });

    // const elementoDiv = document.getElementById('show_table');
    // elementoDiv.style.display = 'none';

    // function toggleDiv(divId) {
    //     const elementoDiv = document.getElementById(divId);
    //     var otroDivId = '';
    //     if (elementoDiv.id === 'show_cards') {
    //         otroDivId = 'show_table';
    //     } else if(elementoDiv.id === 'show_table'){
    //         otroDivId = 'show_cards';
    //     }

    //     const otroElementoDiv = document.getElementById(otroDivId);

    //     if (elementoDiv && otroElementoDiv) {

    //         const estiloActual = elementoDiv.style.display;
    //         const estiloActual2 = otroElementoDiv.style.display;
    //         elementoDiv.style.display = estiloActual === 'none' ? 'block' : 'none';
    //         otroElementoDiv.style.display = otroElementoDiv === 'block' ? 'none' : 'block';

    //     } else {
    //         console.error(`El elemento Div con ID ${divId} o ${otroDivId} no se encontró.`);
    //     }
    // }

    // const btnCard = document.getElementById('btnCard');
    // const btnTable = document.getElementById('btnTable');
    // const btnDowLoad = document.getElementById('btnDowLoad');


    // // Función para cambiar el color al hacer clic y mantener presionado
    // function changeColorOnHold(button) {
    // button.style.color = '#4d90fe';
    // }

    // // Función para restaurar el color al soltar el clic
    // function restoreColor(button) {
    // button.style.color = '#5f6368';
    // }

    // // Evento al hacer clic y mantener presionado
    // btnCard.addEventListener('mousedown', function() { changeColorOnHold(btnCard); });

    // // Evento al soltar el clic
    // btnCard.addEventListener('mouseup', function() { restoreColor(btnCard); });

    // btnTable.addEventListener('mousedown', function() { changeColorOnHold(btnTable); });
    // btnTable.addEventListener('mouseup', function() { restoreColor(btnTable); });

    // btnDowLoad.addEventListener('mousedown', function() { changeColorOnHold(btnDowLoad); });
    // btnDowLoad.addEventListener('mouseup', function() { restoreColor(btnDowLoad); });
</script>
@endpush
