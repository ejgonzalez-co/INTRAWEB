
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
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
                    <a href="{!! url('/') !!}" class="navbar-brand">
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
                                <h5 class="text-center"> @lang('Reset Password')</h5>
                                <hr>
                            </div>
                        </div>
                        <div class="panel-body text-center">
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <p>@lang('Please sing info new password')</p>
                            <form class="d-inline" method="post" action="{{ url('/password/reset') }}">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">

                                <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                                    <input type="email" class="form-control @if ($errors->has('email')) is-invalid @endif" name="email" value="{{ old('email') }}" placeholder="@lang('E-Mail Address')">
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <input type="password" class="form-control @if ($errors->has('password')) is-invalid @endif" name="password" placeholder="@lang('New Password')">
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group has-feedback{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                    <input type="password" name="password_confirmation" class="form-control @if ($errors->has('password_confirmation')) is-invalid @endif" placeholder="@lang('Confirm New Password')">
                                    @if ($errors->has('password_confirmation'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary pull-right">
                                            <i class="fa fa-sync-alt mr-2"></i>@lang('Reset Password')
                                        </button>
                                    </div>
                                </div>
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


