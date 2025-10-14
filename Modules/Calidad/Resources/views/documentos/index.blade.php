@extends('layouts.default')

@section('title', trans('documentos'))

@section('section_img', '')

@section('menu')
    @include('calidad::layouts.menu')
@endsection

@section('content')

<crud
    name="documentos"
    :resource="{default: 'documentos-calidad', get: 'get-documentos'}"
    inline-template
    :init-values-search='{proceso: @json($nombre_proceso) ? @json($nombre_proceso) : null}'
    :crud-avanzado="true">
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('Documentos')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('documentos')'}}</h1>
        <!-- end page-header -->
        {{-- Valida si el usuario en sesión es un administrador de calidad --}}
        @if (Auth::user()->hasRole('Admin Documentos de Calidad'))
            <!-- begin main buttons -->
            <div class="m-t-20">
                <button @click="callFunctionComponent('documento_calidad_ref', 'crearDocumentoInicial')" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-documentos-documento-inicial" data-toggle="modal">
                    <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('documento')
                </button>
                <button onclick="window.location.href = window.location.origin + window.location.pathname.split('/').slice(0, 3).join('/')" class="btn btn-light m-b-10"><i class="fa fa-redo-alt mr-2"></i>Cargar página de nuevo</button>
            </div>
            <!-- end main buttons -->
        @endif
        <viewer-attachement type="table" ref="viewerDocuments"></viewer-attachement>
        <documentos-calidad inline-template ref="documento_calidad_ref" name="documentos-calidad" :listado-tipos-documentos="dataExtra['tipo_documentos']">
            <div>
                <!-- begin #modal-form-form-documentos-documento-inicial -->
                <div class="modal fade" id="modal-form-documentos-documento-inicial">
                    <div class="modal-dialog modal-lg">
                        <form @submit.prevent="crearDocumento()" id="form-documentos-documento-inicial">
                            <div class="modal-content border-0">
                                <div class="modal-header bg-blue">
                                    <h4 class="modal-title text-white">Crear documento</h4>
                                    <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                                </div>
                                <div class="modal-body" v-if="formOpen == 'tipoDocumento'">
                                    <div class="col-md-12">
                                        <!-- Titulo Asunto Field -->
                                        <div class="form-group row m-b-15">
                                            {!! Form::label('titulo', trans('Título') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                                            <div class="col-md-9">
                                                {!! Form::text('titulo', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.titulo }", 'v-model' => 'dataForm.titulo', 'required' => true]) !!}
                                                <small>@lang('Enter the') el título del documento a crear</small>
                                                <div class="invalid-feedback" v-if="dataErrors.titulo">
                                                    <p class="m-b-0" v-for="error in dataErrors.titulo">@{{ error }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Documento Adjunto Field -->
                                        <div class="form-group row">
                                            {!! Form::label('documento_adjunto', trans('Documento').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                            <div class="col-md-9">
                                                {!! Form::file('documento_adjunto', ['accept' => '*', '@change' => 'inputFile($event, "documento_adjunto")', 'required' => true]) !!}
                                                {{-- <small>Opcional: @lang('Select the') una plantilla si desea</small> --}}
                                                <div class="invalid-feedback" v-if="dataErrors.documento_adjunto">
                                                    <p class="m-b-0" v-for="error in dataErrors.documento_adjunto">@{{ error }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>Cerrar</button>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-arrow-right mr-2"></i>Crear documento</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- end #modal-form-documentos-documento-inicial -->

                <!-- begin #modal-form-documentos -->
                <div class="modal fade" id="modal-form-documentos-calidad" v-show="dataForm.origen_documento">
                    <div class="modal-dialog" :class="{'modal-xl': dataForm.origen_documento == 'Adjuntar documento para ser almacenado en Intraweb'}" :style="dataForm.origen_documento == 'Producir documento en línea a través de Intraweb' ? 'max-width: 94vw;' : ''">
                        <form @submit.prevent="actualizarDocumento()" id="form-documentos-calidad" autocomplete="off">
                            <div class="modal-content border-0">
                                <div class="modal-header bg-blue">
                                    <h4 class="modal-title text-white">Creación y edición de documentos de calidad</h4>
                                    <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                                </div>
                                <button v-if="dataForm.origen_documento == 'Producir documento en línea a través de Intraweb'" type="button" :title="btnResizeEditor" class="btn btn-secondary" style="margin-left: auto; margin-bottom: -36px; z-index: 10; margin-right: 10px;" id="btnResizeEditor" @click="resizeEditor($event, this)">
                                    <i v-if="btnResizeEditor == 'Maximizar editor'" class="fa fa-arrow-left" aria-hidden="true"></i>
                                    <i v-if="btnResizeEditor == 'Minimizar editor'" class="fa fa-arrow-right" aria-hidden="true"></i>
                                    @{{ btnResizeEditor }}
                                </button>
                                <div v-if="dataForm.origen_documento == 'Producir documento en línea a través de Intraweb'" style="display: flex;">
                                    <div class="modal-body column col-md-6" id="formularioIzq" v-if="formOpen == 'FormularioDocumento'">
                                        @include('calidad::documentos.fields')
                                        <!-- Ícono flotante -->
                                        <div class="floating-icon" onclick="toggleInfoBubble()" title="Variables Disponibles para Reemplazo en el Documento">
                                            <i class="fa fa-info"></i>
                                        </div>
                                        <!-- Nube de información -->
                                        <div class="info-bubble" id="infoBubble">
                                            <button class="close-btn" onclick="toggleInfoBubble()" type="button">×</button>
                                            <table border="1" class="variables-tabla">
                                                <thead>
                                                    <tr>
                                                        <th>Variable</th>
                                                        <th>Descripción</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>#consecutivo</td>
                                                        <td>El consecutivo del documento</td>
                                                    </tr>
                                                    <tr>
                                                        <td>#titulo</td>
                                                        <td>El título del documento</td>
                                                    </tr>
                                                    <tr>
                                                        <td>#version</td>
                                                        <td>La versión del documento</td>
                                                    </tr>
                                                    <tr>
                                                        <td>#elaboro_cargo</td>
                                                        <td>Cargo de quien elaboró</td>
                                                    </tr>
                                                    <tr>
                                                        <td>#reviso_cargo</td>
                                                        <td>Cargo de quien revisó</td>
                                                    </tr>
                                                    <tr>
                                                        <td>#aprobo_cargo</td>
                                                        <td>Cargo de quien aprobó</td>
                                                    </tr>
                                                    <tr>
                                                        <td>#fecha_elaboro</td>
                                                        <td>Fecha en que se elaboró</td>
                                                    </tr>
                                                    <tr>
                                                        <td>#fecha_reviso</td>
                                                        <td>Fecha en que se revisó</td>
                                                    </tr>
                                                    <tr>
                                                        <td>#fecha_aprobo</td>
                                                        <td>Fecha en que se aprobó</td>
                                                    </tr>
                                                    <tr>
                                                        <td>#firmas</td>
                                                        <td>Las firmas</td>
                                                    </tr>
                                                    <tr>
                                                        <td>#fecha</td>
                                                        <td>La fecha del día actual</td>
                                                    </tr>
                                                    <tr>
                                                        <td>#codigo_validacion</td>
                                                        <td>Código de validación</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <iframe v-if="dataForm.plantilla" class="column" :src="dataForm.plantilla+'?rm=embedded&embedded=true'" id="editorDer" style="width: 100%; margin-right: 1px; border-right-color: rgb(0 0 0 / 56%);"></iframe>
                                    <iframe v-else-if="dataForm.documento_adjunto" class="column" :src="'/storage/'+dataForm.documento_adjunto" id="editorDer" style="width: 100%; margin-right: 1px; border-right-color: rgb(0 0 0 / 56%);"></iframe>
                                </div>
                                <div v-if="dataForm.origen_documento == 'Adjuntar documento para ser almacenado en Intraweb' && formOpen == 'FormularioDocumento'" class="modal-body column col-md-12" id="formularioIzq">
                                    @include('calidad::documentos.fields')
                                </div>

                                <div class="modal-footer">
                                    <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- end #modal-form-documentos -->
            </div>
        </documentos-calidad>

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('documentos'): ${dataPaginator.total}` | capitalize }}</h5>
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
                                {!! Form::text('titulo', null, ['v-model' => 'searchFields.titulo', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => "título"]), '@keyup.enter' => 'pageEventActualizado(1)']) !!}
                                <small>Filtrar por título</small>
                            </div>
                            <div class="col-md-4">
                                {!! Form::text('consecutivo', null, ['v-model' => 'searchFields.consecutivo', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => "consecutivo"]), '@keyup.enter' => 'pageEventActualizado(1)' ]) !!}
                                <small>Filtrar por consecutivo</small>
                            </div>

                            <div class="col-md-4 p-b-5">
                                <select-check css-class="form-control" name-field="calidad_proceso_id" reduce-label="nombre" name-resource="obtener-procesos" :value="searchFields" :is-required="true" :key="dataForm.calidad_tipo_sistema_id" :enable-search="true"></select-check>
                                <small>Filtrar por proceso</small>
                            </div>
                            <div class="col-md-4 p-b-5">
                                <select class="form-control" name="estado" id="estado" v-model="searchFields.estado" required>
                                    <option value="">Todos</option>
                                    <option value="Público">Público</option>
                                    <option value="Elaboración">Elaboración</option>
                                    <option value="Revisión">Revisión</option>
                                    <option value="Aprobación">Aprobación</option>
                                </select>
                                <small>Filtrar por estado</small>
                            </div>
                            <div class="col-md-4 p-b-5">
                                <select class="form-control" name="visibilidad_documento" id="visibilidad_documento" v-model="searchFields.visibilidad_documento" required>
                                    <option value="Público">Público</option>
                                    <option value="Privado">Privado</option>
                                </select>
                                <small>Filtrar por visibilidad del documento</small>
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
            <!-- end #accordion search -->
            <div class="panel-body">
                <!-- begin buttons action table -->

                <!-- end buttons action table -->
                @include('calidad::documentos.table')
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

        <!-- begin #modal-view-documentos -->
        <div class="modal fade" id="modal-view-documentos">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') @lang('documentos')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                       @include('calidad::documentos.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-documentos -->
    </div>
</crud>
@endsection

@push('css')
{!!Html::style('assets/plugins/gritter/css/jquery.gritter.css')!!}
<style>
    /* Estilo del ícono flotante */
    .floating-icon {
        position: fixed;
        bottom: 115px;
        left: 52%;
        width: 30px;
        height: 30px;
        background-color: #007bff;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        z-index: 1000;
    }

    /* Estilo de la nube de información */
    .info-bubble {
        display: none;
        position: fixed;
        bottom: 160px;
        left: 52%;
        background-color: white;
        color: #333;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        z-index: 1000;
    }

    .info-bubble::before {
        content: "";
        position: absolute;
        top: 100%;
        left: 20px;
        margin-left: -10px;
        border-width: 10px;
        border-style: solid;
        border-color: white transparent transparent transparent;
    }

    .info-bubble .close-btn {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #ddd;
        border: none;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        cursor: pointer;
        text-align: center;
        font-size: 16px;
        line-height: 20px;
    }

    .variables-tabla td, .variables-tabla th {
        padding: 4px;
    }
</style>
@endpush

@push('scripts')
{!!Html::script('assets/plugins/gritter/js/jquery.gritter.js')!!}
<script>
    // detecta el enter para no cerrar el modal sin enviar el formulario
    $('#modal-form-documentos, #modal-form-documentos-documento-inicial').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });

    // Función para mostrar/ocultar la nube de información de variables disponibles para usar en el documento plantilla
    function toggleInfoBubble() {
        var bubble = document.getElementById("infoBubble");
        bubble.style.display = bubble.style.display === "block" ? "none" : "block";
    }

    $(document).ready(function() {
        // Cuando la tabla se muestra, hace focus en la primera celda
        $("#historial_completo").on("shown.bs.collapse", function() {
            $("#modal-view-documentos :last-child").focus();
        });
    });

    const elementoDiv = document.getElementById('show_table');
    elementoDiv.style.display = 'none';

    function toggleDiv(divId) {
        const elementoDiv = document.getElementById(divId);
        var otroDivId = '';
        if (elementoDiv.id === 'show_cards') {
            otroDivId = 'show_table';
        } else if(elementoDiv.id === 'show_table'){
            otroDivId = 'show_cards';
        }

        const otroElementoDiv = document.getElementById(otroDivId);

        if (elementoDiv && otroElementoDiv) {

            const estiloActual = elementoDiv.style.display;
            const estiloActual2 = otroElementoDiv.style.display;
            elementoDiv.style.display = estiloActual === 'none' ? 'block' : 'none';
            otroElementoDiv.style.display = otroElementoDiv === 'block' ? 'none' : 'block';

        } else {
            console.error(`El elemento Div con ID ${divId} o ${otroDivId} no se encontró.`);
        }
    }

    const btnCard = document.getElementById('btnCard');
    const btnTable = document.getElementById('btnTable');
    const btnDowLoad = document.getElementById('btnDowLoad');


    // Función para cambiar el color al hacer clic y mantener presionado
    function changeColorOnHold(button) {
    button.style.color = '#4d90fe';
    }

    // Función para restaurar el color al soltar el clic
    function restoreColor(button) {
    button.style.color = '#5f6368';
    }

    // Evento al hacer clic y mantener presionado
    btnCard.addEventListener('mousedown', function() { changeColorOnHold(btnCard); });

    // Evento al soltar el clic
    btnCard.addEventListener('mouseup', function() { restoreColor(btnCard); });

    btnTable.addEventListener('mousedown', function() { changeColorOnHold(btnTable); });
    btnTable.addEventListener('mouseup', function() { restoreColor(btnTable); });

    btnDowLoad.addEventListener('mousedown', function() { changeColorOnHold(btnDowLoad); });
    btnDowLoad.addEventListener('mouseup', function() { restoreColor(btnDowLoad); });
</script>
@endpush
