@extends('layouts.default')

@section('title', 'Notificaciones de intraweb')

@section('section_img', '')

@section('menu')
    @include('notificacionesintraweb::layouts.menu')
@endsection

@section('content')

<crud
    :crud-avanzado="true"
    name="notificaciones-mail-intrawebs"
    :resource="{default: 'notificaciones-mail-intrawebs', get: 'get-notificaciones-mail-intrawebs'}"
    :init-values-search="{estado_notificacion: ''}"
    inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">notificaciones de intraweb</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center">@{{ '@lang('main_view_to_manage') las notificaciones de intraweb'}}</h1>
        <!-- end page-header -->
        <p style="font-size: 17px;" class="text-center  m-b-35">Utiliza los filtros para buscar tus notificaciones de correspondencia</p>
        <!-- begin main buttons -->

        @if (Auth::user()->hasRole('Admin seven'))
            <div class="m-t-20">
                <div class="m-t-20">
                    <button @click="callFunctionComponent('send-petition-message','getPetition','')" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-listado-correos-checkeos" data-toggle="modal">
                        <i class="fas fa-paper-plane"></i> Reenvio de correos
                    </button>
                </div>
            </div>
        @endif

        <!-- end main buttons -->
        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') notificaciones de intraweb: ${dataPaginator.total}` | capitalize }}</h5>
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
                                <select class="form-control" v-model="searchFields._igual_estado_notificacion">
                                    <option value="Enviado">Enviado</option>
                                    <option value="No enviado">No enviado</option>
                                    <option value="Rebote">Rebote</option>
                                    <option value="Cancelado">Cancelado</option>
                                    <option value="Enviado por el correo institucional">Enviado por el correo institucional</option>
                                </select>
                                <small>Filtro por estado de la notificación</small>
                            </div>
                            <div class="col-md-4">
                                <!-- <input v-model="searchFields.modulo" class="form-control" placeholder="Filtro por modulo de origen" name="modulo" type="text" @keyup.enter="pageEventActualizado(1)"> -->
                                <select class="form-control" v-model="searchFields.modulo">
                                    <option value="Correspondencia externa">Correspondencia externa</option>
                                    <option value="Correspondencia interna">Correspondencia interna</option>
                                    <option value="Correspondencia recibida">Correspondencia recibida</option>
                                    <option value="PQRSD">PQRSD</option>
                                    <option value="Documentos electronicos">Documentos electronicos</option>
                                    <option value="Mesa de ayuda">Mesa de ayuda</option>
                                    <option value="Identificación de necesidades">Identificación de necesidades</option>
                                    <option value="Expedientes electrónicos">Expedientes electrónicos</option>
                                    <option value="Planes de mejoramiento">Planes de mejoramiento</option>

                                </select>
                                <small>Filtro por el módulo de donde salio la notificación</small>
                            </div>
                            <div class="col-md-4">
                                <input v-model="searchFields.consecutivo" class="form-control" placeholder="Filtrar por consecutivo" name="consecutivo" type="text" @keyup.enter="pageEventActualizado(1)">
                                <small>Filtro por el consecutivo</small>

                            </div>
                            <div class="col-md-4">
                                <input v-model="searchFields.correo_destinatario" class="form-control" placeholder="Filtrar por correo del destinatario" name="correo_destinatario" type="text" @keyup.enter="pageEventActualizado(1)">
                            </div>
                            <div class="col-md-4">
                                <input v-model="searchFields.asunto_notificacion" class="form-control" placeholder="Filtrar por asunto de la notificación" name="correo_destinatario" type="text" @keyup.enter="pageEventActualizado(1)">
                            </div>
                            <div class="col-md-4">
                                <date-picker
                                    :value="searchFields"
                                    name-field="created_at"
                                    mode="range"
                                    :input-props="{required: true}">
                                </date-picker>
                                <small>Filtrar entre fechas de recepción</small>
                            </div>

                            @role('Administrador intranet')
                                <div class="col-md-4">
                                    {!! Form::text('user_name', null, ['v-model' => 'searchFields.user_name', 'class' => 'form-control', 'placeholder' => 'Filtrar por usuario remitente',  '@keyup.enter' => 'pageEventActualizado(1)' ]) !!}
                                </div>
                            @endrole

                            <div class="col-md-4">
                                <select class="form-control" v-model="searchFields.leido" name="leido">
                                    <option value="Si">Si</option>
                                    <option value="No">No</option>
                                </select>
                                <small>Filtro por correos leidos</small>
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
                <div class="float-xl-right m-b-15">
                    <!-- Acciones para exportar datos de tabla-->
                    <div class="btn-group">
                        <a href="javascript:;" data-toggle="dropdown" class="btn btn-primary"><i class="fa fa-download mr-2"></i> @lang('export_data_table')</a>
                        <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle" aria-expanded="false"><b class="caret"></b></a>
                        <div class="dropdown-menu dropdown-menu-right bg-blue">
                            <a href="javascript:;" @click="exportDataTableAvanzado('xlsx')" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i>Reporte Excel</a>
                            {{-- <a href="export-exaple" class="dropdown-item text-white no-hover" target="_blank">EXCEL</a> --}}
                        </div>
                    </div>
                </div>
                <!-- end buttons action table -->
                @include('notificacionesintraweb::notificaciones_mail_intrawebs.table')
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

        <!-- begin #modal-view-notificaciones-mail-intrawebs -->
        <div class="modal fade" id="modal-view-notificaciones-mail-intrawebs">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') de la notificación</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">

                        @include('notificacionesintraweb::notificaciones_mail_intrawebs.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>

        <dynamic-modal-form
                v-if="isUpdate"
                modal-id="modal-reenviar-notificacion"
                size-modal="lg"
                title="Reenviar notificación"
                :data-form="dataForm"
                name-button="Enviar"
                class-button="fas fa-paper-plane"
                endpoint="reenviar-notificación"
                :is-update="true"
                @saved="
                    if($event.isUpdate) {
                        assignElementList(dataForm.id, $event.data);
                    } else {
                        addElementToList($event.data);
                    }">

                <template #fields="scope">

                        <div class="row">
                            <dt class="text-inverse text-left col-2 ">Asunto de la notifiación:</dt>
                            <dd class="col-7 ">@{{ dataForm.asunto_notificacion ? dataForm.asunto_notificacion : 'N/A' }}</dd>
                        </div>
                        <br>
                        <div class="row">
                               <!-- Cities Id Field -->
                            <dt class="text-inverse text-left col-2 ">Mensaje de la notificación:</dt>
                            <dd class="col-7" v-if="dataForm.mensaje_notificacion" v-html="dataForm.mensaje_notificacion"></dd>
                            <dd class="col-7" v-else>N/A</dd>

                        </div>
                        <br>
                        <div class="row">
                            <dt class="text-inverse text-left col-2 ">Correo destinatario:</dt>
                            <input type="text" class="form-control col-7" v-model="dataForm.correo_destinatario">
                        </div>
                        <div class="row">
                            <dt class="text-inverse text-left col-2"></dt>

                            <label class="col-7" style="font-style: italic; font-size: smaller; color: gray;">
                                <strong>Nota:</strong> Esta notificación ha rebotado debido a un problema con este correo. Por favor, contacte al usuario e ingrese una nueva dirección de correo electrónico. Se recomienda también actualizarlo en la administración de usuarios para evitar futuros problemas de entrega.
                            </label>
                        </div>


                </template>
            </dynamic-modal-form>
        <!-- end #modal-view-notificaciones-mail-intrawebs -->

        <send-petition-message ref="send-petition-message" title-alert="Reenvio de correos masivo" text-alert="¿Desea reenviar todos los correos que no se entregaron debido a restricciones del firewall?" resource-alert="/send-forward-email" :loading-alert="true" text-loading="Reenviando correos..." :assign-element="false" :download-export-data="true" name-export="Reporte_Reenvio_Correos" title-confirmation-swal="Correos reenviados" text-confirmation-swal="Los correos han sido reenviados con éxito. En el archivo PDF exportado, encontrarás la información detallada de los correos que fueron reenviados." request-type="post"></send-petition-message>
        <!-- end #modal-form-notificaciones-mail-intrawebs -->
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
    $('#modal-form-notificaciones-mail-intrawebs').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
