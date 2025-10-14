
@if(Auth::user()->hasRole('PC Gestor de recursos'))
<li class="{{ Request::is('paa-calls*') ? 'active' : '' }}">
   <a href="{{ route('paa-calls.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>
@else

<li class="{{ Request::is('home*') ? 'active' : '' }}">
   <a href="{!! url('/dashboard') !!}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

@endif

<li class="{{ Request::is('needs*') ? 'active' : '' }}">
   <a href="{{ route('needs.index') }}"><i class="fa fa-edit"></i><span>@lang('Needs')</span></a>
</li>

