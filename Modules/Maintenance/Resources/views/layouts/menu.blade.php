<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{!! url('/dashboard') !!}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>
<li class="{{ Request::is('maintenance/asset-types*') ? 'active' : '' }}">
    <a href="{{ route('asset-types.index') }}"><i class="fa fa-cogs"></i><span>Configurar @lang('type') de @lang('Assets')</span></a>
</li>

<li class="{{ Request::is('maintenance/categories*') ? 'active' : '' }}">
    <a href="{{ route('categories.index') }}"><i class="fa fa-tasks"></i><span>Configurar @lang('categories')</span></a>
</li>

<li class="{{ Request::is('maintenance/asset-create-authorizations*') ? 'active' : '' }}">
    <a href="{{ route('asset-create-authorizations.index') }}"><i class="fa fa-users-cog"></i><span>Configurar @lang('authorizations')</span></a>
</li>

<li class="{{ Request::is('maintenance/types-activities*') ? 'active' : '' }}">
    <a href="{{ route('types-activities.index') }}"><i class="fa fa-paste"></i><span>@lang('types-activities-providers')</span></a>
</li>

<li class="{{ Request::is('maintenance/provider-contracts*') ? 'active' : '' }}">
    <a href="{{ route('provider-contracts.index') }}"><i class="fas fa-handshake"></i><span>@lang('Configurar los contratos de los proveedores')</span></a>
</li>

<li class="{{ Request::is('maintenance/tire-brands*') ? 'active' : '' }}">
    <a href="{{ route('tire-brands.index') }}"><i class="fas fa-life-ring"></i><span>@lang('tireBrands')</span></a>
</li>

{{-- <li class="{{ Request::is('maintenance/set-tires*') ? 'active' : '' }}">
    <a href="{{ route('set-tires.index') }}"><i class="fas fa-truck-monster"></i><span>@lang('config_tires')</span></a>
</li>

<li class="{{ Request::is('maintenance/inflation-pressures*') ? 'active' : '' }}">
    <a href="{{ route('inflation-pressures.index') }}"><i class="fas fa-screwdriver"></i><span>@lang('inflationPressures')</span></a>
</li> --}}

<li class="{{ Request::is('maintenance/oil-element-wear-configurations*') ? 'active' : '' }}">
    <a href="{{ route('oil-element-wear-configurations.index') }}"><i class="fab fa-elementor"></i><span>Configurar elementos de desgaste</span></a>
</li>

{{-- <li class="{{ Request::is('new-contracts*') ? 'active' : '' }}">
    <a href="{{ route('new-contracts.index') }}"><i class="fa fa-edit"></i><span>@lang('newContracts')</span></a>
</li> --}}

{{-- <li class="{{ Request::is('contract-news*') ? 'active' : '' }}">
    <a href="{{ route('contract-news.index') }}"><i class="fa fa-edit"></i><span>@lang('contractNews')</span></a>
</li> --}}
{{-- <li class="{{ Request::is('requestAnnotations*') ? 'active' : '' }}">
    <a href="{{ route('requestAnnotations.index') }}"><i class="fa fa-edit"></i><span>@lang('requestAnnotations')</span></a>
</li> --}}

{{-- <li class="{{ Request::is('documentOrders*') ? 'active' : '' }}">
    <a href="{{ route('documentOrders.index') }}"><i class="fa fa-edit"></i><span>@lang('documentOrders')</span></a>
</li> --}}

{{-- <li class="{{ Request::is('historyOrders*') ? 'active' : '' }}">
    <a href="{{ route('historyOrders.index') }}"><i class="fa fa-edit"></i><span>@lang('historyOrders')</span></a>
</li>
 --}}
