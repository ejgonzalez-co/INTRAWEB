<table class="table table-hover m-b-0" id="pc-investment-needs-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('Description')</th>
            <th>@lang('Estimated Value')</th>
            <th>@lang('Observation')</th>
            <th>@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(pcInvestmentNeeds, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ pcInvestmentNeeds.description }}</td>
            <td>@{{ '$ '+currencyFormat(pcInvestmentNeeds.estimated_value) }}</td>
            <td>@{{ pcInvestmentNeeds.observation }}</td>
            <td>
                <button @click="edit(pcInvestmentNeeds)" data-backdrop="static" data-target="#modal-form-pc-investment-needs" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                <button @click="show(pcInvestmentNeeds)" data-target="#modal-view-pc-investment-needs" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                <button @click="drop(pcInvestmentNeeds[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
    </tbody>
</table>
