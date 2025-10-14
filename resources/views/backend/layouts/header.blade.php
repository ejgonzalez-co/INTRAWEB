<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   
   <meta name="keywords" content="ccupsci,estudiantes,contratación,armenia">
   <meta name="description" content="">
   <meta name="author" content="">


   <!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	
	<title>{{ config('app.name') }}</title>

	<!--Hojas de estilo-->
	{!!Html::style('css/admin/app.css')!!}
	{!!Html::style('css/admin/estilos.css')!!}

	@yield('style')
</head>
<body class="hold-transition sidebar-mini">
	<div class="wrapper">


		<!--encabezado-->
		@include('backend.layouts.navegacion')

		<!-- Main Sidebar Container -->
		<aside class="main-sidebar sidebar-dark-primary elevation-4">
		  	<!-- Brand Logo -->
		  	<a href="{{url('manage')}}" class="brand-link">
		    	<img src="{{asset('img/intranet.png')}}" alt="{{ config('app.empresa') }}" class="brand-image img-circle elevation-3"
		         style="opacity: .8">
		    	<span class="brand-text font-weight-light">{{ config('app.empresa') }}</span>
		  	</a>

		  	<!-- Sidebar -->
		  	<div class="sidebar">
		    	<!-- Sidebar user panel (optional) -->
			   <div class="user-panel mt-3 pb-3 mb-3 d-flex">
			      <div class="image">
			      	<a href="{{url('manage/perfil')}}">
			        		<img src="{{asset('img/avatar.png')}}" class="img-circle elevation-2" alt="Perfil">
			        	</a>
			      </div>
			      <div class="info">
			        	<a href="{{url('manage/perfil')}}" class="d-block"></a>
			        	 <a class="font-weight-light">
			        	 	<i class="fa fa-circle text-success"></i>
			      </div>
			   </div>

		    	<!-- Sidebar Menu -->
			   <nav class="mt-2">
			      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
						@include('backend.modulos.gestionPermiso')
						@include('backend.modulos.gestionRol')
						@include('backend.modulos.gestionUsuario')
			      </ul>
			   </nav>
		    	<!-- /.sidebar-menu -->
		  	</div>
		  	<!-- /.sidebar -->
		</aside>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">

		  	<!-- Content Header (Page header) -->
			<div class="content-header">
			   <div class="container-fluid">
			      <div class="row mb-2">
			        	<div class="col-sm-6">
			          	<ol class="breadcrumb float-sm-left">
								<li class="breadcrumb-item"><a href="{{url('administrador')}}">Administración</a></li>
								@yield('migas')
							</ol>
			        	</div><!-- /.col -->
			        	<div class="col-sm-6">
						  
							
			        	</div><!-- /.col -->
			      </div><!-- /.row -->
			   </div><!-- /.container-fluid -->
			</div>
		  	<!-- /.content-header -->

		  	<!-- Main content -->
		  	<section class="content">
		    	<div class="container-fluid">
			      <!-- Main row -->
			      <div class="row">
			        	<!-- Left col -->
			        	<section class="col-lg-12 connectedSortable">						
							<!--Contenido-->
					      @yield('contenido')
							<!--Fin Contenido-->
			        	</section>
			        	<!-- /.Left col -->
			      </div>
		      	<!-- /.row (main row) -->
		    	</div><!-- /.container-fluid -->
		  	</section>
		  	<!-- /.content -->
		</div>
		<!-- /.content-wrapper -->
  		@include('backend.layouts.footer')
  	</div>
	<!-- ./wrapper -->

 	<script src="{{asset('js/admin/app.js')}}"></script>
	

 	@yield('script')
</body>
</html>

