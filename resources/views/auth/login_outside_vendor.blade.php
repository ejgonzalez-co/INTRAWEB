<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Login | {{ config('app.name') }}</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />

	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
	{!!Html::style('assets/css/default/app.min.css')!!}
	<!-- ================== END BASE CSS STYLE ================== -->
</head>
<body class="pace-top">
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade show">
		<span class="spinner"></span>
	</div>
	<!-- end #page-loader -->

	<!-- begin #page-container -->
	<div id="page-container" class="fade">
		<!-- begin login -->
		<div class="login login-with-news-feed">
			<!-- begin news-feed -->
			<div class="news-feed">
				
				{{-- ../assets/img/default/login_banner_vuv.png --}}
				<div class="news-image" style="background-color: white ;background-image: url(https://www.epa.gov.co/images/imagenes/LOGO_EPA.png); background-size: 80%; border-right: solid #E5E5E5 1px"></div>
				<div class="">
					<h4 class="caption-title"><b></b> </h4>
					<p>
					
					</p>
				</div>
			</div>
			<!-- end news-feed -->
			<!-- begin right-content -->
			<div class="right-content">
				<!-- begin login-header -->
				<div class="login-header">
					<div class="brand">
                        {{-- <img src="{{ asset('assets/img/default/icon_login_vuv.png') }}" style="width:30px;height:auto"> --}}
						<!--<span class="logo"></span>--> <b style="text-align: center">Mantenimientos<br>Proveedores internos y externos.</b>
						<small class="mt-3" style=" font-size: 0.4em;">Por favor ingrese el Nit o Cédula y el correo electónico al cual se le notificó la solicitud de producto oservicio. Luego, haga clic en el botón Acceder.</small>
					</div>
				</div>
				<!-- end login-header -->
				<!-- begin login-content -->
				<div class="login-content">
					<form method="post" action="{{ url('/login-outside-vendor') }}" class="margin-bottom-0">
                        @csrf
						@if (session('user_error'))
							<div class="alert alert-info rounded-pill text-center" role="alert">
								<i class="fas fa-info-circle mr-2"></i> {{ session('user_error') }}
							</div>
						@endif
						<div class="form-group m-b-15 has-feedback">
							<input type="email" class="form-control form-control-lg" placeholder="Correo electrónico" name="correo" autofocus required />
						</div>
						<div class="form-group m-b-15 has-feedback">
							<input type="password" class="form-control form-control-lg" placeholder="NIT o cédula" name="pin" required />
						</div>
						<div class="checkbox checkbox-css m-b-30">
							<input type="checkbox" id="remember_me_checkbox" name="remember" value="" />
							<label for="remember_me_checkbox">
							@lang('Remember Me')
							</label>
						</div>
						<div class="login-buttons row">
                            <a href="https://intraepa.gov.co/" class="col-lg-5 mr-4 btn btn-default pr-2 btn-lg"><i class="fa fa-arrow-left mr-2"></i>@lang('back')</a>
							<button type="submit" class="col-lg-6 btn btn-primary pl-2 btn-lg"><i class="fa fa-sign-in-alt mr-2"></i>@lang('Login')</button>
						</div>
						{{-- <div class="m-t-20 m-b-40 p-b-40 text-inverse">
                            Si no recuerda su credencial haga click <a href="{{ url('reset-customer') }}">Aquí</a><br>
						</div> --}}
						<hr />
						<p class="text-center text-grey-darker mb-0">
							Mantenimientos
						</p>
					</form>
				</div>
				<!-- end login-content -->
			</div>
			<!-- end right-container -->
		</div>
		<!-- end login -->

		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->
	</div>
	<!-- end page container -->

	<!-- ================== BEGIN BASE JS ================== -->
	{!!Html::script('assets/js/app.min.js')!!}
	{!!Html::script('assets/js/theme/default.min.js')!!}
	<!-- ================== END BASE JS ================== -->
</body>
</html>
