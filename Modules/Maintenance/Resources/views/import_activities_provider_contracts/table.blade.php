<table class="table table-hover m-b-0" id="import-activities-provider-contracts-table">
    <thead>
        <tr>
            <th>Item</th>
            <th>@lang('Description')</th>
            <th>@lang('Type')</th>
            <th>Sistema</th>
            <th>@lang('Unit Measurement')</th>
            <th>@lang('Quantity')</th>
            <th>@lang('Unit value')</th>
            <th>Iva</th>
            <th>@lang('Total Value')</th>
            <th>@lang('crud.action')</th>
            
        </tr>
    </thead>
    <tbody>
        <tr v-for="(importActivitiesProviderContract, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ importActivitiesProviderContract.item }}</td>
            <td>@{{ importActivitiesProviderContract.description }}</td>
            <td>@{{ importActivitiesProviderContract.type }}</td>
            <td>@{{ importActivitiesProviderContract.system }}</td>
            <td>@{{ importActivitiesProviderContract.unit_measurement }}</td>
            <td>@{{ importActivitiesProviderContract.quantity }}</td>

            

            <td>$@{{ currencyFormat( importActivitiesProviderContract.unit_value) }}</td>
            <td>$@{{ currencyFormat(importActivitiesProviderContract.iva) }}</td>
            <td>$@{{ currencyFormat(importActivitiesProviderContract.total_value) }}</td>
            <td>
                <button @click="edit(importActivitiesProviderContract)" data-backdrop="static" data-target="#modal-form-import-activities-provider-contracts-edit" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                <button @click="show(importActivitiesProviderContract)" data-target="#modal-view-import-activities-provider-contracts" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                <button @click="drop(importActivitiesProviderContract[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
    </tbody>
</table>
