<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{!! url('/dashboard') !!}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>
@if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo') || Auth::user()->hasRole('mant Operador Combustible EDS')|| Auth::user()->hasRole('mant Consulta general') || Auth::user()->hasRole('mant Consulta proceso'))
<li class="{{ Request::is('maintenance/vehicle-fuels*') ? 'active' : '' }}">
    <a href="{{ route('vehicle-fuels.index') }}"><i class="fas fa-gas-pump"></i><span>Combustible vehículos</span></a>
</li>
@endif
{{-- <li class="{{ Request::is('maintenance/vehicle-fuels*') ? 'active' : '' }}">
    <a href="{{ route('vehicle-fuels.index') }}"><i class="fas fa-gas-pump"></i><span>Combustible Equipos menores</span></a>
</li> --}}
@if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo') || Auth::user()->hasRole('mant Operador Combustible equipos menores')|| Auth::user()->hasRole('mant Consulta general') || Auth::user()->hasRole('mant Consulta proceso'))

<li class="{{ Request::is('maintenance/minor-equipment-fuel*') ? 'active' : '' }}">
    <a href="{{ route('minor-equipment-fuel.index') }}"><i class="fas fa-gas-pump"></i><span>@lang('minorEquipmentFuels')</span></a>
</li>
@endif
{{-- <li class="{{ Request::is('maintenance/equipmentMinorFuelConsumptions*') ? 'active' : '' }}">
    <a href="{{ route('equipmentMinorFuelConsumptions.index') }}"><i class="fa fa-edit"></i><span>@lang('equipmentMinorFuelConsumptions')</span></a>
</li>

<li class="{{ Request::is('maintenance/documentsMinorEquipments*') ? 'active' : '' }}">
    <a href="{{ route('documentsMinorEquipments.index') }}"><i class="fa fa-edit"></i><span>@lang('documentsMinorEquipments')</span></a>
</li> --}}

