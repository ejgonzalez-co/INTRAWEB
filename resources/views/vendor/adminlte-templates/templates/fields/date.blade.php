<!-- {{ $fieldTitle }} Field -->
<div class="form-group row m-b-15">
   @{!! Form::label('{{ $fieldName }}', trans('{{ $fieldTitle }}').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <date-picker
            :value="dataForm"
            name-field="{{ $fieldName }}"
            :input-props="{required: true}"
        >
        </date-picker>
        <small>@@lang('Enter the') @@{{ `@@lang('{!! $fieldTitle !!}')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.{{ $fieldName }}">
            <p class="m-b-0" v-for="error in dataErrors.{{ $fieldName }}">@{{ error }}</p>
        </div>
    </div>
</div>
