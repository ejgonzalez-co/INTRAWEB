
<li class="{{ Request::is('home*') ? 'active' : '' }}">
   <a href="{!! url('/dashboard') !!}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<li class="{{ Request::is('contractual-process/plans-budgets-functioning*') ? 'active' : '' }}">
   <a href="{{ route('plans-budgets-functioning') }}"><i class="fa fa-edit"></i><span>@lang('Functioning Needs')</span></a>
</li>

<li class="{{ Request::is('contractual-process/plans-budgets-investment*') ? 'active' : '' }}">
   <a href="{{ route('plans-budgets-investment') }}"><i class="fa fa-edit"></i><span>@lang('Investment Needs')</span></a>
</li>