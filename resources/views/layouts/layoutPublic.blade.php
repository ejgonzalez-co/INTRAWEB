<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	@include('includes.head')
    <script>
        // Obtiene las siguientes variables del .env para usarlas en los componentes de Vue
        window.env = {
            JWT_SECRET_KEY: '{{ env("JWT_SECRET_KEY") }}',
            MAIL_SUPPORT: '{{ env("MAIL_SUPPORT") }}'
        };
    </script>
	<style>
		@media (min-width: 768px) {
    .page-with-wide-sidebar.page-sidebar-minified:not(.page-with-right-sidebar) .footer {
        margin-left: 10px;
        margin-right: 10px;
    }
}
	</style>
</head>
<style>
	@media (min-width: 768px) {
	.page-with-wide-sidebar.page-sidebar-minified:not(.page-with-right-sidebar) .footer {
		margin-left: 10px;
		margin-right: 10px;
	}
}
</style>

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

		<div id="page-container" class="page-container fade page-sidebar-fixed page-header-fixed page-with-wide-sidebar page-with-light-sidebar" style="position: relative; min-height: 100vh;">

			@include('layouts.headerPublic')

			<div id="content" class="text-center d-flex justify-content-center align-items-center">
				@yield('content')
			</div>


			@include('includes.component.scroll-top-btn')

		</div>

	</div>
	@include('includes.page-js')
</body>
</html>
