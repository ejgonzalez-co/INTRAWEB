<table class="table table-hover m-b-0" id="technical-sheets-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('Users')</th>
            <th>@lang('Dependencias')</th>
            <th>@lang('Management Unit')</th>
            <th>@lang('City')</th>
            <th>@lang('Code Bppiepa')</th>
            <th>@lang('Validity')</th>
            <th>@lang('Date Presentation')</th>
            <th>@lang('Update Date')</th>
            <th>@lang('Project Name')</th>
            <th>@lang('Responsible User')</th>
            <th>@lang('Municipal Development Plan')</th>
            <th>@lang('Period')</th>
            <th>@lang('Strategic Line')</th>
            <th>@lang('Program')</th>
            <th>@lang('Subprogram')</th>
            <th>@lang('Sector')</th>
            <th>@lang('Project Line')</th>
            <th>@lang('Identification Project')</th>
            <th>@lang('Description Problem Need')</th>
            <th>@lang('Project Description')</th>
            <th>@lang('Justification')</th>
            <th>@lang('Background')</th>
            <th>@lang('Service Coverage')</th>
            <th>@lang('Number Inhabitants')</th>
            <th>@lang('Neighborhood')</th>
            <th>@lang('Commune')</th>
            <th>@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(technicalSheets, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ technicalSheets.users_id }}</td>
            <td>@{{ technicalSheets.dependencias_id }}</td>
            <td>@{{ technicalSheets.pc_management_unit_id }}</td>
            <td>@{{ technicalSheets.cities_id }}</td>
            <td>@{{ technicalSheets.code_bppiepa }}</td>
            <td>@{{ technicalSheets.validity }}</td>
            <td>@{{ technicalSheets.date_presentation }}</td>
            <td>@{{ technicalSheets.update_date }}</td>
            <td>@{{ technicalSheets.project_name }}</td>
            <td>@{{ technicalSheets.responsible_user }}</td>
            <td>@{{ technicalSheets.municipal_development_plan }}</td>
            <td>@{{ technicalSheets.period }}</td>
            <td>@{{ technicalSheets.strategic_line }}</td>
            <td>@{{ technicalSheets.program }}</td>
            <td>@{{ technicalSheets.subprogram }}</td>
            <td>@{{ technicalSheets.sector }}</td>
            <td>@{{ technicalSheets.project_line }}</td>
            <td>@{{ technicalSheets.identification_project }}</td>
            <td>@{{ technicalSheets.description_problem_need }}</td>
            <td>@{{ technicalSheets.project_description }}</td>
            <td>@{{ technicalSheets.justification }}</td>
            <td>@{{ technicalSheets.background }}</td>
            <td>@{{ technicalSheets.service_coverage }}</td>
            <td>@{{ technicalSheets.number_inhabitants }}</td>
            <td>@{{ technicalSheets.neighborhood }}</td>
            <td>@{{ technicalSheets.commune }}</td>
            <td>
                <button @click="edit(technicalSheets)" data-backdrop="static" data-target="#modal-form-technical-sheets" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                <button @click="show(technicalSheets)" data-target="#modal-view-technical-sheets" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                <button @click="drop(technicalSheets[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
    </tbody>
</table>
