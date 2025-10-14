@extends('layouts.default')

@section('title', trans('request-need-orders'))

@section('section_img', '../assets/img/components/solicitudes.png')

@section('menu')
@include('maintenance::layouts.menus.menu_orders')
@endsection

@section('content')

<crud
    name="request-need-orders"
    :resource="{post: 'request-need-orders/{{$needId}}', default: 'request-need-orders', get: 'get-request-need-orders/{{$needId}}'}" 
    :init-values="{mant_sn_request_id : '{{$needId}}', ordenes_item:[], ordenes_entradas:[]}"
    :crud-avanzado="true"
    inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/home') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('requestNeeds')</li>
            <li class="breadcrumb-item active">@lang('request-need-orders')</li>
        </ol>
        <!-- end breadcrumb -->

     

        @role('Administrador de mantenimientos')
        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">Administrar ordenes de la solicitud de necesidades: {{$needConsecutivo}}</h1>
        <!-- end page-header -->

        <!-- begin main buttons -->
        <div class="m-t-20">

            <a href="/maintenance/request-needs" class="btn btn-primary m-b-10"><i class="fas fa-home"></i> Atrás</a>

            <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-request-need-orders" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('request-need-order')
            </button>
        </div>
        <!-- end main buttons -->
        @endrole
        @if (session('outside') )

        <h1 class="page-header text-center m-b-35">Vista solicitudes de productos -Mantenimientos</h1>
        @else
            @unless (Auth::check() && auth()->user()->hasRole('Administrador de mantenimientos'))
            <h1 class="page-header text-center m-b-35">Administrar ordenes de solicitudes de necesidades</h1>
            @endunless
        @endif

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('request-need-orders'): ${dataPaginator.total}` | capitalize }}</h5>
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
                                {!! Form::text('name', null, ['v-model' => 'searchFields.consecutivo', 'class' => 'form-control', '@keyup.enter' => 'pageEventActualizado(1)']) !!}
                                <small>Filtrar por el consecutivo de la orden.</small>
                            </div>
                            <div class="col-md-4">
                                <select v-model="searchFields.estado" class="form-control">
                                    <option value="Orden en elaboración">Orden en elaboración</option>
                                    <option value="Orden en trámite">Orden en trámite</option>
                                    <option value="Orden Cancelada">Orden Cancelada</option>
                                    <option value="Orden Finalizada">Orden Finalizada</option>
                                </select>
                                <small>Filtrar por el estado.</small>
                            </div>
                            <div class="col-md-4">
                                <select v-model="searchFields.estado_proveedor" class="form-control">
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="Pendiente por finalizar">Pendiente por finalizar</option>
                                    <option value="Finalizado">Finalizado</option>
                                </select>
                                <small>Filtrar por el estado del proveedor.</small>
                            </div>

                            <div class="col-md-4">
                                <select v-model="searchFields.no_factura_igual_" id="no_factura_filter" class="form-control">
                                    <option value=1>Pendientes por número de factura</option>
                                    <option value=0>Con número de factura registrado</option>
                                </select>
                                <small class="form-text text-muted">
                                    Selecciona si deseas ver las entradas que aún no tienen número de factura o aquellas que ya lo registraron.
                                </small>
                            </div>
                            @if (session('outside'))
                                <div class="col-md-4">
                                    <select-check  
                                        css-class="form-control" 
                                        name-field="numero_contrato"
                                        :reduce-label='["contract_number","object"]' 
                                        :name-resource="'get-contracts-by-external-provider/' + @json(session('id'))"
                                        :value="searchFields" 
                                        :enable-search="true" 
                                        reduce-key="id"
                                    ></select-check>
                                    <small>Filtrar por el número del contrato.</small>
                                </div>
                            @endif
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
                {{-- <div class="float-xl-right m-b-15">
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
                <!-- end buttons action table -->
                {{-- verifica si el usuario en sesion es proveedor externo --}}
                @if (session('outside'))
                    @include('maintenance::request_need_orders.table_outside')
                @else
                    @include('maintenance::request_need_orders.table')    
                @endif
               
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

        <!-- begin #modal-view-request-need-orders -->
        <div class="modal fade" id="modal-view-request-need-orders">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') @lang('request-need-orders') #@{{ dataShow.consecutivo }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        @include('maintenance::request_need_orders.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-request-need-orders -->

        <!-- begin #modal-view-request-need-orders-by-external-provider -->
        <div class="modal fade" id="modal-view-request-need-orders-by-external-provider">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Información de la orden: @{{ dataShow.consecutivo }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        @include('maintenance::request_need_orders.show_fields_by_external_provider')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-request-need-orders-by-external-provider -->

        <!-- begin #modal-form-request-need-orders -->
        <div class="modal fade" id="modal-form-request-need-orders">
            <div class="modal-dialog modal-lg">
                <form @submit.prevent="save()" id="form-request-need-orders">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of') @lang('request-need-orders')</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            
                                @include('maintenance::request_need_orders.fields')
                            
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-request-need-orders -->

        

        <dynamic-modal-form
                modal-id="send-request-need-order"
                size-modal="lg"
                title="Aprobar y enviar orden "
                :data-form="dataForm"
                endpoint="send-request-need-order"
                :is-update="true"
                confirmation-message-saved="Estás a punto de aprobar y enviar la orden.<br> <br> ¿Está seguro de aceptar?"
                @saved="
                    if($event.isUpdate) {
                        assignElementList(dataForm.id, $event.data);
                    } else {
                        addElementToList($event.data);
                    }">
                
                <template #fields="scope">

                        <div class="row" v-if="dataForm.estado == 'Orden en elaboración'">    

                            <div class="col-md-12">
                                <div class="form-group row m-b-15">
                                    <h1 for="danger" class="col-form-label col-md-12">Aprobar y enviar la orden</h1>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group row m-b-15">
                                    <label for="estado_orden" class="col-form-label col-md-4 required">Estado de la orden:</label>
                                    <div class="col-md-8">
                                        <select class="form-control" v-model="scope.dataForm.estado_orden" name="estado_orden" id="estado_orden" required>
                                            <option value="">Seleccione</option>
                                            <option value="Aprobar y enviar al proveedor externo">Aprobar y enviar al proveedor externo</option>
                                            <option value="Aprobar y enviar al proveedor interno">Aprobar y enviar al proveedor interno</option>
                                            <option value="Aprobar y enviar al Stock">Aprobar y enviar al Stock</option>

                                        </select>
                                    </div>
                                </div>
                            </div>  


                            <div class="col-md-12" v-if="scope.dataForm.estado_orden=='Aprobar y enviar al proveedor externo'">

                                <div class="form-group row m-b-15">
                                    <label for="asginar" class="col-form-label col-md-4 required">Asignar a:</label>
                                    <div class="col-md-8">
                                     
                                        <input type="text" class="form-control" v-model="scope.dataForm.proveedor_nombre" disabled required>

                                    </div>
                                </div>

                                <div class="form-group row m-b-15">
                                    <label for="bodega" class="col-form-label col-md-4 required">Con destino a la bodega de:</label>
                                    <div class="col-md-8">
                                        <select class="form-control" v-model="scope.dataForm.bodega" name="bodega" id="bodega" required>
                                            <option value="">Seleccione</option>
                                            <option value="Almacén de Aseo">Almacén de Aseo</option>
                                            <option value="Almacén CAM">Almacén CAM</option>
                                            {{-- <option value="Sede Corbones">Sede Corbones</option>
                                            <option value="Gestión captación y tratamiento">Gestión captación y tratamiento</option>
                                            <option value="Laboratorio Calibración de Medidores">Laboratorio Calibración de Medidores</option>
                                            <option value="Laboratorio de ensayo de calidad de agua">Laboratorio de ensayo de calidad de agua</option> --}}
                                        </select>
                                    </div>
                                </div>

                            </div>  

                           

                            <div class="col-md-12" v-if="scope.dataForm.estado_orden=='Aprobar y enviar al proveedor interno'">

                                <div class="form-group row m-b-15">
                                    <label for="asignar" class="col-form-label col-md-4 required">Asignar a:</label>
                                    <div class="col-md-8">
                                       
                                        <select-check  
                                            css-class="form-control" 
                                            name-field="funcionario_id"
                                            reduce-label='name' 
                                            name-resource="get-providers_internals"
                                            :value="dataForm" 
                                            :enable-search="true" 
                                            reduce-key="id"
                                        ></select-check>

                                    </div>
                                </div>

                            </div>  

                            
                            <div class="col-md-12" v-if="scope.dataForm.estado_orden=='Aprobar y enviar al Stock'">

                                <div class="form-group row m-b-15">
                                    <label for="asignar" class="col-form-label col-md-4 required">Asignar a:</label>
                                    <div class="col-md-8">
                                       
                                        <select class="form-control" v-model="scope.dataForm.rol_asignado_nombre" name="rol_asignado_nombre" id="rol_asignado_nombre">
                                            <option value="">Seleccione</option>
                                            <option value="Almacén CAM">Almacén CAM</option>
                                            <option value="Almacén Aseo">Almacén Aseo</option>
                                        </select>

                                    </div>
                                </div>

                            </div>  


                            <div class="col-md-12">
                                <div class="form-group row m-b-15">
                                    <label for="danger" class="col-form-label col-md-4">Observaciones:</label>
                                    <div class="col-md-8">
                                        <textarea class="form-control"  type="text" v-model="scope.dataForm.observacion" placeholder=""></textarea>
                                    </div>
                                </div>
                            </div>

                        </div>
                </template>
            </dynamic-modal-form>
            <!-- end -->


            {{-- formulario para proveedor interno --}}
            <dynamic-modal-form
                modal-id="finish-state-provider"
                size-modal="lg"
                :title="'Finalizar Orden ' + dataForm.consecutivo"
                :data-form="dataForm"
                endpoint="finish-state-provider"
                :is-update="true"
                confirmation-message-saved="Estás a punto de enviar una notificación informativa sobre el estado de la orden de servicio a la Jefatura de Mantenimiento de la EPA.<br> <br> ¿Está seguro de aceptar?"
                @saved="
                    if($event.isUpdate) {
                        assignElementList(dataForm.id, $event.data);
                    } else {
                        addElementToList($event.data);
                    }">
                
                <template #fields="scope">

                        <div class="row">    

                            <div class="col-md-12">
                                <div class="form-group row m-b-15">
                                    <h1 for="danger" class="col-form-label col-md-12">Información general</h1>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group row m-b-15">
                                    <label for="estado_proveedor" class="col-form-label col-md-4 required">Finalizar Solicitud</label>
                                    <div class="col-md-8">
                                        <select class="form-control" v-model="scope.dataForm.estado_proveedor" name="estado_proveedor" id="estado_proveedor" required>
                                            <option value="">Seleccione</option>
                                            <option value="Finalizado">Si - Finalizar</option>
                                            <option value="Pendiente">No - Pendiente por finalizar</option>
                                        </select>
                                    </div>
                                </div>
                            </div>  

        
                            <div class="col-md-12">
                                <div class="form-group row m-b-15">
                                    <label for="danger" class="col-form-label col-md-4 required">Observaciones:</label>
                                    <div class="col-md-8">
                                        <textarea class="form-control" required type="text" v-model="scope.dataForm.observacion" placeholder=""></textarea>
                                    </div>
                                </div>
                            </div>

                        </div>
                </template>
            </dynamic-modal-form>
            <!-- end -->

            
            {{-- formulario de entrada--}}
            <dynamic-modal-form
                modal-id="modal-form-entrada"
                size-modal="lg"
                :title="'Gestionar entrada de la orden: ' + dataForm.consecutivo"
                :data-form="dataForm"
                endpoint="send-entrada"
                :is-update="true"
                confirmation-message-saved="Estás a punto de guardar la entrada. Después de guardar el formulario, no se podrán realizar modificaciones.<br> <br> ¿Está seguro de aceptar?"
                @saved="
                    if($event.isUpdate) {
                        assignElementList(dataForm.id, $event.data);
                    } else {
                        addElementToList($event.data);
                    }">
                
                <template #fields="scope">
                    @include('maintenance::request_need_orders.fields_entrada')
                </template>
            </dynamic-modal-form>
            <!-- end -->

             {{-- formulario de salida--}}
             <dynamic-modal-form
                modal-id="modal-form-salida"
                size-modal="lg"
                title="Gestionar Salida"
                :data-form="dataForm"
                endpoint="send-salida"
                :is-update="true"
                confirmation-message-saved="Estás a punto de gestionar una salida.<br> <br> ¿Está seguro de aceptar?"
                @saved="
                    if($event.isUpdate) {
                        assignElementList(dataForm.id, $event.data);
                    } else {
                        addElementToList($event.data);
                    }">
                
                <template #fields="scope">
                    @include('maintenance::request_need_orders.fields_salida')
                </template>
            </dynamic-modal-form>
            <!-- end -->

                     
            {{-- formulario de numero de factura--}}
            <dynamic-modal-form
                modal-id="modal-form-entrada-factura"
                size-modal="lg"
                :title="'Agregar número de factura de la orden: ' + dataForm.consecutivo"
                :data-form="dataForm"
                endpoint="send-numero-factura"
                :is-update="true"
                confirmation-message-saved="Estás a punto de guardar la entrada. Después de guardar el formulario, no se podrán realizar modificaciones.<br> <br> ¿Está seguro de aceptar?"
                @saved="
                    if($event.isUpdate) {
                        assignElementList(dataForm.id, $event.data);
                    } else {
                        addElementToList($event.data);
                    }">
                
                <template #fields="scope">
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-2 required">Número de entrada almacén:</label>
                            <div class="col-md-10">

                                {!! Form::text('numero_entrada_almacen', null, ['class' => 'form-control', 'v-model' => 'dataForm.numero_entrada_almacen', 'required' => true]) !!}
                                <small></small>
                            </div>
                        </div>
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-2 required">Número de factura:</label>
                            <div class="col-md-10">
                                {!! Form::text('numero_factura', null, ['class' => 'form-control', 'v-model' => 'dataForm.numero_factura', 'required' => true]) !!}
                                <small></small>
                            </div>
                        </div>                       
                </template>
            </dynamic-modal-form>
            <!-- end -->

           <!-- begin #modal-order -->
           <div class="modal fade" id="modal-view-order-history">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') @lang('requestNeeds')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        @include('maintenance::request_need_orders.show_historial')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-order -->

        <dynamic-modal-form
        modal-id="modal-form-request-order"
        size-modal="lg"
        :title="'Finalizar Orden ' + dataForm.consecutivo"
        :is-update="true"
        :data-form="dataForm"
        endpoint="finish-request"
        confirmation-message-saved="Estás a punto de finalizar una orden.<br> <br> ¿Está seguro de aceptar?"
        @saved="
            if($event.isUpdate) {
                assignElementList(dataForm.id, $event.data);
            } else {
                addElementToList($event.data);
            }">
        
            <template #fields="scope">
                <div class="panel" v-show="dataForm.have_spart">
                    <div class="panel-heading">
                        <div class="panel-title" v-if="dataForm.tramite_almacen === 'Entrada Confirmada'"><strong>Información de ingreso al almacén</strong></div>
                        <div class="panel-title" v-if="dataForm.tramite_almacen === 'Salida Confirmada'"><strong>Información de salida del almacén</strong></div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-hover text-inverse table-bordered" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif">
                                <thead class="text-center bg-green">
                                    <tr>
                                        <td><strong>Descripción </strong></td>
                                        <td><strong>Unidad de medida </strong></td>
                                        <td><strong>Cantidad solicitada </strong></td>
                                        <td><strong>Código </strong></td>
                                        <td><strong>Cantidad ingresada al almacén </strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, key) in dataForm.ordenes_item" v-if="item.tipo_necesidad === 'Repuestos'">
                                        <td>@{{ item.descripcion_nombre }}</td>
                                        <td>@{{ item.unidad }}</td>
                                        <td>@{{ item.cantidad }}</td>
                                        <td>
                                            <input v-if="item.codigo_entrada" type="text" :value="item.codigo_entrada" class="form-control" disabled>
                                            <input v-else type="text" :value="item.codigo_salida" class="form-control" disabled>
                                        </td>
                                        <td>
                                            <input v-if="item.cantidad_entrada" type="number" :value="item.cantidad_entrada" class="form-control" disabled>
                                            <input v-else type="number" :value="item.cantidad" class="form-control" disabled>
                                        </td>
                
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-right">
                            <strong>Total: $@{{ dataForm.valor_total_necesidades_repuestos ?? 0 }}</strong>
                        </div>
                    </div>
                </div>
                <div class="panel" v-show="dataForm.have_activities">
                    <div class="panel-heading">
                        <div class="panel-title"><strong>Información de actividades</strong></div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-hover text-inverse table-bordered" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif">
                                <thead class="text-center bg-green">
                                    <tr>
                                        <td><strong>Descripción </strong></td>
                                        <td><strong>Unidad de medida </strong></td>
                                        <td><strong>Cantidad solicitada </strong></td>
                                        <td><strong>Actividad realizada por el proveedor </strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, key) in dataForm.ordenes_item" v-if="item.tipo_necesidad === 'Actividades'">
                                        <td>@{{ item.descripcion_nombre }}</td>
                                        <td>@{{ item.unidad }}</td>
                                        <td>@{{ item.cantidad }}</td>
                                        <td><input type="number" class="form-control" v-model="item.cantidad_final"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p>Si el proveedor realizó la actividad solicitada, por favor deja el campo vacío. En caso de que no haya realizado la actividad, ingrese cero para evitar aplicar el descuento del rubro. Si realizó una cantidad menor o mayor, ingrese la cantidad correspondiente en números para ajustar el descuento en el rubro</p>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row m-b-15">
                        <label for="danger" class="col-form-label col-md-4">Observación:</label>
                        <div class="col-md-8">
                            <textarea class="form-control"  type="text" v-model="scope.dataForm.observacion_finalizacion" placeholder=""></textarea>
                            <small>Por favor, ingrese cualquier observación adicional antes de finalizar la solicitud de  productos, servicios y/o equipos.</small>
                        </div>
                    </div>
                </div>
            </template>
        </dynamic-modal-form>
       
        {{-- Componente para crear y editar una solicitud cuando es mediante el proveedor externo --}}
        <external-provider-form ref="externalProviderForm" name="resume-machinery-vehicles-yellows"></external-provider-form>

        {{-- Componente para exportar solicitud de necesidad --}}
        <request-need endpoint="export-request-need-external-provider" ref="request-need"></request-need>
            

          
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
    $('#modal-form-request-need-orders').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
