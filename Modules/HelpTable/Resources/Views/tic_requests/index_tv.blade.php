<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>{{ config('app.name') }} | Solicitudes TIC</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="" name="description" />
    <meta content="SEVEN Soluciones Informáticas" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- ================== BEGIN BASE CSS STYLE ================== -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    {!!Html::style('assets/css/material/app.min.css')!!}
    {!!Html::style('css/app.css')!!}
    <!-- ================== END BASE CSS STYLE ================== -->

    <!-- Select2 component vue-->
    <link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">

    {!!Html::style('assets/plugins/gritter/css/jquery.gritter.css')!!}
</head>
@php
	$bodyClass = (!empty($boxedLayout)) ? 'boxed-layout' : '';
	$bodyClass .= (!empty($paceTop)) ? 'pace-top ' : '';
	$bodyClass .= (!empty($bodyExtraClass)) ? $bodyExtraClass . ' ' : '';
	$sidebarHide = (!empty($sidebarHide)) ? $sidebarHide : '';
	$sidebarTwo = (!empty($sidebarTwo)) ? $sidebarTwo : '';
	$sidebarSearch = (!empty($sidebarSearch)) ? $sidebarSearch : '';
	$topMenu = (!empty($topMenu)) ? $topMenu : '';
	$footer = (!empty($footer)) ? $footer : '';

	$pageContainerClass = (!empty($topMenu)) ? 'page-with-top-menu ' : '';
	$pageContainerClass .= (!empty($sidebarRight)) ? 'page-with-right-sidebar ' : '';
	$pageContainerClass .= (!empty($sidebarLight)) ? 'page-with-light-sidebar ' : '';
	$pageContainerClass .= (!empty($sidebarWide)) ? 'page-with-wide-sidebar ' : '';
	$pageContainerClass .= (!empty($sidebarHide)) ? 'page-without-sidebar ' : '';
	$pageContainerClass .= (!empty($sidebarMinified)) ? 'page-sidebar-minified ' : '';
	$pageContainerClass .= (!empty($sidebarTwo)) ? 'page-with-two-sidebar ' : '';
	$pageContainerClass .= (!empty($contentFullHeight)) ? 'page-content-full-height ' : '';

	$contentClass = (!empty($contentFullWidth) || !empty($contentFullHeight)) ? 'content-full-width ' : '';
	$contentClass .= (!empty($contentInverseMode)) ? 'content-inverse-mode ' : '';
@endphp
<body class="{{ $bodyClass }} bg-white">
	<div id="app">
        @include('includes.component.page-loader')

		<div id="page-container" class="page-container fade page-sidebar-fixed page-header-fixed page-with-wide-sidebar page-with-light-sidebar">

            <!-- begin #header -->
            <div id="header" class="header navbar-default bg-nav">
                <!-- begin navbar-header -->
                <div class="navbar-header">
                    <button onclick="window.location='{{ url()->previous() }}'" type="button" class="navbar-toggle collapsed navbar-toggle-left text-white" data-click="sidebar-minify">
                        <!--<i class="fa fa-arrow-left"></i>-->
                        <i class="fa fa-chevron-left"></i>
                    </button>
                    <a href="{!! url('/') !!}" class="navbar-brand text-white">
                        {{ config('app.name') }}
                    </a>
                    <img src="{{ asset('assets/img/logo_epa.png')}}" style="width: 80px;" alt="" />
                </div>
            </div>
            <!-- end #header -->

            <div id="content" class="content ml-0">

                <crud
                    name="tic-requests"
                    :resource="{default: 'tic-requests', get: 'get-tic-requests-tv'}"
                    inline-template
                    >
                    <div>
                        <!-- begin page-header -->
                        <h1 class="page-header text-center m-b-35">@{{ '@lang('Lista de') @lang('Tic Requests') - @lang('Help table')' | capitalize }}</h1>
                        <!-- end page-header -->

                        <!-- begin widget -->
                        <div class="row">
                            @foreach ($consolidatedRequestBoard as $key => $requestBoard)
                            <widget-counter
                                class-css-col="col-md-2"
                                class-css-color="bg-light text-black"
                                :qty="{!! $requestBoard->request_counter !!}"
                                title="{!! $requestBoard->name !!}"
                                type-widget="multiple"
                                :item-list="[
                                { name: 'A tiempo', qty: {!! $requestBoard->on_time !!}, colorClass: '#28a745', iconSeeMore: 'far fa-smile-beam f-s-25 mr-2'},
                                { name: 'Próximo a vencer', qty: {!! $requestBoard->next_defeat !!}, colorClass: '#ffc107', iconSeeMore: 'far fa-meh f-s-25 mr-2'},
                                { name: 'Vencido', qty: {!! $requestBoard->expired !!}, colorClass: '#dc3545', iconSeeMore: 'far fa-frown f-s-25 mr-2'}]"
                            >
                            </widget-counter>
                            @endforeach
                        </div>
                        <!-- end widget -->

                        <!-- begin panel -->
                        <div class="panel panel-default">
                            <div class="panel-heading border-bottom">
                                <div class="panel-title">
                                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('Tic Requests'): ${dataPaginator.total}` | capitalize }}</h5>
                                </div>
                            </div>
                            <div class="panel-body">
                                @include('help_table::tic_requests.table_tv')
                            </div>
                        </div>
                        <!-- end panel -->
                    </div>
                </crud>
            </div>

            @includeWhen($footer, 'includes.footer')

            @include('includes.component.scroll-top-btn')

        </div>
    </div>
    <!-- ================== BEGIN BASE JS ================== -->
    {!!Html::script('js/app.js')!!}
    {!!Html::script('assets/js/app.min.js')!!}
    {!!Html::script('assets/js/theme/material.min.js')!!}
    <!-- ================== END BASE JS ================== -->

    {!!Html::script('assets/plugins/gritter/js/jquery.gritter.js')!!}

    <script>
        // Actualiza la pagina cada cierto tiempo
        setInterval(function(){
            window.location.reload(1);
        }, 60000);
    </script>
</body>
</html>