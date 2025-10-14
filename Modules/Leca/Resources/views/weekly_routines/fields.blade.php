<!-- Campo de funcionario -->
<div class="form-group row m-b-15">
    {!! Form::label('users_id', trans('Funcionarios').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <select-check
        css-class="form-control"
        name-field="users_id"
        reduce-label="name"
        reduce-key="id"
        :name-resource="'get-oficcials-montly-routines/'+dataForm.lc_monthly_routines_id"
        :value="dataForm"
        :is-required="true">
        </select-check>
        <small>Seleccione el punto de muestra.</small>
    </div>
</div>

<!-- Routine End Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('non_business_days', 'Dias no laborales'.':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <date-picker-leca
            :key="keyRefresh"
            :value="dataForm"
            name-field="non_business_days"
            mode="multiple"
            :input-props="{required: true}"
        >
        </date-picker-leca>
        <small>Seleccione los dias no habiles</small>
        <div class="invalid-feedback" v-if="dataErrors.non_business_days">
            <p class="m-b-0" v-for="error in dataErrors.non_business_days">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Autocomplete que obtiene los usuarios que tengan el rol de personal de apoyo para remplazo -->
<div class="form-group row m-b-15">
    {!! Form::label('contractor_id','Personal de apoyo'.':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <autocomplete 
        :is-update="isUpdate"
        name-prop="name" 
        :value-default="dataForm.users_contract"
        name-field="contractor_id" 
        :value="dataForm"
        name-resource="get-officials-replacement"
        css-class="form-control"
        :name-labels-display="['name']" 
        reduce-key="id" 
        :is-required="true"
        :key="keyRefresh">
        </autocomplete>
        <small>Ingrese el nombre del contratista</small>
    </div>
</div>