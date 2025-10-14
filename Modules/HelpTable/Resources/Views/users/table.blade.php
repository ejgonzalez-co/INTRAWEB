<div class="table-responsive">
    <table class="table table-hover m-b-0" id="users-table">
        <thead>
            <tr>
                <th>#</th>
                <th>@lang('Name')</th>
                <th>@lang('Username')</th>
                <th>@lang('Email')</th>
                <th>@{{ `@lang('position')` | capitalize }}</th>
                <th>@{{ `@lang('dependency')` | capitalize }}</th>
                <th>@lang('Block Account')</th>
                <th>@lang('Account Verified')</th>
                <th>@lang('Sendemail')</th>
                <th>@lang('Created_at')</th>
                <th>@lang('crud.action')</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(user, key) in advancedSearchFilterPaginate()" :key="key">
                <td>@{{ getIndexItem(key) }}</td>
                <td>@{{ user.name }}</td>
                <td>@{{ user.username }}</td>
                <td>@{{ user.email }}</td>
                <td>@{{ (user.positions)? user.positions.nombre : '' }}</td>
                <td>@{{ (user.dependencies)? user.dependencies.nombre : '' }}</td>
                <td>@{{ (user.block)? '@lang('yes')' : '@lang('no')' }}</td>
                <td>@{{ user.email_verified_at? '@lang('yes')' : '@lang('no')' }}</td>
                <td>@{{ (user.sendEmail)? '@lang('yes')' : '@lang('no')' }}</td>
                <td>@{{ user.created_at }}</td>
                <td>
                    
                    <button @click="callFunctionComponent('add-user', 'loadAddUser', user);"class="btn btn-white btn-icon btn-md" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
              
                    <button @click="show(user)" data-target="#modal-view-users" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                    <button @click="drop(user[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
                </td>

            </tr>
        </tbody>
    </table>
</div>
