<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{!! url('dashboard') !!}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<li class="{{ Request::is('supplies-adequacies/requests*') ? 'active' : '' }}">
    <a href="{{ url('supplies-adequacies/requests') }}"><i class="fa fa-edit"></i><span>@lang('Requests')</span></a>
</li>

@if (Auth::user()->hasRole(["Administrador requerimiento gestión recursos","Operador Infraestuctura","Operador Suministros de consumo","Operador Suministros devolutivo / Asignación"]))
    <li class="{{ Request::is('supplies-adequacies/knowledge-bases*') ? 'active' : '' }}">
        <a href="{{ url('supplies-adequacies/knowledge-bases') }}"><i class="fas fa-clipboard-list"></i><span>@lang('Base de conocimiento')</span></a>
    </li>
@endif
