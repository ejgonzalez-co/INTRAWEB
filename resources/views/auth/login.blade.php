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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

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
                        <img src="{{ asset('assets/img/default/icon_login_vuv.png') }}" style="width:30px;height:auto">
						<!--<span class="logo"></span>--> <b>{{ config('app.name') }}</b>
						<small>Servicio de autenticación para ciudadanos y funcionarios</small>

					</div>
				</div>
				<!-- end login-header -->
				<!-- begin login-content -->
				<div class="login-content">
					<form method="post" action="{{ url('/login') }}" class="margin-bottom-0">
                        @csrf
						<div class="form-group m-b-15 has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
							<input type="email" class="form-control form-control-lg @if ($errors->has('email')) is-invalid @endif" placeholder="@lang('Email')" name="email" value="{{ old('email') }}" required />
                            @if ($errors->has('email'))
                                <span class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
						</div>
						<div class="form-group m-b-15 has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
							<input type="password" class="form-control form-control-lg @if ($errors->has('password')) is-invalid @endif" placeholder="@lang('Password')" name="password" required />
                            @if ($errors->has('password'))
                                <span class="invalid-feedback">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
						</div>
						<div class="login-buttons row">
							<div class="col-6 pr-1">
								<button  type="submit" class="btn btn-primary btn-lg w-100">
									<i class="fa fa-sign-in-alt mr-2"></i>@lang('Login')
								</button>
							</div>
							<div class="col-6 pl-1">
								<a href="{{ url('/register') }}" class="btn btn-link btn-lg w-100">
									<i class="fa fa-user fa-lg mr-2"></i>Registrarme
								</a>
							</div>
						</div>

						<div class="m-t-20 m-b-20 text-inverse">
                            Si no recuerda su contraseña haga click <a href="{{ url('/password/reset') }}">Aquí</a><br>
						</div>
						<hr>

						<div class="list-group w-100">
							<div class="text-center">
								<h5 class="title-bold">Acciones de PQRS</h5>
								<small>Haga clic en la opción que desea realizar.</small>
							</div>
							<a href="{{ url('/correspondence/search-pqrs-ciudadano') }}" class="list-group-item list-group-item-action btn border-left-primary mb-2">
								<i class="fa fa-search fa-lg mr-2"></i><b class="titles-items">Consultar PQRS</b> <br>
								<span class="small">Consulta PQRS de correspondencias recibidas.</span>
							</a>
							<a href="{{ url('/register') }}" class="list-group-item list-group-item-action btn border-left-success mb-2 h-25">
								<i class="fa fa-user fa-lg mr-2"></i> <b class="titles-items">Registrarse</b> <br>
								<span class="small">Regístrese como ciudadano para crear y hacer seguimiento de sus PQRS.</span>
							</a>
							<a href="{{ url('/pqrs/p-q-r-s-ciudadano-anonimo') }}" class="list-group-item list-group-item-action btn border-left-warning mb-2">
								<i class="fa fa-user-secret fa-lg mr-2"></i><b class="titles-items">Crear o consultar PQRS de forma anónima</b> <br>
								<span class="small">Si es un ciudadano anónimo, ingrese aquí para crear y consultar PQRS.</span> 
							</a>
						</div>
                        <div class="list-group w-100">
							<div class="text-center mb-1">
								<h5 class="title-bold mt-2">LECA</h5>
								<small >Haga clic en la opción que desea realizar.</small>
							</div >
							
							<a href="{{ config('app.url_joomla') . '/' . 'index.php?option=com_formasonline&formasonlineform=lc_consultar_empresa&Itemid=362' }}" class="list-group-item list-group-item-action btn border-left-danger mb-2">
								<i class="fa fa-search fa-lg mr-2"></i><b class="titles-items">Acceso clientes.</b>
							</a>
							<a href="{{ config('app.url_joomla') . '/' . 'index.php?option=com_formasonline&formasonlineform=lc_ingreso_funcionario&Itemid=363' }}" class="list-group-item list-group-item-action btn border-left-purple mb-2">
								<i class="fa fa-users fa-lg mr-2"></i><b class="titles-items">Acceso funcionarios.</b>
							</a>
						</div>
                        <div class="list-group w-100">
							<div class="text-center mb-1">
								<h5 class="title-bold mt-2">Mantenimientos</h5>
								<small>Haga clic en la opción que desea realizar.</small>
							</div>
							
							<a href="/login-outside-vendor" class="list-group-item list-group-item-action btn border-left-blue ">
								<i class="fas fa-users-cog mr-2"></i><b class="titles-items">Acceso proveedores internos y externos.</b>
							</a>
						</div>
						<hr>

                        <div class="social-pro">
                            <a href="https://accounts.google.com/signin/v2/identifier?continue=https%3A%2F%2Fmail.google.com%2Fmail%2F&service=mail&btmpl=mobile&hd=epa.gov.co&sacu=1&flowName=GlifWebSignIn&flowEntry=AddSession" target="_blank" class="social-link">
                                <i class="fab fa-google social-icon"></i>
                                <span class="social-text">Correo EPA</span>
                            </a>
                            <a href="https://www.facebook.com/EPA.ARMENIA" target="_blank" class="social-link">
                                <i class="fab fa-facebook-f social-icon"></i>
                                <span class="social-text">Facebook</span>
                            </a>
                            {{-- <a href="https://twitter.com/tu_usuario_de_twitter" target="_blank" class="social-link">
                                <i class="fab fa-twitter social-icon"></i>
                                <span class="social-text">Twitter</span>
                            </a> --}}
                            <a href="https://www.instagram.com/epa_armenia/" target="_blank" class="social-link">
                                <i class="fab fa-instagram social-icon"></i>
                                <span class="social-text">Instagram</span>
                            </a>
                            <a href="https://www.youtube.com/channel/UC7aWkW00l4isAqPh6dyyJrA" target="_blank" class="social-link">
                                <i class="fab fa-youtube social-icon"></i>
                                <span class="social-text">YouTube</span>
                            </a>
                            {{-- <a href="https://www.flickr.com/photos/146031145@N02/" target="_blank" class="social-link">
                                <i class="fab fa-flickr social-icon"></i>
                                <span class="social-text">Flickr</span>
                            </a> --}}
                        </div>



						{{-- Valida los roles para mostrar el módulo de Configuración --}}
						@if(config('app.mod_expedientes'))
							<div class="list-group w-100">
								<div class="text-center mb-1">
									<h5 class="title-bold mt-2">Expedientes electrónicos</h5>
									<small>Haga clic en la opción que desea realizar.</small>
								</div>
								
								<a href="/login-usuarios-externos-expedientes" class="list-group-item list-group-item-action btn border-left-danger ">
									<i class="fas fa-users-cog mr-2"></i><b class="titles-items">Acceso usuarios externos.</b> <br>
									<span class="small">Ingrese con su dirección de correo y el PIN que recibió por email.</span> 
								</a>
							</div>
						@endif
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
    <style>
      /* Contenedor general */
.social-pro {
    display: flex;
    justify-content: center; /* Centra los enlaces */
    flex-wrap: wrap;
    gap: 1rem; /* Espaciado entre elementos */
    padding: 1rem;
}

/* Estilo para los enlaces */
.social-link {
    display: flex;
    flex-direction: column; /* Íconos arriba del texto */
    align-items: center;
    text-decoration: none !important; /* Elimina subrayado */
    color: #333 !important; /* Color del texto */
    font-size: 0.8rem; /* Texto más pequeño */
    width: 4rem; /* Ancho fijo para uniformidad */
    text-align: center;
    transition: transform 0.2s ease;
}

/* Hover: solo un pequeño zoom */
.social-link:hover {
    transform: scale(1.1);
}

/* Estilo para el ícono */
.social-icon {
    font-size: 1.5rem; /* Tamaño del ícono */
    margin-bottom: 0.3rem; /* Separación entre ícono y texto */
    color: inherit !important; /* Toma el color del enlace */
    text-decoration: none !important; /* Sin subrayado */
    transition: color 0.2s ease;
}

/* Estilo para el texto */
.social-text {
    font-size: 0.75rem;
    font-weight: bold;
    color: #333 !important; /* Asegura un color uniforme */
    text-decoration: none !important; /* Elimina subrayado */
}

/* Colores oficiales de Gmail */
.social-link .fa-google {
    color: #ea4335 !important; /* Rojo Google (Gmail) */
}

/* Colores oficiales de cada red social */
.social-link .fa-facebook-f {
    color: #1877f2 !important; /* Azul Facebook */
}

/* Colores oficiales de Twitter */
.social-link .fa-twitter {
    color: #1da1f2 !important; /* Azul Twitter */
}

.social-link .fa-instagram {
    color: #e1306c !important; /* Rosa Instagram */
}

.social-link .fa-youtube {
    color: #ff0000 !important; /* Rojo YouTube */
}

.social-link .fa-flickr {
    color: #0063dc !important; /* Azul Flickr */
}

.social-link:hover .fa-google {
    color: #c5221f !important; /* Hover Gmail */
}

.social-link:hover .fa-facebook-f {
    color: #145dbf !important; /* Hover Facebook */
}

.social-link:hover .fa-twitter {
    color: #0d8ddf !important; /* Hover Twitter */
}

social-link:hover .fa-instagram {
    color: #bc2a8d !important; /* Hover Instagram */
}

.social-link:hover .fa-youtube {
    color: #cc0000 !important; /* Hover YouTube */
}

.social-link:hover .fa-flickr {
    color: #0046a5 !important; /* Hover Flickr */
}


	
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
	.border-left-purple{
		border-left:5px solid #4E25FF;
	}
	.border-left-blue{
        border-left:5px solid #00C9F3;
    }
	.titles-items{
		font-size: 0.8rem;
		font-weight: 600;
	}
	.title-bold{
		font-weight: 600;
		margin-bottom: 3px;
	}
    </style>
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
	</style>
{{-- @endpush --}}