<!-- Autocomplete de los funcionarios -->
<div class="form-group row m-b-15">
    {!! Form::label('users_id',trans('Lc Officials Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <autocomplete 
        name-prop="name" 
        name-field="users_id" 
        :value="dataForm"
        name-resource="get-officials"
        css-class="form-control"
        :name-labels-display="['name']" 
        reduce-key="id" 
        :is-required="true">
        </autocomplete>
        <small>Ingrese el nombre del funcionario</small>
    </div>
</div>

<!-- Dependence Id Field -->
<div class="form-group row m-b-15">
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
</div>