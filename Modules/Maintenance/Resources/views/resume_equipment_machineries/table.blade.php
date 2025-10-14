<table class="table table-hover m-b-0" id="resumeEquipmentMachineries-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('Name Equipment')</th>
        <th>@lang('No Identification')</th>
        <th>@lang('No Inventory')</th>
        <th>@lang('Mark')</th>
        <th>@lang('Model')</th>
        <th>@lang('No Motor')</th>
        <th>@lang('Serie')</th>
        <th>@lang('Ubication')</th>
        <th>@lang('Acquisition Contract')</th>
        <th>@lang('No Invoice')</th>
        <th>@lang('Purchase Price')</th>
        <th>@lang('Equipment Warranty')</th>
        <th>@lang('Equipment Operation')</th>
        <th>@lang('No Batteries')</th>
        <th>@lang('Batteries Reference')</th>
        <th>@lang('No Tires')</th>
        <th>@lang('Tire Reference')</th>
        <th>@lang('Fuel Type')</th>
        <th>@lang('Fuel Capacity')</th>
        <th>@lang('Use Oil')</th>
        <th>@lang('Oil Capacity')</th>
        <th>@lang('Requires Calibration')</th>
        <th>@lang('Periodicity Calibration')</th>
        <th>@lang('Oil Change')</th>
        <th>@lang('Periodicity Oil Change')</th>
        <th>@lang('Fluid Change')</th>
        <th>@lang('Periodicity Fluid Change')</th>
        <th>@lang('Preventive Maintenance')</th>
        <th>@lang('Periodicity Preventive Maintenance')</th>
        <th>@lang('Electrical Checks')</th>
        <th>@lang('Periodicity Electrical Checks')</th>
        <th>@lang('Mechanical Checks')</th>
        <th>@lang('Periodicity Mechanical Checks')</th>
        <th>@lang('General Cleaning')</th>
        <th>@lang('Periodicity General Cleaning')</th>
        <th>@lang('License Expiration')</th>
        <th>@lang('Periodicity License Expiration')</th>
        <th>@lang('Warranty Expiration')</th>
        <th>@lang('Periodicity Warranty Expiration')</th>
        <th>@lang('Insured Asset')</th>
        <th>@lang('Periodicity Insured Asset')</th>
        <th>@lang('Rated Current')</th>
        <th>@lang('Periodicity Rated Current')</th>
        <th>@lang('Rated Power')</th>
        <th>@lang('Periodicity Reated Power')</th>
        <th>@lang('Maximum Voltage')</th>
        <th>@lang('Minimun Voltage')</th>
        <th>@lang('Revolutions')</th>
        <th>@lang('Useful Life')</th>
        <th>@lang('Transportation Precaution')</th>
        <th>@lang('Capacity Force Hp')</th>
        <th>@lang('Protection Type')</th>
        <th>@lang('Minimum Permissible Error')</th>
        <th>@lang('Maximun Permissible Error')</th>
        <th>@lang('Calibration Point')</th>
        <th>@lang('Certified Calibration')</th>
        <th>@lang('No Entry Warehouse')</th>
        <th>@lang('No Exit Warehouse')</th>
        <th>@lang('Warehouse Entry Date')</th>
        <th>@lang('Warehouse Exit Date')</th>
        <th>@lang('Service Start Date')</th>
        <th>@lang('Retirement Date')</th>
        <th>@lang('Frecuency Use Month')</th>
        <th>@lang('Frecuency Use Hours')</th>
        <th>@lang('Operates Equipment')</th>
        <th>@lang('Observation')</th>
        <th>@lang('Status')</th>
        <th>@lang('Dependencias Id')</th>
        <th>@lang('Mant Category Id')</th>
        <th>@lang('Responsable')</th>
        <th>@lang('Mant Providers Id')</th>
            <th>@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(resumeEquipmentMachinery, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ resumeEquipmentMachinery.name_equipment }}</td>
            <td>@{{ resumeEquipmentMachinery.no_identification }}</td>
            <td>@{{ resumeEquipmentMachinery.no_inventory }}</td>
            <td>@{{ resumeEquipmentMachinery.mark }}</td>
            <td>@{{ resumeEquipmentMachinery.model }}</td>
            <td>@{{ resumeEquipmentMachinery.no_motor }}</td>
            <td>@{{ resumeEquipmentMachinery.serie }}</td>
            <td>@{{ resumeEquipmentMachinery.ubication }}</td>
            <td>@{{ resumeEquipmentMachinery.acquisition_contract }}</td>
            <td>@{{ resumeEquipmentMachinery.no_invoice }}</td>
            <td>@{{ resumeEquipmentMachinery.purchase_price }}</td>
            <td>@{{ resumeEquipmentMachinery.equipment_warranty }}</td>
            <td>@{{ resumeEquipmentMachinery.equipment_operation }}</td>
            <td>@{{ resumeEquipmentMachinery.no_batteries }}</td>
            <td>@{{ resumeEquipmentMachinery.batteries_reference }}</td>
            <td>@{{ resumeEquipmentMachinery.no_tires }}</td>
            <td>@{{ resumeEquipmentMachinery.tire_reference }}</td>
            <td>@{{ resumeEquipmentMachinery.fuel_type }}</td>
            <td>@{{ resumeEquipmentMachinery.fuel_capacity }}</td>
            <td>@{{ resumeEquipmentMachinery.use_oil }}</td>
            <td>@{{ resumeEquipmentMachinery.oil_capacity }}</td>
            <td>@{{ resumeEquipmentMachinery.requires_calibration }}</td>
            <td>@{{ resumeEquipmentMachinery.periodicity_calibration }}</td>
            <td>@{{ resumeEquipmentMachinery.oil_change }}</td>
            <td>@{{ resumeEquipmentMachinery.periodicity_oil_change }}</td>
            <td>@{{ resumeEquipmentMachinery.fluid_change }}</td>
            <td>@{{ resumeEquipmentMachinery.periodicity_fluid_change }}</td>
            <td>@{{ resumeEquipmentMachinery.preventive_maintenance }}</td>
            <td>@{{ resumeEquipmentMachinery.periodicity_preventive_maintenance }}</td>
            <td>@{{ resumeEquipmentMachinery.electrical_checks }}</td>
            <td>@{{ resumeEquipmentMachinery.periodicity_electrical_checks }}</td>
            <td>@{{ resumeEquipmentMachinery.mechanical_checks }}</td>
            <td>@{{ resumeEquipmentMachinery.periodicity_mechanical_checks }}</td>
            <td>@{{ resumeEquipmentMachinery.general_cleaning }}</td>
            <td>@{{ resumeEquipmentMachinery.periodicity_general_cleaning }}</td>
            <td>@{{ resumeEquipmentMachinery.license_expiration }}</td>
            <td>@{{ resumeEquipmentMachinery.periodicity_license_expiration }}</td>
            <td>@{{ resumeEquipmentMachinery.warranty_expiration }}</td>
            <td>@{{ resumeEquipmentMachinery.periodicity_warranty_expiration }}</td>
            <td>@{{ resumeEquipmentMachinery.insured_asset }}</td>
            <td>@{{ resumeEquipmentMachinery.periodicity_insured_asset }}</td>
            <td>@{{ resumeEquipmentMachinery.rated_current }}</td>
            <td>@{{ resumeEquipmentMachinery.periodicity_rated_current }}</td>
            <td>@{{ resumeEquipmentMachinery.rated_power }}</td>
            <td>@{{ resumeEquipmentMachinery.periodicity_reated_power }}</td>
            <td>@{{ resumeEquipmentMachinery.maximum_voltage }}</td>
            <td>@{{ resumeEquipmentMachinery.minimun_voltage }}</td>
            <td>@{{ resumeEquipmentMachinery.revolutions }}</td>
            <td>@{{ resumeEquipmentMachinery.useful_life }}</td>
            <td>@{{ resumeEquipmentMachinery.transportation_precaution }}</td>
            <td>@{{ resumeEquipmentMachinery.capacity_force_hp }}</td>
            <td>@{{ resumeEquipmentMachinery.protection_type }}</td>
            <td>@{{ resumeEquipmentMachinery.minimum_permissible_error }}</td>
            <td>@{{ resumeEquipmentMachinery.maximun_permissible_error }}</td>
            <td>@{{ resumeEquipmentMachinery.calibration_point }}</td>
            <td>@{{ resumeEquipmentMachinery.certified_calibration }}</td>
            <td>@{{ resumeEquipmentMachinery.no_entry_warehouse }}</td>
            <td>@{{ resumeEquipmentMachinery.no_exit_warehouse }}</td>
            <td>@{{ resumeEquipmentMachinery.warehouse_entry_date }}</td>
            <td>@{{ resumeEquipmentMachinery.warehouse_exit_date }}</td>
            <td>@{{ resumeEquipmentMachinery.service_start_date }}</td>
            <td>@{{ resumeEquipmentMachinery.retirement_date }}</td>
            <td>@{{ resumeEquipmentMachinery.frecuency_use_month }}</td>
            <td>@{{ resumeEquipmentMachinery.frecuency_use_hours }}</td>
            <td>@{{ resumeEquipmentMachinery.operates_equipment }}</td>
            <td>@{{ resumeEquipmentMachinery.observation }}</td>
            <td>@{{ resumeEquipmentMachinery.status }}</td>
            <td>@{{ resumeEquipmentMachinery.dependencias_id }}</td>
            <td>@{{ resumeEquipmentMachinery.mant_category_id }}</td>
            <td>@{{ resumeEquipmentMachinery.responsable }}</td>
            <td>@{{ resumeEquipmentMachinery.mant_providers_id }}</td>
            <td>
                <button @click="edit(resumeEquipmentMachinery)" data-backdrop="static" data-target="#modal-form-resumeEquipmentMachineries" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                <button @click="show(resumeEquipmentMachinery)" data-target="#modal-view-resumeEquipmentMachineries" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                <button @click="drop(resumeEquipmentMachinery[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
    </tbody>
</table>
