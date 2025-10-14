@extends('layouts.default')

@section('title', trans('officers'))
@section('section_img', '/assets/img/components/intranet_users.png')

@section('menu')
    @include('intranet::layouts.menu')
@endsection

@section('content')

<crud name="users" :resource="{default: 'users', get: 'get-users'}"
    :crud-avanzado="true"
    :init-values="{block: false, autorizado_firmar: false,  sendEmail: false, roles : [], change_user: false}"
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
        <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('accounts') @lang('off') @lang('officers')' | capitalize}}</h1>
        <!-- end page-header -->

        <!-- begin widget -->
        <div class="row">
            <widget-counter
                icon="fa fa-user-check"
                class-css-color="bg-green"
                :qty="{{ $verified_accounts }}"
                title="Cuentas activas"
            ></widget-counter>
            <widget-counter
                icon="far fa-user"
                class-css-color="bg-orange"
                :qty="{{ $unverified_accounts }}"
                title="Cuentas sin activar"
            ></widget-counter>
            <widget-counter
                icon="fa fa-user-times"
                class-css-color="bg-red"
                :qty="{{ $blocked_accounts }}"
                title="Cuentas bloqueadas"
            ></widget-counter>
            <widget-counter
                icon="fa fa-users"
                :qty="{{ $verified_accounts + $unverified_accounts + $blocked_accounts }}"
                title="Total de cuentas creadas"
            ></widget-counter>
        </div>
        <!-- end widget -->

        <!-- begin main buttons -->
        <div class="m-t-20">
            <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-users" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('officers')
            </button>
        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">

                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('officers'): ${dataPaginator.total}` | capitalize }}</h5>

                    {{-- <h5 class="text-center"> @{{ `@lang('total_registers') @lang('officers'): ${advancedSearchFilterPaginate().length}` | capitalize }}</h5> --}}
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
                                {!! Form::text('name', null, ['v-model' => 'searchFields.name', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Name')]),  '@keyup.enter' => 'pageEventActualizado(1)' ]) !!}
                            </div>
                            <div class="col-md-4">
                                {!! Form::text('email', null, ['v-model' => 'searchFields.email', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Email')]),  '@keyup.enter' => 'pageEventActualizado(1)' ]) !!}
                            </div>
                            <div class="col-md-4">
                                <select-check css-class="form-control" name-field="roles" reduce-label="nombre"
                                :value="searchFields" :is-required="true" :enable-search="true" :is-multiple="true"
                                :options-list-manual="{{ $roles }}">
                                </select-check>
                                <small>Filtro por roles</small>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-4">
                                <select-check css-class="form-control" name-field="id_dependencia" reduce-label="nombre" name-resource="get-dependencies" :value="searchFields" :enable-search="true"></select-check>
                                <small>Filtro por dependencia</small>
                            </div>

                            {{-- <div class="col-md-4 mb-2">
                                <select-check
                                    css-class="form-control"
                                    name-field="users_id"
                                    :reduce-label="['dependency', 'name']"
                                    name-resource="get-users-tic"
                                    :value="searchFields"
                                >
                                </select-check>
                                <input type="hidden" v-model="searchFields.typeQuery='users_id'">
                                <small>Filtro por usuario solicitante.</small>
                            </div> --}}

                            <div class="col-md-4">
                                <select-check css-class="form-control" name-field="id_cargo" reduce-label="nombre" name-resource="get-positions" :value="searchFields" :enable-search="true"></select-check>
                               
                                <small>Filtro por cargo</small>
                            </div>
                            <div class="col-md-4">
                                {!! Form::date('created_at', null, ['v-model' => 'searchFields.created_at', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['created_at' => trans('created_at')]), '@keyup.enter' => 'pageEventActualizado(1)' ]) !!}
                                <small>Filtro por fecha de creación</small>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-4">
                                <select v-model="searchFields.block" class="form-control">
                                    <option :value="1">Si</option>
                                    <option :value="0">No</option>
                                </select>
                                <small>Filtro por usuario bloqueado</small>
                            </div>

                            <div class="col-md-4">
                                <select v-model="searchFields.autorizado_firmar" class="form-control">
                                    <option value="1">Si</option>
                                    <option value="0">No</option>
                                </select>
                                <small>Filtro por usuario autorizado para firmar</small>
                            </div>
                            <div class="col-md-4">
                                <select-check css-class="form-control" name-field="sedes_id" reduce-label="nombre" name-resource="get-headquarters" :value="searchFields" :enable-search="true"></select-check>
                                <small>Filtro por sede</small>
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
                            {{-- <a href="javascript:;" @click="exportDataTableAvanzado('pdf')" class="dropdown-item text-white no-hover"><i class="fa fa-file-pdf mr-2"></i> PDF</a> --}}
                            <a href="javascript:;" @click="exportDataTableAvanzado('xlsx')" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i> EXCEL</a>
                        </div>
                    </div>
                </div>
                <!-- end buttons action table -->
                @include('intranet::users.table')
            </div>
            <div class="p-b-15 text-center">
                <!-- Cantidad de elementos a mostrar -->
                <div class="form-group row m-5 col-md-3 text-center d-sm-inline-flex">
                    {!! Form::label('show_qty', trans('show_qty').':', ['class' => 'col-form-label col-md-7']) !!}
                    <div class="col-md-5">
                        {!! Form::select(trans('show_qty'), [5 => 5, 10 => 10, 15 => 15, 20 => 20, 25 => 25, 30 => 30, 50 => 50, 75 => 75], '5', ['class' => 'form-control', 'v-model' => 'dataPaginator.pagesItems', '@change' => 'pageEventActualizado(1)']) !!}
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

        <!-- begin #modal-view-users -->
        <div class="modal fade" id="modal-view-users">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') @lang('officers')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body hljs-wrapper">
                        @include('intranet::users.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-users -->

        <!-- begin #modal-form-users -->
        <div class="modal fade" id="modal-form-users">
            <div class="modal-dialog modal-xl">
                <form @submit.prevent="save()" id="form-users" enctype="multipart/form-data" autocomplete="off">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of') @lang('officers')</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body hljs-wrapper" v-if="openForm">
                            @include('intranet::users.fields')
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
</script>
@endpush
