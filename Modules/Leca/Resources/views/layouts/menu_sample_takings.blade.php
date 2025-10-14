<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{!! url('leca/start-samplings') !!}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>
<li class="{{ Request::is('leca/sample-takings*') ? 'active' : '' }}">
    <a href="{{ route('sample-takings.index') }}"><i class="fas fa-tint"></i><span>@lang('sampleTakings')</span></a>
</li>