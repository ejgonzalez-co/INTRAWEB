@extends('layouts.default')

@section('title', trans('Encuesta Actualización de Datos Personales'))

@section('section_img', '../assets/img/components/solicitudes.png')

@section('menu')
    @include('update_citizen_data::layouts.menu_citizen')
@endsection

@section('content')

<crud name="udc-requests" :resource="{default: 'udc-requests-citizen', get: 'get-udc-requests-citizen'}" inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/home') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('polls')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('polls')'}}</h1>
        <!-- end page-header -->

        <!-- begin main buttons -->
        <div class="m-t-20">
            <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-udc-requests" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('poll')
            </button>
        </div>
        <!-- end main buttons -->

        


        <!-- begin #modal-form-udc-requests -->
        <div class="modal fade" id="modal-form-udc-requests">
            <div class="modal-dialog modal-lg">
                <form @submit.prevent="save()" id="form-udc-requests">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of') @lang('requests_tic')</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('update_citizen_data::udc_requests.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('Send') datos</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-udc-requests -->
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
    $('#modal-form-udc-requests').on('keypress', ( e ) => {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    } );
</script>
@endpush
