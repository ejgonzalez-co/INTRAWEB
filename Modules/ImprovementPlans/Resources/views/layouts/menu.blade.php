<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ url('/dashboard') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

{{-- <li class="{{ Request::is('improvement-plans/rols*') ? 'bg-secondary' : '' }}"  style="border:1px solid #cacaca;">
    <a href="{{ route('rols.index') }}" style="color: #2b2b2b;padding:5px 3px 5px 10px"></i><span class="{{ Request::is('improvement-plans/rols*') ? 'text-white' : 'text-dark' }}">@lang('Roles')</span></a>
</li>

<li class="{{ Request::is('improvement-plans/users*') ? 'bg-secondary' : '' }}" style="border:1px solid #cacaca;">
    <a href="{{ url('/improvement-plans/users') }}" style="color: #2b2b2b;padding:5px 3px 5px 10px"><span class="{{ Request::is('improvement-plans/users*') ? 'text-white' : 'text-dark' }}">@lang('Usuarios')</span></a>
</li>

<li class="{{ Request::is('improvement-plans/evaluation-processes*') ? 'active' : '' }}" style="display: flex;">
    <a href="{{ route('evaluation-processes.index') }}" class="nav-link">
        <span>@lang('Procesos de evaluación')</span>
    </a>
</li> --}}

<li class="{{ Request::is('improvement-plans/type-evaluations*') ? 'active' : '' }}" style="display: flex;">
    <a href="{{ route('type-evaluations.index') }}" class="nav-link">
        {{-- <i class="fa fa-file-signature"></i> --}}
        <span>@lang('Tipos de evaluación')</span>
    </a>
</li>

<li class="{{ Request::is('improvement-plans/type-improvement-plans*') ? 'active' : '' }}" style="display: flex;">
    <a href="{{ route('type-improvement-plans.index') }}" class="nav-link">
        {{-- <i class="fa fa-file-signature"></i> --}}
        <span>@lang('Tipos de planes de mejoramiento')</span>
    </a>
</li>

<li class="{{ Request::is('improvement-plans/source-informations*') ? 'active' : '' }}" style="display: flex;">
    <a href="{{ route('source-informations.index') }}" class="nav-link">
        {{-- <i class="fa fa-file-signature"></i> --}}
        <span>@lang('Fuentes de información')</span>
    </a>
</li>

<li class="{{ Request::is('improvement-plans/type-improvement-opportunities*') ? 'active' : '' }}" style="display: flex;">
    <a href="{{ route('type-improvement-opportunities.index') }}" class="nav-link">
        {{-- <i class="fa fa-file-signature"></i> --}}
        <span>@lang('Tipos de oportunidades de mejora')</span>
    </a>
</li>

<li class="{{ Request::is('improvement-plans/evaluation-criterias*') ? 'active' : '' }}" style="display: flex;">
    <a href="{{ route('evaluation-criterias.index') }}" class="nav-link">
        {{-- <i class="fa fa-file-signature"></i> --}}
        <span>@lang('Criterios de evaluación')</span>
    </a>
</li>

<li class="{{ Request::is('improvement-plans/content-managements*') ? 'active' : '' }}" style="display: flex;">
    <a href="{{ route('content-managements.index') }}" class="nav-link">
        {{-- <i class="fa fa-file-signature"></i> --}}
        <span>@lang('Gestión de contenido')</span>
    </a>
</li>

{{-- <li class="{{ Request::is('annotation-evaluations*') ? 'active' : '' }}">
    <a href="{{ route('annotation-evaluations.index') }}"><i class="fa fa-edit"></i><span>@lang('annotationEvaluations')</span></a>
</li> --}}


