<table class="table table-hover m-b-0" id="holidayCalendars-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('Date')</th>
            <th>@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(holidayCalendar, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ holidayCalendar.date }}</td>
            <td>
                <button @click="edit(holidayCalendar)" data-backdrop="static" data-target="#modal-form-holidayCalendars" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                <button @click="show(holidayCalendar)" data-target="#modal-view-holidayCalendars" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                <button @click="drop(holidayCalendar[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
    </tbody>
</table>
