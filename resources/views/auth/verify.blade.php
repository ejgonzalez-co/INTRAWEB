
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @section('title', trans('Verify Account'))
	@include('includes.head')
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
		<div id="page-container" class="page-container fade page-header-fixed page-with-wide-sidebar page-with-light-sidebar">
			<!-- begin #header -->
            <div id="header" class="header navbar-default bg-nav">
                <!-- begin navbar-header -->
                <div class="navbar-header">
                    <button onclick="window.location='{{ url()->previous() }}'" type="button" class="navbar-toggle collapsed navbar-toggle-left text-white" data-click="sidebar-minify">
                        <!--<i class="fa fa-arrow-left"></i>-->
                        <i class="fa fa-chevron-left"></i>
                    </button>
                    <a href="{!! url('/') !!}" class="navbar-brand  text-white">
                        {{ config('app.name') }}
                    </a>
                </div>
                <!-- end navbar-header -->
                <!-- begin header-nav -->
                <ul class="navbar-nav navbar-right">
                    <li class="dropdown navbar-user">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="d-none d-md-inline text-white">Bienvenido, {{ Auth::user()->name }}</span>
                        @if(!empty(Auth::user()->url_img_profile))
                            <img src="{{ asset('storage')}}/{{ Auth::user()->url_img_profile }}" alt="" />
                        @else
                            <img src="{{ asset('assets/img/user/profile.png')}}" alt="" />
                        @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="javascript:;" class="dropdown-item">@lang('edit_profle')</a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ url('/logout') }}" class="dropdown-item"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                @lang('Logout')
                            </a>
                            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
                <!-- end header navigation right -->
            </div>
            <!-- end #header -->

			<div id="content" class="content ml-0">
                <div class="row">
                    <div class="col-md-6 offset-md-3 panel panel-default">
                        <div class="panel-heading bg-white">
                            <div class="panel-title">
                                <h5 class="text-center"> ¡Su cuenta aún no ha sido activada!</h5>
                            </div>
                        </div>
                        <div class="panel-body text-center">
                            @if (session('resent'))
                                <div class="alert alert-success" role="alert">
                                Se ha enviado un nuevo enlace de verificación a su dirección de correo electrónico.
                                </div>
                            @endif
                            <p>Por favor haga clic en el siguiente botón para volver a enviar el correo donde podrá activar su cuenta.</p>
                            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                                @csrf
                                <button type="submit" class="btn btn-link ">
                                    <i class="fa fa-envelope mr-2"></i>Enviar correo para activar cuenta de usuario
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
			</div>
			@includeWhen($footer, 'includes.footer')
			@include('includes.component.scroll-top-btn')
		</div>

	</div>
	@include('includes.page-js')
</body>
</html>

