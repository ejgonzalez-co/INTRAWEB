@extends('layouts.default')

@section('title', trans('Autorizaciones de hojas de vida'))

@section('section_img', '/assets/img/components/doc_funcionario.png')

@section('menu')
    @include('workhistories::layouts.menu_request')
@endsection

@section('content')

<crud
    ref="crud"
    name="workRequests"
    :resource="{default: 'work-request', get: 'get-work-request'}"
    inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/home') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">Autorizaciones de hojas de vida</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        @if(Auth::user()->hasRole('Administrador historias laborales')) 
        <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') autorizaciones de hojas de vida'}}</h1>
        @else
        <h1 class="page-header text-center m-b-35">Vista principal para gestionar solicitudes de hojas de vida</h1>
        @endif 
        <!-- end page-header -->
        <!-- begin widget -->
        <div class="row">
            <!-- Cada widget es la cantidad de registros que hay por estado -->
            <widget-counter
                icon="fa fa-user-check"
                class-css-color="bg-yellow"
                :qty="dataList.filter((data) =>  data.condition == 'Pendiente').length"
                title="Pendientes"
            ></widget-counter>
            <widget-counter
            icon="fa fa-user-check"
            class-css-color="bg-green"
            :qty="dataList.filter((data) =>  data.condition == 'Aprobado').length"
            title="Aprobados"
            ></widget-counter>
            <widget-counter
            icon="fa fa-user-check"
            class-css-color="bg-orange"
            :qty="dataList.filter((data) =>  data.condition == 'En ejecución').length"
            title="En ejecución"
            ></widget-counter>
            <widget-counter
            icon="fa fa-user-check"
            class-css-color="bg-blue"
            :qty="dataList.filter((data) =>  data.condition ==  'Finalizado').length"
            title="Finalizado"
            ></widget-counter>
            <widget-counter
            icon="fa fa-user-check"
            class-css-color="bg-red"
            :qty="dataList.filter((data) =>  data.condition == 'Cancelado').length"
            title="Cancelado"
            ></widget-counter>
        </div>
        <!-- end widget -->

        <!-- begin main buttons -->
        <div class="m-t-20">
            @if(Auth::user()->hasRole('Gestor hojas de vida'))  
            <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-workRequests" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>@lang('crud.create') solicitud
            </button>
            @endif
        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') autorizaciones de hojas de vida: ${dataPaginator.total}` | capitalize }}</h5>
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
                            <div class="col-md-4 mb-4">
                                {!! Form::text('user_name', null, ['v-model' => 'searchFields.user_name', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Nombre del usuario consultado')]) ]) !!}
                            </div>
                            <div class="col-md-4 mb-4">
                                {!! Form::text('document_user', null, ['v-model' => 'searchFields.document_user', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Cedula del usuario consultado')]) ]) !!}
                            </div>
                            @if(Auth::user()->hasRole('Administrador historias laborales'))
                                <div class="col-md-4 mb-4">
                                    {!! Form::text('create_user', null, ['v-model' => 'searchFields.create_user', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Nombre del usuario que consulta')]) ]) !!}
                                </div>
                            @endif
                            @if(Auth::user()->hasRole('Administrador historias laborales'))
                            <div class="col-md-4 mb-4">
                            <select v-model="searchFields.state" class="form-control col-md-9">
                                <option value="Activo">Activo</option>
                                <option value="Pensionado">Pensionado</option>
                            </select>
                            <small>Seleccione si es pensionado o activo</small>
                            </div>
                            @endif
                            <div class="col-md-4 mb-4">
                                <button @click="clearDataSearch()" class="btn btn-md btn-primary">@lang('clear_search_fields')</button>
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
                @include('workhistories::work_requests.table')
            </div>
            <div class="p-b-15 text-center">
                <!-- Cantidad de elementos a mostrar -->
                <div class="form-group row m-5 col-md-3 text-center d-sm-inline-flex">
                    {!! Form::label('show_qty', trans('show_qty').':', ['class' => 'col-form-label col-md-7']) !!}
                    <div class="col-md-5">
                        {!! Form::select(trans('show_qty'), [5 => 5, 10 => 10, 15 => 15, 20 => 20, 25 => 25, 30 => 30, 50 => 50, 100 => 100, 200 => 200], '5', ['class' => 'form-control', 'v-model' => 'dataPaginator.pagesItems']) !!}
                    </div>
                </div>
                <!-- Paginador de tabla -->
                <div class="col-md-12">
                    <paginate
                        v-model="dataPaginator.currentPage"
                        :page-count="dataPaginator.numPages"
                        :click-handler="pageEvent"
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

        <!-- begin #modal-view-workRequests -->
        <div class="modal fade" id="modal-view-workRequests">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') autorización de hojas de vida</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body" id="view-workRequests">
                        @include('workhistories::work_requests.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-warning" type="button" onclick="printContent('view-workRequests');"><i class="fa fa-print mr-2"></i>@lang('print')</button>
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-workRequests -->

        <!-- begin #modal-form-workRequests -->
        <div class="modal fade" id="modal-form-workRequests">
            <div class="modal-dialog modal-lg">
                <form @submit.prevent="save()" id="form-workRequests">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of') autorización de hojas de vida</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('workhistories::work_requests.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane mr-2"></i>Enviar solicitud</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-workRequests -->

        <approve-request ref="approve-request"></approve-request>



{{--         
                <!--Ventana modal para enviar a verificacion de pago -->
                <dynamic-modal-form
                modal-id="modal-aproved-request"
                size-modal="lg"
                :data-form="dataForm"
                :is-update="true"
                title="Información de la solicitud"
                confirmation-message-saved="Desea enviar esta solicitud a verificación de pago ?"
                endpoint="url-receipt-payment"
                @saved="
                if($event.isUpdate) {
                    assignElementList(dataForm.id, $event.data);
                } else {
                    addElementToList($event.data);
                }"
                >
                <template #fields="scope">
                    <div>
                        <div class="panel" style="border: 200px; padding: 15px;">
                            <div class="row">
                                <label>Nombre del usuario que va ser consultado:  &nbsp; &nbsp;</label>
                                <label><b> @{{ dataForm.user_name }} </b></label>
                            </div>
                            <div class="row">
                                <label>Tiempo solicitado para la consulta:  &nbsp; &nbsp;</label>
                                <label><b> @{{ dataForm.consultation_time }} </b></label>
                            </div>
                            <div class="row">
                                <label>Razón de la consulta:  &nbsp; &nbsp;</label>
                                <label><b> @{{ dataForm.reason_consultation }} </b></label>
                            </div>
                            <div class="row">
                                <label>Nombre del solicitante:  &nbsp; &nbsp;</label>
                                <label><b> @{{ dataForm.users?.name }}  &nbsp;- &nbsp; @{{ dataForm.users?.dependencies?.nombre }}  </b></label>
                            </div>
                        </div>
                    </div>
                </template>
                </dynamic-modal-form>
                <!-- end #modal-form-titleRegistrations --> --}}
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
    $('#modal-form-workRequests').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });


       // Función para imprimir el contenido de un identificador pasado por parámetro
    function printContent(divName) {
            // Se obtiene el elemento del id recibido por parámetro
            var printContent = document.getElementById(divName);
            // Se guarda en una variable la nueva pestaña
            var printWindow = window.open("");
            // Se obtiene el encabezado de la página actual para no peder estilos
            var headContent = document.getElementsByTagName('head')[0].innerHTML;
            // Se escribe todo el contenido del encabezado de la página actual en la pestaña nueva que se abrirá
            printWindow.document.write(headContent);
            // Se escribe todo el contenido del id recibido por parámetro en la pestaña nueva que se abrirá
            printWindow.document.write(printContent.innerHTML);
            printWindow.document.close();
            // Se enfoca en la pestaña nueva
            printWindow.focus();
            // Se esperan 10 milésimas de segundos para imprimir el contenido de la pestaña nueva
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 500);
    }
</script>
@endpush
