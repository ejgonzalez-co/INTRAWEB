<!-- begin #header -->
<div id="header" class="header navbar-default bg-nav">
    <!-- begin navbar-header -->
    <div class="navbar-header">
  
        <button onclick="window.location='https://epa.gov.co/'" type="button" class="navbar-toggle collapsed navbar-toggle-left text-white">
            <i class="fa fa-chevron-left"></i>
        </button>
        <button onclick="window.location='https://epa.gov.co/'" type="button" class="navbar-toggle text-white  pl-3">
            <i class="fa fa-chevron-left"></i>
        </button>
   
        <a href="https://epa.gov.co/" class="navbar-brand text-white">
            {{ config('app.name') }}
        </a>
        <img src="/assets/img/logo_epa.png" style="width: 80px;" alt="" />

    </div>
    <!-- end navbar-header --><!-- begin header-nav -->
    <ul class="navbar-nav navbar-right">
        <li class="dropdown navbar-user">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="d-md-inline text-white">Bienvenido, Ciudadano</span>
            @if(!empty(Auth::user()->url_img_profile))
                <img src="{{ asset('storage')}}/{{ Auth::user()->url_img_profile }}" alt="" />
            @else
                <img src="{{ asset('assets/img/user/profile.png')}}" alt="" />
            @endif
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="{{ url('/profile') }}" class="dropdown-item">@lang('edit_profle')</a>
                <div class="dropdown-divider"></div>
                <a href="{{ url('/logout') }}" class="dropdown-item"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    @lang('Logout')
                </a>
                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>
    </ul>
    <!-- end header navigation right -->
</div>
<!-- end #header -->
