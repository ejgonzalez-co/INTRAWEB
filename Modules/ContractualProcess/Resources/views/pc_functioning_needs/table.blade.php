<table class="table table-hover m-b-0" id="pc-functioning-needs-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('Description')</th>
            <!-- <th>@lang('Estimated Start Date')</th> -->
            <th>@lang('Estimated Total Value')</th>
            <!-- <th>@lang('Estimated Value Current Validity')</th> -->
            <!-- <th>@lang('Total Value')</th> -->
            <!-- <th>@lang('Future Validity Status')</th> -->
            <th>@lang('Observation')</th>
            <th>@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(pcFunctioningNeeds, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ pcFunctioningNeeds.description }}</td>
            <!-- <td>@{{ pcFunctioningNeeds.estimated_start_date }}</td> -->
            <td>@{{ '$ '+currencyFormat(pcFunctioningNeeds.estimated_total_value) }}</td>
            <!-- <td>@{{ pcFunctioningNeeds.estimated_value_current_validity }}</td> -->
            <!-- <td>@{{ pcFunctioningNeeds.total_value }}</td> -->
            <!-- <td>@{{ pcFunctioningNeeds.future_validity_status }}</td> -->
            <td>@{{ pcFunctioningNeeds.observation }}</td>
            <td>
                <button v-if="pcFunctioningNeeds.needs.state == 1 || pcFunctioningNeeds.needs.state == 4" @click="edit(pcFunctioningNeeds)" data-backdrop="static" data-target="#modal-form-pc-functioning-needs" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                
                <button v-if="pcFunctioningNeeds.needs.state == 1 || pcFunctioningNeeds.needs.state == 4" @click="drop(pcFunctioningNeeds[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>

                <button @click="show(pcFunctioningNeeds)" data-target="#modal-view-pc-functioning-needs" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
            </td>
        </tr>
    </tbody>
</table>
