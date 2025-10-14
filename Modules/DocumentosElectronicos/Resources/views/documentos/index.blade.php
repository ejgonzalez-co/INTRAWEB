@extends('layouts.default')

@section('title', trans('Documentos Electrónicos'))

@section('section_img', '')

@section('menu')
    @include('documentoselectronicos::layouts.menu')
@endsection

@section('content')

<crud
    name="documentos"
    :resource="{default: 'documentos', get: 'get-documentos'}"
    :init-values="{de_documento_firmars: [], de_compartidos: []}"
    inline-template :crud-avanzado="true"
    :init-values-search="{_obj_llave_valor_: {}}"
    :actualizar-listado-automatico="true">
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('Documentos electrónicos')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('documentos electrónicos')'}}</h1>
        <!-- end page-header -->

        <documentos-electronicos inline-template ref="documentos_ref" name="documentos" :listado-tipos-documentos="dataExtra['tipo_documentos']">
            <div>

                <div class="m-b-20" style="background-color: #d3d3d329; min-height: 304px; display: flex; justify-content: center; align-items: center;">
                    <div v-if="!loadedImagesComplete && listadoTiposDocumentos?.length != 0" class="spinner" style="margin: auto; position: static;"></div>
                    <div style="margin: auto; width: 90%;" :style="listadoTiposDocumentos?.length == 0 ? 'margin-top: 0;' : ''" v-show="loadedImagesComplete || listadoTiposDocumentos?.length == 0">
                        <div class="d-flex justify-content-between align-items-center mt-3">

                            <div class="d-flex align-items-center justify-content-center">
                                <span style="font-size:1rem">Crear un documento</span>
                            </div>
                            <div class="d-flex align-items-center" style="gap:.5rem;">
                                <button v-if="listadoTiposDocumentos?.length >= 8" type="button" title="Tipos de plantillas" class="btn btn-secondary" id="btnResizeEditor" @click="resizeTiposDocumentos($event, this)">
                                    <i class="fa fa-arrows-alt-v" aria-hidden="true"></i>
                                    Tipos de documentos
                                </button>
                                <h5 v-else>Tipos de documentos</h5>
                                <div v-if="listadoTiposDocumentos?.length >= 8" v-if="!tiposDocumentosExpanded && listadoTiposDocumentos?.length >= 8">
                                    <button class="btn" @click="plusSlides(-1)">&#10094;</button>
                                    <button class="btn" @click="plusSlides(1)">&#10095;</button>
                                </div>
                            </div>
                        </div>


                        <div class="container containerTD" :class="{ 'containterTiposDocumentosExpanded': tiposDocumentosExpanded }">
                            {{-- <div style="z-index: 2;">
                                <div class="p-r-20 m-b-20" style="background-color: #F8F8F8;">
                                    <div class="card" style="background-color: white;" @click="tipoDocumentoPlantilla(null, null, 'plantilla_en_blanco')" data-backdrop="static" data-target="#modal-form-documentos-tipo-documento" data-toggle="modal">
                                        <i class="fa fa-plus" aria-hidden="true" style="font-size: 30px; margin: auto; color: #4A9837;"></i>
                                    </div>
                                    <div class="card-text">Documento en blanco</div>
                                </div>
                            </div> --}}
                            <div v-if="!listadoTiposDocumentos?.length" style="font-size: 14px;">
                                @if(Auth::user()->hasRole('Admin Documentos Electrónicos'))
                                    <span>Aún no existen tipos de documentos creados. Puede crear uno desde la opción <a href="tipo-documentos">Tipos de Documentos</a></span>
                                @else
                                    <span>Aún no existen tipos de documentos creados. Por favor comuníquese con el administrador del sitio.</span>
                                @endif
                            </div>
                            <div class="m-r-20 m-b-20 templateTD" v-for="tipo_documento in listadoTiposDocumentos">
                                <div class="card" :key="tipo_documento.id"  @click="tipoDocumentoPlantilla(tipo_documento.plantilla, tipo_documento.id)" data-backdrop="static" data-target="#modal-form-documentos-tipo-documento" data-toggle="modal">
                                    <img :src="tipo_documento.preview_document ? '/storage/'+tipo_documento.preview_document : '/assets/img/plantilla_documento_vacio.png'" alt="Imagen" style="max-width: -webkit-fill-available; max-height: -webkit-fill-available; padding: 5px; width: 100%; border-radius: 3px;" @load="imageLoaded" onerror="this.src='/assets/img/plantilla_documento_vacio.png'">
                                </div>
                                <div class="card-text">@{{ tipo_documento.nombre }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- begin #modal-form-form-documentos-tipo-documento -->
                <div class="modal fade" id="modal-form-documentos-tipo-documento">
                    <div class="modal-dialog modal-lg">
                        <form @submit.prevent="crearDocumento()" id="form-documentos-tipo-documento">
                            <div class="modal-content border-0">
                                <div class="modal-header bg-blue">
                                    <h4 class="modal-title text-white">Crear documento</h4>
                                    <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                                </div>
                                <div class="modal-body" v-if="formOpen == 'tipoDocumento'">
                                    <div class="col-md-12">

                                        <!-- Origen documento Field -->
                                        <div class="form-group row m-b-15">
                                            {!! Form::label('origen_documento', trans('Origen del documento') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                                            <div class="col-md-9">
                                                {!! Form::select('origen_documento', ["Producir documento en línea a través de Intraweb" => "Producir documento en línea a través de Intraweb", "Adjuntar documento para ser almacenado en Intraweb" => "Adjuntar documento para ser almacenado en Intraweb"], 'Producido digitalmente', [':class' => "{'form-control':true, 'is-invalid':dataErrors.origen_documento }", 'v-model' => 'dataForm.origen_documento', 'required' => true]) !!}
                                                <small>@lang('Select the') el origen del documento</small>
                                                <div class="invalid-feedback" v-if="dataErrors.origen_documento">
                                                    <p class="m-b-0" v-for="error in dataErrors.origen_documento">@{{ error }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Titulo Asunto Field -->
                                        <div class="form-group row m-b-15">
                                            {!! Form::label('titulo_asunto', trans('Título') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                                            <div class="col-md-9">
                                                {!! Form::text('titulo_asunto', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.titulo_asunto }", 'v-model' => 'dataForm.titulo_asunto', 'required' => true]) !!}
                                                <small>@lang('Enter the') el título del documento a crear</small>
                                                <div class="invalid-feedback" v-if="dataErrors.titulo_asunto">
                                                    <p class="m-b-0" v-for="error in dataErrors.titulo_asunto">@{{ error }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Documento Adjunto Field -->
                                        <div class="form-group row" v-if="!dataForm.plantilla && dataForm.origen_documento == 'Producir documento en línea a través de Intraweb'">
                                            {!! Form::label('documento_adjunto', trans('Plantilla').':', ['class' => 'col-form-label col-md-3']) !!}
                                            <div class="col-md-9">
                                                {!! Form::file('documento_adjunto', ['accept' => '*', '@change' => 'inputFile($event, "documento_adjunto")', 'required' => false]) !!}
                                                <small>Opcional: @lang('Select the') una plantilla si desea</small>
                                                <div class="invalid-feedback" v-if="dataErrors.documento_adjunto">
                                                    <p class="m-b-0" v-for="error in dataErrors.documento_adjunto">@{{ error }}</p>
                                                </div>
                                            </div>
                                            <div class="alert alert-info w-100" role="alert">
                                                Si no elige una plantilla, se creará un documento en blanco.
                                            </div>
                                        </div>


                                        <!-- Documento principal Field -->
                                        <div class="form-group row" v-if="dataForm.origen_documento == 'Adjuntar documento para ser almacenado en Intraweb'">
                                            {!! Form::label('document_pdf', trans('Documento principal').':', ['class' => 'col-form-label col-md-3']) !!}
                                            <div class="col-md-9">
                                                {!! Form::file('document_pdf', ['accept' => '*', '@change' => 'inputFile($event, "document_pdf")', 'required' => false]) !!}
                                                <small>Opcional: @lang('Select the') el documento principal</small>
                                                <div class="invalid-feedback" v-if="dataErrors.document_pdf">
                                                    <p class="m-b-0" v-for="error in dataErrors.document_pdf">@{{ error }}</p>
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
                <!-- end #modal-form-documentos-tipo-documento -->

                <!-- begin #modal-form-documentos -->
                <div class="modal fade" id="modal-form-documentos" v-show="dataForm.origen_documento" data-backdrop="static">
                    <div class="modal-dialog" :class="{'modal-xl': dataForm.origen_documento == 'Adjuntar documento para ser almacenado en Intraweb'}" :style="dataForm.origen_documento == 'Producir documento en línea a través de Intraweb' ? 'max-width: 94vw;' : ''">
                        <form @submit.prevent="actualizarDocumento()" id="form-documentos" autocomplete="off">
                            <div class="modal-content border-0">
                                <div class="modal-header bg-blue">
                                    <h4 class="modal-title text-white">Creación y edición de documentos electrónicos</h4>
                                    <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                                </div>
                                <button v-if="dataForm.origen_documento == 'Producir documento en línea a través de Intraweb'" type="button" :title="btnResizeEditor" class="btn btn-secondary" style="margin-left: auto; margin-bottom: -36px; z-index: 10; margin-right: 10px;" id="btnResizeEditor" @click="resizeEditor($event, this)">
                                    <i v-if="btnResizeEditor == 'Maximizar visor'" class="fa fa-arrow-left" aria-hidden="true"></i>
                                    <i v-if="btnResizeEditor == 'Minimizar visor'" class="fa fa-arrow-right" aria-hidden="true"></i>
                                    @{{ btnResizeEditor }}
                                </button>
                                <div v-if="dataForm.origen_documento == 'Producir documento en línea a través de Intraweb'" style="display: flex;">
                                    <div class="modal-body column col-md-6" id="formularioIzq" v-if="formOpen == 'FormularioDocumento'">
                                        @include('documentoselectronicos::documentos.fields')
                                    </div>

                                    <iframe v-if="dataForm.plantilla" class="column" :src="dataForm.plantilla+'?rm=embedded&embedded=true'" id="editorDer" style="width: 100%; margin-right: 1px; border-right-color: rgb(0 0 0 / 56%);"></iframe>
                                    <iframe v-else-if="dataForm.documento_adjunto" class="column" :src="'/storage/'+dataForm.documento_adjunto" id="editorDer" style="width: 100%; margin-right: 1px; border-right-color: rgb(0 0 0 / 56%);"></iframe>
                                </div>
                                <div v-if="dataForm.origen_documento == 'Adjuntar documento para ser almacenado en Intraweb' && formOpen == 'FormularioDocumento'" class="modal-body column col-md-12" id="formularioIzq">
                                    @include('documentoselectronicos::documentos.fields')
                                </div>

                                <div class="modal-footer">
                                    <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                                    <button @click="actualizarDocumento('guardar_avance')" type="button" class="btn btn-secondary"><i class="fa fa-save mr-2"></i>Guardar avance</button>

                                    <button v-if="dataForm.tipo == 'publicacion'" type="submit" class="btn" style="background-color: #8bc34a; color:#FFFFFF"><i class="fas fa-paper-plane mr-2"></i>Publicar documento</button>
                                    <button v-if="dataForm.tipo == 'firmar_varios'" type="submit" class="btn btn-warning" style="background-color: #ff9800"><i class="fas fa-paper-plane mr-2"></i>Enviar a firmar</button>
                                    <button v-if="dataForm.tipo == 'revision'" type="submit" class="btn" style="background-color: #ffeb3b"><i class="fas fa-paper-plane mr-2"></i>Enviar a revisión</button>
                                    <button v-if="dataForm.tipo == 'elaboracion'" type="submit" class="btn btn-primary"><i class="fas fa-paper-plane mr-2"></i>Enviar a elaboración</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- end #modal-form-documentos -->

                <!-- begin #modal-aprobar-cancelar-firma -->
                <div class="modal fade" id="modal-aprobar-cancelar-firma" v-show="dataForm.origen_documento">
                    <div class="modal-dialog" :class="{'modal-xl': dataForm.origen_documento == 'Adjuntar documento para ser almacenado en Intraweb'}" :style="dataForm.origen_documento == 'Producir documento en línea a través de Intraweb' ? 'max-width: 94vw;' : ''">
                        <form @submit.prevent="FirmaDocumento()" id="form-documentos" autocomplete="off">
                            <div class="modal-content border-0">
                                <div class="modal-header bg-blue">
                                    <h4 class="modal-title text-white">Aprobar firma o devolver para modificaciones</h4>
                                    <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                                </div>
                                <button v-if="dataForm.origen_documento == 'Producir documento en línea a través de Intraweb'" type="button" :title="btnResizeEditor" class="btn btn-secondary" style="margin-left: auto; margin-bottom: -36px; z-index: 10; margin-right: 10px;" id="btnResizeEditor" @click="resizeEditor($event, this)">
                                    <i v-if="btnResizeEditor == 'Maximizar visor'" class="fa fa-arrow-left" aria-hidden="true"></i>
                                    <i v-if="btnResizeEditor == 'Minimizar visor'" class="fa fa-arrow-right" aria-hidden="true"></i>
                                    @{{ btnResizeEditor }}
                                </button>
                                <div v-if="dataForm.origen_documento == 'Producir documento en línea a través de Intraweb' && formOpen == 'FormularioFirmarDocumento'" style="display: flex; min-height: 72vh;">
                                    <div class="modal-body column col-md-6" id="formularioIzq">
                                        @include('documentoselectronicos::documentos.fields_aprobar_devolver_firma')
                                    </div>

                                    <iframe v-if="$parent.routeFileUrl && $parent.routeFileUrl != 'cargando_documento'" class="column" :src="$parent.routeFileUrl" id="editorDer" style="width: 100%; margin-right: 1px; border-right-color: rgb(0 0 0 / 56%);"></iframe>
                                    <div class="col-md-6" v-else>
                                        <div class="spinner"></div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- end #modal-aprobar-cancelar-firma -->
            </div>
        </documentos-electronicos>

        <!-- begin main buttons -->
        <button onclick="window.location.href = window.location.href.split('?')[0];" class="btn btn-md btn-primary m-b-10"><i class="fa fa-redo-alt mr-2"></i>Cargar página de nuevo</button>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('documentos'): ${dataPaginator.total}` | capitalize }}</h5>
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
                <div class="card-header bg-white pointer-cursor d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center col-md-5 position-relative">
                        <input 
                            type="text"
                            placeholder="Buscar documento"
                            autocomplete="off"
                            class="w-100"
                            v-model="searchFieldsAdvanced.general" 
                            :style="searchFieldsAdvanced.isFocused ? 'outline:none; box-shadow: rgba(153, 153, 153, 0.48) 2px 2px 5px 1px; transition: box-shadow 0.3s; border: 1px solid rgb(170, 170, 170); border-radius: 20px; padding: 7px 120px 7px 40px;' : 'outline:none; border:1px solid #aaa; border-radius:20px; padding: 7px 120px 7px 40px'" 
                            @focus="searchFieldsAdvanced.isFocused = true" 
                            @blur="searchFieldsAdvanced.isFocused = false" 
                            @keyup.enter="pageEventActualizado(1)"
                            @keyup="!searchFieldsAdvanced.general ? $delete(searchFieldsAdvanced, general) : null">
                        <i class="fa fa-search fa-fw f-s-12 position-absolute" style="left: 30px; top: 50%; transform: translateY(-50%);"></i>
                        <label @click="pageEventActualizado(1)" class="position-absolute m-0 cursor-pointer" style="right: 35px; top: 50%; transform: translateY(-50%); color: #2196F3; font-weight: 500;">Buscar</label>
                    </div>
                    <div class="cursor-pointer">
                        <svg data-toggle="collapse" data-target="#collapseOne" xmlns="http://www.w3.org/2000/svg" 
                            data-container="body" 
                            data-toggle="popover" 
                            data-placement="bottom" 
                            data-html="true"
                            data-content=""
                            width="24" 
                            height="24" 
                            viewBox="0 0 24 24">
                            <path d="M3 17v2h6v-2H3zM3 5v2h10V5H3zm10 16v-2h8v-2h-8v-2h-2v6h2zM7 9v2H3v2h4v2h2V9H7zm14 4v-2H11v2h10zm-6-4h2V7h4V5h-4V3h-2v6z"></path>
                        </svg>
                    </div>
                </div>
                <div id="collapseOne" class="collapse border-bottom p-l-40 p-r-40" data-parent="#accordion">
                    <div class="card-body">
                        <!-- Campos de busqueda -->
                        <div class="row form-group">
                            <div class="col-md-4">
                                <input type="text" v-model="searchFields.consecutivo" class="form-control" @keyup.enter="pageEventActualizado(1)" @keyup="!searchFields.consecutivo ? $delete(searchFields, consecutivo) : null">
                                <i class="fas fa-hashtag" style="position: absolute; right: 5%; top: 30%; transform: translateY(-50%);"></i>
                                <small>Filtrar por el consecutivo</small>
                            </div>

                            <div class="col-md-4 p-b-5">
                                <input type="text" v-model="searchFields.titulo_asunto" class="form-control" @keyup.enter="pageEventActualizado(1)" @keyup="!searchFields.titulo_asunto ? $delete(searchFields, titulo_asunto) : null">
                                <small>Filtrar por  el título del documento</small>
                            </div>

                            <div class="col-md-4 p-b-5">
                                <select class="form-control" v-model="searchFields.estado">
                                    <option value="Devuelto para modificaciones">Devuelto para modificaciones</option>
                                    <option value="Elaboracion">Elaboración</option>
                                    <option value="Pendiente de firma">Pendiente de firma</option>
                                    <option value="Público">Público</option>
                                    <option value="Revisión">Revisión</option>
                                </select>
                                <small>Filtrar por estado</small>
                            </div>

                            <div class="col-md-4 p-b-5">
                                <date-picker
                                    :value="searchFields"
                                    name-field="de_documento.created_at"
                                    mode="range"
                                    :input-props="{required: true}">
                                </date-picker>
                                <i class="far fa-calendar-alt" style="position: absolute; right: 5%; top: 23%; transform: translateY(-50%);"></i>
                                <small>Filtrar por fecha de creación</small>
                            </div>

                            <div class="col-md-4 mb-3">
                                <input type="text" v-model="searchFields.users_name" class="form-control" @keyup.enter="pageEventActualizado(1)" @keyup="!searchFields.users_name ? $delete(searchFields, users_name) : null">
                                <i class="far fa-user" style="position: absolute; right: 5%; top: 30%; transform: translateY(-50%);"></i>
                                <small>Filtrar por el usuario quien creo el documento</small>
                            </div>

                            <div class="col-md-4 mb-3">
                                <input type="text" v-model="searchFields.compartidos" class="form-control" @keyup.enter="pageEventActualizado(1)" @keyup="!searchFields.compartidos ? $delete(searchFields, compartidos) : null">
                                <i class="far fa-user" style="position: absolute; right: 5%; top: 30%; transform: translateY(-50%);"></i>
                                <small>Filtrar por usuarios compartidos</small>
                            </div>

                            <div class="col-md-4 p-b-5">
                                <select-check
                                    css-class="form-control"
                                    name-field="vigencia"
                                    reduce-label="vigencia"
                                    reduce-key="valor"
                                    name-resource="/get-vigencias/de_documento/vigencia"
                                    :value="searchFields">
                                </select-check>
                                <small>Filtrar por vigencia</small>
                            </div>

                            <div class="col-md-12 p-b-5">
                                <select-check
                                    css-class="form-control"
                                    name-field="de_tipos_documentos_id"
                                    reduce-label="nombre"
                                    reduce-key="id"
                                    name-resource="obtener-tipo-documentos-filtros-docs"
                                    :value="searchFields"
                                    :value-aux="searchFieldsAux"
                                    :is-required="true"
                                    :enable-search="true"
                                    name-field-object="tipo_documentos"
                                    :ids-to-empty="['_obj_llave_valor_']"
                                    :is-filtro="true">
                                 </select-check>
                                <small>Filtrar por tipo de documento</small>
                            </div>

                            <div v-if="searchFieldsAux.tipo_documentos?.de_metadatos.length > 0" class="col-md-10 p-t-10 p-b-10 ml-4 my-3" style="border: 1px solid #6c757d57;">
                                <strong>Metadatos del tipo de documento</strong>
                                <span class="row">
                                    <div class="col-md-4 p-t-5" v-for="metadato in searchFieldsAux.tipo_documentos?.de_metadatos">
                                        <input v-if="metadato.tipo != 'Listado'" :type="metadato.tipo == 'Texto' ? 'text' : (metadato.tipo == 'Número' ? 'number' : ('date'))" v-model="searchFields._obj_llave_valor_[metadato.metadato_v_model]" :name="'metadato_'+metadato.id" :id="'metadato_'+metadato.id" class="form-control" :required="metadato.requerido" :placeholder="'Filtrar por '+metadato.nombre_metadato" @keyup.enter='pageEventActualizado(1)' @keyup="!searchFields._obj_llave_valor_[metadato.metadato_v_model] ? $delete(searchFields._obj_llave_valor_, metadato.metadato_v_model) : null">
                                        <span v-else>
                                            <select v-model="searchFields._obj_llave_valor_[metadato.metadato_v_model]" :name="'metadato_'+metadato.id" :id="'metadato_'+metadato.id" class="form-control" :required="metadato.requerido">
                                                <option v-for="opcion in metadato.opciones_listado.split(', ')" :value="opcion">@{{ opcion }}</option>
                                            </select>
                                            <small>@{{ metadato.texto_ayuda }}</small>
                                        </span>
                                    </div>
                                </span>
                            </div>
                            <div class="col-md-5">
                                <button @click="pageEventActualizado(1)" class="btn btn-md btn-add mr-2"><i class="fa fa-search mr-2"></i>Buscar</button>
                                <button @click="clearDataSearchAvanzado()" class="btn btn-md btn-light"><i class="fas fa-broom mr-2"></i>@lang('clear_search_fields')</button>
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
                <viewer-attachement :link-file-name="true" type="table" ref="viewerDocuments"></viewer-attachement>

                @if(config('app.mod_expedientes'))
                    <expedientes-general
                        ref="expedientes"
                        :campo-consecutivo="'consecutivo'"
                        :modulo="'Documentos electrónicos'"
                        :puede-crear-expedientes="{{ Auth::user()->roles->pluck('name')->intersect(['Operador Expedientes Electrónicos'])->isNotEmpty() ? 'true' : 'false' }}"
                        :user-id="{{ Auth::user()->id }}"
                    ></expedientes-general>
                @endif

                @include('documentoselectronicos::documentos.table')
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
                        <h4 class="modal-title text-white d-flex justify-content-between align-items-center w-100">
                            <span>Detalles del documento electrónico</span>
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                       @include('documentoselectronicos::documentos.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-documentos -->

        <annotations-general ref="anotacionesDocumentos" route="/documentos-electronicos/documento-anotacions" name-list="documento_anotacions" file-path="public/container/documentos_electronicos_{{ date('Y') }}/anotaciones"  field-title="Anotaciones del Documento eletrónico: " field-title-var="consecutivo" name-content="contenido" name-attached="adjuntos" name-users-name="nombre_usuario"></annotations-general>

        <dynamic-modal-form
            modal-id="compartir-documento"
            size-modal="lg"
            :title="'Compartir documento electrónico: '+dataForm.consecutivo"
            :data-form.sync="dataForm"
            endpoint="documento-compartir"
            :is-update="true"
            @saved="
                if($event.isUpdate) {
                    assignElementList(dataForm.id, $event.data);
                } else {
                    addElementToList($event.data);
                }">
            <template #fields="scope">
                <div class="row">
                    <div class="col-md-12">
                        <h6>Comparta el documento con usuarios o dependencias para que puedan visualizarlo</h6>
                        <!-- Compartidos -->
                        <div class="form-group row m-b-15">
                            {!! Form::label('users_compartidos', 'Funcionarios con quien se compartirá el documento:', ['class' => 'col-form-label col-md-3']) !!}
                            <div class="col-md-9">
                                <add-list-autocomplete :value="dataForm" name-prop="ids"
                                    name-field-autocomplete="users_compartidos_auto" name-field="users_compartidos"
                                    name-resource="/documentos-electronicos/get-compartidos"
                                    name-options-list="de_compartidos" :name-labels-display="['nombre']" name-key="id"
                                    help="Autocomplete el nombre de uno o varios funcionarios con quien se compartirá el documento y seleccionelo o presione la tecla enter para agregarlo. Ejemplo: Fernanda"
                                    :key="keyRefresh">
                                </add-list-autocomplete>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </dynamic-modal-form>
    </div>
</crud>
@endsection


@push('css')
{!!Html::style('assets/plugins/gritter/css/jquery.gritter.css')!!}
<style>
    .column {
  /* flex: 1; */
  overflow: auto;
  padding: 10px;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

.resizer {
  width: 10px;
  cursor: ew-resize;
  background: #333;
  /* height: 100%; */
  position: relative;
  right:-5px;
}
.oculto
{
	display:none !important;
}

.visible
{
	display:inline !important;
}

.vdr.active:before{
    outline: none !important;
}
/* Estilos para pantallas más pequeñas, por ejemplo, dispositivos móviles */
@media (max-width: 768px) {
    /* Oculta el iframe en dispositivos móviles */
    iframe#editorDer {
        display: none;
    }

    #btnResizeEditor {
        display: none;
    }


}
/* Estilos para pantallas más grandes */

@media (min-width: 769px) {
    #botonGoogleDocs {
        display: none;
    }

    #btnResizeEditor {
        display: block;
    }
}

.selected {
    font-size: 14px; /* Tamaño más grande para el botón seleccionado */
    color: #fff; /* Cambia el color del texto del botón seleccionado a blanco */
    font-weight: bold; /* Añade negrita para hacerlo más resaltado */
}

</style>
@endpush

@push('scripts')
{!!Html::script('assets/plugins/gritter/js/jquery.gritter.js')!!}
<script>
    // detecta el enter para no cerrar el modal sin enviar el formulario
    $('#modal-form-documentos, #compartir-documento, #form-documentos-tipo-documento').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });

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
