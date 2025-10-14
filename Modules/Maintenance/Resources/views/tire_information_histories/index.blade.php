@extends('layouts.default')

@section('title', trans('tireInformationHistories'))

@section('section_img', '/assets/img/components/gestion_llantas.png')

@section('menu')
@include('maintenance::layouts.menus.menu_tires')
@endsection

@section('content')

<crud
    name="tireInformationHistories"
    :resource="{default: 'tire-information-histories', get: 'get-tire-information-histories?machinery={!! $machinery ?? null !!}'}"
    :init-values="{machinery: '{!! $machinery ?? null !!}'}"
    :init-values-search="{status : 'Activo'}"
    inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/home') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('tireInformationHistories')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('tireInformationHistories')'}}</h1>
        <!-- end page-header -->

        <!-- begin main buttons -->
        <div class="m-t-20">
            {{-- <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-tireInformationHistories" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('tireInformationHistories')
            </button> --}}
            @if (!empty($machinery))
            <a :href="'/maintenance/tire-informations?machinery={!! $machinery !!}'" class="btn btn-primary m-b-10"><i class="fa fa-arrow-left mr-3"></i>Atrás</a>
            @else
            <a href="/maintenance/tire-informations" class="btn btn-primary m-b-10"><i class="fa fa-arrow-left mr-3"></i>Atrás</a>
            @endif
           
            
        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('tireInformationHistories'): ${dataPaginator.total}` | capitalize }}</h5>
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
                            <div class="col-md-4 mb-2">
                                <date-picker
                                        :value="searchFields"
                                        name-field="created_at"
                                        :input-props="{required: true}">
                                </date-picker>
                                <small>Fecha de ingreso de la llanta</small>
                            </div>
                            <div class="col-md-4 mb-2">
                                {!! Form::text('user_name', null, ['v-model' => 'searchFields.user_name', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('nombre del usuario')]) ]) !!}
                            </div>
                            <div class="col-md-4 mb-2">
                                {!! Form::text('plaque', null, ['v-model' => 'searchFields.plaque', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Plaque')]) ]) !!}
                            </div>
                            <div class="col-md-4 mb-2">
                                {!! Form::text('dependencia', null, ['v-model' => 'searchFields.dependencia', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('dependencia')]) ]) !!}
                            </div>
                            <div class="col-md-4 mb-2">
                                {!! Form::select('assignment_type',['Activo'=>'Activo','Almacén'=>'Almacén'] ,null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.action }", 'v-model' => 'searchFields.assignment_type', 'required' => true]) !!}
                                <small>Seleccione la acción.</small>
                            </div>
                            <div class="col-md-4 mb-2">
                                {!! Form::text('code', null, ['v-model' => 'searchFields.code', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('código de la llanta')]) ]) !!}
                            </div>
                            <div class="col-md-4 mb-2">
                                {!! Form::text('position', null, ['v-model' => 'searchFields.position', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('posición de llanta')]) ]) !!}
                            </div>
                            <div class="col-md-4 mb-2">
                                {!! Form::select('status',['Activo'=>'Activo','Oculto'=>'Oculto'] ,null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.action }", 'v-model' => 'searchFields.status', 'required' => true]) !!}
                                <small>Seleccione el estado.</small>
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
                @include('maintenance::tire_information_histories.table')
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



        <input-search ref="input-search"></input-search>
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
    $('#modal-form-tireInformationHistories').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
