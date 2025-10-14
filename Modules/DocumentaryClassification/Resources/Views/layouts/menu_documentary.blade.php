
<li class="{{ Request::is('home*') ? 'active' : '' }}">
   <a href="{{ url('/dashboard') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<li class="{{ Request::is('documentary-classification/series-subseries*') ? 'active' : '' }}">
   <a href="{{ route('series-subseries.index') }}"><i class="fa fa-folder-open"></i><span>@lang('seriesSubSeries')</span></a>
</li>
