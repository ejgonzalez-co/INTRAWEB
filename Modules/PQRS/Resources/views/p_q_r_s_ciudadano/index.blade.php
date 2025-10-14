@extends('layouts.default')

@section('title', trans('PQRS'))

@section('section_img', '/assets/img/components/convocatoria.png')

@section('menu')
    @include('pqrs::layouts.menu_ciudadano')
@endsection

@section('content')

<crud
    name="p-q-r-s"
    :resource="{default: 'p-q-r-s-ciudadano', get: 'get-p-q-r-s'}"
    :init-values-search="{pqrs_propios: 'rol_superior'}"
    inline-template :crud-avanzado="true">
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">PQRS</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">PQRS radicados</h1>
        <!-- end page-header -->
        <pqr-component ref="componentePQR" :key="keyRefresh" :mostrar-componente="false"></pqr-component>
        <!-- begin main buttons -->
        <div class="m-t-20">

            <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-p-q-r-s" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>@lang('crud.create') PQR
            </button>
        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') PQRS: ${dataPaginator.total}` | capitalize }}</h5>
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
                                <select-check
                                    css-class="form-control"
                                    name-field="vigencia"
                                    reduce-label="vigencia"
                                    reduce-key="valor"
                                    name-resource="/get-vigencias/pqr/vigencia"
                                    :value="searchFields">
                                </select-check>
                                <small>Filtrar por vigencia</small>
                            </div>
                            <div class="col-md-4">
                                <input type="text" v-model="searchFields.pqr_id" class="form-control" placeholder="Filtrar por número de radicado" @keyup.enter="pageEventActualizado(1)" @keyup="!searchFields.pqr_id ? $delete(searchFields, pqr_id) : null">
                            </div>
                            <div class="col-md-4">
                                {!! Form::select('estado', 
                                    ["Abierto" => "Abierto", 
                                    "Asignado" => "Asignado", 
                                    "En trámite" => "En trámite", 
                                    "Esperando respuesta del ciudadano" => "Esperando respuesta del ciudadano",
                                    "Finalizado" => "Finalizado",
                                    "Finalizado vencido justificado" => "Finalizado vencido justificado",
                                    "Respuesta parcial" => "Respuesta parcial",
                                    "Devuelto" => "Devuelto",
                                    "Cancelado" => "Cancelado"], null, ['v-model' => 'searchFields.estado', 'class' => 'form-control']) !!}
                                <small>Filtrar por estado</small>
                            </div>
                            <div class="col-md-4">
                                {!! Form::select('linea_tiempo', 
                                    ["A tiempo" => "A tiempo", 
                                    "Próximo a vencer" => "Próximo a vencer", 
                                    "Vencido" => "Vencido"], null, ['v-model' => 'searchFields.linea_tiempo', 'class' => 'form-control']) !!}
                                <small>Filtrar por categoría en tiempo</small>
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
                                <date-picker
                                    :value="searchFields"
                                    name-field="fecha_vence"
                                    mode="range"
                                    :input-props="{required: true}">
                                </date-picker>
                                <small>Filtrar por fecha de vencimiento</small>
                            </div>
                            <div class="col-md-4">
                                <input type="text" v-model="searchFields.funcionario_destinatario" class="form-control" placeholder="Filtrar por funcionario destinatario" @keyup.enter="pageEventActualizado(1)" @keyup="!searchFields.funcionario_destinatario ? $delete(searchFields, funcionario_destinatario) : null">
                            </div>
                            <div class="col-md-4">
                                <select-check
                                    css-class="form-control"
                                    name-field="pqr_eje_tematico_id"
                                    reduce-label="nombre"
                                    reduce-key="id"
                                    name-resource="get-p-q-r-eje-tematicos"
                                    :value="searchFields">
                                </select-check>
                                <small>Filtrar por eje temático</small>
                            </div>
                            <div class="col-md-4">
                                {!! Form::select('destacado', 
                                    [1 => "Si"], null, ['v-model' => 'searchFields.destacado', 'class' => 'form-control']) !!}
                                <small>Filtrar por destacado</small>
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
                {{-- <div class="float-xl-right m-b-15" v-if="dataList.length">
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
                <viewer-attachement :link-file-name="true" type="table" ref="viewerDocuments"></viewer-attachement>
                <!-- end buttons action table -->
                @include('pqrs::p_q_r_s_ciudadano.table')
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

        <!-- begin #modal-view-p-q-r-s -->
        <div class="modal fade" id="modal-view-p-q-r-s">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of')l PQR</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                       @include('pqrs::p_q_r_s_ciudadano.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-p-q-r-s -->

        <!-- begin #modal-form-p-q-r-s -->
        <div class="modal fade" id="modal-form-p-q-r-s">
            <div class="modal-dialog modal-xl">
                <form @submit.prevent="!isUpdate ? callFunctionComponent('componentePQR','addRequerimiento') : save();" id="form-p-q-r-s">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of') PQR</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('pqrs::p_q_r_s_ciudadano.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-p-q-r-s -->
        <holiday-calendar ref="holiday-calendars" name="holiday-calendars"></holiday-calendar>

        <!-- Formulario para que el ciudadano de su respuesta -->
        <dynamic-modal-form modal-id="answer-p-q-r-s" size-modal="lg" title="Responder PQR"
            :data-form.sync="dataForm" endpoint="p-q-r-s-answer" :is-update="true"
            @saved="
            if($event.isUpdate) {
                assignElementList(dataForm.id, $event.data);
            } else {
                addElementToList($event.data);
            }">
            <template #fields="scope">
                <div class="row">
                    <div class="col-md-12 mt-5">
                        <div class="form-group row m-b-15">
                            {!! Form::label('pregunta_ciudadano', 'Preguntas', ['class' => 'col-form-label col-md-3 required']) !!}
                            <div class="col-md-9">
                                <textarea
                                rows="10"
                                style="cursor:not-allowed; background-color: #e9ecef;"
                                type="text"
                                class="field form-control"
                                v-model="dataForm.pregunta_ciudadano"
                                readonly>
                                </textarea>
                                <small>Pregunta del funcionario (espacio que diligencia el funcionario)</small>
                                <div class="invalid-feedback" v-if="dataErrors.pregunta_ciudadano">
                                    <p class="m-b-0" v-for="error in dataErrors.pregunta_ciudadano">@{{ error }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-5">
                        <!-- Respuesta del ciudadano -->
                        <div class="form-group row m-b-15">
                            {!! Form::label('respuesta_ciudadano', 'Respuesta del ciudadano', ['class' => 'col-form-label col-md-3 required']) !!}
                            <div class="col-md-9">
                                {!! Form::textarea('respuesta_ciudadano', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.respuesta_ciudadano }", 'v-model' => 'dataForm.respuesta_ciudadano', 'required' => true]) !!}
                                <small>Respuesta del ciudadano (espacio que diligencia el ciudadano)</small>
                                <div class="invalid-feedback" v-if="dataErrors.respuesta_ciudadano">
                                    <p class="m-b-0" v-for="error in dataErrors.respuesta_ciudadano">@{{ error }}</p>
                                </div>
                            </div>
                        </div>

                        <!--  adjunto respuesta del ciudadano-->
                        <div class="form-group row m-b-15">
                            {!! Form::label('adjunto_r_ciudadano', 'Adjunto respuesta ciudadano:', ['class' => 'col-form-label col-md-3']) !!}
                            <div class="col-md-9">
                                <input-file :file-name-real="true":value="dataForm" name-field="adjunto_r_ciudadano" :max-files="1"
                                    :max-filesize="11" file-path="public/container/pqr_{{ date('Y') }}"
                                    message="Arrastre o seleccione los archivos" help-text="Lista de archivo de respuesta parcial\. El tamaño máximo permitido es de 10 MB\."
                                    :is-update="isUpdate" :key="keyRefresh" ruta-delete-update="pqrs/p-q-r-s-delete-file" :id-file-delete="dataForm.id" :mostrar-eliminar-adjunto="dataForm.users_id == {{ Auth::user()->id }}">
                                </input-file>
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
@endpush

@push('scripts')
{!!Html::script('assets/plugins/gritter/js/jquery.gritter.js')!!}
<script>
    // detecta el enter para no cerrar el modal sin enviar el formulario
    $('#modal-form-p-q-r-s').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
