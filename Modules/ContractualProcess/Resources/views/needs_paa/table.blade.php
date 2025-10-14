<table class="table table-hover m-b-0" id="needs-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('Paa Call')</th>
            <th>@lang('Start Date')</th>
            <th>@lang('Closing Date')</th>
            <th>@lang('Call Status')</th>
            <th>@lang('Name Process')</th>
            <!-- <th>@lang('Total Operating Value')</th>
            <th>@lang('Total Investment Value')</th> -->
            <th>@lang('Total Value Paa')</th>
            <th>@lang('State')</th>
            <th>@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(needs, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ needs.paa_calls.name }}</td>
            <td>@{{ needs.paa_calls.start_date }}</td>
            <td>@{{ needs.paa_calls.closing_date }}</td>
            <td>
                <span :style="{ 'background-color': needs.paa_calls.state_colour, 'color': '#FFFFFF' }" class="p-5">
                    @{{ needs.paa_calls.state_name }}
                </span>
            </td>
            <td>@{{ needs.name_process }}</td>
            <!-- <td>@{{ needs.total_operating_value }}</td>
            <td>@{{ needs.total_investment_value }}</td> -->
            <td>@{{ '$ '+currencyFormat(needs.total_value_paa) }}</td>
            <td>
                <span :style="{ 'background-color': needs.state_colour, 'color': '#FFFFFF' }" class="p-5">
                    @{{ needs.state_name }}
                </span>
            </td>
            <td>
                @if(Auth::user()->hasRole('PC Gestor de recursos'))
                <!-- <button @click="edit(needs)" data-backdrop="static" data-target="#modal-form-needs" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                
                <button @click="drop(needs[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button> -->

                {{-- Abre la vista de las necesidades de funcionamiento --}}
                <a :href="'functioning-needs?need='+row.id">
                    <button class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Functioning Needs')"><i class="fas fa-hand-holding-usd"></i></button>
                </a>
                <a :href="'investment-technical-sheets?need='+needs.id">
                    <button class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Investment Needs')"><i class="fas fa-file-invoice-dollar"></i></button>
                </a>

                <button v-if="needs.state == 3" @click="callFunctionComponent('assess-needs-paa', 'loadData', needs);"  class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Approve')"><i class="fas fa-thumbs-up"></i></button>
                @endif

                @if(Auth::user()->hasRole('PC Líder de proceso'))
                <a :href="'pc-functioning-needs?need='+needs.id">
                    <button class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Functioning Needs')"><i class="fas fa-hand-holding-usd"></i></button>
                </a>

                <a v-if="'{!! Auth::user()->id !!}' == needs.process_leaders.users_id && (needs.state < 3 || needs.state == 4)" :href="'investment-technical-sheets?need='+needs.id">
                    <button class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Investment Needs')"><i class="fas fa-file-invoice-dollar"></i></button>
                </a>

                <button @click="callFunctionComponent('novelties-paa', 'loadData', needs);" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Novelties') PAA"><i class="fas fa-list-alt"></i></button>

                <execution-from-action
                    v-if="'{!! Auth::user()->id !!}' == needs.process_leaders.users_id && (needs.state < 3 || needs.state == 4)"
                    :value="needs"
                    route="send-review-needs"
                    field-update="state"
                    value-update="3"
                    css-class="fas fa-paper-plane"
                    title="@lang('Submit a review')"
                    >
                </execution-from-action>

                @endif

                <button @click="show(needs)" data-target="#modal-view-needs" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>

                <a v-if="needs.state == 5" :href="'export-approved-needs/'+needs.id">
                    <button class="btn btn-white btn-icon btn-md" title="Exportar necesidades aprobadas"><i class="fas fa-file-excel"></i></button>
                </a>
            </td>
        </tr>
    </tbody>
</table>
