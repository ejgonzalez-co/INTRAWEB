@extends('layouts.default')

@section('title', trans('Events'))

@section('menu')
    @include('intranet::layouts.menus.menu_calendar')
@endsection

@section('content')


<crud
    name="events"
    :resource="{default: 'events', get: 'get-events'}"
    inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('Events')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">@{{ '@lang('Calendario de eventos')'}}</h1>
        <!-- end page-header -->

        <!-- begin main buttons -->
        <div class="m-t-20">
            <!-- <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-events" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('Events')
            </button> -->
        </div>
        <!-- end main buttons -->

        <calendar-event :data-list="dataList"></calendar-event>

        <!-- begin vertical-box -->
        <div class="vertical-box">
            <!-- begin calendar -->
            <!-- <div id="calendar" class="vertical-box-column calendar"></div> -->
            <!-- end calendar -->
        </div>
        <!-- end vertical-box -->

    </div>
</crud>
@endsection

@push('css')

{!!Html::style('assets/plugins/gritter/css/jquery.gritter.css')!!}
{!!Html::style('assets/plugins/fullcalendar/dist/fullcalendar.print.css', array('media' => 'print'))!!}
{!!Html::style('assets/plugins/fullcalendar/dist/fullcalendar.min.css')!!}

@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js"></script>
{!!Html::script('assets/plugins/gritter/js/jquery.gritter.js')!!}
{!!Html::script('assets/plugins/moment/min/moment.min.js')!!}
{!!Html::script('assets/plugins/fullcalendar/dist/fullcalendar.js')!!}
{!!Html::script('assets/js/demo/calendar.demo.js')!!}
<script>
    // detecta el enter para no cerrar el modal sin enviar el formulario
    $('#modal-form-events').on('keypress', ( e ) => {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    } );
</script>
@endpush
