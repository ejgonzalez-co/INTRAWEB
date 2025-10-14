
<li class="{{ Request::is('list-trials*') ? 'active' : '' }}">
    <a href="{{ route('list-trials.index') }}"><i class="fa fa-edit"></i><span>@lang('listTrials')</span></a>
</li><li class="{{ Request::is('observacionDuplicados*') ? 'active' : '' }}">
    <a href="{{ route('observacionDuplicados.index') }}"><i class="fa fa-edit"></i><span>@lang('observacionDuplicados')</span></a>
</li>

<li class="{{ Request::is('observacionesDuplicadoHierros*') ? 'active' : '' }}">
    <a href="{{ route('observacionesDuplicadoHierros.index') }}"><i class="fa fa-edit"></i><span>@lang('observacionesDuplicadoHierros')</span></a>
</li>

<li class="{{ Request::is('observacionesDuplicadoFosfatos*') ? 'active' : '' }}">
    <a href="{{ route('observacionesDuplicadoFosfatos.index') }}"><i class="fa fa-edit"></i><span>@lang('observacionesDuplicadoFosfatos')</span></a>
</li>

<li class="{{ Request::is('observacionesDuplicadoNitritos*') ? 'active' : '' }}">
    <a href="{{ route('observacionesDuplicadoNitritos.index') }}"><i class="fa fa-edit"></i><span>@lang('observacionesDuplicadoNitritos')</span></a>
</li>

<li class="{{ Request::is('observacionesDuplicadoNitratos*') ? 'active' : '' }}">
    <a href="{{ route('observacionesDuplicadoNitratos.index') }}"><i class="fa fa-edit"></i><span>@lang('observacionesDuplicadoNitratos')</span></a>
</li>

<li class="{{ Request::is('observacionesDuplicadoCarbonoOrganicos*') ? 'active' : '' }}">
    <a href="{{ route('observacionesDuplicadoCarbonoOrganicos.index') }}"><i class="fa fa-edit"></i><span>@lang('observacionesDuplicadoCarbonoOrganicos')</span></a>
</li>

<li class="{{ Request::is('observacionesDuplicadoAcidezs*') ? 'active' : '' }}">
    <a href="{{ route('observacionesDuplicadoAcidezs.index') }}"><i class="fa fa-edit"></i><span>@lang('observacionesDuplicadoAcidezs')</span></a>
</li>

<li class="{{ Request::is('observacionesDuplicadoCloruros*') ? 'active' : '' }}">
    <a href="{{ route('observacionesDuplicadoCloruros.index') }}"><i class="fa fa-edit"></i><span>@lang('observacionesDuplicadoCloruros')</span></a>
</li>

<li class="{{ Request::is('observacionesDuplicadoCalcios*') ? 'active' : '' }}">
    <a href="{{ route('observacionesDuplicadoCalcios.index') }}"><i class="fa fa-edit"></i><span>@lang('observacionesDuplicadoCalcios')</span></a>
</li>

<li class="{{ Request::is('observacionesDuplicadoDurezas*') ? 'active' : '' }}">
    <a href="{{ route('observacionesDuplicadoDurezas.index') }}"><i class="fa fa-edit"></i><span>@lang('observacionesDuplicadoDurezas')</span></a>
</li>

<li class="{{ Request::is('observacionesDuplicadoTurbiedads*') ? 'active' : '' }}">
    <a href="{{ route('observacionesDuplicadoTurbiedads.index') }}"><i class="fa fa-edit"></i><span>@lang('observacionesDuplicadoTurbiedads')</span></a>
</li>


<li class="{{ Request::is('observacionesEspectros*') ? 'active' : '' }}">
    <a href="{{ route('observacionesEspectros.index') }}"><i class="fa fa-edit"></i><span>@lang('observacionesEspectros')</span></a>
</li>

