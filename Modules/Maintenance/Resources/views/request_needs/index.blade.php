@extends('layouts.default')

@section('title', trans('requestNeeds'))

@section('section_img', '../assets/img/components/solicitudes.png')

@section('menu')
    @include('maintenance::layouts.menus.menu_resume')
@endsection

@section('content')

<crud
    name="request-needs"
    :resource="{default: 'request-needs', get: 'get-request-needs'}"
    :init-values="{ necesidades:[], dependencia: '{!! $dependencia ?? '' !!}'}"
    :crud-avanzado="true"
    inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('requestNeeds')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('requestNeeds')'}}</h1>
        <!-- end page-header -->

        
        @if(Auth::user()->hasRole('Administrador de mantenimientos'))
            <button type="button" @click="getDataWidgets" data-toggle="collapse" data-target="#contenedor_tablero"
                class="btn btn-outline-success" style="">
                <i class="fa fa-table"></i>&nbsp;&nbsp;&nbsp;
                <span id="text_btn_consolidado">Mostrar/Ocultar Contadores</span>
            </button>

            <div class="collapse border-bottom p-l-40 p-r-40 row justify-content-center pt-1" id="contenedor_tablero">
                <widget-counter
                icon="fa fa-folder"
                class-css-color="bg-grey"
                :qty="dataWidgets.total_solicitudes ?? 0"
                status="all"
                title="Todas"
                name-field="estado"
                :value="searchFields"
                :limpiar-filtros="['external_received.id']"
                :eliminar-parametros-url="['qder']">
                </widget-counter>

                <widget-counter
                    icon="fa fa-book-open"
                    class-css-color="bg-primary"
                    :qty="dataWidgets.estados?.en_elaboracion ?? 0"
                    status="En elaboración"
                    title="En elaboración"
                    name-field="estado"
                    :value="searchFields"
                    :limpiar-filtros="['external_received.id']"
                    :eliminar-parametros-url="['qder']">
                </widget-counter>

                <widget-counter
                    icon="far fa-cogs"
                    bg-color="#ffeb3b"
                    :qty="dataWidgets.estados?.en_revision ?? 0"
                    status="En revisión"
                    title="En revisión"
                    name-field="estado"
                    :value="searchFields"
                    :limpiar-filtros="['external_received.id']"
                    :eliminar-parametros-url="['qder']">
                </widget-counter>

                <widget-counter
                    icon="fas fa-thumbs-up"
                    class-css-color="bg-primary"
                    :qty="dataWidgets.estados?.solicitud_adicion ?? 0"
                    status="Solicitud de adición"
                    title="Solicitudes de adición"
                    name-field="estado"
                    :value="searchFields"
                    :limpiar-filtros="['external_received.id']"
                    :eliminar-parametros-url="['qder']">
                </widget-counter>
                <widget-counter
                    icon="fas fa-thumbs-up"
                    class-css-color="bg-warning"
                    :qty="dataWidgets.estados?.en_tramite ?? 0"
                    status="En trámite"
                    title="En trámite"
                    name-field="estado"
                    :value="searchFields"
                    :limpiar-filtros="['external_received.id']"
                    :eliminar-parametros-url="['qder']">
                </widget-counter>
                <widget-counter
                    icon="fa fa-check-circle"
                    class-css-color="bg-green"
                    :qty="dataWidgets.estados?.finalizada ?? 0"
                    status="Finalizada"
                    title="Finalizadas"
                    name-field="estado"
                    :value="searchFields"
                    :limpiar-filtros="['external_received.id']"
                    :eliminar-parametros-url="['qder']">
                </widget-counter>
                <widget-counter
                    icon="fa fa-times"
                    class-css-color="bg-gray"
                    :qty="dataWidgets.estados?.cancelada ?? 0"
                    status="Cancelada"
                    title="Cancelada"
                    name-field="estado"
                    :value="searchFields"
                    :limpiar-filtros="['external_received.id']"
                    :eliminar-parametros-url="['qder']">
                </widget-counter>
            </div>

        @endif
        <!-- end widget -->

        <!-- begin main buttons -->
        <div class="m-t-20">
            <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-request-needs" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('requestNeeds')
            </button>
        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('requestNeeds'): ${dataPaginator.total}` | capitalize }}</h5>
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
                                <date-picker
                                :value="searchFields"
                                name-field="created_at"
                                mode="range"
                                :key="keyRefresh" 
                             >
                             </date-picker>
                                <small>Filtrar por la fecha.</small>
                            </div>
                            <div class="col-md-4">
                                {!! Form::text('name', null, ['v-model' => 'searchFields.consecutivo', 'class' => 'form-control' ,'@keyup.enter' => 'pageEventActualizado(1)']) !!}
                                <small>Filtrar por el consecutivo.</small>
                            </div>
                            <div class="col-md-4">
                                <select v-model="searchFields.estado" class="form-control">
                                    <option value="En elaboración">En elaboración</option>
                                    <option value="En revisión">En revisión</option>
                                    <option value="En trámite">En trámite</option>
                                    <option value="Finalizada">Finalizada</option>
                                    <option value="Cancelada">Cancelada</option>
                                </select>
                                <small>Filtrar por el estado.</small>
                            </div>
                            <div class="col-md-4">
                                <select v-model="searchFields.tipo_solicitud" class="form-control">
                                    <option value="Activo">Activo</option>
                                    <option value="Inventario">Inventario</option>
                                    <option value="Stock">Stock</option>
                                </select>
                                <small>Filtrar por el Tipo de solicitud.</small>
                            </div>
                            <div class="col-md-4">
                                <select-check css-class="form-control" 
                                name-field="dependencias_id" 
                                reduce-label="nombre" 
                                name-resource="/intranet/get-dependencies" 
                                :value="searchFields" 
                                ref-select-check="dependencias_ref" 
                                :enable-search="true" >
                            </select-check>
                                <small>Filtrar por proceso</small>
                            </div>
                            <div class="col-md-4">
                               <autocomplete
                                name-prop="plaque"
                                name-field="activo_id_igual_"
                                :name-labels-display="['plaque']"
                                :value='searchFields'
                                name-resource="vehicles/id"
                                css-class="form-control"
                                reduce-key="id"
                                :is-required="true"
                                :key="keyRefresh"
                                >
                                </autocomple>
                                <small>Filtrar por la placa del vehículo.</small>
                            </div>   
                            <div class="col-md-4">
                                <select-check  
                                    css-class="form-control" 
                                    name-field="rubro_objeto_contrato_id_igual_"
                                    :reduce-label='["contract_number","object"]' 
                                    name-resource="get-contracts"
                                    :value="searchFields" 
                                    :enable-search="true" 
                                    reduce-key="id"
                                ></select-check>
                                <small>Filtrar por el número del contrato.</small>
                            </div>                                                     
                            <div class="col-md-4">
                                <select-check  
                                    css-class="form-control" 
                                    name-field="provider_id"
                                    :reduce-label='["name"]' 
                                    name-resource="get-providers"
                                    :value="searchFields" 
                                    :enable-search="true" 
                                    reduce-key="id"
                                ></select-check>
                                <small>Filtrar por el nombre del proveedor.</small>
                            </div>                                                     
                            <div class="col-md-5 mt-2">
                                <button @click="pageEventActualizado(1)" class="btn btn-md btn-primary"><i class="fa fa-search mr-2"></i>Buscar</button>
                                <button @click="clearDataSearchAvanzado()" class="btn btn-md btn-primary"><i class="fas fa-broom mr-2"></i>@lang('clear_search_fields')</button>
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
                            {{-- <a href="javascript:;" @click="exportDataTable('pdf')" class="dropdown-item text-white no-hover"><i class="fa fa-file-pdf mr-2"></i> PDF</a> --}}
                            <a href="javascript:;" @click="exportDataTableAvanzado('xlsx')" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i> EXCEL</a>
                        </div>
                    </div>
                </div>
                <!-- end buttons action table -->
                @include('maintenance::request_needs.table')
            </div>
            <div class="p-b-15 text-center">
                <!-- Cantidad de elementos a mostrar -->
                <div class="form-group row m-5 col-md-3 text-center d-sm-inline-flex">
                    {!! Form::label('show_qty', trans('show_qty').':', ['class' => 'col-form-label col-md-7']) !!}
                    <div class="col-md-5">
                        {!! Form::select(trans('show_qty'), [5 => 5, 10 => 10, 15 => 15, 20 => 20, 25 => 25, 30 => 30, 50 => 50, 100 => 100, 200 => 200], '5', ['class' => 'form-control', 'v-model' => 'dataPaginator.pagesItems','@change' => 'pageEventActualizado(1)']) !!}
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

        <!-- begin #modal-view-request-needs -->
        <div class="modal fade" id="modal-view-request-needs">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') @lang('requestNeeds')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        @include('maintenance::request_needs.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-request-needs -->


         <!-- begin #modal-view-request-needs -->
         <div class="modal fade" id="modal-view-request-needs-history">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') @lang('requestNeeds')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        @include('maintenance::request_needs.show_historial')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-request-needs -->
       

        <!-- begin #modal-form-request-needs -->
        <div class="modal fade" id="modal-form-request-needs">
            <div class="modal-dialog modal-xl">
                <form @submit.prevent="save()" id="form-request-needs">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of') @lang('requestNeeds') - @{{ dataForm.consecutivo }}</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        @if ($is_leader)
                            <div class="modal-body" v-if="openForm">
                                @include('maintenance::request_needs.fields_leader')
                            </div>
                        @else
                            <div class="modal-body" v-if="openForm">
                                @include('maintenance::request_needs.fields')
                            </div>
                        @endif
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-request-needs -->

        <alert-confirmation ref="enviar-revision"
            title="¿Enviar la solicitud de necesidades para revisión por parte del supervisor del contrato?"
            confirmation-text="Enviar a revisión"
            cancellation-text="Cerrar"
            name-resource="send-request-need"
            title-successful-shipment="Solicitud enviada con éxito">
        </alert-confirmation>

        {{-- Alerta de confirmar para el tipo de solicitud de stock --}}
        <alert-confirmation ref="enviar-revision-stock"
            title="Estás a punto de enviar una solicitud de salida al almacén"
            secondary-text="¿Está seguro de aceptar?"
            confirmation-text="Enviar a revisión"
            cancellation-text="Cerrar"
            name-resource="request-need-stock"
            title-successful-shipment="Solicitud enviada a revisión con éxito">
        </alert-confirmation>

        {{-- Alerta de confirmar para el tipo de solicitud de stock y de un proceso de aseo para enviar la orden directamente al almacen aseo --}}
        <alert-confirmation ref="enviar-revision-stock-aseo"
            title="Estás a punto de enviar una solicitud de salida al almacén aseo"
            secondary-text="¿Está seguro de aceptar?"
            confirmation-text="Enviar al almacén aseo"
            cancellation-text="Cancelar"
            name-resource="request-need-stock-aseo"
            title-successful-shipment="Solicitud enviada a revisión con éxito">
        </alert-confirmation>

        {{-- Alerta de confirmar para el tipo de solicitud de almacen --}}
        <alert-confirmation ref="enviar-revision-proveedor"
            title="Estás a punto de aprobar y enviar la orden al proveedor del contrato"
            secondary-text="¿Está seguro de aceptar?"
            confirmation-text="Enviar orden"
            cancellation-text="Cerrar"
            name-resource="request-need-warehouse"
            title-successful-shipment="Solicitud enviada con éxito">
        </alert-confirmation>

        
        <dynamic-modal-form
                v-if="openForm"
                modal-id="change-state-request"
                size-modal="lg"
                :title="'Cambiar estado de la solicitud ' + dataForm.consecutivo"
                :data-form="dataForm"
                endpoint="change-state-request"
                :is-update="true"
                confirmation-message-saved="Estás a punto de cambiar el estado de la solicitud.<br> <br> ¿Está seguro de aceptar?"
                @saved="
                    if($event.isUpdate) {
                        assignElementList(dataForm.id, $event.data);
                    } else {
                        addElementToList($event.data);
                    }">
                
                <template #fields="scope">

                    @include('maintenance::request_needs.fields_change_status')
                </template>
            </dynamic-modal-form>
            <!-- end -->
    
            <request-need ref="request-need"></request-need>

    </div>
</crud>
@endsection


@push('css')
{!!Html::style('assets/plugins/gritter/css/jquery.gritter.css')!!}

<style>
.custom-tooltip {
    position: relative;
    display: inline-block;
}

.custom-tooltip-content {
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    background-color: #f8f9fa;
    padding: 10px;
    border: 1px solid #ced4da;
    border-radius: 5px;
    display: none;
    width: 300px; /* Ajusta el ancho según tus necesidades */

}

.custom-tooltip:hover .custom-tooltip-content {
    display: block;
}

.custom-tooltip-trigger {
    cursor: pointer;
    color: #007bff;
}

</style>
@endpush

@push('scripts')
{!!Html::script('assets/plugins/gritter/js/jquery.gritter.js')!!}
<script>
    // detecta el enter para no cerrar el modal sin enviar el formulario
    $('#modal-form-request-needs').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });

    function hideShow() {
        var consolidado = document.getElementById("widget");
        if (consolidado.style.display === "none") {
            consolidado.style.display = "flex";
        } else {
            consolidado.style.display = "none";
        }
    }


</script>
@endpush
