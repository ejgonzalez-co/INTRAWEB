<!-- {{ $fieldTitle }} Field -->
<div class="form-group col-sm-6">
    @{!! Form::label('{{ $fieldName }}', '{{ $fieldTitle }}:') !!}
    @{!! Form::file('{{ $fieldName }}') !!}
</div>
<div class="clearfix"></div>