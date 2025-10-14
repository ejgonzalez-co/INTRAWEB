<table class="table table-hover m-b-0" id="polls-table">
    <thead>
        <tr>
            
        <th>@lang('ID')</th>
        <th>@lang('Número de cuenta')</th>
        <th>@lang('Ciudadano')</th>
        <th>@lang('Género')</th>
        <th>@lang('Calidad de suscriptor')</th>
        <th>@lang('Fecha de creación')</th>
            <th>@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(polls, key) in advancedSearchFilterPaginate()" :key="key">
            
            <td>@{{ polls.id }}</td>
            <td>@{{ polls.number_account }}</td>
            <td>@{{ polls.name }}</td>
            <td>@{{ polls.gender }}</td>
            <td>@{{ polls.suscriber_quality }}</td>
            <td>@{{ polls.created_at }}</td>
            <td>
                <button @click="show(polls)" data-target="#modal-view-polls" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>             
            </td>
        </tr>
    </tbody>
</table>
