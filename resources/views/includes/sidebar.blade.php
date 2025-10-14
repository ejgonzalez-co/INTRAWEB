@php
	$sidebarClass = (!empty($sidebarTransparent)) ? 'sidebar-transparent' : '';
	$ultima_version = DB::table('intraweb_version_update')->latest()->first();

	$customLogo = env('DEFAULT_LOGO', ''); // Si está vacía, usará la imagen por defecto
    $defaultLogo = asset('assets/img/default/intraweb_default.svg'); // Imagen por defecto
    $logoUrl = !empty($customLogo) ? asset('assets/img/default/' . $customLogo) : $defaultLogo;
@endphp
<!-- begin #sidebar -->
<div id="sidebar" class="sidebar {{ $sidebarClass }}">
	<!-- begin sidebar scrollbar -->
	<div data-scrollbar="true" data-height="100%">
		@if (!$sidebarSearch)
		<!-- begin sidebar user -->
		<ul class="nav">
			<li class="nav-profile" data-toggle="nav-profile">
				<a href="javascript:;">
					<div class="cover">
                        <img src="{{ asset('assets/img/default/banner_user_profile_gray.png') }}" class="img-responsive">
                    </div>
					{{-- <div class="image">
						@if(View::hasSection('section_img'))
						<img src="{!! asset($__env->yieldContent('section_img')) !!}" alt="" />
						@else
						<img src="{{ asset('assets/img/default/icon_users_profile_vuv.png') }}" alt="" />
						@endif
					</div> --}}

					<div class="image" style="margin: auto; display:block">

						<img src="{{ $logoUrl }}" alt="Logo" />						
						<p style="color: gray; font-size: 12px; text-align:center">Versión: {{$ultima_version->numero_version}}</p>
					</div>


					<div class="info text-black text-center">
						<!-- Administración de usuarios -->
						@yield('title')
						{{-- Auth::user()->name --}}
						<!--<small>Adminsitrador | Planeación | Director</small>-->
					</div>
				</a>
			</li>
		</ul>
		<!-- end sidebar user -->
		@endif
		<!-- begin sidebar nav -->
		<ul class="nav">
			@if ($sidebarSearch)
			<li class="nav-search">
            <input type="text" class="form-control" placeholder="Sidebar menu filter..." data-sidebar-search="true" />
			</li>
			@endif
			<li class="nav-header">Navegación</li>
            @yield('menu')
		</ul>
		<!-- end sidebar nav -->
	</div>
	<!-- end sidebar scrollbar -->
</div>
<div class="sidebar-bg"></div>
<!-- end #sidebar -->

