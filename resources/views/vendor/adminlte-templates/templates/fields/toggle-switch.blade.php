<!-- 'bootstrap / Toggle Switch {{ $fieldTitle }} Field' -->
<div class="form-group col-sm-6">
        @{!! Form::label('{{ $fieldName }}', '{{ $fieldTitle }}:') !!}
        <label class="checkbox-inline">
            @{!! Form::hidden('{{ $fieldName }}', 0) !!}
            @{!! Form::checkbox('{{ $fieldName }}', 1, null,  ['data-toggle' => 'toggle']) !!}
        </label>
    </div>
    