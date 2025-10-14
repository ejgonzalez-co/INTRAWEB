<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ url('/dashboard') }}"><i class="fa fa-arrow-left"></i><span>@lang('back')</span></a>
</li>

<li class="{{ Request::is('ticAssetsHistories*') ? 'active' : '' }}">
    <a href="{{ route('ticAssetsHistories.index') }}"><i class="fa fa-edit"></i><span>@lang('ticAssetsHistories')</span></a>
</li>

<li class="{{ Request::is('htTicTypeAssets*') ? 'active' : '' }}">
    <a href="{{ route('htTicTypeAssets.index') }}"><i class="fa fa-edit"></i><span>@lang('htTicTypeAssets')</span></a>
</li>

<li class="{{ Request::is('htTicAssets*') ? 'active' : '' }}">
    <a href="{{ route('htTicAssets.index') }}"><i class="fa fa-edit"></i><span>@lang('htTicAssets')</span></a>
</li>

<li class="{{ Request::is('htTicAssetsHistories*') ? 'active' : '' }}">
    <a href="{{ route('htTicAssetsHistories.index') }}"><i class="fa fa-edit"></i><span>@lang('htTicAssetsHistories')</span></a>
</li>

<li class="{{ Request::is('htTicAssetsHistories*') ? 'active' : '' }}">
    <a href="{{ route('htTicAssetsHistories.index') }}"><i class="fa fa-edit"></i><span>@lang('htTicAssetsHistories')</span></a>
</li>

<li class="{{ Request::is('htTicAssetsHistories*') ? 'active' : '' }}">
    <a href="{{ route('htTicAssetsHistories.index') }}"><i class="fa fa-edit"></i><span>@lang('htTicAssetsHistories')</span></a>
</li>

<li class="{{ Request::is('htTicMaintenances*') ? 'active' : '' }}">
    <a href="{{ route('htTicMaintenances.index') }}"><i class="fa fa-edit"></i><span>@lang('htTicMaintenances')</span></a>
</li>

<li class="{{ Request::is('htTicTypeRequests*') ? 'active' : '' }}">
    <a href="{{ route('htTicTypeRequests.index') }}"><i class="fa fa-edit"></i><span>@lang('htTicTypeRequests')</span></a>
</li>

<li class="{{ Request::is('htTicRequestStatuses*') ? 'active' : '' }}">
    <a href="{{ route('htTicRequestStatuses.index') }}"><i class="fa fa-edit"></i><span>@lang('htTicRequestStatuses')</span></a>
</li>

<li class="{{ Request::is('ticRequests*') ? 'active' : '' }}">
    <a href="{{ route('ticRequests.index') }}"><i class="fa fa-edit"></i><span>@lang('ticRequests')</span></a>
</li>

<li class="{{ Request::is('htTicRequestHistories*') ? 'active' : '' }}">
    <a href="{{ route('htTicRequestHistories.index') }}"><i class="fa fa-edit"></i><span>@lang('htTicRequestHistories')</span></a>
</li>

<li class="{{ Request::is('htTicSatisfactionPolls*') ? 'active' : '' }}">
    <a href="{{ route('htTicSatisfactionPolls.index') }}"><i class="fa fa-edit"></i><span>@lang('htTicSatisfactionPolls')</span></a>
</li>

<li class="{{ Request::is('htTicKnowledgeBases*') ? 'active' : '' }}">
    <a href="{{ route('htTicKnowledgeBases.index') }}"><i class="fa fa-edit"></i><span>@lang('htTicKnowledgeBases')</span></a>
</li>

<li class="{{ Request::is('htTicProviders*') ? 'active' : '' }}">
    <a href="{{ route('htTicProviders.index') }}"><i class="fa fa-edit"></i><span>@lang('htTicProviders')</span></a>
</li>

<li class="{{ Request::is('htTicPollQuestions*') ? 'active' : '' }}">
    <a href="{{ route('htTicPollQuestions.index') }}"><i class="fa fa-edit"></i><span>@lang('htTicPollQuestions')</span></a>
</li>

<li class="{{ Request::is('tic-period-validities*') ? 'active' : '' }}">
    <a href="{{ route('tic-period-validities.index') }}"><i class="fa fa-edit"></i><span>@lang('Tic Period Validities')</span></a>
</li>

<li class="{{ Request::is('ticTypeTicCategories*') ? 'active' : '' }}">
    <a href="{{ route('ticTypeTicCategories.index') }}"><i class="fa fa-edit"></i><span>@lang('ticTypeTicCategories')</span></a>
</li>

<li class="{{ Request::is('ticRequests*') ? 'active' : '' }}">
    <a href="{{ route('ticRequests.index') }}"><i class="fa fa-edit"></i><span>@lang('ticRequests')</span></a>
</li>

<li class="{{ Request::is('ticTypeRequests*') ? 'active' : '' }}">
    <a href="{{ route('ticTypeRequests.index') }}"><i class="fa fa-edit"></i><span>@lang('ticTypeRequests')</span></a>
</li>

<li class="{{ Request::is('ticRequestStates*') ? 'active' : '' }}">
    <a href="{{ route('ticRequestStates.index') }}"><i class="fa fa-edit"></i><span>@lang('ticRequestStates')</span></a>
</li>

<li class="{{ Request::is('ticMaintenances*') ? 'active' : '' }}">
    <a href="{{ route('ticMaintenances.index') }}"><i class="fa fa-edit"></i><span>@lang('ticMaintenances')</span></a>
</li>

<li class="{{ Request::is('ticKnowledgeBases*') ? 'active' : '' }}">
    <a href="{{ route('ticKnowledgeBases.index') }}"><i class="fa fa-edit"></i><span>@lang('ticKnowledgeBases')</span></a>
</li>

<li class="{{ Request::is('ticRequestStatuses*') ? 'active' : '' }}">
    <a href="{{ route('ticRequestStatuses.index') }}"><i class="fa fa-edit"></i><span>@lang('ticRequestStatuses')</span></a>
</li>

<li class="{{ Request::is('ticRequestHistories*') ? 'active' : '' }}">
    <a href="{{ route('ticRequestHistories.index') }}"><i class="fa fa-edit"></i><span>@lang('ticRequestHistories')</span></a>
</li>

<li class="{{ Request::is('ticSatisfactionPolls*') ? 'active' : '' }}">
    <a href="{{ route('ticSatisfactionPolls.index') }}"><i class="fa fa-edit"></i><span>@lang('ticSatisfactionPolls')</span></a>
</li>

<li class="{{ Request::is('ticProviders*') ? 'active' : '' }}">
    <a href="{{ route('ticProviders.index') }}"><i class="fa fa-edit"></i><span>@lang('ticProviders')</span></a>
</li>

<li class="{{ Request::is('ticPeriodValidities*') ? 'active' : '' }}">
    <a href="{{ route('ticPeriodValidities.index') }}"><i class="fa fa-edit"></i><span>@lang('ticPeriodValidities')</span></a>
</li>

<li class="{{ Request::is('ticPollQuestions*') ? 'active' : '' }}">
    <a href="{{ route('ticPollQuestions.index') }}"><i class="fa fa-edit"></i><span>@lang('ticPollQuestions')</span></a>
</li>


<li class="nav-item">
    <a href="{{ route('ticAssetDocuments.index') }}" class="nav-link {{ Request::is('ticAssetDocuments*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Tic Asset Documents</p>
    </a>
</li>
