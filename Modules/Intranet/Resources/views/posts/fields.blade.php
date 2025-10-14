<!-- Category Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('category_id', trans('Category Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('category_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.category_id }", 'v-model' => 'dataForm.category_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Category Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.category_id">
            <p class="m-b-0" v-for="error in dataErrors.category_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Title Field -->
<div class="form-group row m-b-15">
    {!! Form::label('title', trans('Title').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('title', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.title }", 'v-model' => 'dataForm.title', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Title')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.title">
            <p class="m-b-0" v-for="error in dataErrors.title">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Slug Field -->
<div class="form-group row m-b-15">
    {!! Form::label('slug', trans('Slug').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('slug', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.slug }", 'v-model' => 'dataForm.slug', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Slug')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.slug">
            <p class="m-b-0" v-for="error in dataErrors.slug">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Cover Path Field -->
<div class="form-group row m-b-15">
    {!! Form::label('cover_path', trans('Cover Path').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('cover_path', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.cover_path }", 'v-model' => 'dataForm.cover_path', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Cover Path')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.cover_path">
            <p class="m-b-0" v-for="error in dataErrors.cover_path">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Content Field -->
<div class="form-group row m-b-15">
    {!! Form::label('content', trans('Content').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('content', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.content }", 'v-model' => 'dataForm.content', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Content')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.content">
            <p class="m-b-0" v-for="error in dataErrors.content">@{{ error }}</p>
        </div>
    </div>
</div>

<!--  Online Field -->
<div class="form-group row m-b-15">
    {!! Form::label('online', trans('Online').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- online switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="online" id="online"  v-model="dataForm.online">
        <label for="online"></label>
        <small>@lang('Select the') @{{ `@lang('Online')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.online">
            <p class="m-b-0" v-for="error in dataErrors.online">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- User Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('user_id', trans('User Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('user_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.user_id }", 'v-model' => 'dataForm.user_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('User Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.user_id">
            <p class="m-b-0" v-for="error in dataErrors.user_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Visits Field -->
<div class="form-group row m-b-15">
    {!! Form::label('visits', trans('Visits').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('visits', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.visits }", 'v-model' => 'dataForm.visits', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Visits')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.visits">
            <p class="m-b-0" v-for="error in dataErrors.visits">@{{ error }}</p>
        </div>
    </div>
</div>
