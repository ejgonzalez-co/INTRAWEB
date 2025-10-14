<div class="table-responsive">
    <table class="table table-hover m-b-0" id="tic-requests-table">
        <thead>
            <tr>
                <th>Número de solicitud</th>
                <th>@lang('Created_at')</th>
                <th>@lang('Dependency')</th>
                <th>@lang('User')</th>
                <th>@lang('Priority Request')</th>
                <th>@lang('Affair')</th>
                <th>@lang('Allotted time')</th>
                <th>@lang('Usuario asignado')</th>
                <th>@lang('Expiration Date')</th>
                <th>@lang('Date Attention')</th>
                <th>@lang('State')</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody :key="keyRefresh">
            <tr v-for="(ticRequests, key) in advancedSearchFilterPaginate()" :key="key">
                <td>@{{ ticRequests.id }}</td>
                <td>@{{ ticRequests.created_at }}</td>
                <td>@{{ ticRequests.users?  ticRequests.users.dependencies.nombre: '' }}</td>
                <td>@{{ ticRequests.users? ticRequests.users.name: '' }}</td>
                <td>@{{ ticRequests.priority_request? ticRequests.priority_request_name: '' }}</td>
                <td>@{{ ticRequests.affair }}</td>
                <td>@{{ ticRequests.tic_type_request? ticRequests.tic_type_request.term + ' ' + ticRequests.tic_type_request.unit_time_name: '' }}</td>
                <td>@{{ ticRequests.assigned_user? ticRequests.assigned_user.name: ''}}</td>
                <td>@{{ ticRequests.expiration_date }}</td>
                <td>@{{ ticRequests.date_attention }}</td>
                <td>
                    <div class="text-white text-center p-2" :style="'background-color:'+ticRequests.status_color" v-html=" ticRequests.status_name"></div>
                </td>
                <td>
                    <button @click="show(ticRequests)" data-target="#modal-view-tic-requests" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
