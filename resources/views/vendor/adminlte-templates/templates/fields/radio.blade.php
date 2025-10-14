<div class="radio radio-css radio-inline">
    @{!! Form::radio('{{ $fieldName }}', "$VALUE$", null) !!}
    <label for="{{ $fieldName }}">$LABEL$</label>
    <small>@@lang('Select the') @{{ `@@lang('{{ $fieldTitle }}')` | lowercase }}</small>
    <div class="invalid-feedback" v-if="dataErrors.{{ $fieldName }}">
        <p class="m-b-0" v-for="error in dataErrors.{{ $fieldName }}">@{{ error }}</p>
    </div>
</div>
