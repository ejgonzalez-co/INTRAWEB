<li class="{{ Request::is('leca/customers*') ? 'active' : '' }}">
    <a href="{{ route('customers.index') }}"><i class="fas fa-user-tie"></i><span>@lang('customers')</span></a>
</li>

<li class="{{ Request::is('leca/sample-points*') ? 'active' : '' }}">
    <a href="{{ route('sample-points.index') }}"><i class="fas fa-map-marked-alt"></i><span>@lang('samplePoints')</span></a>
</li>