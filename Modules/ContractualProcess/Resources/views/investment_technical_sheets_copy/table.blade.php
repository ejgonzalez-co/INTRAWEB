<table class="table table-hover m-b-0" id="investment-technical-sheets-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('Code Bppiepa')</th>
            <th>@lang('Validity')</th>
            <th>@lang('Date Presentation')</th>
            <th>@lang('Name Project')</th>
            <th>@lang('Submanagement or Direction')</th>
            <th>@lang('Management Unit')</th>
            {{-- <th>@lang('State')</th> --}}
            <th>@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(investmentTechnicalSheets, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ investmentTechnicalSheets.code_bppiepa }}</td>
            <td>@{{ investmentTechnicalSheets.validities.name }}</td>
            <td>@{{ investmentTechnicalSheets.date_presentation }}</td>
            <td>@{{ investmentTechnicalSheets.name_projects.name }}</td>
            <td>@{{ investmentTechnicalSheets.dependencies.nombre }}</td>
            <td>@{{ investmentTechnicalSheets.management_unit.name }}</td>
            {{-- <td>@{{ investmentTechnicalSheets.state }}</td> --}}
            <td>
                @if(!Auth::user()->hasRole('PC Gestor de recursos'))
                <button @click="edit(investmentTechnicalSheets)" data-backdrop="static" data-target="#modal-form-investment-technical-sheets" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>

                <button @click="edit(investmentTechnicalSheets)" data-backdrop="static" data-target="#goals-indicators" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Parte 2: @lang('Goals and indicators')"><i class="fas fa-bullseye"></i></button>

                <button @click="edit(investmentTechnicalSheets)" data-backdrop="static" data-target="#information-tariff-harmonization" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Parte 3: @lang('Tariff harmonization information')"><i class="fas fa-donate"></i></button>

                <button @click="edit(investmentTechnicalSheets)" data-backdrop="static" data-target="#environmental-impacts" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Parte 4: @lang('Environmental impacts')"><i class="fab fa-pagelines"></i></button>


                <button @click="callFunctionComponent('alternative-investment', 'loadInvestment', investmentTechnicalSheets);" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"  title="Parte 5: @lang('Alternative budget')"><i class="fas fa-file-invoice-dollar"></i></button>

                <button @click="edit(investmentTechnicalSheets)" data-backdrop="static" data-target="#chronograms" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Parte 6: @lang('Timelines')"><i class="far fa-calendar-alt"></i></button>

                <button @click="drop(investmentTechnicalSheets[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
                @endif

                <button @click="show(investmentTechnicalSheets)" data-target="#modal-view-investment-technical-sheets" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>

                

            </td>
        </tr>
    </tbody>
</table>
