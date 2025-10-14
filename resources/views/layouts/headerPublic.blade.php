@php
    $ultima_configuracion = DB::table('configuration_general')->latest()->first();
    $defaultLogoUrl = 'https://intraweb.seven.com.co/assets/img/default/intraweb_default.png';
    $logoUrl = $ultima_configuracion && !empty($ultima_configuracion->logo)
        ? (Str::startsWith($ultima_configuracion->logo, ['http', 'https']) 
            ? $ultima_configuracion->logo 
            : asset('storage/' . $ultima_configuracion->logo))
        : $defaultLogoUrl;
@endphp

<div id="header" class="header navbar-default" style="background-color: {{ $ultima_configuracion->color_barra ?? '#343a40' }};">
    <nav class="navbar navbar-expand-lg navbar-light w-100" style="padding: 0px; min-height:60px;">
        
        <!-- Logo y nombre app -->
        <a class="d-flex align-items-center flex-shrink-0" href="/dashboard" style="font-size:18px; font-weight:300;">
            <div class="d-flex justify-content-center align-items-center ml-2 mr-2" style="background-color: azure; width: 50px; height: 50px;">
                <img class="img-fluid" src="{{ $logoUrl }}" alt="Logo" style="max-height: 40px; object-fit: contain;">
            </div>
            <span class="ml-2 text-white">{{ config('app.name') }}</span>
        </a>

        <!-- Botón de menú para móviles -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Usuario (sin menú) -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto d-flex align-items-center flex-wrap pr-4">
                <li class="nav-item d-flex align-items-center text-white" style="white-space: normal;">
                    @php
                        $avatar = (!empty(Auth::user()->url_img_profile) && Auth::user()->url_img_profile !== "users/avatar/default.png")
                            ? asset('storage/' . Auth::user()->url_img_profile)
                            : asset('assets/img/user/profile.png');
                    @endphp
                    <img src="{{ $avatar }}" alt="Perfil" class="rounded-circle mr-2" style="width: 35px; height: 35px;">
                    <span class="text-white font-weight-normal">{{ Auth::user() ? Auth::user()->name : 'Usuario(a)' }}</span>
                </li>
            </ul>
        </div>
        
    </nav>
</div>
