<!-- 'Boolean {{ $fieldTitle }} Field' checked by default -->
<div class="form-group row m-b-15">
    @{!! Form::label('{{ $fieldName }}', '{{ $fieldTitle }}:', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9 pb-2 pt-2">
        <div class="custom-control custom-checkbox ">
           @{!! Form::checkbox('{{ $fieldName }}', 1, true, ['class' => 'custom-control-input', 'id' => '{{ $fieldTitle }}', 'v-model' => 'dataForm.{{ $fieldName }}', 'required' => true]) !!}
            <!-- remove {, true} to make it unchecked by default -->
            <label class="custom-control-label" for="{{ $fieldTitle }}">Check this custom checkbox</label>
        </div>
    </div>
</div>
