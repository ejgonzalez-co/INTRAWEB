@extends('layouts.default')

@section('title', trans('tireQuantitites'))

@section('section_img', '/assets/img/components/gestion_llantas.png')

@section('menu')
    @include('maintenance::layouts.menus.menu_tires')
@endsection

@section('content')

<crud
    name="tireWears"
    :resource="{default: 'tire-wears', get: 'get-tire-wears?tire_id_plaque={!! $tire_id_plaque ?? null !!}&id_vehicle={!! $mant_resume_machinery_vehicles_yellow_id ?? null !!}'}"
    :init-values="{tire_id_plaque: '{!! $tire_id_plaque ?? null !!}', record_depth: [], depth_tire: '{!! $depth_tire ?? null !!}', revision_mileage:'' , mileage_initial:'{!! $mileage_initial ?? null !!}', general_cost_mm: '{!! $general_cost_mm ?? null!!}', max_wear: '{!! $max_wear ?? null !!}', date: '{!! $date ?? null !!}', machinery: '{!! $machinery ?? null !!}', mant_resume_machinery_vehicles_yellow_id: '{!! $mant_resume_machinery_vehicles_yellow_id ?? null !!}', last_revision_date: '{!! $last_revision_date ?? null !!}'} "
    inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/home') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">Llantas</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">Vista principal para la gestión desgaste de la llanta: {{$plaque}}</h1>
        <!-- end page-header -->
        @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo')|| Auth::user()->hasRole('mant Operador Llantas'))
        <!-- begin main buttons -->
        <div class="m-t-20">
            @if (empty($machinery))
                <a href="/maintenance/tire-informations" class="btn btn-primary m-b-10"><i class="fas fa-home"></i> Atrás</a>
            @else
                <a :href="'tire-informations?{{$machinery}}'" class="btn btn-primary m-b-10"><i class="fas fa-home"></i> Atrás</a>
            @endif
            
            <button @click="callFunctionComponent('FormTireWearsInformation','showModal',false)" type="button" class="btn btn-primary m-b-10" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>Agregar desgaste
            </button>

            <a  :href="'tire-wear-histories?tire={{$tire_id_plaque}}'" class="btn btn-primary m-b-10"
                    data-toggle="tooltip" data-placement="top" title="Historial"><i
                        class="fa fa-history mr-3"></i> Historial</a>
        </div>
        <!-- end main buttons -->
        @endif
        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') de tipo de activos: ${dataPaginator.total}` | capitalize }}</h5>
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
                                <date-picker :value="searchFields" name-field="revision_date" :input-props="{required: true}">
                                </date-picker>
                                <small>Filtro por fecha de revisión</small>
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
                <div class="float-xl-right m-b-15">
                    <!-- Acciones para exportar datos de tabla-->
                    <div class="btn-group">
                        <a href="javascript:;" data-toggle="dropdown" class="btn btn-primary"><i class="fa fa-download mr-2"></i> @lang('export_data_table')</a>
                        <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle" aria-expanded="false"><b class="caret"></b></a>
                        <div class="dropdown-menu dropdown-menu-right bg-blue">
                            {{-- <a href="javascript:;" @click="exportDataTable('pdf')" class="dropdown-item text-white no-hover"><i class="fa fa-file-pdf mr-2"></i> PDF</a> --}}
                            <a href="javascript:;" @click="exportDataTable('xlsx')" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i> EXCEL</a>
                        </div>
                    </div>
                </div>
                <!-- end buttons action table -->
                @include('maintenance::tire_wears.table')
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

        <!-- begin #modal-view-tireWears -->
        <div class="modal fade" id="modal-view-tireWears">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Datalle de desgaste de la llanta {{$plaque}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        @include('maintenance::tire_wears.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-tireWears -->

        <!-- begin #modal-form-tireWears -->
        {{-- <div class="modal fade" id="modal-form-tireWears">
            <div class="modal-dialog modal-xl">
                <form @submit.prevent="save()" id="form-tireWears">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">Formulario para agregar desgaste de la llanta</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('maintenance::tire_wears.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div> --}}
        <!-- end #modal-form-tireWears -->

    <form-tire-wears-information ref="FormTireWearsInformation" :form-data="dataForm" :init-values="initValues"></form-tire-wears-information>

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
    $('#modal-form-tireWears').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
