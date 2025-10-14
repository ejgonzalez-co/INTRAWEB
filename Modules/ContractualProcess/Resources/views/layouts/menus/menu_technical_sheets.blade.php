
<li class="{{ Request::is('pc-investment-needs*') ? 'active' : '' }}">
   <a href="{{ route('pc-investment-needs.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<li class="{{ Request::is('investment-technical-sheets*') ? 'active' : '' }}">
   <a href="{{ route('investment-technical-sheets.index') }}"><i class="fa fa-edit"></i><span>@lang('Investment Technical Sheets')</span></a>
</li>
