@if (Auth::user()->hasRole('Usuario TIC'))
    <div class="">
        <div class="panel">
            <div class="panel-heading">
                <div class="panel-title"><strong>Información del equipo</strong></div>
            </div>
            <div class="panel-body">
                <!-- Equipment Type Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('equipment_type', trans('Equipment type') . ':', [
                        'class' => 'col-form-label col-md-2 required',
                    ]) !!}
                    <div class="col-md-4">
                        {!! Form::text('equipment_type', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.equipment_type }",
                            'v-model' => 'dataForm.equipment_type',
                            'required' => true,
                        ]) !!}
                        <div class="invalid-feedback" v-if="dataErrors.equipment_type">
                            <p class="m-b-0" v-for="error in dataErrors.equipment_type">@{{ error }}</p>
                        </div>
                    </div>

                    <!-- Equipment Mark Field -->
                    {!! Form::label('equipment_mark', trans('Equipment mark') . ':', [
                        'class' => 'col-form-label col-md-2 required',
                    ]) !!}
                    <div class="col-md-4">
                        {!! Form::text('equipment_mark', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.equipment_mark }",
                            'v-model' => 'dataForm.equipment_mark',
                            'required' => true,
                        ]) !!}
                        <div class="invalid-feedback" v-if="dataErrors.equipment_mark">
                            <p class="m-b-0" v-for="error in dataErrors.equipment_mark">@{{ error }}</p>
                        </div>
                    </div>
                </div>

                <!-- Equipment Model Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('equipment_model', trans('Equipment model') . ':', [
                        'class' => 'col-form-label col-md-2 required',
                    ]) !!}
                    <div class="col-md-4">
                        {!! Form::text('equipment_model', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.equipment_model }",
                            'v-model' => 'dataForm.equipment_model',
                            'required' => true,
                        ]) !!}
                        <div class="invalid-feedback" v-if="dataErrors.equipment_model">
                            <p class="m-b-0" v-for="error in dataErrors.equipment_model">@{{ error }}</p>
                        </div>
                    </div>

                    <!-- Equipment Serial Field -->
                    {!! Form::label('equipment_serial', trans('Serial') . ':', [
                        'class' => 'col-form-label col-md-2 required',
                    ]) !!}
                    <div class="col-md-4">
                        {!! Form::text('equipment_serial', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.equipment_serial }",
                            'v-model' => 'dataForm.equipment_serial',
                            'required' => true,
                        ]) !!}
                        <div class="invalid-feedback" v-if="dataErrors.equipment_serial">
                            <p class="m-b-0" v-for="error in dataErrors.equipment_serial">@{{ error }}</p>
                        </div>
                    </div>
                </div>

                <!-- Inventory Plate Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('inventory_plate', trans('Inventory Plate') . ':', [
                        'class' => 'col-form-label col-md-2 required',
                    ]) !!}
                    <div class="col-md-4">
                        {!! Form::text('inventory_plate', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.inventory_plate }",
                            'v-model' => 'dataForm.inventory_plate',
                            'required' => true,
                        ]) !!}
                        <div class="invalid-feedback" v-if="dataErrors.inventory_plate">
                            <p class="m-b-0" v-for="error in dataErrors.inventory_plate">@{{ error }}</p>
                        </div>
                    </div>
                    <!-- Inventory Manager Field -->
                    {!! Form::label('inventory_manager', trans('In the inventory of') . ':', [
                        'class' => 'col-form-label col-md-2 required',
                    ]) !!}
                    <div class="col-md-4">
                        {!! Form::text('inventory_manager', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.inventory_manager }",
                            'v-model' => 'dataForm.inventory_manager',
                            'required' => true,
                        ]) !!}
                        <div class="invalid-feedback" v-if="dataErrors.inventory_manager">
                            <p class="m-b-0" v-for="error in dataErrors.inventory_manager">@{{ error }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endif

@if (Auth::user()->hasRole('Administrador TIC'))
    <div class="">
        <div class="panel">
            <div class="panel-heading">
                <div class="panel-title"><strong>Asignación del funcionario</strong></div>
            </div>
            <div class="panel-body">
                <div class="form-group row m-b-15">
                    <label for="" class="col-form-label col-md-2 required">Nombre de usuario</label>
                    <div class="col-md-4">
                        <select-check
                        css-class="form-control"
                        name-field="id_staff_member"
                        reduce-label="name"
                        reduce-key="id"
                        name-resource="tics-users"
                        :value="dataForm"
                        :key="keyRefresh"
                        :enable-search="true"
                        >
                        </select-check>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel">
            <div class="panel-heading">
                <div class="panel-title"><strong>Información del equipo</strong></div>
            </div>
            <div class="panel-body">
                <!-- Equipment Type Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('equipment_type', trans('Equipment type') . ':', [
                        'class' => 'col-form-label col-md-2 required',
                    ]) !!}
                    <div class="col-md-4">
                        {!! Form::text('equipment_type', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.equipment_type }",
                            'v-model' => 'dataForm.equipment_type',
                            'required' => true,
                        ]) !!}
                        <div class="invalid-feedback" v-if="dataErrors.equipment_type">
                            <p class="m-b-0" v-for="error in dataErrors.equipment_type">@{{ error }}</p>
                        </div>
                    </div>

                    <!-- Equipment Mark Field -->
                    {!! Form::label('equipment_mark', trans('Equipment mark') . ':', [
                        'class' => 'col-form-label col-md-2 required',
                    ]) !!}
                    <div class="col-md-4">
                        {!! Form::text('equipment_mark', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.equipment_mark }",
                            'v-model' => 'dataForm.equipment_mark',
                            'required' => true,
                        ]) !!}
                        <div class="invalid-feedback" v-if="dataErrors.equipment_mark">
                            <p class="m-b-0" v-for="error in dataErrors.equipment_mark">@{{ error }}</p>
                        </div>
                    </div>
                </div>

                <!-- Equipment Model Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('equipment_model', trans('Equipment model') . ':', [
                        'class' => 'col-form-label col-md-2 required',
                    ]) !!}
                    <div class="col-md-4">
                        {!! Form::text('equipment_model', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.equipment_model }",
                            'v-model' => 'dataForm.equipment_model',
                            'required' => true,
                        ]) !!}
                        <div class="invalid-feedback" v-if="dataErrors.equipment_model">
                            <p class="m-b-0" v-for="error in dataErrors.equipment_model">@{{ error }}</p>
                        </div>
                    </div>

                    <!-- Equipment Serial Field -->
                    {!! Form::label('equipment_serial', trans('Serial') . ':', [
                        'class' => 'col-form-label col-md-2 required',
                    ]) !!}
                    <div class="col-md-4">
                        {!! Form::text('equipment_serial', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.equipment_serial }",
                            'v-model' => 'dataForm.equipment_serial',
                            'required' => true,
                        ]) !!}
                        <div class="invalid-feedback" v-if="dataErrors.equipment_serial">
                            <p class="m-b-0" v-for="error in dataErrors.equipment_serial">@{{ error }}</p>
                        </div>
                    </div>
                </div>

                <!-- Inventory Plate Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('inventory_plate', trans('Inventory Plate') . ':', [
                        'class' => 'col-form-label col-md-2 required',
                    ]) !!}
                    <div class="col-md-4">
                        {!! Form::text('inventory_plate', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.inventory_plate }",
                            'v-model' => 'dataForm.inventory_plate',
                            'required' => true,
                        ]) !!}
                        <div class="invalid-feedback" v-if="dataErrors.inventory_plate">
                            <p class="m-b-0" v-for="error in dataErrors.inventory_plate">@{{ error }}</p>
                        </div>
                    </div>
                    <!-- Inventory Manager Field -->
                    {!! Form::label('inventory_manager', trans('In the inventory of') . ':', [
                        'class' => 'col-form-label col-md-2 required',
                    ]) !!}
                    <div class="col-md-4">
                        {!! Form::text('inventory_manager', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.inventory_manager }",
                            'v-model' => 'dataForm.inventory_manager',
                            'required' => true,
                        ]) !!}
                        <div class="invalid-feedback" v-if="dataErrors.inventory_manager">
                            <p class="m-b-0" v-for="error in dataErrors.inventory_manager">@{{ error }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="panel">
            <div class="panel-heading">
                <div class="panel-title"><strong>Asignación del funcionario Técnico</strong></div>
            </div>
            <div class="panel-body">
                <div class="form-group row m-b-15">
                    <label for="" class="col-form-label col-md-2 required">Nombre Técnico</label>
                    <div class="col-md-4">
                        <select-check
                        css-class="form-control"
                        name-field="technician_id"
                        reduce-label="name"
                        reduce-key="id"
                        name-resource="technicians"
                        :value="dataForm"
                        :key="keyRefresh"
                        :enable-search="true"
                        >
                        </select-check>
                    </div>

                    <label for="" class="col-form-label col-md-2 required">Fecha de vencimiento</label>
                    <div class="col-md-4">
                        <date-picker :value="dataForm" name-field="expiration_date"
                        :input-props="{ required: true }">
                        </date-picker>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if (Auth::user()->hasRole('Soporte TIC') || Auth::user()->hasRole('Revisor concepto técnico TIC') || Auth::user()->hasRole('Aprobación concepto técnico TIC'))
    <div class="">
        <div class="panel">
            <div class="panel-heading">
                <div class="panel-title"><strong>Información del equipo</strong></div>
            </div>
            <div class="panel-body">
                <!-- Equipment Type Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('equipment_type', trans('Equipment type') . ':', [
                        'class' => 'col-form-label col-md-2 required',
                    ]) !!}
                    <div class="col-md-4">
                        {!! Form::text('equipment_type', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.equipment_type }",
                            'v-model' => 'dataForm.equipment_type',
                            'required' => true,
                        ]) !!}
                        <div class="invalid-feedback" v-if="dataErrors.equipment_type">
                            <p class="m-b-0" v-for="error in dataErrors.equipment_type">@{{ error }}</p>
                        </div>
                    </div>

                    <!-- Equipment Mark Field -->
                    {!! Form::label('equipment_mark', trans('Equipment mark') . ':', [
                        'class' => 'col-form-label col-md-2 required',
                    ]) !!}
                    <div class="col-md-4">
                        {!! Form::text('equipment_mark', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.equipment_mark }",
                            'v-model' => 'dataForm.equipment_mark',
                            'required' => true,
                        ]) !!}
                        <div class="invalid-feedback" v-if="dataErrors.equipment_mark">
                            <p class="m-b-0" v-for="error in dataErrors.equipment_mark">@{{ error }}</p>
                        </div>
                    </div>
                </div>

                <!-- Equipment Model Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('equipment_model', trans('Equipment model') . ':', [
                        'class' => 'col-form-label col-md-2 required',
                    ]) !!}
                    <div class="col-md-4">
                        {!! Form::text('equipment_model', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.equipment_model }",
                            'v-model' => 'dataForm.equipment_model',
                            'required' => true,
                        ]) !!}
                        <div class="invalid-feedback" v-if="dataErrors.equipment_model">
                            <p class="m-b-0" v-for="error in dataErrors.equipment_model">@{{ error }}</p>
                        </div>
                    </div>

                    <!-- Equipment Serial Field -->
                    {!! Form::label('equipment_serial', trans('Serial') . ':', [
                        'class' => 'col-form-label col-md-2 required',
                    ]) !!}
                    <div class="col-md-4">
                        {!! Form::text('equipment_serial', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.equipment_serial }",
                            'v-model' => 'dataForm.equipment_serial',
                            'required' => true,
                        ]) !!}
                        <div class="invalid-feedback" v-if="dataErrors.equipment_serial">
                            <p class="m-b-0" v-for="error in dataErrors.equipment_serial">@{{ error }}</p>
                        </div>
                    </div>
                </div>

                <!-- Inventory Plate Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('inventory_plate', trans('Inventory Plate') . ':', [
                        'class' => 'col-form-label col-md-2 required',
                    ]) !!}
                    <div class="col-md-4">
                        {!! Form::text('inventory_plate', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.inventory_plate }",
                            'v-model' => 'dataForm.inventory_plate',
                            'required' => true,
                        ]) !!}
                        <div class="invalid-feedback" v-if="dataErrors.inventory_plate">
                            <p class="m-b-0" v-for="error in dataErrors.inventory_plate">@{{ error }}</p>
                        </div>
                    </div>
                    <!-- Inventory Manager Field -->
                    {!! Form::label('inventory_manager', trans('In the inventory of') . ':', [
                        'class' => 'col-form-label col-md-2 required',
                    ]) !!}
                    <div class="col-md-4">
                        {!! Form::text('inventory_manager', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.inventory_manager }",
                            'v-model' => 'dataForm.inventory_manager',
                            'required' => true,
                        ]) !!}
                        <div class="invalid-feedback" v-if="dataErrors.inventory_manager">
                            <p class="m-b-0" v-for="error in dataErrors.inventory_manager">@{{ error }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="panel">
            <div class="panel-heading">
                <div class="panel-title"><strong>Concepto técnico</strong></div>
            </div>
            <div class="panel-body">
                <div class="form-group row m-b-15">
                    <div class="col-md-6">
                        <textarea class="form-control" rows="10" v-model="dataForm.technical_concept" required></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-heading">
                <div class="panel-title"><strong>Observaciones</strong></div>
            </div>
            <div class="panel-body">
                <div class="form-group row m-b-15">
                    <div class="col-md-6">
                        <textarea class="form-control" rows="10" v-model="dataForm.observations"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-heading">
                <div class="panel-title"><strong>Adjuntos</strong></div>
            </div>
            <div class="panel-body">
                <div class="form-group row m-b-15">
                    <div class="col-md-12">
                        <input-file :file-name-real="true":value="dataForm" name-field="url_attachments" :max-files="8" :max-filesize="11"
                        file-path="public/technical_concepts/url_attachments"
                        message="Arrastre o seleccione las imágenes"
                        help-text="Agregue aquí máximo 8 imágenes o pdf. Peso máximo 5MB."
                        :is-update="isUpdate"
                        accepted-files=".jpeg,.jpg,.png,.pdf">
                        </input-file>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endif