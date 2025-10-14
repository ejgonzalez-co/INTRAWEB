<!-- Question Number Field -->
<div class="form-group row m-b-15">
    {!! Form::label('question_number', trans('Question Number').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('question_number', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.question_number }", 'v-model' => 'dataForm.question_number', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Question Number')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.question_number">
            <p class="m-b-0" v-for="error in dataErrors.question_number">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Question Field -->
<div class="form-group row m-b-15">
    {!! Form::label('question', trans('Question').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('question', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.question }", 'v-model' => 'dataForm.question', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Question')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.question">
            <p class="m-b-0" v-for="error in dataErrors.question">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Answer Option Field -->
<div class="form-group row m-b-15">
    {!! Form::label('answer_option', trans('Answer Option').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('answer_option', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.answer_option }", 'v-model' => 'dataForm.answer_option', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Answer Option')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.answer_option">
            <p class="m-b-0" v-for="error in dataErrors.answer_option">@{{ error }}</p>
        </div>
    </div>
</div>
