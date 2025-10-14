{{-- <!-- Lc Monthly Routines Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('lc_monthly_routines_id', trans('Lc Monthly Routines Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('lc_monthly_routines_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.lc_monthly_routines_id }", 'v-model' => 'dataForm.lc_monthly_routines_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Lc Monthly Routines Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.lc_monthly_routines_id">
            <p class="m-b-0" v-for="error in dataErrors.lc_monthly_routines_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Users Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('users_id', trans('Users Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('users_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.users_id }", 'v-model' => 'dataForm.users_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Users Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.users_id">
            <p class="m-b-0" v-for="error in dataErrors.users_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- User Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('user_name', trans('User Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('user_name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.user_name }", 'v-model' => 'dataForm.user_name', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('User Name')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.user_name">
            <p class="m-b-0" v-for="error in dataErrors.user_name">@{{ error }}</p>
        </div>
    </div>
</div> --}}


<!-- Autocomplete de los funcionarios -->
<div class="form-group row m-b-15">
    {!! Form::label('users_id',trans('Lc Officials Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <autocomplete 
        :is-update="isUpdate"
        name-prop="name" 
        :value-default="dataForm.users"
        name-field="users_id" 
        :value="dataForm"
        name-resource="get-officials-monthly"
        css-class="form-control"
        :name-labels-display="['name']" 
        reduce-key="id" 
        :is-required="true"
        name-field-object="objet_oficial_monthly">
        </autocomplete>
        <small>Ingrese el nombre del funcionario</small>
    </div>
</div>

<div class="col-md-6">
    <div class="panel">
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Ensayos</strong></h4>
        </div>
        <div class="alert hljs-wrapper fade show">
            <!--<span class="close" data-dismiss="alert">×</span>-->
            <i class="fa fa-info fa-2x pull-left m-r-10 m-t-5"></i>
            <p class="m-b-0">Seleccione Los tipos de ensayo que ejercerá el analista</p>
        </div>
        <div class="panel-body">
            <!-- Checks Roles -->
            <select-check-leca
                css-class="custom-control-input"
                name-field="lc_list_trials" reduce-label="name"
                name-resource="get-list-trials" :value="dataForm"
                :is-check="true" >
            </select-check-leca>
        </div>
    </div>
</div>
<div class="panel" style="border: 200px; padding: 15px;" v-if="dataForm.id>0">
    <!-- Observation Field -->
    <div class="form-group row m-b-15">
        {!! Form::label('observation', trans('Observation') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
        <div class="col-md-7">
            {!! Form::textarea('observation', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observation }", 'v-model' => 'dataForm.observation', 'required' => true]) !!}
            <small>@lang('Enter the') @{{ `@lang('Observation')` | lowercase }}</small>
            <div class="invalid-feedback" v-if="dataErrors.observation">
                <p class="m-b-0" v-for="error in dataErrors.observation">@{{ error }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Dependence Id Field -->
{{-- <div class="form-group row m-b-15">
    {!! Form::label('lc_list_trials_id', trans('types_of_routines').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <select-check
        css-class="form-control"
        name-field="lc_list_trials_id"
        reduce-label="name"
        reduce-key="id"
        name-resource="get-list-trials"
        :value="dataForm"
        :is-required="true">
        </select-check>
        <div class="invalid-feedback" v-if="dataErrors.lc_list_trials_id">
            <p class="m-b-0" v-for="error in dataErrors.lc_list_trials_id">@{{ error }}</p>
        </div>
    </div>
</div> --}}