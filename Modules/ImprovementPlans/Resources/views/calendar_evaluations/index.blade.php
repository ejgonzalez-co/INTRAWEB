@extends('layouts.default')

@section('title', trans('Calendario'))

@section('section_img', '')

@section('menu')
    @include('improvementplans::layouts.menu_component_evaluated')
@endsection

@section('content')

<crud
    name="calendarEvaluations"
    :resource="{default: 'calendar-evaluations', get: 'get-calendar-evaluations'}"
    inline-template>
    <div>
        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">@{{ '@lang('Calendario de mis evaluaciones')'}}</h1>
        <!-- end page-header -->

        <div class="m-t-20">
                <a href="{{ route('my-evaluations.index') }}">
            
            <button type="button" class="btn btn-light m-b-10">
                <i class="fas fa-arrow-left mr-2"></i>@lang('back')
            </button>
        </a>
        </div>

        <calendar-evaluation :data-list="dataList"></calendar-evaluation>

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
@endpush

@push('scripts')
{!!Html::script('assets/plugins/gritter/js/jquery.gritter.js')!!}
<script>
    // detecta el enter para no cerrar el modal sin enviar el formulario
    $('#modal-form-calendarEvaluations').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
