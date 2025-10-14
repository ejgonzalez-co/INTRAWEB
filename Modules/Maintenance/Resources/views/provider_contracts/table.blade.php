<table class="table table-hover m-b-0" id="provider-contracts-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('Proveedor')</th>
            <th>@lang('Objeto')</th>
            <th>@lang('Tipo de contrato')</th>
            <th>@lang('Número de contrato')</th>
            <th>@lang('Fecha de acta de inicio')</th>
            <th>Estado</th>
            <th>Valor contrato</th>
            <th>Valor disponible</th>
            <th>Porcentaje de ejecución</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(providerContract, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ providerContract . providers . name }}</td>
            <td>@{{ providerContract . object }}</td>
            <td>@{{ providerContract . type_contract }}</td>
            <td>@{{ providerContract . contract_number }}</td>
            <td>@{{ providerContract . start_date }}</td>
            <td>@{{ providerContract . condition }}</td>
            <td>$ @{{ currencyFormat(providerContract . total_value_contract) }}</td>
            <td>$ @{{ currencyFormat(providerContract . value_avaible) }}</td>
            <td v-if="providerContract.total_percentage<=70">
                <div style="background-color: green; color: white;">
                    @{{ currencyFormat(providerContract . total_percentage) }} %</div>
            </td>
            <td v-if="providerContract.total_percentage>70 && providerContract.total_percentage<86">
                <div style="background-color: yellow; color: black;">
                    @{{ currencyFormat(providerContract . total_percentage) }} %</div>
            </td>
            <td v-if="providerContract.total_percentage>=86">
                <div style="background-color: red; color: white;">
                    @{{ currencyFormat(providerContract . total_percentage) }} %
                </div>
            </td>
            <td>
                <div class="row">
                    <div v-if="providerContract.condition=='Activo'">
                        @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                        <button @click="edit(providerContract)" data-backdrop="static"
                            data-target="#modal-form-provider-contracts" data-toggle="modal"
                            class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                            title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                        @endif

                        <button @click="show(providerContract)" data-target="#modal-view-provider-contracts"
                            data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip"
                            data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                            @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                            <button @click="edit(providerContract)" data-target="#modal-delete-provider-contract"
                            data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip"
                            data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
                            @endif
                            @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                        <button @click="edit(providerContract)" data-target="#modal-new-condition" data-toggle="modal"
                            class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                            title="Cambiar de estado"><i class="fa fa-lock"></i></button>
                            @endif
                        @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                        <a :href="'{!! url('maintenance/budget-assignations') !!}?mpc=' + providerContract[customId]"
                            class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                            title="Asignación presupuestal"><i class="fas fa-comment-dollar"></i></a>
                        @endif
                        @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                        <a :href="'{!! url('maintenance/documents-provider-contracts') !!}?mpc=' + providerContract[customId]"
                            class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                            title="@lang('Documentos del contrato')"><i class="fas fa-folder-plus"></i></a>
                        @endif
                        @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                        <a :href="'{!! url('maintenance/import-parts-provider-cont') !!}?mpc=' + providerContract[customId]"
                            class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                            title="@lang('Importar repuestos')"><i class="fas fa-tools"></i></a>
                        @endif
                        @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                        <a :href="'{!! url('maintenance/import-acti-provider-cont') !!}?mpc=' + providerContract[customId]"
                            class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                            title="@lang('Importar actividades')"><i class="fas fa-file-import"></i></a>
                        @endif
                    </div>
                    <div v-else>
                        <button @click="show(providerContract)" data-target="#modal-view-provider-contracts"
                        data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip"
                        data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                        @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                        <button @click="edit(providerContract)" data-target="#modal-new-condition" data-toggle="modal"
                        class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                        title="Cambiar de estado"><i class="fa fa-lock"></i></button>
                        @endif
                    </div>
                </div>

            </td>
        </tr>
    </tbody>
</table>
