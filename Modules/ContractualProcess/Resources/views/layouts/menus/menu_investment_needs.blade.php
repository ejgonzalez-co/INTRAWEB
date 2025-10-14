
<li class="{{ Request::is('needs*') ? 'active' : '' }}">
    <a href="{{ route('needs.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<li class="{{ Request::is('contractual-process/investment-technical-sheets*') ? 'active' : '' }}">
    <a href="{{ route('investment-technical-sheets.index') }}"><i class="fa fa-edit"></i><span>@lang('Investment Needs')</span></a>
</li>