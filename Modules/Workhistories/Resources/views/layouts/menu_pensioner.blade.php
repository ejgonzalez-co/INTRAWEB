<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ url('/dashboard') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<li class="{{ Request::is('work-histories/work-hist-pensioners*') ? 'active' : '' }}">
    <a href="{{ route('work-hist-pensioners.index') }}"><i class="fa fa-edit"></i><span>@lang('Work histories') @lang('pensioners')</span></a>
</li>

<li class="{{ Request::is('work-histories/quota-parts-pensioners*') ? 'active' : '' }}">
    <a href="{{ route('quota-parts-pensioners.index') }}"><i class="fa fa-project-diagram"></i><span>@lang('Quota Parts')</span></a>
</li>


<li class="{{ Request::is('work-histories/substitutes*') ? 'active' : '' }}">
    <a href="{{ route('substitutes.index') }}"><i class="fa fa-users"></i><span>@lang('Substitutes')</span></a>
</li>



@if(Auth::user()->hasRole('Administrador historias laborales'))     

    <li class="{{ Request::is('work-histories/config-doc-pensioners*') ? 'active' : '' }}">
        <a href="{{ route('config-doc-pensioners.index') }}"><i class="fa fa-clipboard"></i><span>@lang('Configuration') @lang('documents')</span></a>
    </li>

@endif 

