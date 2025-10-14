
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @section('title', trans('Reset Password'))
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
                    <a href="{!! url('/') !!}" class="navbar-brand text-white">
                        {{ config('app.name') }}
                    </a>
                </div>
            </div>
            <!-- end #header -->
			<div id="content" class="content ml-0">
                <div class="row">
                    <div class="col-md-6 offset-md-3 panel panel-default">
                        <div class="panel-heading bg-white">
                            <div class="panel-title">
                                <h5 class="text-center"> @lang('Request Reset Password')</h5>
                                <hr>
                            </div>
                        </div>
                        <div class="panel-body text-center">
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                                <p>La notificación para reestablecer contraseña fue enviada con éxito, por favor revise su bandeja de correo o spam. Si desea volver a enviar el enlace haga clic en el siguiente botón.</p>
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="{{ url('/password/reset') }}" class="btn btn-primary pull-right">
                                            <i class="fa fa-sync-alt mr-2"></i>@lang('Resend reset password')
                                        </a>
                                    </div>
                                </div>
                            @else
                            <p>@lang('Enter Email To Request Reset Password'), si no recuerda el correo, comuniquese con el administrador de la Intranet.</p>
                            <form class="d-inline" method="post" action="{{ url('/password/email') }}">
                                @csrf
                                <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                                    <input type="email" class="form-control @if ($errors->has('email')) is-invalid @endif" name="email" value="{{ old('email') }}" placeholder="@lang('E-Mail Address')">
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('email') }}, comuniquese con el administrador de la Intranet</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary pull-right">
                                            <i class="fa fa-btn fa-envelope"></i> @lang('Send Password Reset Link')
                                        </button>
                                    </div>
                                </div>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
			</div>

            {{-- === INICIO: Sección adicional (recuadro azul) === --}}
            <remember-citizen-mail mail-support="{{ (env("MAIL_SUPPORT") ?: 'soporte@seven.com.co') }}" name-resource="/search-mail-citizen"  message-error="No se encontró un ciudadano con el número de identificación ingresado." name-prop="email" text-label="Correo asociado">
            </remember-citizen-mail>
            {{-- === FIN: Sección adicional (recuadro azul) === --}}


			@includeWhen($footer, 'includes.footer')
			@include('includes.component.scroll-top-btn')
		</div>

	</div>
	@include('includes.page-js')
</body>
</html>


