<table class="table table-hover m-b-0" id="pcPreviousStudiesRadications-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('Proceso')</th>
            <th>@lang('Tipo')</th>
            <th>@lang('Objecto')</th>
            <th>@lang('Líder')</th>
            <th>@lang('Valor')</th>
            <th>@lang('Fecha de envío')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(pcPreviousStudiesRadication, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ pcPreviousStudiesRadication.process }}</td>
            <td>@{{ pcPreviousStudiesRadication.pc_previous_studies.type }}</td>
            <td>@{{ pcPreviousStudiesRadication.object }}</td>
            <td>@{{ pcPreviousStudiesRadication.boss }}</td>
            <td>@{{ pcPreviousStudiesRadication.value }}</td>
            <td>@{{ pcPreviousStudiesRadication.date_send }}</td>
          
        </tr>
    </tbody>
</table>
