<div class="panel">
    <div class="panel-heading">
        <div class="panel-title">
            <strong>Datos del equipo</strong>
        </div>
    </div>
    <div class="panel-body">
        <div class="form-group row col-12 m-b-15">
            <!-- Asset Type Field -->
            <div class="row mb-3 w-100">
                {!! Form::label('asset_type', trans('Equip Type') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
                <div class="col-md-8">
                    <select class="form-control" v-model="dataForm.asset_type" name="asset_type">
                        <option value="Computador">Computador</option>
                        <option value="Portátil">Portátil</option>
                        <option value="Servidor">Servidor</option>
                    </select>
                </div>
            </div>
    
            <!-- Equipment Status Field -->
            <div class="row mb-3 w-100">
                {!! Form::label('status_equipment', 'Estado del Equipo:', ['class' => 'col-form-label col-md-2 required']) !!}
                <div class="col-md-8">
                    <select class="form-control" v-model="dataForm.status_equipment" name="status_equipment">
                        <option value="Activo">Activo</option>
                        <option value="Desactivado">No Activo</option>
                        <option value="En reparación">En reparación</option>
                        <option value="Dado de Baja">Dado de Baja</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    
</div>

<div class="panel" v-if="dataForm.asset_type == 'Computador' || dataForm.asset_type == 'Portátil' || dataForm.asset_type == 'Servidor'">
    <div class="panel-heading">
        <div class="panel-title"><strong>Datos del Funcionario</strong></div>
    </div>
    <div class="panel-body">
        <!-- Domain User Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('domain_user', trans('Usuario del dominio (EPA. LOCAL)') . ':', [
                'class' => 'col-form-label col-md-3',
            ]) !!}
            <div class="col-md-3">
                {!! Form::text('domain_user', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.domain_user }",
                    'v-model' => 'dataForm.domain_user',
                    'required' => false,
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.domain_user">
                    <p class="m-b-0" v-for="error in dataErrors.domain_user">@{{ error }}</p>
                </div>
            </div>

            <!-- Officer Field -->
            {!! Form::label('officer', trans('Officer') . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-3">
                {!! Form::text('officer', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.officer }",
                    'v-model' => 'dataForm.officer',
                    'required' => false,
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.officer">
                    <p class="m-b-0" v-for="error in dataErrors.officer">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Contract Type Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('contract_type', trans('Contract Type') . ':', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-3">
                <select class="form-control" name="contract_type" v-model="dataForm.contract_type">
                    <option value="Contratista">Contratista</option>
                    <option value="Oficial">Oficial</option>
                    <option value="Libre nombramiento">Libre nombramiento</option>
                    <option value="Pasante">Pasante</option>
                </select>
            </div>

            <!-- Charge Field -->
            {!! Form::label('charge', trans('Charge') . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-3">
                {!! Form::text('charge', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.charge }",
                    'v-model' => 'dataForm.charge',
                    'required' => false,
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.charge">
                    <p class="m-b-0" v-for="error in dataErrors.charge">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Dependence Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('dependence', trans('Dependence') . ':', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-3">
                <select-check css-class="form-control" name-field="dependence" reduce-label="nombre" name-resource="get-dependencies" :value="dataForm" :enable-search="true"></select-check>
                <div class="invalid-feedback" v-if="dataErrors.dependence">
                    <p class="m-b-0" v-for="error in dataErrors.dependence">@{{ error }}</p>
                </div>
            </div>

            <!-- Area Field -->
            {!! Form::label('area', trans('Area') . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-3">
                {!! Form::text('area', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.area }",
                    'v-model' => 'dataForm.area',
                    'required' => false,
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.area">
                    <p class="m-b-0" v-for="error in dataErrors.area">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Site Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('site', trans('Site') . ':', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-3">
                <select-check css-class="form-control" name-field="ht_sedes_id" reduce-label="name"
                     reduce-key="id" name-resource="get-sedes-tics" :value="dataForm" :enable-search="true">
                  </select-check>
                <div class="invalid-feedback" v-if="dataErrors.site">
                    <p class="m-b-0" v-for="error in dataErrors.site">@{{ error }}</p>
                </div>
            </div>

            <!-- Service Manager Field -->
            {!! Form::label('service_manager', trans('Service Manager') . ':', [
                'class' => 'col-form-label col-md-2',
            ]) !!}
            <div class="col-md-3">
                {!! Form::text('service_manager', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.service_manager }",
                    'v-model' => 'dataForm.service_manager',
                    'required' => false,
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.service_manager">
                    <p class="m-b-0" v-for="error in dataErrors.service_manager">@{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
             <!-- Area Field -->
             {!! Form::label('name_user_domain', trans('Nombre del usuario de dominio') . ':', ['class' => 'col-form-label col-md-3']) !!}
             <div class="col-md-3">
                 {!! Form::text('name_user_domain', null, [
                     ':class' => "{'form-control':true, 'is-invalid':dataErrors.name_user_domain }",
                     'v-model' => 'dataForm.name_user_domain',
                     'required' => false,
                 ]) !!}
                 <div class="invalid-feedback" v-if="dataErrors.name_user_domain">
                     <p class="m-b-0" v-for="error in dataErrors.name_user_domain">@{{ error }}</p>
                 </div>
             </div>
        </div>
    </div>

</div>
@if(session('is_provider') || Cookie::get('provider_name'))

    <div class="panel">
        <div class="panel-heading">
            <div class="panel-title"><strong>Mantenimiento</strong></div>
        </div>
        <div class="panel-body">
            <!-- Maintenance Type Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('maintenance_type', trans('Maintenance Type') . ':', [
                    'class' => 'col-form-label col-md-3 required',
                ]) !!}
                <div class="col-md-3">
                    <select class="form-control" name="maintenance_type" v-model="dataForm.maintenance_type" required>
                        <option value="Preventivo">Preventivo</option>
                        <option value="Predictivo">Predictivo</option>
                        <option value="Evolutivo">Evolutivo</option>
                        <option value="Correctivo">Correctivo</option>
                    </select>
                    <div class="invalid-feedback" v-if="dataErrors.maintenance_type">
                        <p class="m-b-0" v-for="error in dataErrors.maintenance_type">@{{ error }}</p>
                    </div>
                </div>

                <!-- Cycle Field -->
                {!! Form::label('cycle', trans('Cycle') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
                <div class="col-md-3">
                    {!! Form::text('cycle', null, [
                        ':class' => "{'form-control':true, 'is-invalid':dataErrors.cycle }",
                        'v-model' => 'dataForm.cycle',
                        'required' => true,
                    ]) !!}
                    <div class="invalid-feedback" v-if="dataErrors.cycle">
                        <p class="m-b-0" v-for="error in dataErrors.cycle">@{{ error }}</p>
                    </div>
                </div>
            </div>

            <!-- Contract Number Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('contract_number', trans('Contract Number') . ':', [
                    'class' => 'col-form-label col-md-3 required',
                ]) !!}
                <div class="col-md-3">
                    {!! Form::text('contract_number', null, [
                        ':class' => "{'form-control':true, 'is-invalid':dataErrors.contract_number }",
                        'v-model' => 'dataForm.contract_number',
                        'required' => true,
                    ]) !!}
                    <div class="invalid-feedback" v-if="dataErrors.contract_number">
                        <p class="m-b-0" v-for="error in dataErrors.contract_number">@{{ error }}</p>
                    </div>
                </div>

                <!-- Contract Date Field -->
                {!! Form::label('contract_date', trans('Contract Date') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
                <div class="col-md-3">
                    <date-picker :value="dataForm" name-field="contract_date" :input-props="{ required: true }">
                    </date-picker>
                    <div class="invalid-feedback" v-if="dataErrors.contract_date">
                        <p class="m-b-0" v-for="error in dataErrors.contract_date">@{{ error }}</p>
                    </div>
                </div>
            </div>

            <!-- Maintenance Date Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('maintenance_date', trans('Maintenance Date') . ':', [
                    'class' => 'col-form-label col-md-3 required',
                ]) !!}
                <div class="col-md-3">
                    <date-picker :value="dataForm" name-field="maintenance_date" :input-props="{ required: true }">
                    </date-picker>
                    <div class="invalid-feedback" v-if="dataErrors.maintenance_date">
                        <p class="m-b-0" v-for="error in dataErrors.maintenance_date">@{{ error }}</p>
                    </div>
                </div>

                <!-- Provider Field -->
                {!! Form::label('provider', trans('Provider') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
                <div class="col-md-3">
                    {!! Form::text('provider', null, [
                        ':class' => "{'form-control':true, 'is-invalid':dataErrors.provider }",
                        'v-model' => 'dataForm.provider',
                        'required' => true,
                    ]) !!}
                    <div class="invalid-feedback" v-if="dataErrors.provider">
                        <p class="m-b-0" v-for="error in dataErrors.provider">@{{ error }}</p>
                    </div>
                </div>
            </div>

            <!-- Contract Value Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('contract_value', trans('Contract Value') . ':', [
                    'class' => 'col-form-label col-md-3 required',
                ]) !!}
                <div class="col-md-3">
                    {!! Form::text('contract_value', null, [
                        ':class' => "{'form-control':true, 'is-invalid':dataErrors.contract_value }",
                        'v-model' => 'dataForm.contract_value',
                        'required' => true,
                    ]) !!}
                    <div class="invalid-feedback" v-if="dataErrors.contract_value">
                        <p class="m-b-0" v-for="error in dataErrors.contract_value">@{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel">
        <div class="panel-heading">
            <div class="panel-title"><strong>Lista de chequeo mantenimiento</strong></div>
        </div>
        <div class="panel-body">
            <!-- Has Internal And External Hardware Cleaning Field -->
            <div class="form-group row m-b-15">
                {!! Form::label(
                    'has_internal_and_external_hardware_cleaning',
                    trans('Has Internal And External Hardware Cleaning') . ':',
                    ['class' => 'col-form-label col-md-3 required'],
                ) !!}
                <div class="col-md-3">
                    <select class="form-control" name="has_internal_and_external_hardware_cleaning"
                        v-model="dataForm.has_internal_and_external_hardware_cleaning" required>
                        <option value="Si">Si</option>
                        <option value="No">No</option>
                    </select>
                    <div class="invalid-feedback" v-if="dataErrors.has_internal_and_external_hardware_cleaning">
                        <p class="m-b-0" v-for="error in dataErrors.has_internal_and_external_hardware_cleaning">
                            @{{ error }}</p>
                    </div>
                </div>

                <!-- Observation Internal And External Hardware Cleaning Field -->
                {!! Form::label('observation_internal_and_external_hardware_cleaning', trans('Observation') . ':', [
                    'class' => 'col-form-label col-md-2',
                ]) !!}
                <div class="col-md-3">
                    {!! Form::textarea('observation_internal_and_external_hardware_cleaning', null, [
                        ':class' => "{'form-control':true, 'is-invalid':dataErrors.observation_internal_and_external_hardware_cleaning }",
                        'v-model' => 'dataForm.observation_internal_and_external_hardware_cleaning',
                        'rows' => '3'
                    ]) !!}
                    <div class="invalid-feedback" v-if="dataErrors.observation_internal_and_external_hardware_cleaning">
                        <p class="m-b-0" v-for="error in dataErrors.observation_internal_and_external_hardware_cleaning">
                            @{{ error }}</p>
                    </div>
                </div>
            </div>

            <!-- Has Ram Cleaning Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('has_ram_cleaning', trans('Has Ram Cleaning') . ':', [
                    'class' => 'col-form-label col-md-3 required',
                ]) !!}
                <div class="col-md-3">
                    <select class="form-control" name="has_ram_cleaning" v-model="dataForm.has_ram_cleaning" required>
                        <option value="Si">Si</option>
                        <option value="No">No</option>
                    </select>
                </div>

                <!-- Observation Ram Cleaning Field -->
                {!! Form::label('observation_ram_cleaning', trans('Observation') . ':', [
                    'class' => 'col-form-label col-md-2',
                ]) !!}
                <div class="col-md-3">
                    {!! Form::textarea('observation_ram_cleaning', null, [
                        ':class' => "{'form-control':true, 'is-invalid':dataErrors.observation_ram_cleaning }",
                        'v-model' => 'dataForm.observation_ram_cleaning',
                        'rows' => '3'
                    ]) !!}
                    <div class="invalid-feedback" v-if="dataErrors.observation_ram_cleaning">
                        <p class="m-b-0" v-for="error in dataErrors.observation_ram_cleaning">@{{ error }}</p>
                    </div>
                </div>
            </div>

            <!-- Has Board Memory Cleaning Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('has_board_memory_cleaning', trans('Has Board Memory Cleaning') . ':', [
                    'class' => 'col-form-label col-md-3 required',
                ]) !!}
                <div class="col-md-3">
                    <select class="form-control" name="has_board_memory_cleaning" v-model="dataForm.has_board_memory_cleaning"
                        required>
                        <option value="Si">Si</option>
                        <option value="No">No</option>
                    </select>
                </div>

                <!-- Observation Board Memory Cleaning Field -->
                {!! Form::label('observation_board_memory_cleaning', trans('Observation') . ':', [
                    'class' => 'col-form-label col-md-2',
                ]) !!}
                <div class="col-md-3">
                    {!! Form::textarea('observation_board_memory_cleaning', null, [
                        ':class' => "{'form-control':true, 'is-invalid':dataErrors.observation_board_memory_cleaning }",
                        'v-model' => 'dataForm.observation_board_memory_cleaning',
                        'rows' => '3'
                    ]) !!}
                    <div class="invalid-feedback" v-if="dataErrors.observation_board_memory_cleaning">
                        <p class="m-b-0" v-for="error in dataErrors.observation_board_memory_cleaning">
                            @{{ error }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Has Power Supply Cleaning Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('has_power_supply_cleaning', trans('Has Power Supply Cleaning') . ':', [
                    'class' => 'col-form-label col-md-3 required',
                ]) !!}
                <div class="col-md-3">
                    <select class="form-control" name="has_power_supply_cleaning" v-model="dataForm.has_power_supply_cleaning"
                        required>
                        <option value="Si">Si</option>
                        <option value="No">No</option>
                    </select>
                </div>
                <!-- Observation Power Supply Cleaning Field -->
                {!! Form::label('observation_power_supply_cleaning', trans('Observation') . ':', [
                    'class' => 'col-form-label col-md-2',
                ]) !!}
                <div class="col-md-3">
                    {!! Form::textarea('observation_power_supply_cleaning', null, [
                        ':class' => "{'form-control':true, 'is-invalid':dataErrors.observation_power_supply_cleaning }",
                        'v-model' => 'dataForm.observation_power_supply_cleaning',
                        'rows' => '3'
                    ]) !!}
                    <div class="invalid-feedback" v-if="dataErrors.observation_power_supply_cleaning">
                        <p class="m-b-0" v-for="error in dataErrors.observation_power_supply_cleaning">
                            @{{ error }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Has Dvd Drive Cleaning Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('has_dvd_drive_cleaning', trans('Has Dvd Drive Cleaning') . ':', [
                    'class' => 'col-form-label col-md-3 required',
                ]) !!}
                <div class="col-md-3">
                    <select class="form-control" name="has_dvd_drive_cleaning" v-model="dataForm.has_dvd_drive_cleaning" required>
                        <option value="Si">Si</option>
                        <option value="No">No</option>
                    </select>
                    <div class="invalid-feedback" v-if="dataErrors.has_dvd_drive_cleaning">
                        <p class="m-b-0" v-for="error in dataErrors.has_dvd_drive_cleaning">@{{ error }}</p>
                    </div>
                </div>

                <!-- Observation Dvd Drive Cleaning Field -->
                {!! Form::label('observation_dvd_drive_cleaning', trans('Observation') . ':', [
                    'class' => 'col-form-label col-md-2',
                ]) !!}
                <div class="col-md-3">
                    {!! Form::textarea('observation_dvd_drive_cleaning', null, [
                        ':class' => "{'form-control':true, 'is-invalid':dataErrors.observation_dvd_drive_cleaning }",
                        'v-model' => 'dataForm.observation_dvd_drive_cleaning',
                        'rows' => '3'
                    ]) !!}
                    <div class="invalid-feedback" v-if="dataErrors.observation_dvd_drive_cleaning">
                        <p class="m-b-0" v-for="error in dataErrors.observation_dvd_drive_cleaning">
                            @{{ error }}</p>
                    </div>
                </div>
            </div>

            <!-- Has Monitor Cleaning Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('has_monitor_cleaning', trans('Has Monitor Cleaning') . ':', [
                    'class' => 'col-form-label col-md-3 required',
                ]) !!}
                <div class="col-md-3">
                    <select class="form-control" name="has_monitor_cleaning" v-model="dataForm.has_monitor_cleaning" required>
                        <option value="Si">Si</option>
                        <option value="No">No</option>
                    </select>
                    <div class="invalid-feedback" v-if="dataErrors.has_monitor_cleaning">
                        <p class="m-b-0" v-for="error in dataErrors.has_monitor_cleaning">@{{ error }}</p>
                    </div>
                </div>

                <!-- Observation Monitor Cleaning Field -->
                {!! Form::label('observation_monitor_cleaning', trans('Observation') . ':', [
                    'class' => 'col-form-label col-md-2',
                ]) !!}
                <div class="col-md-3">
                    {!! Form::textarea('observation_monitor_cleaning', null, [
                        ':class' => "{'form-control':true, 'is-invalid':dataErrors.observation_monitor_cleaning }",
                        'v-model' => 'dataForm.observation_monitor_cleaning',
                        'rows' => '3'
                    ]) !!}
                    <div class="invalid-feedback" v-if="dataErrors.observation_monitor_cleaning">
                        <p class="m-b-0" v-for="error in dataErrors.observation_monitor_cleaning">@{{ error }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Has Keyboard Cleaning Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('has_keyboard_cleaning', trans('Has Keyboard Cleaning') . ':', [
                    'class' => 'col-form-label col-md-3 required',
                ]) !!}
                <div class="col-md-3">
                    <select class="form-control" name="has_keyboard_cleaning" v-model="dataForm.has_keyboard_cleaning" required>
                        <option value="Si">Si</option>
                        <option value="No">No</option>
                    </select>
                    <div class="invalid-feedback" v-if="dataErrors.has_keyboard_cleaning">
                        <p class="m-b-0" v-for="error in dataErrors.has_keyboard_cleaning">@{{ error }}</p>
                    </div>
                </div>

                <!-- Observation Keyboard Cleaning Field -->
                {!! Form::label('observation_keyboard_cleaning', trans('Observation') . ':', [
                    'class' => 'col-form-label col-md-2',
                ]) !!}
                <div class="col-md-3">
                    {!! Form::textarea('observation_keyboard_cleaning', null, [
                        ':class' => "{'form-control':true, 'is-invalid':dataErrors.observation_keyboard_cleaning }",
                        'v-model' => 'dataForm.observation_keyboard_cleaning',
                        'rows' => '3'
                    ]) !!}
                    <div class="invalid-feedback" v-if="dataErrors.observation_keyboard_cleaning">
                        <p class="m-b-0" v-for="error in dataErrors.observation_keyboard_cleaning">
                            @{{ error }}</p>
                    </div>
                </div>
            </div>

            <!-- Has Mouse Cleaning Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('has_mouse_cleaning', trans('Has Mouse Cleaning') . ':', [
                    'class' => 'col-form-label col-md-3 required',
                ]) !!}
                <div class="col-md-3">
                    <select class="form-control" name="has_mouse_cleaning" v-model="dataForm.has_mouse_cleaning" required>
                        <option value="Si">Si</option>
                        <option value="No">No</option>
                    </select>
                    <div class="invalid-feedback" v-if="dataErrors.has_mouse_cleaning">
                        <p class="m-b-0" v-for="error in dataErrors.has_mouse_cleaning">@{{ error }}</p>
                    </div>
                </div>

                <!-- Observation Mouse Cleaning Field -->
                {!! Form::label('observation_mouse_cleaning', trans('Observation') . ':', [
                    'class' => 'col-form-label col-md-2',
                ]) !!}
                <div class="col-md-3">
                    {!! Form::textarea('observation_mouse_cleaning', null, [
                        ':class' => "{'form-control':true, 'is-invalid':dataErrors.observation_mouse_cleaning }",
                        'v-model' => 'dataForm.observation_mouse_cleaning',
                        'rows' => '3'
                    ]) !!}
                    <div class="invalid-feedback" v-if="dataErrors.observation_mouse_cleaning">
                        <p class="m-b-0" v-for="error in dataErrors.observation_mouse_cleaning">@{{ error }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Has Thermal Paste Change Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('has_thermal_paste_change', trans('Has Thermal Paste Change') . ':', [
                    'class' => 'col-form-label col-md-3 required',
                ]) !!}
                <div class="col-md-3">
                    <select class="form-control" name="has_thermal_paste_change" v-model="dataForm.has_thermal_paste_change" required>
                        <option value="Si">Si</option>
                        <option value="No">No</option>
                    </select>
                    <div class="invalid-feedback" v-if="dataErrors.has_thermal_paste_change">
                        <p class="m-b-0" v-for="error in dataErrors.has_thermal_paste_change">@{{ error }}</p>
                    </div>
                </div>

                <!-- Observation Thermal Paste Change Field -->
                {!! Form::label('observation_thermal_paste_change', trans('Observation') . ':', [
                    'class' => 'col-form-label col-md-2',
                ]) !!}
                <div class="col-md-3">
                    {!! Form::textarea('observation_thermal_paste_change', null, [
                        ':class' => "{'form-control':true, 'is-invalid':dataErrors.observation_thermal_paste_change }",
                        'v-model' => 'dataForm.observation_thermal_paste_change',
                        'rows' => '3'
                    ]) !!}
                    <div class="invalid-feedback" v-if="dataErrors.observation_thermal_paste_change">
                        <p class="m-b-0" v-for="error in dataErrors.observation_thermal_paste_change">
                            @{{ error }}</p>
                    </div>
                </div>
            </div>

            <!-- Has Heatsink Cleaning Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('has_heatsink_cleaning', trans('Has Heatsink Cleaning') . ':', [
                    'class' => 'col-form-label col-md-3 required',
                ]) !!}
                <div class="col-md-3">
                    <select class="form-control" name="has_heatsink_cleaning" v-model="dataForm.has_heatsink_cleaning" required>
                        <option value="Si">Si</option>
                        <option value="No">No</option>
                    </select>
                    <div class="invalid-feedback" v-if="dataErrors.has_heatsink_cleaning">
                        <p class="m-b-0" v-for="error in dataErrors.has_heatsink_cleaning">@{{ error }}</p>
                    </div>
                </div>

                <!-- Observation Heatsink Cleaning Field -->
                {!! Form::label('observation_heatsink_cleaning', trans('Observation') . ':', [
                    'class' => 'col-form-label col-md-2',
                ]) !!}
                <div class="col-md-3">
                    {!! Form::textarea('observation_heatsink_cleaning', null, [
                        ':class' => "{'form-control':true, 'is-invalid':dataErrors.observation_heatsink_cleaning }",
                        'v-model' => 'dataForm.observation_heatsink_cleaning',
                        'rows' => '3'
                    ]) !!}
                    <div class="invalid-feedback" v-if="dataErrors.observation_heatsink_cleaning">
                        <p class="m-b-0" v-for="error in dataErrors.observation_heatsink_cleaning">
                            @{{ error }}</p>
                    </div>
                </div>
            </div>

            <!-- Technical Report Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('technical_report', trans('Technical Report') . ':', [
                    'class' => 'col-form-label col-md-3',
                ]) !!}
                <div class="col-md-3">
                    {!! Form::textarea('technical_report', null, [
                        ':class' => "{'form-control':true, 'is-invalid':dataErrors.technical_report }",
                        'v-model' => 'dataForm.technical_report',
                    ]) !!}
                    <div class="invalid-feedback" v-if="dataErrors.technical_report">
                        <p class="m-b-0" v-for="error in dataErrors.technical_report">@{{ error }}</p>
                    </div>
                </div>

                <!-- Observation Field -->
                {!! Form::label('observation', trans('Observation') . ':', ['class' => 'col-form-label col-md-2']) !!}
                <div class="col-md-3">
                    {!! Form::textarea('observation', null, [
                        ':class' => "{'form-control':true, 'is-invalid':dataErrors.observation }",
                        'v-model' => 'dataForm.observation',
                    ]) !!}
                    <div class="invalid-feedback" v-if="dataErrors.observation">
                        <p class="m-b-0" v-for="error in dataErrors.observation">@{{ error }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endif



<div class="panel" v-if="dataForm.asset_type == 'Computador' || dataForm.asset_type == 'Portátil' || dataForm.asset_type == 'Servidor'">
    <div class="panel-heading">
        <div class="panel-title"><strong>Configuración del actual hardware - Torre</strong></div>
    </div>
    <div class="panel-body">
        <!-- Tower Inventory Number Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('tower_inventory_number', trans('Placa de Inventario') . ':', [
                'class' => 'col-form-label col-md-2',
            ]) !!}
            <div class="col-md-3">
                {!! Form::text('tower_inventory_number', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_inventory_number }",
                    'v-model' => 'dataForm.tower_inventory_number',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.tower_inventory_number">
                    <p class="m-b-0" v-for="error in dataErrors.tower_inventory_number">@{{ error }}</p>
                </div>
            </div>

            <!-- Tower Field -->
            {!! Form::label('tower', trans('Marca Torre') . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-3">
                <select-check css-class="form-control" name-field="tower" reduce-label="brand_name"
                    reduce-key="brand_name" name-resource="get-config-towers-actives" :value="dataForm">
                </select-check>
                <div class="invalid-feedback" v-if="dataErrors.tower">
                    <p class="m-b-0" v-for="error in dataErrors.tower">@{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
            <!-- Tower Field -->
            {!! Form::label('id_tower_reference', trans('Referencia') . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-3">
                <select-check css-class="form-control" name-field="id_tower_reference" reduce-label="reference"
                reduce-key="id" name-resource="get-config-tower-references-active" :value="dataForm" :enable-search="true">
                </select-check>
                <div class="invalid-feedback" v-if="dataErrors.id_tower_reference">
                    <p class="m-b-0" v-for="error in dataErrors.id_tower_reference">@{{ error }}</p>
                </div>
            </div>
            {!! Form::label('tower_series', trans('Serial/ST') . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-3">
                {!! Form::text('tower_series', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_series }",
                    'v-model' => 'dataForm.tower_series',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.tower_series">
                    <p class="m-b-0" v-for="error in dataErrors.tower_series">@{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
            <!-- Tower Field -->
            {!! Form::label('tower_warranty_end_date', trans('Fecha Finalizacíon Garantía') . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-3">
               <input
                    type="date"
                    class="form-control"
                    :class="{ 'is-invalid': dataErrors.tower_warranty_end_date }"
                    v-model="dataForm.tower_warranty_end_date"
                />

                <div class="invalid-feedback" v-if="dataErrors.tower_warranty_end_date">
                    <p class="m-b-0" v-for="error in dataErrors.tower_warranty_end_date">@{{ error }}</p>
                </div>
            </div>
            {!! Form::label('tower_equipment_year', trans('Año del equipo') . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-3">
                {!! Form::selectRange('tower_equipment_year', 2015, 2030, null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_equipment_year }",
                    'v-model' => 'dataForm.tower_equipment_year',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.tower_equipment_year">
                    <p class="m-b-0" v-for="error in dataErrors.tower_equipment_year">@{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
            <!-- Tower Field -->
            {!! Form::label('id_tower_size', trans('Tamaño de la torre') . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-3">
                <select-check css-class="form-control" name-field="id_tower_size" reduce-label="size"
                reduce-key="id" name-resource="get-config-tower-size-active" :value="dataForm" :enable-search="true">
                </select-check>
                <div class="invalid-feedback" v-if="dataErrors.id_tower_size">
                    <p class="m-b-0" v-for="error in dataErrors.id_tower_size">@{{ error }}</p>
                </div>
            </div>
            {!! Form::label('id_tower_processor', trans('Procesador') . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-3">
                <select-check css-class="form-control" name-field="id_tower_processor" reduce-label="processor"
                reduce-key="id" name-resource="get-config-tower-processor-active" :value="dataForm" :enable-search="true">
                </select-check>
                <div class="invalid-feedback" v-if="dataErrors.id_tower_processor">
                    <p class="m-b-0" v-for="error in dataErrors.id_tower_processor">@{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
            <!-- Tower Field -->
            {!! Form::label('id_tower_ram', trans('Memoria RAM') . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-3">
                <select-check css-class="form-control" name-field="id_tower_ram" reduce-label="memory_ram"
                reduce-key="id" name-resource="get-config-tower-rams-active" :value="dataForm" :enable-search="true">
                </select-check>
                <div class="invalid-feedback" v-if="dataErrors.id_tower_ram">
                    <p class="m-b-0" v-for="error in dataErrors.id_tower_ram">@{{ error }}</p>
                </div>
            </div>
            {!! Form::label('id_tower_ssd_capacity', trans('Capacidad SSD') . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-3">
                <select-check css-class="form-control" name-field="id_tower_ssd_capacity" :reduce-label="['marca','ssd_capacity']"
                reduce-key="id" name-resource="get-config-tower-ssd-active" :value="dataForm" :enable-search="true">
            </select-check>
                <div class="invalid-feedback" v-if="dataErrors.tower_ssd_capacity_gb">
                    <p class="m-b-0" v-for="error in dataErrors.tower_ssd_capacity_gb">@{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
            <!-- Tower Field -->
            {!! Form::label('id_tower_hdd_capacity', trans('Capacidad HDD') . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-3">
                <select-check css-class="form-control" name-field="id_tower_hdd_capacity" :reduce-label="['marca','hdd_capacity']"
                reduce-key="id" name-resource="get-config-tower-hdd-active" :value="dataForm" :enable-search="true">
                </select-check>
                <div class="invalid-feedback" v-if="dataErrors.id_tower_hdd_capacity">
                    <p class="m-b-0" v-for="error in dataErrors.id_tower_hdd_capacity">@{{ error }}</p>
                </div>
            </div>
            {!! Form::label('id_tower_video_card', trans('Tarjeta Gráfica') . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-3">
                <select-check css-class="form-control" name-field="id_tower_video_card" :reduce-label="['marca','video_card']"
                reduce-key="id" name-resource="get-config-tower-video-card-active" :value="dataForm" :enable-search="true"  >
                </select-check>
                <div class="invalid-feedback" v-if="dataErrors.id_tower_video_card">
                    <p class="m-b-0" v-for="error in dataErrors.id_tower_video_card">@{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
            {!! Form::label('teamviewer', trans('ID TeamViewer') . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-3">
                    {!! Form::text('teamviewer', null, [ ':class' => "{'form-control':true, 'is-invalid':dataErrors.teamviewer }", 'v-model' => 'dataForm.teamviewer', ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.teamviewer">
                    <p class="m-b-0" v-for="error in dataErrors.teamviewer">@{{ error }}</p> 
                </div> 
            </div>
            <!-- Tower Host Field -->
                {!! Form::label('tower_host', trans('Nombre de equipo/HOST') . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-3">
                {!! Form::text('tower_host', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_host }",
                    'v-model' => 'dataForm.tower_host',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.tower_host">
                    <p class="m-b-0" v-for="error in dataErrors.tower_host">@{{ error }}</p>
                </div>
            </div>
            
        </div>

        <div class="form-group row m-b-15">


            {{-- DOMINIO (texto)  --}}
            {!! Form::label('tower_domain', trans('Dominio') . ':', [
                'class' => 'col-form-label col-md-2',
            ]) !!}
            <div class="col-md-3">
                {!! Form::text('tower_domain', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_domain }",
                    'v-model' => 'dataForm.tower_domain',
                    'required' => false,
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.tower_domain">
                    <p class="m-b-0" v-for="error in dataErrors.tower_domain">@{{ error }}</p>
                </div>
            </div>


            {{-- Usuario Directorio Activo (Texto)  --}}
            {!! Form::label('tower_directory_active', trans('Usuario Directorio Activo') . ':', [
                'class' => 'col-form-label col-md-2',
            ]) !!}
            <div class="col-md-3">
                {!! Form::text('tower_directory_active', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_directory_active }",
                    'v-model' => 'dataForm.tower_directory_active',
                    'required' => false,
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.tower_directory_active">
                    <p class="m-b-0" v-for="error in dataErrors.tower_directory_active">@{{ error }}</p>
                </div>
            </div>
             
        </div>

        <div class="form-group row m-b-15">
            {!! Form::label('id_tower_shared_folder', trans('Carpeta Compartida') . ':', ['class' => 'col-form-label col-md-2']) !!}
             <div class="col-md-3">
                <select-check css-class="form-control" name-field="id_tower_shared_folder" reduce-label="shared_folder"
                    reduce-key="id" name-resource="get-config-tower-shared-folder-active" :value="dataForm" :enable-search="true">
                </select-check>
                 <div class="invalid-feedback" v-if="dataErrors.id_tower_shared_folder">
                     <p class="m-b-0" v-for="error in dataErrors.id_tower_shared_folder">@{{ error }}</p>
                 </div>
             </div>


             {{-- Punto de red (Texto)  --}}
             {!! Form::label('tower_network_point', trans('Punto de red') . ':', [
                'class' => 'col-form-label col-md-2',
            ]) !!}
            <div class="col-md-3">
                {!! Form::text('tower_network_point', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_network_point }",
                    'v-model' => 'dataForm.tower_network_point',
                    'required' => false,
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.tower_network_point">
                    <p class="m-b-0" v-for="error in dataErrors.tower_network_point">@{{ error }}</p>
                </div>
            </div>
            
       </div>

       <div class="form-group row m-b-15">
            <!-- Faceplate Patch Panel Field -->
            {!! Form::label('faceplate_patch_panel', trans('Puerto en Patch Panel') . ':', [
                'class' => 'col-form-label col-md-2',
            ]) !!}
            <div class="col-md-3">
                {!! Form::text('faceplate_patch_panel', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.faceplate_patch_panel }",
                    'v-model' => 'dataForm.faceplate_patch_panel',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.faceplate_patch_panel">
                    <p class="m-b-0" v-for="error in dataErrors.faceplate_patch_panel">@{{ error }}</p>
                </div>
            </div>
            
            <!-- Tower Mac Address Field -->
            {!! Form::label('tower_mac_address', trans('Física MAC') . ':', [
                'class' => 'col-form-label col-md-2',
            ]) !!}
            <div class="col-md-3">
                {!! Form::text('tower_mac_address', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_mac_address }",
                    'v-model' => 'dataForm.tower_mac_address',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.tower_mac_address">
                    <p class="m-b-0" v-for="error in dataErrors.tower_mac_address">@{{ error }}</p>
                </div>
            </div>

            
        </div>

        <div class="form-group row m-b-15">
            <!-- Faceplate Patch Panel Field -->
            {!! Form::label('tower_dhcp', trans('DHCP Habilitado') . ':', [
                'class' => 'col-form-label col-md-2',
            ]) !!}
            <div class="col-md-3">
                <select class="form-control" v-model="dataForm.tower_dhcp" name="tower_dhcp">
                    <option value="Si">Si</option>
                    <option value="No">No</option>
                </select>
                <div class="invalid-feedback" v-if="dataErrors.tower_dhcp">
                    <p class="m-b-0" v-for="error in dataErrors.tower_dhcp">@{{ error }}</p>
                </div>
            </div>
            
            {!! Form::label('tower_ipv4_address', trans('Dirección IPV4') . ':', [
                'class' => 'col-form-label col-md-2',
            ]) !!}
            <div class="col-md-3">
                {!! Form::text('tower_ipv4_address', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_ipv4_address }",
                    'v-model' => 'dataForm.tower_ipv4_address',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.tower_ipv4_address">
                    <p class="m-b-0" v-for="error in dataErrors.tower_ipv4_address">@{{ error }}</p>
                </div>
            </div>
        </div>


        <div class="form-group row m-b-15">
            <!-- Tower Ipv6 Address Field -->
            {!! Form::label('tower_ipv6_address', trans('Dirección IPV6 ') . ':', [
                'class' => 'col-form-label col-md-2',
            ]) !!}
            <div class="col-md-3">
                {!! Form::text('tower_ipv6_address', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_ipv6_address }",
                    'v-model' => 'dataForm.tower_ipv6_address',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.tower_ipv6_address">
                    <p class="m-b-0" v-for="error in dataErrors.tower_ipv6_address">@{{ error }}</p>
                </div>
            </div>
            <!-- Tower Field -->
            {!! Form::label('id_tower_network_card', trans('Tarjeta de red') . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-3">
                <select-check css-class="form-control" name-field="id_tower_network_card" reduce-label="network_card"
                reduce-key="id" name-resource="get-config-tower-network-card-active" :value="dataForm"
                :enable-search="true">
                </select-check>
                <div class="invalid-feedback" v-if="dataErrors.id_tower_network_card">
                    <p class="m-b-0" v-for="error in dataErrors.id_tower_network_card">@{{ error }}</p>
                </div>
            </div>
        </div>
        <div class="form-group row m-b-15">
            {!! Form::label('observation', trans('Observación') . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-3">
                {!! Form::textarea('observation', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.observation }",
                    'v-model' => 'dataForm.observation',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.observation">
                    <p class="m-b-0" v-for="error in dataErrors.observation">@{{ error }}</p>
                </div>
            </div>
        </div>

{{-- -------------------------------------------------------------------------------------------------------------- --}}


    </div>
</div>

<div class="panel" v-if="dataForm.asset_type == 'Computador'">
    <div class="panel-heading">
        <div class="panel-title"><strong>Configuración actual de hardware – Monitor</strong></div>
    </div>
    <div class="panel-body">
        <!-- Monitor Inventory Number Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('monitor_number_inventory', trans('Inventory Number') . ':', [
                'class' => 'col-form-label col-md-3',
            ]) !!}
            <div class="col-md-3">
                {!! Form::text('monitor_number_inventory', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.monitor_number_inventory }",
                    'v-model' => 'dataForm.monitor_number_inventory',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.monitor_number_inventory">
                    <p class="m-b-0" v-for="error in dataErrors.monitor_number_inventory">@{{ error }}</p>
                </div>
            </div>

            <!-- Monitor Field -->
            {!! Form::label('monitor', trans('Mark') . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-3">
                <select-check css-class="form-control" name-field="monitor" reduce-label="brand_name"
                    reduce-key="brand_name" name-resource="get-config-monitors-actives" :value="dataForm">
                </select-check>
                <div class="invalid-feedback" v-if="dataErrors.monitor">
                    <p class="m-b-0" v-for="error in dataErrors.monitor">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Monitor Model Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('monitor_model', trans('Model') . ':', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-3">
                {!! Form::text('monitor_model', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.monitor_model }",
                    'v-model' => 'dataForm.monitor_model',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.monitor_model">
                    <p class="m-b-0" v-for="error in dataErrors.monitor_model">@{{ error }}</p>
                </div>
            </div>

            <!-- Monitor Serial Field -->
            {!! Form::label('monitor_serial', trans('Serial') . ':', [
                'class' => 'col-form-label col-md-2',
            ]) !!}
            <div class="col-md-3">
                {!! Form::text('monitor_serial', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.monitor_serial }",
                    'v-model' => 'dataForm.monitor_serial',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.monitor_serial">
                    <p class="m-b-0" v-for="error in dataErrors.monitor_serial">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Monitor Value Field -->
        {{-- <div class="form-group row m-b-15">
            {!! Form::label('monitor_value', trans('Monitor Value') . ':', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-3">
                {!! Form::text('monitor_value', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.monitor_value }",
                    'v-model' => 'dataForm.monitor_value',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.monitor_value">
                    <p class="m-b-0" v-for="error in dataErrors.monitor_value">@{{ error }}</p>
                </div>
            </div>

            <!-- Monitor Contract Number Field -->
            {!! Form::label('monitor_contract_number', trans('Contract Number') . ':', [
                'class' => 'col-form-label col-md-2',
            ]) !!}
            <div class="col-md-3">
                {!! Form::text('monitor_contract_number', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.monitor_contract_number }",
                    'v-model' => 'dataForm.monitor_contract_number',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.monitor_contract_number">
                    <p class="m-b-0" v-for="error in dataErrors.monitor_contract_number">@{{ error }}</p>
                </div>
            </div>
        </div> --}}

    </div>
</div>
{{-- 
<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Configuración actual de hardware – Teclado</strong></div>
    </div>
    <div class="panel-body">
        <!-- Keyboard Inventory Number Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('keyboard_number_inventory', trans('Inventory Number') . ':', [
                'class' => 'col-form-label col-md-3',
            ]) !!}
            <div class="col-md-3">
                {!! Form::text('keyboard_number_inventory', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.keyboard_number_inventory }",
                    'v-model' => 'dataForm.keyboard_number_inventory',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.keyboard_number_inventory">
                    <p class="m-b-0" v-for="error in dataErrors.keyboard_number_inventory">@{{ error }}</p>
                </div>
            </div>

            <!-- Keyboard Field -->
            {!! Form::label('keyboard', trans('Keyboard') . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-3">
                <select-check css-class="form-control" name-field="keyboard" reduce-label="brand_name"
                    reduce-key="brand_name" name-resource="get-config-keyboards" :value="dataForm">
                </select-check>
                <div class="invalid-feedback" v-if="dataErrors.keyboard">
                    <p class="m-b-0" v-for="error in dataErrors.keyboard">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Keyboard Model Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('keyboard_model', trans('Model') . ':', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-3">
                {!! Form::text('keyboard_model', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.keyboard_model }",
                    'v-model' => 'dataForm.keyboard_model',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.keyboard_model">
                    <p class="m-b-0" v-for="error in dataErrors.keyboard_model">@{{ error }}</p>
                </div>
            </div>

            <!-- Keyboard Serial Field -->
            {!! Form::label('keyboard_serial', trans('Serial') . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-3">
                {!! Form::text('keyboard_serial', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.keyboard_serial }",
                    'v-model' => 'dataForm.keyboard_serial',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.keyboard_serial">
                    <p class="m-b-0" v-for="error in dataErrors.keyboard_serial">@{{ error }}</p>
                </div>
            </div>
        </div>


        <!-- Keyboard Value Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('keyboard_value', trans('Value') . ':', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-3">
                {!! Form::text('keyboard_value', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.keyboard_value }",
                    'v-model' => 'dataForm.keyboard_value',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.keyboard_value">
                    <p class="m-b-0" v-for="error in dataErrors.keyboard_value">@{{ error }}</p>
                </div>
            </div>

            <!-- Keyboard Contract Number Field -->
            {!! Form::label('keyboard_contract_number', trans('Contract Number') . ':', [
                'class' => 'col-form-label col-md-2',
            ]) !!}
            <div class="col-md-3">
                {!! Form::text('keyboard_contract_number', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.keyboard_contract_number }",
                    'v-model' => 'dataForm.keyboard_contract_number',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.keyboard_contract_number">
                    <p class="m-b-0" v-for="error in dataErrors.keyboard_contract_number">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Configuración actual de hardware – Mouse</strong></div>
    </div>
    <div class="panel-body">
        <!-- Mouse Inventory Number Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('mouse_number_inventory', trans('Inventory Number') . ':', [
                'class' => 'col-form-label col-md-3',
            ]) !!}
            <div class="col-md-3">
                {!! Form::text('mouse_number_inventory', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.mouse_number_inventory }",
                    'v-model' => 'dataForm.mouse_number_inventory',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.mouse_number_inventory">
                    <p class="m-b-0" v-for="error in dataErrors.mouse_number_inventory">@{{ error }}</p>
                </div>
            </div>

            <!-- Mouse Field -->
            {!! Form::label('mouse', trans('Mouse') . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-3">
                <select-check css-class="form-control" name-field="mouse" reduce-label="brand_name"
                    reduce-key="brand_name" name-resource="get-config-mouses" :value="dataForm">
                </select-check>
                <div class="invalid-feedback" v-if="dataErrors.mouse">
                    <p class="m-b-0" v-for="error in dataErrors.mouse">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Mouse Model Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('mouse_model', trans('Model') . ':', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-3">
                {!! Form::text('mouse_model', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.mouse_model }",
                    'v-model' => 'dataForm.mouse_model',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.mouse_model">
                    <p class="m-b-0" v-for="error in dataErrors.mouse_model">@{{ error }}</p>
                </div>
            </div>

            <!-- Mouse Serial Field -->
            {!! Form::label('mouse_serial', trans('Serial') . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-3">
                {!! Form::text('mouse_serial', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.mouse_serial }",
                    'v-model' => 'dataForm.mouse_serial',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.mouse_serial">
                    <p class="m-b-0" v-for="error in dataErrors.mouse_serial">@{{ error }}</p>
                </div>
            </div>
        </div>


        <!-- Mouse Value Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('mouse_value', trans('Mouse Value') . ':', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-3">
                {!! Form::text('mouse_value', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.mouse_value }",
                    'v-model' => 'dataForm.mouse_value',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.mouse_value">
                    <p class="m-b-0" v-for="error in dataErrors.mouse_value">@{{ error }}</p>
                </div>
            </div>

            <!-- Mouse Contract Number Field -->
            {!! Form::label('mouse_contract_number', trans('Contract Number') . ':', [
                'class' => 'col-form-label col-md-2',
            ]) !!}
            <div class="col-md-3">
                {!! Form::text('mouse_contract_number', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.mouse_contract_number }",
                    'v-model' => 'dataForm.mouse_contract_number',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.mouse_contract_number">
                    <p class="m-b-0" v-for="error in dataErrors.mouse_contract_number">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
</div> --}}
{{-- 
<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Configuración actual de hardware – Otros dispositivos</strong></div>
    </div>
    <div class="panel-body">
        <dynamic-list label-button-add="Agregar equipo" :data-list.sync="dataForm.configuration_other_equipments" :is-remove="true"
            :data-list-options="[
                { label: 'No. inventario', name: 'inventory_number', isShow: true },
                { label: 'Marca', name: 'mark', isShow: true },
                { label: 'Modelo', name: 'model', isShow: true },
                { label: 'Serial', name: 'serial', isShow: true },
                { label: 'Valor dispositivo', name: 'monitor_value', isShow: true },
                { label: 'No. Contrato', name: 'contract_number', isShow: true }
            ]"
            class-container="col-md-12" class-table="table table-bordered" :is-remove="false">
            <template #fields="scope">
                <!-- Other Equipment Inventory Number Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('inventory_number', trans('Inventory Number') . ':', [
                        'class' => 'col-form-label col-md-3',
                    ]) !!}
                    <div class="col-md-3">
                        {!! Form::text('inventory_number', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.inventory_number }",
                            'v-model' => 'scope.dataForm.inventory_number',
                        ]) !!}
                        <div class="invalid-feedback" v-if="dataErrors.inventory_number">
                            <p class="m-b-0" v-for="error in dataErrors.inventory_number">
                                @{{ error }}</p>
                        </div>
                    </div>

                    <!-- Other Equipment Mark Field -->
                    {!! Form::label('mark', trans('Mark') . ':', ['class' => 'col-form-label col-md-2']) !!}
                    <div class="col-md-3">
                        {!! Form::text('mark', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.mark }",
                            'v-model' => 'scope.dataForm.mark',
                        ]) !!}
                        <div class="invalid-feedback" v-if="dataErrors.mouse">
                            <p class="m-b-0" v-for="error in dataErrors.mouse">@{{ error }}</p>
                        </div>
                    </div>
                </div>

                <!-- Other Equipment Model Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('model', trans('Model') . ':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-3">
                        {!! Form::text('model', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.model }",
                            'v-model' => 'scope.dataForm.model',
                        ]) !!}
                        <div class="invalid-feedback" v-if="dataErrors.model">
                            <p class="m-b-0" v-for="error in dataErrors.model">
                                @{{ error }}</p>
                        </div>
                    </div>

                    <!-- Other Equipment Serial Field -->
                    {!! Form::label('serial', trans('Serial') . ':', ['class' => 'col-form-label col-md-2']) !!}
                    <div class="col-md-3">
                        {!! Form::text('serial', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.serial }",
                            'v-model' => 'scope.dataForm.serial',
                        ]) !!}
                        <div class="invalid-feedback" v-if="dataErrors.serial">
                            <p class="m-b-0" v-for="error in dataErrors.serial">
                                @{{ error }}</p>
                        </div>
                    </div>
                </div>


                <!-- Other Equipment Value Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('monitor_value', trans('Value') . ':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-3">
                        {!! Form::text('monitor_value', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.monitor_value }",
                            'v-model' => 'scope.dataForm.monitor_value',
                        ]) !!}
                        <div class="invalid-feedback" v-if="dataErrors.monitor_value">
                            <p class="m-b-0" v-for="error in dataErrors.monitor_value">
                                @{{ error }}</p>
                        </div>
                    </div>

                    <!-- Other Equipment Contract Number Field -->
                    {!! Form::label('contract_number', trans('Contract Number') . ':', [
                        'class' => 'col-form-label col-md-2',
                    ]) !!}
                    <div class="col-md-3">
                        {!! Form::text('contract_number', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.contract_number }",
                            'v-model' => 'scope.dataForm.contract_number',
                        ]) !!}
                        <div class="invalid-feedback" v-if="dataErrors.contract_number">
                            <p class="m-b-0" v-for="error in dataErrors.contract_number">
                                @{{ error }}</p>
                        </div>
                    </div>
                </div>

            </template>
        </dynamic-list>

    </div>
</div> --}}

<div class="panel" v-if="dataForm.asset_type == 'Computador' || dataForm.asset_type == 'Portátil' || dataForm.asset_type == 'Servidor'">
    <div class="panel-heading">
        <div class="panel-title"><strong>Software</strong></div>
    </div>
    <div class="panel-body">

        <!-- Operating System Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('operating_system', trans('Operating System') . ':', [
                'class' => 'col-form-label col-md-3',
            ]) !!}
            <div class="col-md-3">
                <select-check css-class="form-control" name-field="operating_system" reduce-label="name"
                    reduce-key="id" name-resource="config-operation-systems-activated" :value="dataForm">
                </select-check>
                <div class="invalid-feedback" v-if="dataErrors.operating_system">
                    <p class="m-b-0" v-for="error in dataErrors.operating_system">@{{ error }}</p>
                </div>
            </div>

            <!-- Operating System Version Field -->
            {!! Form::label('operating_system_version', trans('Versión') . ':', [
                'class' => 'col-form-label col-md-2',
            ]) !!}
            <div class="col-md-3">
                {!! Form::text('operating_system_version', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.operating_system_version }",
                    'v-model' => 'dataForm.operating_system_version',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.operating_system_version">
                    <p class="m-b-0" v-for="error in dataErrors.operating_system_version">@{{ error }}</p>
                </div>
            </div>
        </div>


        <!-- Operating System License Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('operating_system_license', trans('Operating System License') . ':', [
                'class' => 'col-form-label col-md-3',
            ]) !!}
            <div class="col-md-3">
                {!! Form::text('operating_system_license', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.operating_system_license }",
                    'v-model' => 'dataForm.operating_system_license',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.operating_system_license">
                    <p class="m-b-0" v-for="error in dataErrors.operating_system_license">@{{ error }}</p>
                </div>
            </div>

              <!-- Antivirus Field -->
              {!! Form::label('antivirus_version', trans('Versión ANTIVIRUS') . ':', ['class' => 'col-form-label col-md-2']) !!}
              <div class="col-md-3">
                  {!! Form::text('antivirus_version', null, [
                      ':class' => "{'form-control':true, 'is-invalid':dataErrors.antivirus_version }",
                      'v-model' => 'dataForm.antivirus_version',
                  ]) !!}
                  <div class="invalid-feedback" v-if="dataErrors.antivirus_version">
                      <p class="m-b-0" v-for="error in dataErrors.antivirus_version">@{{ error }}</p>
                  </div>
              </div>
        </div>

        <!-- Office Automation Field -->
        <div class="form-group row m-b-15">

            {!! Form::label('antivirus_agent_version', trans('Versión AGENTE ANTIVIRUS') . ':', [
                'class' => 'col-form-label col-md-3',
            ]) !!}
            <div class="col-md-3">
                {!! Form::text('antivirus_agent_version', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.antivirus_agent_version }",
                    'v-model' => 'dataForm.antivirus_agent_version',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.antivirus_agent_version">
                    <p class="m-b-0" v-for="error in dataErrors.antivirus_agent_version">@{{ error }}
                    </p>
                </div>
            </div>
            {!! Form::label('id_storage_status', trans('Estado Almacenamiento') . ':', [
                'class' => 'col-form-label col-md-2',
            ]) !!}
            <div class="col-md-3">
                <select-check css-class="form-control" name-field="id_storage_status" reduce-label="storage_status"
                reduce-key="id" name-resource="get-config-storage-statuses-active" :value="dataForm" :enable-search="true">
                </select-check>
                <div class="invalid-feedback" v-if="dataErrors.id_storage_status">
                    <p class="m-b-0" v-for="error in dataErrors.id_storage_status">@{{ error }}
                    </p>
                </div>
            </div>
            {{-- <!-- Office Automation Version Field -->
            {!! Form::label('office_automation_version', trans('Versión') . ':', [
                'class' => 'col-form-label col-md-2',
            ]) !!}
            <div class="col-md-3">
                {!! Form::text('office_automation_version', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.office_automation_version }",
                    'v-model' => 'dataForm.office_automation_version',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.office_automation_version">
                    <p class="m-b-0" v-for="error in dataErrors.office_automation_version">@{{ error }}
                    </p>
                </div>
            </div> --}}
        </div>


        <!-- Office Automation License Field -->
        <div class="form-group row m-b-15">
            
            {!! Form::label('id_office_version', trans('Version Office') . ':', [
                'class' => 'col-form-label col-md-3',
            ]) !!}
            <div class="col-md-3">
                <select-check css-class="form-control" name-field="id_office_version" reduce-label="office_version"
                reduce-key="id" name-resource="get-config-office-versions-active" :value="dataForm" :enable-search="true">
                </select-check>
                <div class="invalid-feedback" v-if="dataErrors.id_office_version">
                    <p class="m-b-0" v-for="error in dataErrors.id_office_version">@{{ error }}</p>
                </div>
            </div>
            {!! Form::label('office_automation_license', trans('Licencia Office') . ':', [
                'class' => 'col-form-label col-md-2',
            ]) !!}
            <div class="col-md-3">
                {!! Form::text('office_automation_license', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.office_automation_license }",
                    'v-model' => 'dataForm.office_automation_license',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.office_automation_license">
                    <p class="m-b-0" v-for="error in dataErrors.office_automation_license">@{{ error }}
                    </p>
                </div>
            </div>
            {{-- <!-- Antivirus Field -->
            {!! Form::label('antivirus', trans('Antivirus') . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-3">
                {!! Form::text('antivirus', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.antivirus }",
                    'v-model' => 'dataForm.antivirus',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.antivirus">
                    <p class="m-b-0" v-for="error in dataErrors.antivirus">@{{ error }}</p>
                </div>
            </div> --}}
        </div>


        <!-- Installed Product Field -->
        <div class="form-group row m-b-15">
            
            {!! Form::label('installed_product', trans('Installed Product') . ':', [
                'class' => 'col-form-label col-md-3',
            ]) !!}
            <div class="col-md-3">
                {!! Form::text('installed_product', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.installed_product }",
                    'v-model' => 'dataForm.installed_product',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.installed_product">
                    <p class="m-b-0" v-for="error in dataErrors.installed_product">@{{ error }}</p>
                </div>
            </div>


            <!-- Installed Product Version Field -->
            {!! Form::label('installed_product_version', trans('Versión') . ':', [
                'class' => 'col-form-label col-md-2',
            ]) !!}
            <div class="col-md-3">
                {!! Form::text('installed_product_version', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.installed_product_version }",
                    'v-model' => 'dataForm.installed_product_version',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.installed_product_version">
                    <p class="m-b-0" v-for="error in dataErrors.installed_product_version">@{{ error }}
                    </p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
            
            {!! Form::label('id_unnecessary_app', trans('Borrar aplicaciones no necesarios') . ':', [
                'class' => 'col-form-label col-md-3',
            ]) !!}
            <div class="col-md-3">
                <select-check css-class="form-control" name-field="id_unnecessary_app" reduce-label="unnecessary_app"
                reduce-key="id" name-resource="get-config-unnecessary-apps-active" :value="dataForm" :enable-search="true">
                </select-check>
                <div class="invalid-feedback" v-if="dataErrors.id_unnecessary_app">
                    <p class="m-b-0" v-for="error in dataErrors.id_unnecessary_app">@{{ error }}</p>
                </div>
            </div>

            
            <!-- Installed Product Version Field -->
            {!! Form::label('office_license_inventory', trans('Placa de Inventario Licencia Office') . ':', [
                'class' => 'col-form-label col-md-2',
            ]) !!}
            <div class="col-md-3">
                {!! Form::text('office_license_inventory', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.office_license_inventory }",
                    'v-model' => 'dataForm.office_license_inventory',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.office_license_inventory">
                    <p class="m-b-0" v-for="error in dataErrors.office_license_inventory">@{{ error }}
                    </p>
                </div>
            </div>
        </div>
        {{-- <!-- Browser Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('browser', trans('Browser') . ':', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-3">
                {!! Form::text('browser', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.browser }",
                    'v-model' => 'dataForm.browser',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.browser">
                    <p class="m-b-0" v-for="error in dataErrors.browser">@{{ error }}</p>
                </div>
            </div>

            <!-- Browser Version Field -->
            {!! Form::label('browser_version', trans('Versión') . ':', [
                'class' => 'col-form-label col-md-2',
            ]) !!}
            <div class="col-md-3">
                {!! Form::text('browser_version', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.browser_version }",
                    'v-model' => 'dataForm.browser_version',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.browser_version">
                    <p class="m-b-0" v-for="error in dataErrors.browser_version">@{{ error }}</p>
                </div>
            </div>
        </div> --}}


        {{-- <!-- Teamviewer Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('teamviewer', trans('Teamviewer') . ':', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-3">
                {!! Form::text('teamviewer', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.teamviewer }",
                    'v-model' => 'dataForm.teamviewer',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.teamviewer">
                    <p class="m-b-0" v-for="error in dataErrors.teamviewer">@{{ error }}</p>
                </div>
            </div>

            <!-- Other Field -->
            {!! Form::label('other', trans('Other') . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-3">
                {!! Form::text('other', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.other }",
                    'v-model' => 'dataForm.other',
                ]) !!}
                <div class="invalid-feedback" v-if="dataErrors.other">
                    <p class="m-b-0" v-for="error in dataErrors.other">@{{ error }}</p>
                </div>
            </div>
        </div> --}}

    </div>
</div>


<div class="panel" v-if="dataForm.asset_type == 'Computador' || dataForm.asset_type == 'Portátil' || dataForm.asset_type == 'Servidor'">
    <div class="panel-heading">
        <div class="panel-title"><strong>Información compra equipo</strong></div>
    </div>

    <div class="panel-body">
            <div class="form-group row m-b-15">
                {!! Form::label('date', trans('Numero de contrato') . ':', ['class' => 'col-form-label col-md-3']) !!}

                <div class="col-md-3">
                <select-check  
                    css-class="form-control" 
                    name-field="contract_number"
                    reduce-label="contract_number" 
                    name-resource="equipment-purchase"
                    :value="dataForm" 
                    :enable-search="true" 
                    reduce-key="id"
                    name-field-object="contract"
                ></select-check>
                </div>
                {!! Form::label('date', trans('Date') . ':', ['class' => 'col-form-label col-md-2']) !!}
                <div class="col-md-3">
                    <input-check 
                    prefix="otros"
                    :disabled="true"
                    name-field="date"
                    css-class="form-control" 
                    type-input="llenadoPorObjeto"
                    :key="dataForm.id"
                    :value="dataForm"
                    :value-recibido="['contract','date'] ?? date"
                    ></input-check>
                    <div class="invalid-feedback" v-if="dataErrors.date">
                        <p class="m-b-0" v-for="error in dataErrors.date">@{{ error }}</p>
                    </div>
                </div>
            </div>

            <div class="form-group row m-b-15">
                {!! Form::label('provider', trans('Provider') . ':', ['class' => 'col-form-label col-md-3']) !!}
                <div class="col-md-3">
                    <input-check 
                        prefix="otros"
                        :disabled="true"
                        name-field="provider"
                        css-class="form-control" 
                        type-input="llenadoPorObjeto"
                        :key="dataForm.contract_number"
                        :value="dataForm"
                        :value-recibido="['contract','provider']"
                        ></input-check>
                    <div class="invalid-feedback" v-if="dataErrors.provider">
                        <p class="m-b-0" v-for="error in dataErrors.provider">@{{ error }}</p>
                    </div>
                </div>

                {!! Form::label('warranty_in_years', trans('Waranty years') . ':', ['class' => 'col-form-label col-md-2']) !!}
                <div class="col-md-3">
                    <input-check 
                        prefix="otros"
                        :disabled="true"
                        name-field="warranty_in_years"
                        css-class="form-control" 
                        type-input="llenadoPorObjeto"
                        :key="dataForm.contract_number"
                        :value="dataForm"
                        :value-recibido="['contract','warranty_in_years']"
                        ></input-check>
                    <div class="invalid-feedback" v-if="dataErrors.warranty_in_years">
                        <p class="m-b-0" v-for="error in dataErrors.warranty_in_years">@{{ error }}</p>
                    </div>
                </div>
            </div>

            <div class="form-group row m-b-15">
                {!! Form::label('contract_total_value', trans('Contract Total Value') . ':', ['class' => 'col-form-label col-md-3']) !!}
                <div class="col-md-3">
                    <input-check 
                    prefix="otros"
                    :disabled="true"
                    name-field="contract_total_value"
                    css-class="form-control" 
                    type-input="llenadoPorObjeto"
                    :key="dataForm.contract_number"
                    :value="dataForm"
                    :value-recibido="['contract','contract_total_value']"
                    ></input-check>
                    <div class="invalid-feedback" v-if="dataErrors.contract_total_value">
                        <p class="m-b-0" v-for="error in dataErrors.contract_total_value">@{{ error }}</p>
                    </div>
                </div>

                {!! Form::label('warranty_termination_date', trans('Waranty Termination Date') . ':', ['class' => 'col-form-label col-md-2']) !!}
                <div class="col-md-3">
                    <input-check 
                    prefix="otros"
                    :disabled="true"
                    name-field="warranty_termination_date"
                    css-class="form-control" 
                    type-input="llenadoPorObjeto"
                    :key="dataForm.contract_number"
                    :value="dataForm"
                    :value-recibido="['contract','warranty_termination_date'] ?? warranty_termination_date"
                    ></input-check>
                    <div class="invalid-feedback" v-if="dataErrors.warranty_termination_date">
                        <p class="m-b-0" v-for="error in dataErrors.warranty_termination_date">@{{ error }}</p>
                    </div>
                </div>
            </div>
    </div>
</div>

{{--     
<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Información compra equipo</strong></div>
    </div>
    <div class="panel-body">
        <dynamic-list class-button-add="d-none"
            :data-list.sync="dataForm.equipment_purchase_details" :is-remove="true"
            :data-list-options="[
                { label: 'No. contrato', name: 'contract_number', isShow: true },
                { label: 'Fecha', name: 'date', isShow: true },
                { label: 'Proveedor', name: 'provider', isShow: true },
                { label: 'Garantía años', name: 'warranty_in_years', isShow: true },
                { label: 'Valor total contrato', name: 'contract_total_value',
                isShow: true },
                { label: 'Estado', name: 'status', isShow: true },
                { label: 'Fecha terminación garantía', name: 'warranty_termination_date',
                    isShow: true }
            ]"
            class-container="col-md-12" class-table="table table-bordered" :is-remove="false">
            <template #fields="scope">
                <!-- Equipment Purchase Details Contract Number Field -->
                <div class="form-group row m-b-15">


                    <select-check  
                        css-class="form-control" 
                        name-field="contract_number"
                        :reduce-label="['id','contract_number']" 
                        name-resource="equipment-purchase"
                        :value="scope.dataForm" 
                        :enable-search="true" 
                        reduce-key="id"
                        name-field-object="contract"
                        ref-select-check="descripcionRef"
                    ></select-check>

                    
                    {{-- <select-check 
                        css-class="form-control"
                        name-field="contract_number"
                        reduce-label="id"
                        name-resource="equipment-purchase"
                        :value="scope.dataForm"
                        :is-required="false"
                        :enable-search="true"
                        name-field-object="contract"
                        >
                    </select-check > --}}
                    {{-- {!! Form::label('contract_number', trans('Contract Number') . ':', [
                        'class' => 'col-form-label col-md-3',
                    ]) !!}
                    <div class="col-md-3">
                        {!! Form::text('contract_number', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.contract_number }",
                            'v-model' => 'dataForm.contract_number'
                        ]) !!}
                        <div class="invalid-feedback" v-if="dataErrors.contract_number">
                            <p class="m-b-0" v-for="error in dataErrors.contract_number">
                                @{{ error }}</p>
                        </div>
                    </div>
                    <!-- Equipment Purchase Details Date Field -->
                    {!! Form::label('date', trans('Date') . ':', ['class' => 'col-form-label col-md-2']) !!}
                    <div class="col-md-3">
                        <input type="date" class="form-control"
                            v-model="scope.dataForm.date"
                            >
                        <div class="invalid-feedback" v-if="dataErrors.date">
                            <p class="m-b-0" v-for="error in dataErrors.date">
                                @{{ error }}</p>
                        </div>
                    </div>
                </div>

                <!-- Equipment Purchase Details Provider Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('provider', trans('Provider') . ':', [
                        'class' => 'col-form-label col-md-3',
                    ]) !!}
                    <div class="col-md-3">

                        <input-check 
                        prefix="otros"
                        :disabled="true"
                        name-field="provider"
                        css-class="form-control" 
                        type-input="llenadoPorObjeto"
                        :value="scope.dataForm"
                        :value-recibido="['contract','provider']"
                        ></input-check>
                        <div class="invalid-feedback" v-if="dataErrors.provider">
                            <p class="m-b-0" v-for="error in dataErrors.provider">
                                @{{ error }}</p>
                        </div>
                    </div>

                    <!-- Equipment Purchase Details Waranty In Years Field -->
                    {!! Form::label('warranty_in_years', trans('Waranty years') . ':', [
                        'class' => 'col-form-label col-md-2',
                    ]) !!}
                    <div class="col-md-3">
                        {!! Form::text('warranty_in_years', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.warranty_in_years }",
                            'v-model' => 'scope.dataForm.warranty_in_years',
                            'disabled' => true
                        ]) !!}
                        <div class="invalid-feedback" v-if="dataErrors.warranty_in_years">
                            <p class="m-b-0" v-for="error in dataErrors.warranty_in_years">
                                @{{ error }}</p>
                        </div>
                    </div>
                </div>


                <!-- Equipment Purchase Details Contract Total Value Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('contract_total_value', trans('Contract Total Value') . ':', [
                        'class' => 'col-form-label col-md-3',
                    ]) !!}
                    <div class="col-md-3">
                        {!! Form::text('contract_total_value', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.contract_total_value }",
                            'v-model' => 'scope.dataForm.contract_total_value',
                            'disabled' => true
                        ]) !!}
                        <div class="invalid-feedback"
                            v-if="dataErrors.contract_total_value">
                            <p class="m-b-0"
                                v-for="error in dataErrors.contract_total_value">
                                @{{ error }}</p>
                        </div>
                    </div>

                    {!! Form::label('warranty_termination_date', trans('Waranty Termination Date') . ':', [
                        'class' => 'col-form-label col-md-3',
                    ]) !!}
                    <div class="col-md-3">
                        <input type="date" class="form-control"
                            v-model="scope.dataForm.warranty_termination_date"
                            :disabled="disabled"
                            >
                        <div class="invalid-feedback"
                            v-if="dataErrors.warranty_termination_date">
                            <p class="m-b-0"
                                v-for="error in dataErrors.warranty_termination_date">
                                @{{ error }}</p>
                        </div>
                    </div>
                    {{-- <!-- Equipment Purchase Details Status Field -->
                    {!! Form::label('status', trans('Status') . ':', [
                        'class' => 'col-form-label col-md-2',
                    ]) !!}
                    <div class="col-md-3">
                        <select class="form-control" name="status"
                            v-model="scope.dataForm.status">
                            <option value="Activo">Activo</option>
                            <option value="Bodega">Bodega</option>
                            <option value="Dado de baja">Dado de baja</option>
                        </select>
                        <div class="invalid-feedback" v-if="dataErrors.status">
                            <p class="m-b-0" v-for="error in dataErrors.status">
                                @{{ error }}</p>
                        </div>
                    </div>
                </div>

            </template>
        </dynamic-list>
    </div>
</div> --}}
