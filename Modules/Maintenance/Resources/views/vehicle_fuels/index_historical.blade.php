@extends('layouts.default')

@section('title', 'Combustibles')

@section('section_img', '../assets/img/components/gestion_combustibles.png')

@section('menu')
    @include('maintenance::layouts.menus.menu_vehicle_fuel')
@endsection

@section('content')

<crud
    name="historical-vehicle-fuel"
    :resource="{default: 'historical-import-vehicle-fuel', get: 'get-historical-vehicle'}"
    inline-template
    ref="crud">
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/home') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">Combustibles</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">Gestión de combustible de vehículos agosto a diciembre de 2020</h1>
        <!-- end page-header -->

        <!-- begin main buttons -->
        <div class="m-t-20">
            <a type="button" href="vehicle-fuels" class="btn btn-primary m-b-10"><i class="fa fa-arrow-left"></i> Regresar a gestión de combustibles</a>
        </div>

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') combustible de vehículos: ${dataPaginator.total}` | capitalize }}</h5>
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
                                {!! Form::text('name', null, ['v-model' => 'searchFields.current_mileage', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => 'Kilometraje']) ]) !!}
                            </div> 

                            <div class="col-md-4">
                                {!! Form::date('created_at', null, ['v-model' => 'searchFields.created_migration', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Created_at')]) ]) !!}
                                <small>Filtrar por fecha de creación</small>
                            </div>
                            
                            <div class="col-md-4">
                                {!! Form::text('name', null, ['v-model' => 'searchFields.current_hourmeter', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Horómetro')]) ]) !!}
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-4">
                                {!! Form::text('name', null, ['v-model' => 'searchFields.fuel_quantity', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Quantity Fuel')]) ]) !!}
                            </div>

                            <div class="col-md-4">
                                {!! Form::text('name', null, ['v-model' => 'searchFields.performance_by_gallon', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => 'Rendimiento por galón']) ]) !!}
                            </div>

                            <div class="col-md-4">
                                {!! Form::text('name', null, ['v-model' => 'searchFields.variation_route_hour', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => 'Variación en KM por tanqueo']) ]) !!}
                            </div>

                        </div>

                        <div class="row form-group">

                            <div class="col-md-4">
                                {!! Form::text('name', null, ['v-model' => 'searchFields.gallon_price', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Gallon Price')]) ]) !!}
                            </div>

                            <div class="col-md-4">
                                {!! Form::text('name', null, ['v-model' => 'searchFields.total_price', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Total Price')]) ]) !!}
                            </div>

                            <div class="col-md-4">
                                {!! Form::text('name', null, ['v-model' => 'searchFields.variation_tanking_hour', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => 'Variación de HR en los tanqueos']) ]) !!}
                            </div>
                        </div>

                        <div class="row form-group">
                            

                            <input type="hidden" v-model="searchFields.typeQuery='mant_resume_machinery_vehicles_yellow_id'">
                            <div class="col-md-4">
                                <auto
                                :is-update="isUpdate"
                                name-prop="plaque"
                                name-field="mant_resume_machinery_vehicles_yellow_id"
                                :name-labels-display="['plaque']"
                                :value='searchFields'
                                name-resource='get-vehicle'
                                css-class="form-control"
                                reduce-key="id"
                                asignar-al-data=""
                                :key="keyRefresh"
                                :min-text-input="2"
                                :is-required="true"
                                ref = "placa"
                                >
                                </auto>
                                <small>Filtrar por placa</small>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-4">
                                <button @click="callFunctionComponent('loadVehicleFuel', 'cleanAuto');"  class="btn btn-md btn-primary">@lang('clear_search_fields')</button>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- end card search -->
            </div>
            <!-- end #accordion search -->

            <vehicle-fuels-create ref="loadVehicleFuel" name = "vehicleFuels"></vehicle-fuels-create>

            <div class="panel-body">
                <!-- begin buttons action table -->
                <div class="float-xl-right m-b-15">
                    <!-- Acciones para exportar datos de tabla-->
                    <div class="btn-group">
                        <a href="javascript:;" data-toggle="dropdown" class="btn btn-primary"><i class="fa fa-download mr-2"></i> @lang('export_data_table')</a>
                        <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle" aria-expanded="false"><b class="caret"></b></a>
                        <div class="dropdown-menu dropdown-menu-right bg-blue">
                            <a href="javascript:;" @click="exportDataTable('xlsx')" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i> EXCEL</a>
                        </div>
                    </div>
                </div>
                <!-- end buttons action table -->
                @include('maintenance::vehicle_fuels.table_historical')
            </div>

            <!-- begin #modal-view-vehicleFuels -->
            <div class="modal fade" id="modal-view-historical">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">Información del @lang('Vehicle Fuel')</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" id="showFields">
                            @include('maintenance::vehicle_fuels.show_fields_historical')
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end #modal-view-vehicleFuels -->

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
    </div>
    
</crud>
@endsection
