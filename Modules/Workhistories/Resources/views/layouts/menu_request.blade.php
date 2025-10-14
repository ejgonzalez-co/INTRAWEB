<li class="{{ Request::is('home*') ? 'active' : '' }}">
   <a href="{{ url('/dashboard') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>
<li class="{{ Request::is('work-histories/work-request*') ? 'active' : '' }}">
   <a href="{{ route('work-request.index') }}"><i class="fa fa-address-card"></i><span>Autorizaciones de hojas de vida</span></a>
</li>