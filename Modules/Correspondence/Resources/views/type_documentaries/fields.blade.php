<div class="row">
    <!-- Name Field -->
    <div class="form-group row m-b-15 col-sm-6">
        {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
        <div class="col-md-9">
            {!! Form::text('name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.name }", 'v-model' => 'dataForm.name', 'required' => true]) !!}
            <small>@lang('Enter the') @{{ `@lang('Name')` | lowercase }}</small>
            <div class="invalid-feedback" v-if="dataErrors.name">
                <p class="m-b-0" v-for="error in dataErrors.name">@{{ error }}</p>
            </div>
        </div>
    </div>



    <!--  State Field -->
    <div class="form-group row m-b-15 col-sm-6">
        {!! Form::label('state', trans('State').':', ['class' => 'col-form-label col-md-3 required']) !!}
        <div class="col-md-9">
            <select-check
                css-class="form-control"
                name-field="state"
                reduce-label="name"
                reduce-key="id"
                name-resource="get-constants/states_documentary_types"
                :value="dataForm"
                :is-required="true">
            </select-check>
            {{-- <small>@lang('Enter the') @{{ `@lang('Finish Pqr')` | lowercase }}</small> --}}
            <div class="invalid-feedback" v-if="dataErrors.state">
                <p class="m-b-0" v-for="error in dataErrors.state">@{{ error }}</p>
            </div>
        </div>
    </div>

</div>

<div class="row">
    <!-- prefix Field -->
    <div class="form-group row m-b-15 col-sm-6">
        {!! Form::label('prefix', trans('prefix').':', ['class' => 'col-form-label col-md-3 required']) !!}
        <div class="col-md-9">
            {!! Form::text('prefix', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.prefix }", 'v-model' => 'dataForm.prefix', 'required' => true]) !!}
            <small>@lang('Enter the') @{{ `@lang('prefix')` | lowercase }}</small>
            <div class="invalid-feedback" v-if="dataErrors.prefix">
                <p class="m-b-0" v-for="error in dataErrors.prefix">@{{ error }}</p>
            </div>
        </div>
    </div>
</div>
