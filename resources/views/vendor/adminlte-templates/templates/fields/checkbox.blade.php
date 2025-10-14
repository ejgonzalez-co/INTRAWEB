<!--  {{ $fieldTitle }} Field -->
<div class="form-group row m-b-15">
    @{!! Form::label('{{ $fieldName }}', trans('{{ $fieldTitle }}').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- {{ $fieldName }} switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="{{ $fieldName }}" id="{{ $fieldName }}"  v-model="dataForm.{{ $fieldName }}">
        <label for="{{ $fieldName }}"></label>
        <small>@@lang('Select the') @{{ `@@lang('{{ $fieldTitle }}')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.{{ $fieldName }}">
            <p class="m-b-0" v-for="error in dataErrors.{{ $fieldName }}">@{{ error }}</p>
        </div>
    </div>
</div>
