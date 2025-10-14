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
	<link rel="icon" href="{{ asset('assets/img/favicon.ico') }}">

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
			@php
			$ultima_configuracion = DB::table('configuration_general')->latest()->first();
			@endphp
			<div class="news-feed">
				{{-- <div class="news-image" style="background-image: url(../assets/img/default/login_banner_vuv.png)"></div> --}}
				<div class="news-image"></div>


				<div class="news-caption">
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
                        <img src="{{ asset('assets/img/default/icon_login_vuv.png') }}" style="width: 30px; height: auto">
						<b>Expedientes electrónicos <br /> Usuarios externos</b>
						<small class="mt-3" style=" font-size: 0.4em;">Por favor ingrese el correo electrónico y el pin de acceso el cual se le notificó al correo electrónico. Luego, haga clic en el botón Acceder.</small>
					</div>
				</div>
				<!-- end login-header -->
				<!-- begin login-content -->
				<div class="login-content">
					<form method="post" action="{{ url('/login-usuarios-externos-expedientes') }}" class="margin-bottom-0">
                        @csrf
						@if (session('user_error'))
							<div class="alert alert-info rounded-pill text-center" role="alert">
								<i class="fas fa-info-circle mr-2"></i> {{ session('user_error') }}
							</div>
						@endif
						<div class="form-group m-b-15 has-feedback {{ $errors->has('correo') ? ' has-error' : '' }}">
							<input type="email" class="form-control form-control-lg @if ($errors->has('correo')) is-invalid @endif" placeholder="@lang('Correo electrónico')" name="correo" value="{{ old('correo') }}" required />
                            @if ($errors->has('correo'))
                                <span class="invalid-feedback">
                                <strong>{{ $errors->first('correo') }}</strong>
                            </span>
                            @endif
						</div>
						<div class="form-group m-b-15 has-feedback{{ $errors->has('pin_acceso') ? ' has-error' : '' }}">
							<input type="password" class="form-control form-control-lg @if ($errors->has('pin_acceso')) is-invalid @endif" placeholder="@lang('Pin de acceso')" name="pin_acceso" required />
                            @if ($errors->has('pin_acceso'))
                                <span class="invalid-feedback">
                                <strong>{{ $errors->first('pin_acceso') }}</strong>
                            </span>
                            @endif
						</div>
						<div class="login-buttons row">
							<div class="col-6 pl-1">
								<a href="{{ url('/login') }}" class="btn btn-link btn-lg w-100">
									<i class="fa fa-arrow-left fa-lg mr-2"></i>Regresar
								</a>
							</div>
							<div class="col-6 pr-1">
								<button  type="submit" class="btn btn-primary btn-lg w-100">
									<i class="fa fa-sign-in-alt mr-2"></i>@lang('Login')
								</button>
							</div>
						</div>

						<hr />
						<p class="text-center text-grey-darker mb-0">
							{{ config('app.name') }}
						</p>
					</form>
					<!-- Horarios de Atención -->
					
					@if (!empty($ultima_configuracion->horario))
					<div class="text-center mt-4">
						<p class="text-center text-grey-darker mb-0">{{ $ultima_configuracion->horario ?? null }}</p>
					</div>
					@else
					<div class="text-center mt-4">
						<hr/>
						&copy; {{ date("Y") }} <a href="https://web.whatsapp.com/send/?phone=573243018787" target="_blank">Intraweb</a> - Todos los derechos reservados. <a href="#" data-toggle="modal" data-target="#terminos_condiciones_modal">Términos, Condiciones de uso y Aviso Legal</a>
					</div>
					@endif
					
					
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
<style>

  
	/* Imagen para pantallas grandes */
	.news-image {
	  background-image: url('{{ asset('storage') }}/{{ $ultima_configuracion->imagen_fondo }}');
	}
  
	/* Imagen para pantallas pequeñas */
	@media (max-width: 1500px) {
	  .news-image {
		background-image: url('{{ asset('storage') }}/{{$ultima_configuracion->imagen_fondo_responsive ? $ultima_configuracion->imagen_fondo_responsive : $ultima_configuracion->imagen_fondo }}');
	  }
	}
  </style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>    
    
    <script>
        function funcionSwal() {
            Swal.fire({
                text: 'Ocurrió un problema al conectarse al servidor. Intente más tarde o contacte al soporte técnico.',
                icon: 'info',
                confirmButtonText: 'OK'
            });
        }

        // Aquí verificas si hay errores y llamas a la función
        @if ($errors->has('server'))
            funcionSwal();
        @endif
    </script>

</html>
{{-- @push('css')
{!!Html::style('assets/plugins/gritter/css/jquery.gritter.css')!!} --}}

<style>
	
	/* 
	Estilos personalizados para agregar bordes de colores en el lado izquierdo 
	de los elementos. Estas clases son útiles para destacar visualmente diferentes 
	categorías o estados de contenido con colores específicos.
	*/
	/* Clase para un borde izquierdo de color azul (Primario) */
	.border-left-primary {
	  border-left: 5px solid #007bff;
	}
	
	/* Clase para un borde izquierdo de color verde (Éxito) */
	.border-left-success {
	  border-left: 5px solid #28a745; 
	}
	
	/* Clase para un borde izquierdo de color amarillo (Advertencia) */
	.border-left-warning {
	  border-left: 5px solid #ffc107; 
	}
	
	/* Clase para un borde izquierdo de color rojo (Peligro) */
	.border-left-danger {
	  border-left: 5px solid #dc3545;
	}
	.titles-items{
		font-size: 0.8rem;
		font-weight: 600;
	}
	.title-bold{
		font-weight: 600;
		margin-bottom: 3px;
	}

	.login.login-with-news-feed .right-content {
		width: 520px;
	}

	.login.login-with-news-feed .news-feed {
		right: 520px;
	}
	</style>
{{-- @endpush --}}