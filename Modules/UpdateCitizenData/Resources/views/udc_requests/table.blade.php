<table class="table table-hover m-b-0" id="udcRequests-table">
    <thead>
        <tr>
            <th>@lang('ID')</th>
            <th>@lang('Fecha')</th>
            <th>@lang('Payment Account Number')</th>
            <th>@lang('Subscriber Quality')</th>
            <th>@lang('Citizen Name')</th>
            <th>@lang('Document Type')</th>
            <th>@lang('Identification')</th>
            <th>@lang('Contact')</th>
            <th>@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(udcRequest, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ udcRequest.id }}</td>
            <td>@{{ udcRequest.updated_at }}</td>
            <td>@{{ udcRequest.payment_account_number }}</td>
            <td>@{{ udcRequest.subscriber_quality }}</td>
            <td>@{{ udcRequest.citizen_name }}</td>
            <td>@{{ udcRequest.document_type }}</td>
            <td>@{{ udcRequest.identification }}</td>
            <td>@{{ udcRequest.telephone }} - @{{ udcRequest.email }}</td>
            <td>
                <button @click="edit(udcRequest)" data-backdrop="static" data-target="#modal-form-udc-requests" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                <button @click="show(udcRequest)" data-target="#modal-view-udc-requests" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                <button @click="drop(udcRequest[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
    </tbody>
</table>
