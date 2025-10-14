<table class="table table-hover m-b-0" id="resumeInventoryLecas-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('No Inventory Epa Esp')</th>
        <th>@lang('Leca Code')</th>
        <th>@lang('Description Equipment Name')</th>
        <th>@lang('Maker')</th>
        <th>@lang('Serial Number')</th>
        <th>@lang('Model')</th>
        <th>@lang('Location')</th>
        <th>@lang('Measured Used')</th>
        <th>@lang('Unit Measurement')</th>
        <th>@lang('Resolution')</th>
        <th>@lang('Manufacturer Error')</th>
        <th>@lang('Operation Range')</th>
        <th>@lang('Range Use')</th>
        <th>@lang('Operating Conditions Temperature')</th>
        <th>@lang('Condition Oper Elative Humidity Hr')</th>
        <th>@lang('Condition Oper Voltage Range')</th>
        <th>@lang('Maintenance Metrological Operation Frequency')</th>
        <th>@lang('Calibration Metrological Operating Frequency')</th>
        <th>@lang('Qualification Metrological Operating Frequency')</th>
        <th>@lang('Intermediate Verification Metrological Operating Frequency')</th>
        <th>@lang('Total Interventions')</th>
        <th>@lang('Name Elaborated')</th>
        <th>@lang('Cargo Role Elaborated')</th>
        <th>@lang('Name Updated')</th>
        <th>@lang('Cargo Role Updated')</th>
        <th>@lang('Technical Director')</th>
        <th>@lang('Mant Category Id')</th>
        <th>@lang('Responsable')</th>
            <th>@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(resumeInventoryLeca, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ resumeInventoryLeca.no_inventory_epa_esp }}</td>
            <td>@{{ resumeInventoryLeca.leca_code }}</td>
            <td>@{{ resumeInventoryLeca.description_equipment_name }}</td>
            <td>@{{ resumeInventoryLeca.maker }}</td>
            <td>@{{ resumeInventoryLeca.serial_number }}</td>
            <td>@{{ resumeInventoryLeca.model }}</td>
            <td>@{{ resumeInventoryLeca.location }}</td>
            <td>@{{ resumeInventoryLeca.measured_used }}</td>
            <td>@{{ resumeInventoryLeca.unit_measurement }}</td>
            <td>@{{ resumeInventoryLeca.resolution }}</td>
            <td>@{{ resumeInventoryLeca.manufacturer_error }}</td>
            <td>@{{ resumeInventoryLeca.operation_range }}</td>
            <td>@{{ resumeInventoryLeca.range_use }}</td>
            <td>@{{ resumeInventoryLeca.operating_conditions_temperature }}</td>
            <td>@{{ resumeInventoryLeca.condition_oper_elative_humidity_hr }}</td>
            <td>@{{ resumeInventoryLeca.condition_oper_voltage_range }}</td>
            <td>@{{ resumeInventoryLeca.maintenance_metrological_operation_frequency }}</td>
            <td>@{{ resumeInventoryLeca.calibration_metrological_operating_frequency }}</td>
            <td>@{{ resumeInventoryLeca.qualification_metrological_operating_frequency }}</td>
            <td>@{{ resumeInventoryLeca.intermediate_verification_metrological_operating_frequency }}</td>
            <td>@{{ resumeInventoryLeca.total_interventions }}</td>
            <td>@{{ resumeInventoryLeca.name_elaborated }}</td>
            <td>@{{ resumeInventoryLeca.cargo_role_elaborated }}</td>
            <td>@{{ resumeInventoryLeca.name_updated }}</td>
            <td>@{{ resumeInventoryLeca.cargo_role_updated }}</td>
            <td>@{{ resumeInventoryLeca.technical_director }}</td>
            <td>@{{ resumeInventoryLeca.mant_category_id }}</td>
            <td>@{{ resumeInventoryLeca.responsable }}</td>
            <td>
                <button @click="edit(resumeInventoryLeca)" data-backdrop="static" data-target="#modal-form-resumeInventoryLecas" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                <button @click="show(resumeInventoryLeca)" data-target="#modal-view-resumeInventoryLecas" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                <button @click="drop(resumeInventoryLeca[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
    </tbody>
</table>
