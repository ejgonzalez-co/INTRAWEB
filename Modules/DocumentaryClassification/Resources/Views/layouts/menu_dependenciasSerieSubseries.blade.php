<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ url('/dashboard') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<li class="{{ Request::is('documentaryClassification/dependencias') ? 'active' : '' }}">
    <a href="{{ route('dependencias.index') }}"><i class="fa fa-file"></i><span>@lang('dependencias')</span></a>
</li>
