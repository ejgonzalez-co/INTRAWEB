<li class="{{ Request::is('home*') ? 'active' : '' }}">
   <a href="{!! url('/intranet/notices-public') !!}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<li class="{{ Request::is('intranet/events*') ? 'active' : '' }}">
   <a href="{{ route('events.index') }}"><i class="fas fa-calendar-day"></i><span>@lang('Events')</span></a>
</li>

@role('Administrador intranet de eventos')
   <li class="{{ Request::is('intranet/type-events*') ? 'active' : '' }}">
      <a href="{{ route('type-events.index') }}"><i class="fa fa-edit"></i><span>@lang('Type Events')</span></a>
   </li>
@endrole

