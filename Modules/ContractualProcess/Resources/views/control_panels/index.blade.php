@extends('layouts.default')

@section('title', trans('Paa Calls'))

@section('section_img', '/assets/img/components/convocatoria.png')

@section('content')
    <a href="{{ url('/dashboard') }}" class="btn btn-primary m-b-10"><i class="fa fa-arrow-left mr-2"></i><span>@lang('back') al listado de @lang('Previous studies')</span></a>
    <control-panel-page public-path="{!! asset('/') !!}"></control-panel-page>
@endsection

@push('css')
{!!Html::style('assets/plugins/jvectormap-next/jquery-jvectormap.css')!!}
@endpush

@push('scripts')
{!!Html::script('assets/plugins/jquery-sparkline/jquery.sparkline.min.js')!!}
{!!Html::script('assets/plugins/jvectormap-next/jquery-jvectormap.min.js')!!}
{!!Html::script('assets/plugins/jvectormap-next/jquery-jvectormap-world-mill.js')!!}

{!!Html::script('assets/plugins/flot/source/jquery.canvaswrapper.js')!!}
{!!Html::script('assets/plugins/flot/source/jquery.colorhelpers.js')!!}
{!!Html::script('assets/plugins/flot/source/jquery.flot.js')!!}
{!!Html::script('assets/plugins/flot/source/jquery.flot.saturated.js')!!}
{!!Html::script('assets/plugins/flot/source/jquery.flot.browser.js')!!}
{!!Html::script('assets/plugins/flot/source/jquery.flot.drawSeries.js')!!}
{!!Html::script('assets/plugins/flot/source/jquery.flot.uiConstants.js')!!}
{!!Html::script('assets/plugins/flot/source/jquery.flot.time.js')!!}
{!!Html::script('assets/plugins/flot/source/jquery.flot.resize.js')!!}
{!!Html::script('assets/plugins/flot/source/jquery.flot.pie.js')!!}
{!!Html::script('assets/plugins/flot/source/jquery.flot.crosshair.js')!!}
{!!Html::script('assets/plugins/flot/source/jquery.flot.categories.js')!!}
{!!Html::script('assets/plugins/flot/source/jquery.flot.navigate.js')!!}
{!!Html::script('assets/plugins/flot/source/jquery.flot.touchNavigate.js')!!}
{!!Html::script('assets/plugins/flot/source/jquery.flot.hover.js')!!}
{!!Html::script('assets/plugins/flot/source/jquery.flot.touch.js')!!}
{!!Html::script('assets/plugins/flot/source/jquery.flot.selection.js')!!}
{!!Html::script('assets/plugins/flot/source/jquery.flot.symbol.js')!!}
{!!Html::script('assets/plugins/flot/source/jquery.flot.legend.js')!!}
{!!Html::script('assets/plugins/jquery-sparkline/jquery.sparkline.min.js')!!}

{!!Html::script('assets/js/demo/dashboard.js')!!}


@endpush


