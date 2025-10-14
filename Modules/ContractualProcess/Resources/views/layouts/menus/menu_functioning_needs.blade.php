
<li class="{{ Request::is('contractual-process/needs*') ? 'active' : '' }}">
    <a href="{{ route('needs.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<li class="{{ Request::is('contractual-process/functioning-needs*') ? 'active' : '' }}">
    <a href="{{ route('functioning-needs.index') }}"><i class="fa fa-edit"></i><span>@lang('Functioning Needs')</span></a>
</li>
