<table class="table table-hover m-b-0" id="providers-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('Type Person')</th>
            <th>@lang('Document Type')</th>
            <th>@lang('Identification')</th>
            <th>@lang('Name')</th>
            <th>@lang('Mail')</th>
            <th>@lang('State')</th>
            <th>@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(providers, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ providers.type_person }}</td>
            <td>@{{ providers.document_type }}</td>
            <td>@{{ providers.identification }}</td>
            <td>@{{ providers.name }}</td>
            <td>@{{ providers.mail }}</td>
            <td>@{{ providers.state }}</td>
            <td>
                @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                <button @click="edit(providers)" data-backdrop="static" data-target="#modal-form-providers" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                @endif
                <button @click="show(providers)" data-target="#modal-view-providers" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                <button @click="drop(providers[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
                @endif
                <a :href="'{!! url('maintenance/supports-providers') !!}?mp=' + providers[customId]" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Documents')"><i class="fas fa-folder-plus"></i></a>
            </td>
        </tr>
    </tbody>
</table>
