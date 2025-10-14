@extends('layouts.default')

@section('title', trans('officials'))

@section('section_img', '/assets/img/components/estructura_organizacional.png')

@section('menu')
    @include('leca::layouts.menu_officials')
@endsection

@section('content')

<crud
    name="officials"
    :resource="{default: 'officials', get: 'get-officials'}"
    inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('officials')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('officials')'}}</h1>
        <!-- end page-header -->

        <!-- begin main buttons -->
        <div class="m-t-20">
            <a href="/dashboard" class="btn btn-primary m-b-10">
                <i class="fa fa-arrow-left mr-3"></i>Regresar
            </a>
            <a  href="javascript:location.reload()"  class="btn btn-primary m-b-10"> <i class="fas fa-sync mr-2"></i> Actualizar página</a>

            <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-officials" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('officials')
            </button>
        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('officials'): ${dataPaginator.total}` | capitalize }}</h5>
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
                                {!! Form::text('name', null, ['v-model' => 'searchFields.name', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('ejemplo')]) ]) !!}
                            </div>
                            <div class="col-md-4">
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
                @include('leca::officials.table')
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

        <!-- begin #modal-view-officials -->
        <div class="modal fade" id="modal-view-officials">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') @lang('officials')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body" id="showFields">
                        @include('leca::officials.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-warning" type="button" onclick="printContent('showFields');"><i class="fa fa-print mr-2"></i>@lang('print')</button>
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-officials -->

        <!-- begin #modal-form-officials -->
        <div class="modal fade" id="modal-form-officials">
            <div class="modal-dialog modal-lg">
                <form @submit.prevent="save()" id="form-officials">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of') @lang('officials')</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('leca::officials.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('save_and_generate_pin')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-officials -->

        <!-- Inicia modal de inactivar funcionario -->
        <dynamic-modal-form
        modal-id="modal-inactivate-official"
        size-modal="lg"
        :data-form="dataForm"
        :is-update="true"
        title="Inactivar Funcionario"
        confirmation-message-saved="¿Esta seguro que desea Inactivar al funcionario?"
        endpoint="state-officials-inactive"
        @saved="
        if($event.isUpdate) {
            assignElementList(dataForm.id, $event.data);
        } else {
            addElementToList($event.data);
        }"
        >
        <template #fields="scope">
            <div>
                <div class="panel mt-2" style="border: 200px; padding: 15px;">
                    {{-- <label><b>Aprobar para continuar con la solicitud o cancelar por pago no confirmado con el área de Tesorería.</b></label> --}}
                    <div class="form-group row m-b-15">
                        <!-- Select donde dice si se aprueba o cancela el pago -->
                        {!! Form::label('state', 'Estado del Funcionario'.':', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-9">
                            <input type="text" disabled placeholder="Inactivar">
                        </div>
                    </div>
                </div>
            </div>
        </template>
        </dynamic-modal-form>
        <!-- Final de modal de inactivar funcionario -->

        <!-- Inicia modal de activar funcionario -->
        <dynamic-modal-form
        modal-id="modal-activate-official"
        size-modal="lg"
        :data-form="dataForm"
        :is-update="true"
        title="Activar Funcionario"
        confirmation-message-saved="¿Esta seguro que desea Activar al funcionario?"
        endpoint="state-officials-activate"
        @saved="
        if($event.isUpdate) {
            assignElementList(dataForm.id, $event.data);
        } else {
            addElementToList($event.data);
        }"
        >
        <template #fields="scope">
            <div>
                <div class="panel mt-2" style="border: 200px; padding: 15px;">
                    {{-- <label><b>Aprobar para continuar con la solicitud o cancelar por pago no confirmado con el área de Tesorería.</b></label> --}}
                    <div class="form-group row m-b-15">
                        <!-- Select donde dice si se aprueba o cancela el pago -->
                        {!! Form::label('state', 'Estado del Funcionario'.':', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-9">
                            <input type="text" disabled placeholder="Activar">
                        </div>
                    </div>
                </div>
            </div>
        </template>
        </dynamic-modal-form>
        <!-- Final de modal de activar funcionario -->

        <!-- Inicia modal de Habilitar el Recepcionista -->
        <dynamic-modal-form
        modal-id="modal-activate-receptionist"
        size-modal="lg"
        :data-form="dataForm"
        :is-update="true"
        title="Activar Funcionario"
        confirmation-message-saved="¿Esta seguro que desea Habilitar el Recepcionista?"
        endpoint="state-receptionist-activate"
        @saved="
        if($event.isUpdate) {
            assignElementList(dataForm.id, $event.data);
        } else {
            addElementToList($event.data);
        }"
        >
        <template #fields="scope">
            <div>
                <div class="panel mt-2" style="border: 200px; padding: 15px;">
                    {{-- <label><b>Aprobar para continuar con la solicitud o cancelar por pago no confirmado con el área de Tesorería.</b></label> --}}
                    <div class="form-group row m-b-15">
                        <!-- Select donde dice si se aprueba o cancela el pago -->
                        {!! Form::label('state', 'Recepcionista'.':', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-9">
                            <input type="text" disabled placeholder="Habilitar">
                        </div>
                    </div>
                </div>
            </div>
        </template>
        </dynamic-modal-form>
        <!-- Final de modal de Habilitar el Recepcionista -->

        <!-- Inicia modal de Inhabilitar el Recepcionista -->
        <dynamic-modal-form
        modal-id="modal-disable-receptionist"
        size-modal="lg"
        :data-form="dataForm"
        :is-update="true"
        title="Activar Funcionario"
        confirmation-message-saved="¿Esta seguro que desea Inhabilitar el Recepcionista?"
        endpoint="state-receptionist-disable"
        @saved="
        if($event.isUpdate) {
            assignElementList(dataForm.id, $event.data);
        } else {
            addElementToList($event.data);
        }"
        >
        <template #fields="scope">
            <div>
                <div class="panel mt-2" style="border: 200px; padding: 15px;">
                    {{-- <label><b>Aprobar para continuar con la solicitud o cancelar por pago no confirmado con el área de Tesorería.</b></label> --}}
                    <div class="form-group row m-b-15">
                        <!-- Select donde dice si se aprueba o cancela el pago -->
                        {!! Form::label('state', 'Recepcionista'.':', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-9">
                            <input type="text" disabled placeholder="Inhabilitar">
                        </div>
                    </div>
                </div>
            </div>
        </template>
        </dynamic-modal-form>
        <!-- Final de modal de Inhabilitar el Recepcionista -->
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
    $('#modal-form-officials').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });

    function printContent(divName) {
        
        // Se obtiene el elemento del id recibido por parámetro
        var printContent = document.getElementById(divName);
        // Se guarda en una variable la nueva pestaña
        var printWindow = window.open("");
        // Se obtiene el encabezado de la página actual para no perder estilos
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
        }, 30);
    }
</script>
@endpush
