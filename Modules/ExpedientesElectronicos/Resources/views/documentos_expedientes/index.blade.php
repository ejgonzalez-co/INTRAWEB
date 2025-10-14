@extends('layouts.default')

@section('title', trans('documentos-expedientes'))

@section('section_img', '')

@section('menu')
    @include('expedienteselectronicos::layouts.menu_documentos')
@endsection

@section('content')

<crud
    name="documentos-expedientes"
    :resource="{default: 'documentos-expedientes', show: 'documentos-expedientes', get: 'get-documentos-expedientes?c={!! $c ?? null !!}'}"
    :init-values='{
        "c": {!! json_encode($c ?? null) !!},
        "metadatos": {},
        "serie_clasificacion_documental": {!! json_encode($infoExpediente["serie_clasificacion_documental"] ?? [], JSON_FORCE_OBJECT) !!},
        "subserie_clasificacion_documental": {!! json_encode($infoExpediente["subserie_clasificacion_documental"] ?? [], JSON_FORCE_OBJECT) !!}
    }'
    inline-template
    :init-values-search='{_obj_llave_valor_: {}}'
    :crud-avanzado="true">
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('documentos-expedientes')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        {{-- <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('documentos-expedientes') del expediente'}} <strong>{{ $infoExpediente["consecutivo"] }}</strong></h1> --}}
        <!-- end page-header -->
        <div class="container my-4">
            <div class="card shadow-sm">
                <div class="card-header text-center text-white bg-primary">
                    <h4 class="mb-0">Información de Expediente: {{$infoExpediente["consecutivo"] ?? 'N/A'}}</h4>
                </div>
                <div class="card-body" style="font-size: 14px;">
                    <div class="row">
                        <div class="col-md-6 d-flex">
                            <p class="text-primary mr-1">Nombre del expediente: </p>
                            <p class="text-secondary">{{$infoExpediente["nombre_expediente"] ?? 'N/A'}}</p>
                        </div>
                        <div class="col-md-6  d-flex">
                            <p class="text-primary mr-1">Fecha inicio del expediente: </p>
                            <p class="text-secondary">{{$infoExpediente["fecha_inicio_expediente"] ?? 'N/A'}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6  d-flex">
                            <p class="text-primary mr-1">Descripción del expediente: </p>
                            <p class="text-secondary">{{$infoExpediente["descripcion"] ?? 'N/A'}}</p>
                        </div>
                        <div class="col-md-6  d-flex">
                            <p class="text-primary mr-1">Estado del expediente: </p>

                            <div style="font-size: 14px;">
                                <span class="badge px-3 py-1"
                                      style="background-color: {{ $infoExpediente['estado'] == 'Abierto' ? '#2196f3' : ($infoExpediente['estado'] == 'Pendiente de firma' ? '#ff9800' : ($infoExpediente['estado'] == 'Devuelto para modificaciones' ? '#f44336' : ($infoExpediente['estado'] == 'Cerrado' ? '#8bc34a' : '#6c757d'))) }};">
                                    {{$infoExpediente["estado"] ?? 'N/A'}}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nota informativa -->
        <div style="background-color: rgb(191 238 244 / 15%); border: 1px solid #b6d7f0; color: #0c5460; padding: 10px 15px; border-radius: 6px; margin: 15px 0; display: flex; align-items: flex-start; font-size: 14px;">
            <span style="font-size: 18px; margin-right: 8px;">ℹ️</span>
            <div>
                <strong>Nota informativa:</strong>  
                Está visualizando únicamente los documentos del expediente a los cuales tiene permiso de acceso.  
                Si observa un orden o listado que no coincide con el total de documentos existentes, puede deberse a que algunos documentos no están disponibles para su perfil de usuario.
            </div>
        </div>

        <!-- begin main buttons -->
        <div class="m-t-20" style="display: flow-root;">
            @if($infoExpediente["estado"] == 'Abierto' && ($infoExpediente["id_responsable"] == Auth::user()->id || $infoExpediente["permiso_usar_expediente"]))
                <button @click="add()" type="button" class="btn btn-add m-b-10" data-backdrop="static" data-target="#modal-form-documentos-expedientes" data-toggle="modal">
                    <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('documentos-expedientes')
                </button>
            @endif
            @if((!empty($infoExpediente["ee_permiso_usuarios_expedientes"]) && $infoExpediente["ee_permiso_usuarios_expedientes"][0]["limitar_descarga_documentos"] == 1) || $infoExpediente["id_responsable"] == Auth::user()->id)
                <button @click="$refs.expedientes.descargarDocumentos(dataForm.c, '{{$infoExpediente["consecutivo"] ?? 'N/A'}}');" type="button" class="btn btn-light m-b-10">
                    <i class="fa fa-file-archive mr-2"></i>Exportar documentos del expediente
                </button>
            @endif
            {{-- <a type="button" class="btn btn-primary m-b-10" :href="'{!! url('expedientes-electronicos/doc-expediente-historials') !!}?c={{ $c }}'"><i class="nav-icon fas fa-history"></i> Historial</a> --}}
            <div class="float-xl-right m-b-10">
            <!-- Acciones para exportar datos de tabla-->
                <div class="btn-group">
                    <a href="javascript:;" data-toggle="dropdown" class="btn btn-light"><i class="fa fa-download mr-2"></i> @lang('export_data_table')</a>
                    <a href="#" data-toggle="dropdown" class="btn btn-light dropdown-toggle" aria-expanded="false"><b class="caret"></b></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="javascript:;" @click="exportDataTableAvanzado('pdf')" class="dropdown-item no-hover"> <i class="fa fa-file-pdf mr-2 text-danger"></i>Indice electrónico</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('documentos-expedientes'): ${dataPaginator.total}` | capitalize }}</h5>
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
                            <div class="col-md-4 p-b-5">
                                {!! Form::text('consecutivo', null, ['v-model' => 'searchFields.consecutivo', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => "consecutivo"]) ]) !!}
                                <small>Filtrar por el consecutivo</small>
                            </div>
                            <div class="col-md-4">
                                <date-picker
                                    :value="searchFields"
                                    name-field="fecha_documento"
                                    mode="range"
                                    :input-props="{required: true}">
                                </date-picker>
                                <small>Filtrar por fecha de creación</small>
                            </div>
                            <div class="col-md-4">
                                {!! Form::text('nombre_documento', null, ['v-model' => 'searchFields.nombre_documento', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('consecutivo del documento asociado')]) ]) !!}
                            </div>
                            <div class="col-md-4">
                                <select id="modulo_intraweb" v-model="searchFields.modulo_intraweb" class="custom-select" name="modulo_intraweb" required>
                                    <option value="" selected>Seleccione</option>
                                    <option value="Correspondencia interna">Correspondencia interna</option>
                                    <option value="Correspondencia recibida">Correspondencia recibida</option>
                                    <option value="Correspondencia enviada">Correspondencia enviada</option>
                                    <option value="PQRSD">PQRSD</option>
                                    <option value="Documentos electrónicos">Documentos electrónicos</option>
                                    <option value="Expediente electrónico">Expediente electrónico</option>
                                </select>
                                <small>Filtrar por módulo de intraweb</small>
                            </div>
                            <div class="col-md-4">
                                <date-picker
                                    :value="searchFields"
                                    name-field="created_at"
                                    mode="range"
                                    :input-props="{required: true}">
                                </date-picker>
                                <small>Filtrar por fecha de incorporación</small>
                            </div>
                            <div class="col-md-4">
                                <select-check css-class="form-control" name-field="ee_tipos_documentales_id"
                                    reduce-label="name"
                                    reduce-key="id"
                                    :name-resource="'/expedientes-electronicos/get-tipos-documentales-crear-expedientes/' + (dataForm.serie_clasificacion_documental?.id || 0) + '/' + (dataForm.subserie_clasificacion_documental?.id || 0)"
                                    :value="searchFields"
                                    :value-aux="searchFieldsAux"
                                    :is-required="true"
                                    ref-select-check="tipos_documentales_ref"
                                    name-field-object="metadatos_tipo_documental"
                                    :enable-search="true">
                                </select-check>
                                <small>Filtrar por tipo documental</small>
                            </div>
                            <div class="col-md-4 p-b-5">
                                {!! Form::text('Orden documento', null, ['v-model' => 'searchFields.orden_documento', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => "Orden documento"]) ]) !!}
                                <small>Filtrar por orden del documento</small>
                            </div>
                            <div class="col-md-4">
                                <select-check css-class="form-control" name-field="users_id" reduce-label="fullname" :name-resource="'/expedientes-electronicos/get-users-asocio-documento-filtro/'+dataForm.c" :value="searchFields" :is-required="true" :enable-search="true"></select-check>
                                <small>Filtrar por usuario que asoció el documento</small>
                            </div>
                            {{-- metadatos --}}
                            <div
                                v-if="searchFieldsAux.metadatos_tipo_documental?.criterios_busqueda" class="col-md-12 m-l-10 m-b-10 p-t-10 p-b-10" style="border: 1px solid #6c757d57;">
                                <strong>Metadatos</strong>
                                <span class="row">
                                    <div class="col-md-4 p-t-5" v-for="metadato in searchFieldsAux.metadatos_tipo_documental?.criterios_busqueda">
                                        <input v-if="metadato.tipo_campo != 'Lista'"
                                        :type="metadato.tipo_campo == 'Texto' ? 'text' : (metadato.tipo_campo == 'Número' ? 'number' : 'text')"
                                        v-model="searchFields._obj_llave_valor_[metadato.id]" :name="''+metadato.id" :id="''+metadato.id" class="form-control" :required="metadato.requerido" :placeholder="'Filtrar por '+metadato.nombre">
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

            <viewer-attachement :link-file-name="true" type="table" ref="viewerDocuments"></viewer-attachement>

            <!-- Modal para visualizar un modal de alerta de confirmacion -->
            <alert-confirmation ref="alert-confirmation" secondary-text="Ingrese una justificación" loading-data="Eliminando documento..." title="¿Esta seguro de eliminar el documento?" confirmation-text="Eliminar documento" cancellation-text="Cancelar" :textarea="true" name-resource="cambiar-estado-documento" title-successful-shipment="Documento eliminado"></alert-confirmation>

            <!-- end #accordion search -->
            <div class="panel-body">
                <!-- begin buttons action table -->

                <!-- end buttons action table -->
                @include('expedienteselectronicos::documentos_expedientes.table')
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

        <!-- begin #modal-view-documentos-expedientes -->
        <div class="modal fade" id="modal-view-documentos-expedientes">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') @lang('documentos-expedientes')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body" id="showFields">
                       @include('expedienteselectronicos::documentos_expedientes.show_fields')
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
        <!-- end #modal-view-documentos-expedientes -->

        <!-- begin #modal-form-documentos-expedientes -->
        <div class="modal fade" id="modal-form-documentos-expedientes">
            <div class="modal-dialog modal-lg">
                <form @submit.prevent="save()" id="form-documentos-expedientes">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">Documento del expediente {!! $infoExpediente["consecutivo"] !!}</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('expedienteselectronicos::documentos_expedientes.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-documentos-expedientes -->

        @if(config('app.mod_expedientes'))
            <expedientes-general
                ref="expedientes"
                :campo-consecutivo="'consecutivo'"
                :modulo="'Expedientes'"
                :puede-crear-expedientes="{{ Auth::user()->roles->pluck('name')->intersect(['Operador Expedientes Electrónicos'])->isNotEmpty() ? 'true' : 'false' }}"
                :user-id="{{ Auth::user()->id }}"
            ></expedientes-general>
        @endif

        <annotations-general ref="annotations" route="/expedientes-electronicos/documentos-expedientes-anotaciones" name-list="documento_expediente_anotaciones" file-path="public/container/expedientes_electronicos_{{ date('Y') }}/documento_expediente_anotaciones"  field-title="Anotaciones del documento electrónico: " field-title-var="consecutivo" name-content="anotacion" name-users-name="nombre_usuario"></annotations-general>
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
    $('#modal-form-documentos-expedientes').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
