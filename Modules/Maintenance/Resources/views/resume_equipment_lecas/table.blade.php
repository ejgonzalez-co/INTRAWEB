<table class="table table-hover m-b-0" id="resumeEquipmentLecas-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('Name Equipment')</th>
        <th>@lang('Internal Code Leca')</th>
        <th>@lang('Inventory No')</th>
        <th>@lang('Mark')</th>
        <th>@lang('Serie')</th>
        <th>@lang('Model')</th>
        <th>@lang('Location')</th>
        <th>@lang('Software')</th>
        <th>@lang('Purchase Date')</th>
        <th>@lang('Commissioning Date')</th>
        <th>@lang('Date Withdrawal Service')</th>
        <th>@lang('Maker')</th>
        <th>@lang('Provider')</th>
        <th>@lang('Catalogue')</th>
        <th>@lang('Idiom')</th>
        <th>@lang('Instructive')</th>
        <th>@lang('Instructional Location')</th>
        <th>@lang('Magnitude Control')</th>
        <th>@lang('Consumables')</th>
        <th>@lang('Resolution')</th>
        <th>@lang('Accessories')</th>
        <th>@lang('Operation Range')</th>
        <th>@lang('Voltage')</th>
        <th>@lang('Use')</th>
        <th>@lang('Use Range')</th>
        <th>@lang('Allowable Error')</th>
        <th>@lang('Minimum Permissible Error')</th>
        <th>@lang('Environmental Operating Conditions')</th>
        <th>@lang('Dependencias Id')</th>
        <th>@lang('Mant Category Id')</th>
        <th>@lang('Responsable')</th>
            <th>@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(resumeEquipmentLeca, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ resumeEquipmentLeca.name_equipment }}</td>
            <td>@{{ resumeEquipmentLeca.internal_code_leca }}</td>
            <td>@{{ resumeEquipmentLeca.inventory_no }}</td>
            <td>@{{ resumeEquipmentLeca.mark }}</td>
            <td>@{{ resumeEquipmentLeca.serie }}</td>
            <td>@{{ resumeEquipmentLeca.model }}</td>
            <td>@{{ resumeEquipmentLeca.location }}</td>
            <td>@{{ resumeEquipmentLeca.software }}</td>
            <td>@{{ resumeEquipmentLeca.purchase_date }}</td>
            <td>@{{ resumeEquipmentLeca.commissioning_date }}</td>
            <td>@{{ resumeEquipmentLeca.date_withdrawal_service }}</td>
            <td>@{{ resumeEquipmentLeca.maker }}</td>
            <td>@{{ resumeEquipmentLeca.provider }}</td>
            <td>@{{ resumeEquipmentLeca.catalogue }}</td>
            <td>@{{ resumeEquipmentLeca.idiom }}</td>
            <td>@{{ resumeEquipmentLeca.instructive }}</td>
            <td>@{{ resumeEquipmentLeca.instructional_location }}</td>
            <td>@{{ resumeEquipmentLeca.magnitude_control }}</td>
            <td>@{{ resumeEquipmentLeca.consumables }}</td>
            <td>@{{ resumeEquipmentLeca.resolution }}</td>
            <td>@{{ resumeEquipmentLeca.accessories }}</td>
            <td>@{{ resumeEquipmentLeca.operation_range }}</td>
            <td>@{{ resumeEquipmentLeca.voltage }}</td>
            <td>@{{ resumeEquipmentLeca.use }}</td>
            <td>@{{ resumeEquipmentLeca.use_range }}</td>
            <td>@{{ resumeEquipmentLeca.allowable_error }}</td>
            <td>@{{ resumeEquipmentLeca.minimum_permissible_error }}</td>
            <td>@{{ resumeEquipmentLeca.environmental_operating_conditions }}</td>
            <td>@{{ resumeEquipmentLeca.dependencias_id }}</td>
            <td>@{{ resumeEquipmentLeca.mant_category_id }}</td>
            <td>@{{ resumeEquipmentLeca.responsable }}</td>
            <td>
                <button @click="edit(resumeEquipmentLeca)" data-backdrop="static" data-target="#modal-form-resumeEquipmentLecas" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                <button @click="show(resumeEquipmentLeca)" data-target="#modal-view-resumeEquipmentLecas" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                <button @click="drop(resumeEquipmentLeca[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
    </tbody>
</table>
