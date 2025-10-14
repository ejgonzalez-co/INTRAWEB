@extends('layouts.default')

@section('title', trans('Árbol de documentos'))

@section('section_img', '')

@section('menu')
    @include('calidad::layouts.menu')
@endsection

@section('content')

<crud
    name="documentos"
    :resource="{default: 'obtener-arbol-documentos'}"
    inline-template
    :init-values="{expanded: false}">
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('Árbol de documentos')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">Árbol de documentos</h1>
        <!-- end page-header -->

        <documentos-calidad ref="documento_calidad_ref" name="documentos-calidad"></documentos-calidad>

        <div style="background-color: ghostwhite; padding: 5px;">
            <div @click="callFunctionComponent('documento_calidad_ref', 'toggleArbolDocumentos', dataForm)" class="toggle-container" style="font-size: 17px;">
                <i :class="{'fa': true, 'fa-chevron-down': dataForm.expanded, 'fa-chevron-right': !dataForm.expanded}" class="m-r-5"></i>
                <i :class="{'fas': true, 'fa-folder-open': dataForm.expanded, 'fa-folder': !dataForm.expanded}"></i>
                Tipos de sistemas
            </div>
            <ul v-show="dataForm.expanded" style="font-size: 17px;">
                <li v-for="tipoSistema in advancedSearchFilterPaginate()" :key="tipoSistema.id">
                    <div @click="callFunctionComponent('documento_calidad_ref', 'toggleArbolDocumentos', tipoSistema)" class="toggle-container">
                        <i :class="{'fa': true, 'fa-chevron-down': tipoSistema.expanded, 'fa-chevron-right': !tipoSistema.expanded}" class="m-r-5"></i>
                        <i class="fa fa-bars"></i>
                        @{{ tipoSistema.nombre_sistema }}
                    </div>
                    <ul v-show="tipoSistema.expanded">
                        <li v-for="proceso in tipoSistema['procesos_arbol']" :key="proceso.id">
                            <div @click="callFunctionComponent('documento_calidad_ref', 'toggleArbolDocumentos', proceso)" class="toggle-container">
                                <i :class="{'fa': true, 'fa-chevron-down': proceso.expanded, 'fa-chevron-right': !proceso.expanded}"></i>
                                <i :class="{'fas': true, 'fa-folder-open': proceso.expanded, 'fa-folder': !proceso.expanded}"></i>
                                Proceso <strong>@{{ proceso.nombre }}</strong>
                            </div>
                            <ul v-show="proceso.expanded">
                                <li v-for="subproceso in proceso['subprocesos_arbol']" :key="subproceso.id">
                                    <div @click="callFunctionComponent('documento_calidad_ref', 'toggleArbolDocumentos', subproceso)" class="toggle-container">
                                        <i :class="{'fa': true, 'fa-chevron-down': subproceso.expanded, 'fa-chevron-right': !subproceso.expanded}"></i>
                                        <i :class="{'fas': true, 'fa-folder-open': subproceso.expanded, 'fa-folder': !subproceso.expanded}"></i>
                                        Subproceso <strong>@{{ subproceso.nombre }}</strong>
                                    </div>
                                    <ul v-show="subproceso.expanded">
                                        <li v-for="documento in subproceso['documentos_arbol']" :key="documento.id">
                                            <a :href="'/storage/' + documento.document_pdf" target="_blank">
                                                <img v-if="documento.formato_archivo == 'bizagi'" src="/assets/img/bizagi_icon.png" width="16" height="16" style="vertical-align: text-top;">
                                                <i v-else :class="{
                                                    'fas': true,
                                                    'fa-file-excel': (documento.formato_publicacion == 'Formato original' ? (documento.formato_archivo == 'xlsx' || documento.formato_archivo == 'xls') : false),
                                                    'fa-file-word': (documento.formato_publicacion == 'Formato original' ? (documento.formato_archivo == 'docx' || documento.formato_archivo == 'doc') : false),
                                                    'fa-file-pdf': documento.formato_publicacion == 'PDF' || documento.formato_archivo == 'pdf',
                                                    'fa-file-archive': documento.formato_archivo == 'zip',
                                                    'fa-file': !(documento.formato_archivo == 'xlsx' || documento.formato_archivo == 'xls' || documento.formato_archivo == 'docx' || documento.formato_archivo == 'doc' || documento.formato_archivo == 'pdf')
                                                }"></i>
                                                Documento <strong>@{{ documento.titulo }}</strong>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li v-for="documento in proceso['documentos_arbol']" :key="documento.id">
                                    <a :href="'/storage/' + documento.document_pdf" target="_blank">
                                        <img v-if="documento.formato_archivo == 'bizagi'" src="/assets/img/bizagi_icon.png" width="16" height="16" style="vertical-align: text-top;">
                                        <i v-else :class="{
                                            'fas': true,
                                            'fa-file-excel': (documento.formato_publicacion == 'Formato original' ? (documento.formato_archivo == 'xlsx' || documento.formato_archivo == 'xls') : false),
                                            'fa-file-word': (documento.formato_publicacion == 'Formato original' ? (documento.formato_archivo == 'docx' || documento.formato_archivo == 'doc') : false),
                                            'fa-file-pdf': documento.formato_publicacion == 'PDF' || documento.formato_archivo == 'pdf',
                                            'fa-file': !(documento.formato_archivo == 'xlsx' || documento.formato_archivo == 'xls' || documento.formato_archivo == 'docx' || documento.formato_archivo == 'doc' || documento.formato_archivo == 'pdf')
                                        }"></i>
                                        Documento <strong>@{{ documento.titulo }}</strong>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</crud>
@endsection

@push('css')
    {!!Html::style('assets/plugins/gritter/css/jquery.gritter.css')!!}
    <style>
        li {
            list-style-type: none; /* Elimina el marcador */
        }

        .fa {
            width: 15px;
        }

        a {
            color: black;
        }

        .fa-folder, .fa-folder-open {
            color: #EEC260;
            width: 20px;
        }

        .fa-file-word {
            color: #015097;
        }

        .fa-file-excel {
            color: #006D37;
        }

        .fa-file-pdf {
            color: #AD0B00;
        }

        .fa-file {
            color: #F7F7F7;
        }
    </style>
@endpush

@push('scripts')
{!!Html::script('assets/plugins/gritter/js/jquery.gritter.js')!!}
<script>
    // detecta el enter para no cerrar el modal sin enviar el formulario
    $('#modal-form-documentos').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
