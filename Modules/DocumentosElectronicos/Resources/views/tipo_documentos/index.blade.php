@extends('layouts.default')

@section('title', trans('tipo-documentos'))

@section('section_img', '')

@section('menu')
    @include('documentoselectronicos::layouts.menu')
@endsection

@section('content')

<crud
    name="tipo-documentos"
    :resource="{default: 'tipo-documentos', get: 'get-tipo-documentos'}"
    :crud-avanzado="true"
    :init-values="{variables_plantilla_value: [], de_permiso_crear_documentos: [], sub_estados_value: [], de_metadatos: [],de_permiso_consultar_documentos:[]}"
    inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('tipo-documentos')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('tipos de documentos')'}}</h1>
        <!-- end page-header -->
        {{-- Componente de documentos electrónicos para invocar la función de inicializarValoresTipoDocumento --}}
        <documentos-electronicos ref="tipo_documentos_ref" name="TiposDeDocumentos"></documentos-electronicos>

        <!-- begin main buttons -->
        <div class="m-t-20">
            <button @click="add(); $refs.tipo_documentos_ref.inicializarValoresTipoDocumento();" type="button" class="btn btn-add m-b-10" data-backdrop="static" data-target="#modal-form-tipo-documentos" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('tipo-documento')
            </button>
        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('tipo-documentos'): ${dataPaginator.total}` | capitalize }}</h5>
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
                                <input type="text" v-model="searchFields.nombre" class="form-control" placeholder="Filtrar por nombre" @keyup.enter="pageEventActualizado(1)" @keyup="!searchFields.nombre ? $delete(searchFields, nombre) : null">
                                <small>Filtrar por el nombre del tipo de documento</small>
                            </div>

                            <div class="col-md-4">
                                <input type="text" v-model="searchFields.prefijo" class="form-control" placeholder="Filtrar por prefijo" @keyup.enter="pageEventActualizado(1)" @keyup="!searchFields.prefijo ? $delete(searchFields, prefijo) : null">
                                <small>Filtrar por el prefijo</small>
                            </div>

                            <div class="col-md-4">
                                <input type="text" v-model="searchFields.codigo_formato" class="form-control" placeholder="Filtrar por código de formato" @keyup.enter="pageEventActualizado(1)" @keyup="!searchFields.codigo_formato ? $delete(searchFields, codigo_formato) : null">
                                <small>Filtrar por código del formato</small>
                            </div>

                            <div class="col-md-4">
                                <select-check css-class="form-control" name-field="formato_consecutivo" reduce-label="nombre"
                                    :value="searchFields"
                                    :enable-search="true"
                                    :options-list-manual="[{ id: 'prefijo_dependencia', nombre: 'Prefijo de la dependencia' },
                                        { id: 'serie_documental', nombre: 'Serie documental' },
                                        { id: 'subserie_documental', nombre: 'Subserie documental' },
                                        { id: 'prefijo_documento', nombre: 'Prefijo del documento' },
                                        { id: 'vigencia_actual', nombre: 'Año actual' },
                                        { id: 'consecutivo_documento', nombre: 'Número de Orden de Documento' }
                                    ]">
                                </select-check>
                                <small>Filtro por formato del consecutivo</small>
                            </div>

                            <div class="col-md-4">
                                <select-check css-class="form-control" name-field="prefijo_incrementan_consecutivo" reduce-label="nombre"
                                    :value="searchFields"
                                    :enable-search="true"
                                    :options-list-manual="[{ id: 'prefijo_dependencia', nombre: 'Prefijo de la dependencia' },
                                        { id: 'serie_documental', nombre: 'Serie documental' },
                                        { id: 'subserie_documental', nombre: 'Subserie documental' },
                                        { id: 'prefijo_documento', nombre: 'Prefijo del documento' },
                                        { id: 'vigencia_actual', nombre: 'Año actual' }
                                    ]">
                                </select-check>
                                <small>Filtro por el prefijo que incrementa el Consecutivo</small>
                            </div>

                            <div class="col-md-4 p-b-5">
                                <select class="form-control" name="estado" id="estado" v-model="searchFields.estado" required>
                                    <option value="Público">Público</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="Obsoleto">Obsoleto</option>
                                </select>
                                <small>Filtrar por estado</small>
                            </div>

                            <div class="col-md-4 p-b-5">
                                <select-check css-class="form-control" name-field="variables_plantilla"
                                    reduce-label="nombre" :value="searchFields"
                                    :options-list-manual="[{ id: '#consecutivo', nombre: '#consecutivo - Consecutivo del documento' },
                                        { id: '#titulo', nombre: '#titulo - Título del documento' },
                                        { id: '#dependencia_remitente',
                                            nombre: '#dependencia_remitente - Dependencia remitente del documento' },
                                        { id: '#compartidos', nombre: '#compartidos - Usuarios con permiso de ver el documento' },
                                        { id: '#tipo_documento', nombre: '#tipo_documento - Tipo de documento' },
                                        { id: '#elaborado', nombre: '#elaborado - Pérsona que elaboró el documento' },
                                        { id: '#revisado', nombre: '#revisado - Persona que revisó el document' },
                                        { id: '#proyecto', nombre: '#proyecto - Persona que proyectó el documento' },
                                        { id: '#codigo_formato', nombre: '#codigo_formato - Código o versión del documento en su formato' },
                                        { id: '#documento_asociado',
                                            nombre: '#documento_asociado - Documento asociado' },
                                        { id: '#codigo_dependencia',
                                            nombre: '#codigo_dependencia - Código de la dependencia' },
                                        { id: '#fecha', nombre: '#fecha - Fecha de publicación del documento' },
                                        { id: '#codigo_validacion',
                                            nombre: '#codigo_validacion - Código de validación del documento' },
                                        { id: '#firmas',
                                            nombre: '#firmas - Firmas del documento' }
                                    ]">
                                </select-check>
                                <small>Filtrar por variables de la plantilla</small>
                            </div>

                            <div class="col-md-4 p-b-5">
                                <select-check css-class="form-control" name-field="dependencias_id"
                                    name-field-object="dependencias" reduce-label="nombre"
                                    name-resource="get-dependencies" :value="searchFields" :is-required="true"
                                    ref-select-check="dependencias_ref" :enable-search="true">
                                </select-check>
                                <small>Filtrar por dependencia con permiso de usar este tipo de documento</small>
                            </div>

                            <div class="col-md-4 p-b-5">
                                <date-picker
                                    :value="searchFields"
                                    name-field="de_documento.created_at"
                                    mode="range"
                                    :input-props="{required: true}">
                                </date-picker>
                                <small>Filtrar por fecha de creación</small>
                            </div>

                            <div class="col-md-4">
                                <autocomplete
                                    name-prop="name"
                                    name-field="funcionario_elaboracion_revision"
                                    :value='searchFields'
                                    name-resource="/documentos-electronicos/get-usuarios"
                                    css-class="form-control"
                                    :is-required="true"
                                    :name-labels-display="['fullname']"
                                    reduce-key="id"
                                    :key="keyRefresh">
                                </autocomplete>
                                <small>Filtrar por usuario creador</small>
                            </div>

                            <div class="col-md-6">
                                <button @click="pageEventActualizado(1)" class="btn btn-add"><i class="fa fa-search mr-2"></i>Buscar</button>
                                <button @click="clearDataSearchAvanzado()" class="btn btn-md btn-light">
                                    <i class="fas fa-broom mr-2"></i>Limpiar campos de búsqueda
                                </button>
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
                @include('documentoselectronicos::tipo_documentos.table')
            </div>
            <div class="p-b-15 text-center">
                <!-- Cantidad de elementos a mostrar -->
                <div class="form-group row m-5 col-md-3 text-center d-sm-inline-flex">
                    <label for="show_qty" class="col-form-label col-md-7">Cantidad a mostrar:</label>
                    <div class="col-md-5">
                        <select class="form-control" v-model="dataPaginator.pagesItems" name="Cantidad a mostrar"><option value="5" selected="selected">5</option><option value="10">10</option><option value="15">15</option><option value="20">20</option><option value="25">25</option><option value="30">30</option><option value="50">50</option><option value="75">75</option></select>
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

        <!-- begin #modal-view-tipo-documentos -->
        <div class="modal fade" id="modal-view-tipo-documentos">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Información del tipo de documento</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                       @include('documentoselectronicos::tipo_documentos.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-tipo-documentos -->

        <!-- begin #modal-form-tipo-documentos -->
        <div class="modal fade" id="modal-form-tipo-documentos">
            <div class="modal-dialog modal-xl">
                <form @submit.prevent="save()" id="form-tipo-documentos">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of') tipo de documento</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('documentoselectronicos::tipo_documentos.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-tipo-documentos -->
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
    $('#modal-form-tipo-documentos').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
