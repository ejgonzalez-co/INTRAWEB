<li class="{{ Request::is('home*') ? 'active' : '' }}">
   <a href="{{ url('/dashboard') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>


{{-- <li class="{{ Request::is('help-table/tic-period-validities*') ? 'active' : '' }}">
   <a href="{{ route('tic-period-validities.index') }}"><i class="fa fa-calendar"></i><span> @lang('Tic Period Validities')</span></a>
</li> --}}

<li class="{{ Request::is('help-table/tic-type-assets*') ? 'active' : '' }}">
   <a href="{{ route('tic-type-assets.index') }}"><i class="fas fa-list"></i><span> @lang('Tic Type Assets')</span></a>
</li>

<li class="{{ Request::is('help-table/equipment-resumes*') ? 'active' : '' }}">
   <a href="{{ route('equipment-resumes.index') }}"><i class="fas fa-window-restore"></i><span>@lang('Tic Assets')</span></a>
</li>

<li class="{{ Request::is('help-table/tic-maintenances*') ? 'active' : '' }}">
   <a href="{{ route('tic-maintenances.index') }}"><i class="fas fa-tools"></i><span> @lang('Tic Maintenances')</span></a>
</li>
