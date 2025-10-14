<div class="table-responsive">
    <table-component
        id="equipmentMinorFuelConsumptions-table"
        :data="dataList"
        sort-by="equipmentMinorFuelConsumptions"
        sort-order="asc"
        table-class="table table-hover m-b-0"
        :show-filter="false"
        :pagination="dataPaginator"
        :show-caption="false"
        filter-placeholder="@lang('Quick filter')"
        filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4"
        :cache-lifetime="0"
        >
        <table-column show="created_at" label="Fecha de registro"></table-column>
            <table-column show="supply_date" label="@lang('Supply Date')"></table-column>
            <table-column show="updated_at" label="Fecha de modificación"></table-column>
            <table-column show="mant_resume_equipment_machinery.name_equipment" label="@lang('Equipment Description')">
                <template slot-scope="row">
                    <p>@{{ row.mant_resume_equipment_machinery ? row.mant_resume_equipment_machinery.name_equipment + ' - ' + row.mant_resume_equipment_machinery.no_inventory : '' }}</p>
                </template>
            </table-column>
            <table-column show="dependencias.nombre" label="@lang('Process')"></table-column>
           
            <table-column show="gallons_supplied" label="@lang('Gallons Supplied')">
                <template slot-scope="row" :sortable="false" :filterable="false">
                  @{{ formatNumber(row.gallons_supplied,4)}} gal
                </template>
            </table-column>
           
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">
              
                @if (Auth::user()->hasRole('Operario combustible') )
                    <div v-if="dataForm.sentinel==false">
                        <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-equipmentMinorFuelConsumptions" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                    </div>
                    <div v-if="dataForm.sentinel==false">
                        <a :href="'documents-minor-equipments?equipmentConsume='+row.id" class="btn btn-white btn-icon btn-md"
                        data-placement="top" title="Agregar documentos">
                        <i class="fas fa-folder-plus"></i>
                        </a>
                    </div>
                    <div v-if="dataForm.sentinel==false">
                        <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                            <i class="fa fa-trash"></i></button>
                    </div>
                @endif
                @if (Auth::user()->hasRole('Administrador de mantenimientos') )
                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-equipmentMinorFuelConsumptions" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>
                @endif
                <button @click="show(row)" data-target="#modal-view-equipmentMinorFuelConsumptions" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                @if (Auth::user()->hasRole('Administrador de mantenimientos') )
                <a :href="'documents-minor-equipments?equipmentConsume='+row.id" class="btn btn-white btn-icon btn-md"
                data-placement="top" title="Agregar documentos">
                <i class="fas fa-folder-plus"></i>
                </a>
                @endif

                @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                <button @click="edit(row)" data-target="#modal-delete-provider-contract" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip"
                data-placement="top" title="@lang('drop')">
                <i class="fa fa-trash"></i>
                </button>
                @endif

            </template>
        </table-column>
    </table-component>
</div>
