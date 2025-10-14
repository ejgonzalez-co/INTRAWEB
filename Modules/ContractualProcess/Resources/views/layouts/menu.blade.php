<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ route('paa-calls.index') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<li class="{{ Request::is('needs*') ? 'active' : '' }}">
    <a href="{{ route('needs.index') }}"><i class="fa fa-edit"></i><span>@lang('Needs')</span></a>
</li>

<li class="{{ Request::is('pc-functioning-needs*') ? 'active' : '' }}">
    <a href="{{ route('pc-functioning-needs.index') }}"><i class="fa fa-edit"></i><span>@lang('Functioning Needs')</span></a>
</li>

<li class="{{ Request::is('investment-needs*') ? 'active' : '' }}">
    <a href="{{ route('investment-needs.index') }}"><i class="fa fa-edit"></i><span>@lang('investmentNeeds')</span></a>
</li>

<li class="{{ Request::is('pc-previous-studies*') ? 'active' : '' }}">
    <a href="{{ route('pc-previous-studies.index') }}"><i class="fa fa-edit"></i><span>@lang('Studies') @lang('Previous')</span></a>
</li>

<li class="{{ Request::is('needs*') ? 'active' : '' }}">
    <a href="{{ route('needs.index') }}"><i class="fa fa-edit"></i><span>@lang('needs')</span></a>
</li>

<li class="{{ Request::is('functioningNeeds*') ? 'active' : '' }}">
    <a href="{{ route('functioningNeeds.index') }}"><i class="fa fa-edit"></i><span>@lang('functioningNeeds')</span></a>
</li>

<li class="{{ Request::is('investmentTechnicalSheets*') ? 'active' : '' }}">
    <a href="{{ route('investmentTechnicalSheets.index') }}"><i class="fa fa-edit"></i><span>@lang('investmentTechnicalSheets')</span></a>
</li>



<li class="{{ Request::is('paacalls*') ? 'active' : '' }}">
    <a href="{{ route('paacalls.index') }}"><i class="fa fa-edit"></i><span>@lang('paacalls')</span></a>
</li>

<li class="{{ Request::is('paaCalls*') ? 'active' : '' }}">
    <a href="{{ route('paaCalls.index') }}"><i class="fa fa-edit"></i><span>@lang('paaCalls')</span></a>
</li>

<li class="{{ Request::is('paaNeeds*') ? 'active' : '' }}">
    <a href="{{ route('paaNeeds.index') }}"><i class="fa fa-edit"></i><span>@lang('paaNeeds')</span></a>
</li>

