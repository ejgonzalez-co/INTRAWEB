<li class="{{ Request::is('login*') ? 'active' : '' }}">
    <a href="{{ route('login') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<!--
<li class="{{-- Request::is('positions*') ? 'active' : '' --}}">
    <a href="{{-- route('positions.index') --}}"><i class="fa fa-home"></i><span>@lang('positions')</span></a>
</li>

<li class="{{-- Request::is('headquarters*') ? 'active' : '' --}}">
    <a href="{{-- route('headquarters.index') --}}"><i class="fa fa-home"></i><span>@lang('headquarters')</span></a>
</li>
<li class="{{-- Request::is('dependencies*') ? 'active' : '' --}}">
    <a href="{{-- route('dependencies.index') --}}"><i class="fa fa-edit"></i><span>@lang('dependencies')</span></a>
</li>

<li class="{{-- Request::is('officers*') ? 'active' : '' --}}">
    <a href="{{-- route('officers.index') --}}"><i class="fa fa-edit"></i><span>@lang('officers')</span></a>
</li>
-->

