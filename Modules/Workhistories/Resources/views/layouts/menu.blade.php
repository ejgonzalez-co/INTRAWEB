<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ url('/dashboard') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>
<li class="{{ Request::is('work-histories/work-histories-actives*') ? 'active' : '' }}">
    <a href="{{ route('work-histories-actives.index') }}"><i class="fa fa-edit"></i><span>@lang('Work histories') @lang('Actives')</span></a>
</li>

@if(Auth::user()->hasRole('Administrador historias laborales'))     

    <li class="{{ Request::is('work-histories/configuration-documents*') ? 'active' : '' }}">
        <a href="{{ route('configuration-documents.index') }}"><i class="fa fa-clipboard"></i><span>@lang('Configuration') @lang('documents')</span></a>
    </li>

@endif


