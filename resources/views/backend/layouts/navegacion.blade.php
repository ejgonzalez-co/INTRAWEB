<!-- Navbar -->
<nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
	<!-- Left navbar links -->
	<ul class="navbar-nav">    
		<li class="nav-item">
			<a href="#" class="nav-link" data-widget="pushmenu" >
				<i class="fa fa-bars fa-lg"></i>
			</a>
		</li>
	</ul>

	<!-- Right navbar links -->
	<ul class="navbar-nav ml-auto">
		<li class="nav-item dropdown">
			<a class="nav-link" data-toggle="dropdown" href="#">
				<i class="fa fa-th-large fa-lg"></i>
			</a>
			<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
				<div class="dropdown-divider"></div>
				<a href="{{url('salir')}}" class="dropdown-item" onclick="window.location='{{url('salir')}}';">
					<i class="fa fa-sign-out mr-2"></i> Cerrar sesión
				</a>
			</div>
		</li>
	</ul>
</nav>