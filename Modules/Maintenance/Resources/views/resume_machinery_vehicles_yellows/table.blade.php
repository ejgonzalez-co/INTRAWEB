
<div class="table-responsive"  style="overflow-y: clip; !important">
    <table class="table table-hover m-b-0" id="resume-machinery-vehicles-yellows-table">
        <thead>
            <tr>
                <th>#</th>
                <th>@lang('Fecha de creación')</th>
                <th>@lang('Mant_asset_type')</th>
                <th>@lang('Nombre del activo')</th>
                <th>@lang('Dependencia')</th>
                <th>@lang('Nº de inventario')</th>
                <th>@lang('Mark')</th>
                <th>@lang('Model')</th>
                <th>@lang('Placa de vehículo')</th>
                <th>@lang('Estado')</th>
                <th>Tipo de categoría</th>
                <th>@lang('crud.action')</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(resumeMachineryVehiclesYellow, key) in dataList" :key="key">
                <td>@{{ getIndexItem(key) }}</td>
                <td>@{{ resumeMachineryVehiclesYellow.created_at }}</td>
                <td>@{{ resumeMachineryVehiclesYellow.asset_type?.name }}</td>
                <td v-if="resumeMachineryVehiclesYellow.mant_category?.mant_asset_type.form_type == 1">@{{ resumeMachineryVehiclesYellow.name_vehicle_machinery }}</td>
                <td v-if="resumeMachineryVehiclesYellow.mant_category?.mant_asset_type.form_type == 2">@{{ resumeMachineryVehiclesYellow.name_equipment }}</td>
                <td v-if="resumeMachineryVehiclesYellow.mant_category?.mant_asset_type.form_type == 3">@{{ resumeMachineryVehiclesYellow.name_equipment_machinery }}</td>
                <td v-if="resumeMachineryVehiclesYellow.mant_category?.mant_asset_type.form_type == 4">@{{ resumeMachineryVehiclesYellow.name_equipment }}</td>
                <td v-if="resumeMachineryVehiclesYellow.mant_category?.mant_asset_type.form_type == 5">@{{ resumeMachineryVehiclesYellow.description_equipment_name }}</td>
                <td>@{{ resumeMachineryVehiclesYellow.dependencies ? resumeMachineryVehiclesYellow.dependencies.nombre : 'No aplica' }}</td>
                <td>@{{ resumeMachineryVehiclesYellow.no_inventory ? resumeMachineryVehiclesYellow.no_inventory : (resumeMachineryVehiclesYellow.inventory_no ? resumeMachineryVehiclesYellow.inventory_no : (resumeMachineryVehiclesYellow.no_inventory_epa_esp ? resumeMachineryVehiclesYellow.no_inventory_epa_esp : "")) }}</td>
                <td>@{{ resumeMachineryVehiclesYellow.mark ?? 'No aplica' }}</td>
                <td>@{{ resumeMachineryVehiclesYellow.model }}</td>
                <td>@{{ resumeMachineryVehiclesYellow.plaque ? resumeMachineryVehiclesYellow.plaque : 'No aplica' }}</td>
                <td>@{{ resumeMachineryVehiclesYellow.status ? resumeMachineryVehiclesYellow.status : 'No aplica' }}</td>
                {{-- <td>@{{ resumeMachineryVehiclesYellow.mant_documents_asset.length }}</td> --}}
                <td>@{{ resumeMachineryVehiclesYellow.mant_category?.name }}</td>
                <td>
                    @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                    <button data-backdrop="static" @click="callFunctionComponent('loadAssets', 'loadAssets', resumeMachineryVehiclesYellow); callFunctionComponent('loadAssets', 'formId', resumeMachineryVehiclesYellow.id);" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                    <a v-if="resumeMachineryVehiclesYellow.asset_type.form_type == '1'" class="btn btn-white btn-icon btn-md" :href="'tire-informations?machinery='+resumeMachineryVehiclesYellow.id" title="Agregar desgaste de llanta"><i class="fas fa-cog"></i></a>
                    @endif

                    <button @click="show(resumeMachineryVehiclesYellow)" data-target="#modal-view-resume-machinery-vehicles-yellows" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                    
                    @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                    <button @click="drop(resumeMachineryVehiclesYellow[customId]+'_'+resumeMachineryVehiclesYellow.mant_category?.mant_asset_type.form_type)" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
                    @endif
                    <a :href="'{!! url('maintenance/documents-assets') !!}?ma=' + resumeMachineryVehiclesYellow[customId] + '&mft=' + resumeMachineryVehiclesYellow.mant_category?.mant_asset_type.form_type" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Documents')"><i class="fas fa-folder-plus"></i></a>

                    @if (Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Líder de proceso'))
                    <button v-if="resumeMachineryVehiclesYellow.mant_asset_type_id == 10" @click="callFunctionComponent('send-petition','exportFormatoNecesidadesGoogle',resumeMachineryVehiclesYellow.id)" class="btn btn-white btn-icon btn-md" title="Formato de hoja de vida de equipos menores">
                        <i class="fa fa-file-excel"></i>
                   </button>
                    @endif

                    @if (Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Líder de proceso'))
                    <button v-if="resumeMachineryVehiclesYellow.mant_asset_type_id == 11 || resumeMachineryVehiclesYellow.mant_asset_type_id == 8" @click="callFunctionComponent('send-petition-machinery','exportFormatoNecesidadesGoogle',resumeMachineryVehiclesYellow.id)" class="btn btn-white btn-icon btn-md" title="Formato de hoja de vida de equipos menores">
                        <i class="fa fa-file-excel"></i>
                   </button>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
</div>
