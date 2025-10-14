<li class="nav-item {{ Request::is('home*') ? 'active' : '' }}">
   <a href="{{ url('/dashboard') }}" class="nav-link">
       <i class="nav-icon fas fa-arrow-left"></i>
       <span>@lang('back')</span>
   </a>
</li>