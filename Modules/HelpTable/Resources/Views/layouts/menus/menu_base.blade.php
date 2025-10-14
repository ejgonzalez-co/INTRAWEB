<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ url('/dashboard') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


<li class="{{ Request::is('help-table/tic-knowledge-bases*') ? 'active' : '' }}">
    <a href="{{ route('tic-knowledge-bases.index') }}"><i class="fas fa-braille"></i><span>@lang('Tic Knowledge Bases')</span></a>
</li>
