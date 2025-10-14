<table class="table table-hover m-b-0" id="resumeEquipmentMachineryLecas-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('Name Equipment Machinery')</th>
        <th>@lang('No Identification')</th>
        <th>@lang('No Inventory Epa Esp')</th>
        <th>@lang('Mark')</th>
        <th>@lang('Model')</th>
        <th>@lang('Serie')</th>
        <th>@lang('Location')</th>
        <th>@lang('Path Information')</th>
        <th>@lang('Acquisition Contract')</th>
        <th>@lang('Provider Data')</th>
        <th>@lang('Apply')</th>
        <th>@lang('Location Specification')</th>
        <th>@lang('Language')</th>
        <th>@lang('Version')</th>
        <th>@lang('Purchase Date')</th>
        <th>@lang('Commissioning Date')</th>
        <th>@lang('Date Withdrawal Service')</th>
        <th>@lang('Observations')</th>
        <th>@lang('Vo Bo Name')</th>
        <th>@lang('Vo Bo Cargo')</th>
        <th>@lang('Magnitude')</th>
        <th>@lang('Unit Measurement')</th>
        <th>@lang('Scale Division')</th>
        <th>@lang('Manufacturer Specification Max Permissible Error')</th>
        <th>@lang('Max Permissible Error Technical Standard Process')</th>
        <th>@lang('Measurement Range')</th>
        <th>@lang('Operation Range')</th>
        <th>@lang('Use Parameter')</th>
        <th>@lang('Use Recommendations')</th>
        <th>@lang('Resolution')</th>
        <th>@lang('Analog Indication')</th>
        <th>@lang('Digital Indication')</th>
        <th>@lang('Wavelength Indication')</th>
        <th>@lang('Adsorption Indication')</th>
        <th>@lang('Feeding')</th>
        <th>@lang('Voltage')</th>
        <th>@lang('Rh')</th>
        <th>@lang('Power')</th>
        <th>@lang('Temperature')</th>
        <th>@lang('Frequency')</th>
        <th>@lang('Revolutions Per Minute')</th>
        <th>@lang('Type Protection')</th>
        <th>@lang('Rated Current')</th>
        <th>@lang('Rated Power')</th>
        <th>@lang('Operating Conditions')</th>
        <th>@lang('Calibration Validation External Verification')</th>
        <th>@lang('Calibration Frequency')</th>
        <th>@lang('Preventive Maintenance')</th>
        <th>@lang('Maintenance Frequency')</th>
        <th>@lang('Verification Internal Verification')</th>
        <th>@lang('Verification Frequency')</th>
        <th>@lang('Procedure Code')</th>
        <th>@lang('Calibration Points')</th>
        <th>@lang('Calibration Under Accreditation')</th>
        <th>@lang('Reference Norm')</th>
        <th>@lang('Measure Pattern')</th>
        <th>@lang('Criteria Acceptance')</th>
        <th>@lang('Calibration Test')</th>
        <th>@lang('Dependencias Id')</th>
        <th>@lang('Mant Category Id')</th>
        <th>@lang('Responsable')</th>
            <th>@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(resumeEquipmentMachineryLeca, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.name_equipment_machinery }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.no_identification }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.no_inventory_epa_esp }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.mark }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.model }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.serie }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.location }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.path_information }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.acquisition_contract }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.provider_data }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.apply }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.location_specification }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.language }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.version }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.purchase_date }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.commissioning_date }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.date_withdrawal_service }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.observations }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.vo_bo_name }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.vo_bo_cargo }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.magnitude }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.unit_measurement }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.scale_division }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.manufacturer_specification_max_permissible_error }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.max_permissible_error_technical_standard_process }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.measurement_range }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.operation_range }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.use_parameter }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.use_recommendations }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.resolution }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.analog_indication }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.digital_indication }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.wavelength_indication }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.adsorption_indication }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.feeding }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.voltage }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.RH }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.power }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.temperature }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.frequency }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.revolutions_per_minute }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.type_protection }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.rated_current }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.rated_power }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.operating_conditions }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.calibration_validation_external_verification }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.calibration_frequency }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.preventive_maintenance }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.maintenance_frequency }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.verification_internal_verification }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.verification_frequency }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.procedure_code }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.calibration_points }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.calibration_under_accreditation }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.reference_norm }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.measure_pattern }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.criteria_acceptance }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.calibration_test }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.dependencias_id }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.mant_category_id }}</td>
            <td>@{{ resumeEquipmentMachineryLeca.responsable }}</td>
            <td>
                <button @click="edit(resumeEquipmentMachineryLeca)" data-backdrop="static" data-target="#modal-form-resumeEquipmentMachineryLecas" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                <button @click="show(resumeEquipmentMachineryLeca)" data-target="#modal-view-resumeEquipmentMachineryLecas" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                <button @click="drop(resumeEquipmentMachineryLeca[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
    </tbody>
</table>
