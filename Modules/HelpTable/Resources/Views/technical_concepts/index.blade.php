@extends('layouts.default')

@section('title', trans('Technical concepts'))

@section('section_img', '/assets/img/components/solicitudes.png')

@section('menu')
    @include('help_table::layouts.menus.menu_requests')
@endsection

@section('content')

<crud
    name="technicalConcepts"
    :resource="{default: 'technical-concepts', get: 'get-technical-concepts'}"
    :init-values="{ url_attachments:[] }"
    inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/home') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('Technical concepts')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        @if(Auth::user()->hasRole("Usuario TIC"))
            <h1 class="page-header text-center m-b-35">@{{ '@lang('View of technical concept request management')'}}</h1>
        @endif
        @if(Auth::user()->hasRole("Soporte TIC"))
            <h1 class="page-header text-center m-b-35">@{{ '@lang('View request management technical concept')'}}</h1>
        @endif
        @if(Auth::user()->hasRole("Administrador TIC"))
            <h1 class="page-header text-center m-b-35">@{{ '@lang('Main view to manage requests for technical concepts')'}}</h1>
        @endif
        @if(Auth::user()->hasRole("Revisor concepto técnico TIC"))
            <h1 class="page-header text-center m-b-35">@{{ '@lang('View of technical concepts for review')'}}</h1>
        @endif
        @if(Auth::user()->hasRole("Aprobación concepto técnico TIC"))
            <h1 class="page-header text-center m-b-35">@{{ '@lang('Main view to manage requests for technical concepts')'}}</h1>
        @endif
        <!-- end page-header -->

        <!-- begin main buttons -->
        @if(Auth::user()->hasRole("Usuario TIC") || Auth::user()->hasRole("Administrador TIC"))
            <div class="m-t-20">
                <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-technical-concepts-by-staff-member" data-toggle="modal">
                    <i class="fa fa-plus mr-2"></i>@lang('Register request')
                </button>
            </div>
        <!-- end main buttons -->
        @endif

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('technical concepts'): ${dataPaginator.total}` | capitalize }}</h5>
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
                                {!! Form::select('status',['Asignado'=>'Asignado','En revisión'=>'En revisión','Aprobación pendiente'=>'Aprobación pendiente','Aprobado'=>'Aprobado'] ,null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.status }", 'v-model' => 'searchFields.status']) !!}
                                <small>Filtrar por el estado de la solicitud</small>
                            </div>
                            <div class="col-md-4">
                                <date-picker
                                :value="searchFields"
                                name-field="created_at"
                                mode="range"
                                >
                                </date-picker>
                                <small>Filtrar por rango de fecha de creación</small>
                            </div>
                            <div class="col-md-4">
                                <select-check
                                css-class="form-control"
                                name-field="id_staff_member"
                                reduce-label="name"
                                reduce-key="id"
                                name-resource="tics-users"
                                :value="searchFields"
                                :key="keyRefresh"
                                :enable-search="true"
                                >
                                </select-check>
                                <small>Filtrar por usuario solicitante</small>
                            </div>
                            @if (Auth::user()->hasRole("Administrador TIC") || Auth::user()->hasRole("Revisor concepto técnico TIC") || Auth::user()->hasRole("Aprobación concepto técnico TIC"))                                
                                <div class="col-md-4 mt-2">
                                    <select-check
                                    css-class="form-control"
                                    name-field="technician_id"
                                    reduce-label="name"
                                    reduce-key="id"
                                    name-resource="technicians"
                                    :value="searchFields"
                                    :key="keyRefresh"
                                    :enable-search="true"
                                    >
                                    </select-check>
                                    <small>Filtrar por funcionario técnico</small>
                                </div>

                                <div class="col-md-4 mt-2">
                                    <select-check
                                    css-class="form-control"
                                    name-field="dependence_name"
                                    reduce-label="nombre"
                                    reduce-key="nombre"
                                    name-resource="dependencies"
                                    :value="searchFields"
                                    :key="keyRefresh"
                                    :enable-search="true"
                                    >
                                    </select-check>
                                    <small>Filtrar por la dependencia</small>
                                </div>
                            @endif
                            @if (Auth::user()->hasRole("Soporte TIC"))                                
                                <div class="col-md-4 mt-2">
                                    <select-check
                                    css-class="form-control"
                                    name-field="dependence_name"
                                    reduce-label="nombre"
                                    reduce-key="nombre"
                                    name-resource="dependencies"
                                    :value="searchFields"
                                    :key="keyRefresh"
                                    :enable-search="true"
                                    >
                                    </select-check>
                                    <small>Filtrar por la dependencia del usuario TIC</small>
                                </div>
                            @endif
                            <div class="col-md-4 mt-2">
                                <button @click="clearDataSearch()" class="btn btn-md btn-primary">@lang('clear_search_fields')</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end card search -->
            </div>
            <!-- end #accordion search -->
            <div class="panel-body">
                @if(Auth::user()->hasRole(["Administrador TIC","Soporte TIC","Aprobación concepto técnico TIC","Revisor concepto técnico TIC"]))
                    <!-- begin buttons action table -->
                    <div class="float-xl-right m-b-15">
                        <!-- Acciones para exportar datos de tabla-->
                        <div class="btn-group">
                            <a href="javascript:;" data-toggle="dropdown" class="btn btn-primary"><i class="fa fa-download mr-2"></i> @lang('export_data_table')</a>
                            <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle" aria-expanded="false"><b class="caret"></b></a>
                            <div class="dropdown-menu dropdown-menu-right bg-blue">
                                <a href="javascript:;" @click="exportDataTable('xlsx','Conceptos tecnicos')" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i> EXCEL</a>
                            </div>
                        </div>
                    </div>
                @endif
                <!-- end buttons action table -->
                @include('help_table::technical_concepts.table')
            </div>
            <div class="p-b-15 text-center">
                <!-- Cantidad de elementos a mostrar -->
                <div class="form-group row m-5 col-md-3 text-center d-sm-inline-flex">
                    {!! Form::label('show_qty', trans('show_qty').':', ['class' => 'col-form-label col-md-7']) !!}
                    <div class="col-md-5">
                        {!! Form::select(trans('show_qty'), [5 => 5, 10 => 10, 15 => 15, 20 => 20, 25 => 25, 30 => 30, 50 => 50, 75 => 75], '5', ['class' => 'form-control', 'v-model' => 'dataPaginator.pagesItems']) !!}
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

        <!-- begin #modal-view-technicalConcepts -->
        <div class="modal fade" id="modal-view-technicalConcepts">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') @lang('technical equipment concept')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        @include('help_table::technical_concepts.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-warning" type="button" onclick="printContent('modal-view-technicalConcepts');"><i class="fa fa-print mr-2"></i>@lang('print')</button>
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-technicalConcepts -->

        <!-- begin #modal-view-technical-concept-history -->
        <div class="modal fade" id="modal-view-technical-concept-history">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('Follow-up and control')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        @include('help_table::technical_concepts.history')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-technical-concept-history -->

        <dynamic-modal-form
        modal-id="modal-form-technical-concepts-by-staff-member"
        size-modal="xl"
        :data-form="dataForm"
        title="@lang('Register request')"
        confirmation-message-saved="¿Está seguro de enviar la solicitud de Concepto técnico a la Secretaría TIC?"
        button-icon-submit="fas fa-paper-plane"
        button-text-submit="Enviar solicitud"
        endpoint="request-technical-concept"
        @saved="
        if($event.isUpdate) {
            assignElementList(dataForm.id, $event.data);
        } else {
            addElementToList($event.data);
        }"
        >
        <template #fields="scope">
            <div>
                @include('help_table::technical_concepts.fields')
            </div>
        </template>
        </dynamic-modal-form>

        <!-- begin #modal-form-technicalConcepts -->
        <div class="modal fade" id="modal-form-technicalConcepts">
            <div class="modal-dialog modal-xl">
                <form @submit.prevent="save()" id="form-technicalConcepts">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of') @lang('technical equipment concept')</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('help_table::technical_concepts.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-technicalConcepts -->

        <!-- begin #modal-form-send-review -->
        <dynamic-modal-form
        modal-id="modal-form-send-review"
        size-modal="xl"
        :data-form="dataForm"
        :is-update="true"
        title="@lang('Enviar a revisión')"
        button-icon-submit="fas fa-paper-plane"
        button-text-submit="Enviar a revisión"
        endpoint="send-to-reviewers"
        @saved="
        if($event.isUpdate) {
            assignElementList(dataForm.id, $event.data);
        } else {
            addElementToList($event.data);
        }"
        >
        <template #fields="scope">
            <div>
                @include('help_table::technical_concepts.send_review')
            </div>
        </template>
        </dynamic-modal-form>
        <!-- end #modal-form-send-review -->

        <!-- begin #modal-form-send-approval -->
        <dynamic-modal-form
        modal-id="modal-form-send-approval"
        size-modal="xl"
        :data-form="dataForm"
        :is-update="true"
        title="@lang('Enviar para aprobación / Devolver')"
        button-icon-submit="fas fa-paper-plane"
        button-text-submit="Enviar"
        endpoint="validate-request-status"
        @saved="
        if($event.isUpdate) {
            assignElementList(dataForm.id, $event.data);
        } else {
            addElementToList($event.data);
        }"
        >
        <template #fields="scope">
            <div>
                @include('help_table::technical_concepts.send_approval')
            </div>
        </template>
        </dynamic-modal-form>
        <!-- end #modal-form-send-approval -->

        <!-- begin #modal-form-approve-request -->
        <dynamic-modal-form
        modal-id="modal-form-approve-request"
        size-modal="xl"
        :data-form="dataForm"
        :is-update="true"
        title="@lang('Aprobar concepto / Devolver')"
        button-icon-submit="fas fa-save"
        button-text-submit="Guardar"
        endpoint="validate-approval-request"
        @saved="
        if($event.isUpdate) {
            assignElementList(dataForm.id, $event.data);
        } else {
            addElementToList($event.data);
        }"
        >
        <template #fields="scope">
            <div>
                @include('help_table::technical_concepts.approval_request')
            </div>
        </template>
        </dynamic-modal-form>
        <!-- end #modal-form-approve-request -->

        <!-- begin #modal-form-send-approval -->
        <div class="modal fade" id="modal-form-send-approval">
            <div class="modal-dialog modal-xl">
                <form @submit.prevent="save()" id="form-technicalConcepts">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of') @lang('technical equipment concept')</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('help_table::technical_concepts.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane mr-2"></i>@lang('Enviar a revisión')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-send-approval -->
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
    $('#modal-form-technicalConcepts').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
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
        }, 10);
    }

</script>
@endpush
