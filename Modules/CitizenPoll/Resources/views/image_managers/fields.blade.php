<!-- Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.name }", 'v-model' => 'dataForm.name', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Name')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.name">
            <p class="m-b-0" v-for="error in dataErrors.name">@{{ error }}</p>
        </div>
    </div>
</div>


<div class="form-group row m-b-15">
    {!! Form::label('url_image', trans('imagen').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-6">
        {!! Form::file('url_image', ['accept' => 'image/*', '@change' => 'inputFile($event, "url_image")', 'required' => true]) !!}
        <br>
        
    </div>
    <div class="col-md-3" v-if="isUpdate">
        <img width="150" :src="'{{ asset('storage') }}/'+dataForm.url_image" alt="">
    </div>
</div>
