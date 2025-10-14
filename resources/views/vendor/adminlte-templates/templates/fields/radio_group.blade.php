<!-- {{ $fieldTitle }} Field -->
<div class="form-group row m-b-10">
    @{!! Form::label('{{ $fieldName }}', trans('{{ $fieldTitle }}').':', ['class' => 'col-md-3 col-form-label']) !!}
    <div class="col-md-9">
       {!! $radioButtons !!}
    </div>
</div>
