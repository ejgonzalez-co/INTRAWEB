<!-- {{ $fieldTitle }} Field -->
<div class="form-group row m-b-15">
    @{!! Form::label('{{ $fieldName }}', trans('{{ $fieldTitle }}').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        @{!! Form::password('{{ $fieldName }}', [':class' => "{'form-control':true, 'is-invalid':dataErrors.{{ $fieldName }} }", 'v-model' => 'dataForm.{{ $fieldName }}', 'required' => true]) !!}
        <small>@@lang('Enter the') @@{{ `@@lang('{!! $fieldTitle !!}')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.{{ $fieldName }}">
            <p class="m-b-0" v-for="error in dataErrors.{{ $fieldName }}">@{{ error }}</p>
        </div>
    </div>
</div>
