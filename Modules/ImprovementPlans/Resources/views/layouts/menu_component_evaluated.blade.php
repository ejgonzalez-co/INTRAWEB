<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ url('/dashboard') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<li class="{{ Request::is('improvement-plans/my-evaluations*') ? 'active' : '' }}" style="display: flex;">
    <a href="{{ route('my-evaluations.index') }}" class="nav-link">
        {{-- <i class="fa fa-file-signature"></i> --}}
        <span>@lang('Mis evaluaciones')</span>
    </a>
</li>

<li class="{{ Request::is('improvement-plans/improvement-plans*') || Request::is('improvement-plans/non-conforming-criterias*') || Request::is('improvement-plans/goals*') ? 'active' : '' }}" style="display: flex;">
    <a href="{{ route('improvement-plans.index') }}" class="nav-link">
        {{-- <i class="fa fa-file-signature"></i> --}}
        <span>@lang('Planes de mejoramiento')</span>
    </a>
</li>