@extends('layouts.default')

@section('title', trans('officials'))

@section('section_img', '/assets/img/components/Funcionarios.png')

@section('menu')
@include('leca::layouts.menu_officials')
@endsection

@section('content')

<crud name="users" :resource="{default: 'users-leca', get: 'get-users'}"
    :init-values="{block: false, sendEmail: false, roles: [], change_user: false}"
    :optional-data="{op1: false, op2: false}"
    :exclude-export-data="['id_cargo', 'id_dependencia', 'url_digital_signature', 'url_img_profile', 'roles', 'Work_groups']"
    :related-data-export="[{name: 'positions', value: 'nombre'}, {name: 'dependencies', value: 'nombre'}]"
    inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('officers')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') Recurso Humano ' | capitalize}}</h1>
        <!-- end page-header -->

        <!-- begin widget -->
        <div class="row">
            <widget-counter
                icon="fa fa-user-check"
                class-css-color="bg-green"
                :qty="dataList.filter((data) =>  data.email_verified_at !== null).length"
                title="Cuentas activas"
            ></widget-counter>

            <widget-counter
                icon="fa fa-user-times"
                class-css-color="bg-red"
                :qty="dataList.filter((data) =>  data.block === true ).length"
                title="Cuentas inactivas"
            ></widget-counter>
            <widget-counter
                icon="fa fa-users"
                :qty="dataList.length"
                title="Total de cuentas creadas"
            ></widget-counter>
        </div>
        <!-- end widget -->

        <!-- begin main buttons -->
        <div class="m-t-20">
            <a href="/dashboard" class="btn btn-primary m-b-10">
                <i class="fa fa-arrow-left mr-3"></i>Regresar
            </a>
            <a  href="javascript:location.reload()"  class="btn btn-primary m-b-10"> <i class="fas fa-sync mr-2"></i> Actualizar página</a>

            <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-users" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>Recurso Humano
            </button>
        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') Recurso Humano: ${dataPaginator.total}` | capitalize }}</h5>
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
                                {!! Form::text('name', null, ['v-model' => 'searchFields.name', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Name')]) ]) !!}
                            </div>
                            <div class="col-md-4">
                                {!! Form::text('email', null, ['v-model' => 'searchFields.email', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Email')]) ]) !!}
                            </div>
                            <div class="col-md-4">
                                {!! Form::text('username', null, ['v-model' => 'searchFields.username', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Username')]) ]) !!}
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-4">
                                <select-check css-class="form-control" name-field="id_dependencia" reduce-label="nombre" name-resource="get-dependencies" :value="searchFields" :is-required="true"></select-check>
                                <small>Filtro por dependencia</small>
                            </div>
                            <div class="col-md-4">
                                <select-check css-class="form-control" name-field="id_cargo" reduce-label="nombre" name-resource="get-positions" :value="searchFields" :is-required="true"></select-check>
                                <small>Filtro por cargo</small>
                            </div>
                            <div class="col-md-4">
                                {!! Form::date('created_at', null, ['v-model' => 'searchFields.created_at', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['created_at' => trans('created_at')]) ]) !!}
                                <small>Filtro por fecha de creación</small>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-4">
                                <select v-model="searchFields.block" class="form-control">
                                    <option value="1">Si</option>
                                    <option value="0">No</option>
                                </select>
                                <small>Filtro por usuario bloquedo</small>
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
                @include('leca::users.table')
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

        <!-- begin #modal-view-users -->
        <div class="modal fade" id="modal-view-users">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') Recurso Humano</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body hljs-wrapper" id="showFields">
                        @include('leca::users.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-warning" type="button" onclick="printContent('showFields');"><i class="fa fa-print mr-2"></i>@lang('print')</button>
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-users -->

        <!-- begin #modal-form-users -->
        <div class="modal fade" id="modal-form-users">
            <div class="modal-dialog modal-xl">
                <form @submit.prevent="save()" id="form-users" enctype="multipart/form-data">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of') Recurso Humano</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body hljs-wrapper" v-if="openForm">
                            @include('leca::users.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-users -->
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
    $('#modal-form-users').on('keypress', ( e ) => {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    } );

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
        }, 500);
    }
</script>
@endpush
