@extends('layouts.default')

@section('title', 'Combustibles')

@section('section_img', '../assets/img/components/combustible_de_vehiculos.png')

@section('menu')
    @include('maintenance::layouts.menus.menu_vehicle_fuel')
@endsection

@section('content')

<crud
    name="vehicle-fuels"
    :resource="{default: 'historical-vehicle-fuels', get: 'get-vehicle-fuels'}"
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
        <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') el @lang('Vehicle Fuels')'}}</h1>
        <!-- end page-header -->

        <!-- begin main buttons -->
        <div class="m-t-20">
            <button type="button" @click="add()"  class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-import-vehicleFuels" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>@lang('crud.create') combustible
            </button>
        </div>
        <!-- end main buttons -->

        <!-- begin #modal-form-vehicleFuels -->
        <div class="modal fade" id="modal-form-import-vehicleFuels">
            <div class="modal-dialog modal-lg">
                <form @submit.prevent="save()" id="form-import-vehicleFuels">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of') registro de combustibles para vehículos</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('maintenance::vehicle_fuels.fields_import')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-vehicleFuels -->

    </div>
    
</crud>
@endsection


