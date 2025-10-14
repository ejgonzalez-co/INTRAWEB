<table class="table table-hover m-b-0" id="pc-previous-studies-history-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('Date')</th>
            <th>@lang('User')</th>
            <th>@lang('Organizational Unit')</th>
            <th>@lang('State')</th>
            <th>@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(pcPreviousStudiesHistory, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ pcPreviousStudiesHistory.created_at }}</td>
            <td>@{{ pcPreviousStudiesHistory.users_name }}</td>
            <td>@{{ pcPreviousStudiesHistory.organizational_unit }}</td>
            <td>
                <span :class="pcPreviousStudiesHistory.state_colour">
                    @{{ pcPreviousStudiesHistory.state_name }}
                </span>
            </td>
            <td>
                <button @click="show(pcPreviousStudiesHistory)" data-target="#modal-view-pc-previous-studies-history" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
            </td>
        </tr>
    </tbody>
</table>
