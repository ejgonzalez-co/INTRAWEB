<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ url('/dashboard') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<li class="{{ Request::is('improvement-plans/evaluations*') ? 'active' : '' }}" style="display: flex;">
    <a href="{{ route('evaluations.index') }}" class="nav-link">
        {{-- <i class="fa fa-file-signature"></i> --}}
        <span>@lang('Evaluaciones')</span>
    </a>
</li>

<li class="{{ Request::is('improvement-plans/approved-improvement-plans*') ? 'active' : '' }}" style="display: flex;">
    <a href="{{ route('approved-improvement-plans.index') }}" class="nav-link">
        {{-- <i class="fa fa-file-signature"></i> --}}
        <span>@lang('Planes de mejoramiento')</span>
    </a>
</li>

<li class="{{ Request::is('improvement-plans/closed-improvement-plans*') ? 'active' : '' }}" style="display: flex;">
    <a href="{{ route('closed-improvement-plans.index') }}" class="nav-link">
        {{-- <i class="fa fa-file-signature"></i> --}}
        <span>@lang('Cerrar planes de mejoramiento')</span>
    </a>
</li>
