<div class="form-group row m-b-15">
    {!! Form::label('category', trans('Category').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <select-check css-class="form-control"
        name-field="intranet_news_category_id" 
        reduce-label="name" 
        name-resource="get-categories" 
        :value="dataForm" 
        :is-required="true" ></select-check>
        
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

<!-- Content Field -->
<div class="form-group row m-b-15">
    {!! Form::label('content', 'Contenido:', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">

        <text-area-editor
        :value="dataForm"
        name-field="content"
        :hide-modules="{
           'bold': true,
           'image': true,
           'code': true,
           'link': false
        }"
        placeholder="Ingrese el contenido"
     >
     </text-area-editor>
        <div class="invalid-feedback" v-if="dataErrors.content">
            <p class="m-b-0" v-for="error in dataErrors.content">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  State Field -->
<div class="form-group row m-b-15">
    {!! Form::label('state', trans('State').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <!-- state switcher -->
    <div class="col-md-9">
        {!! Form::select('state', ["1" => "Publicado", "0" => "Despublicado"], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.directlink }", 'v-model' => 'dataForm.state', 'required' => true]) !!}
        <label for="state"></label>
        <small>@lang('Select the') @{{ `@lang('State')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.state">
            <p class="m-b-0" v-for="error in dataErrors.state">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Date Start Field -->
<div class="form-group row m-b-15">
    {!! Form::label('date_start', trans('Start Date').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <date-picker
            :value="dataForm"
            name-field="date_start"
            :input-props="{required: true}"
        >
        </date-picker>

        {{-- <input type="datetime-local" id="date_start"
        name="date_start" v-model="dataForm.date_start" required> --}}

        <small>@lang('Enter the') @{{ `@lang('Start Date')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.date_start">
            <p class="m-b-0" v-for="error in dataErrors.date_start">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Date End Field -->
<div class="form-group row m-b-15">
    {!! Form::label('date_end', trans('End Date').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <date-picker
            :value="dataForm"
            name-field="date_end"
            :input-props="{required: true}"
        >
        </date-picker>

        {{-- <input type="datetime-local" id="date_end"
       name="date_end" v-model="dataForm.date_end" required> --}}

        <small>@lang('Enter the') @{{ `@lang('End Date')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.date_end">
            <p class="m-b-0" v-for="error in dataErrors.date_end">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Image Presentation Field -->
<div class="form-group row m-b-15">
    {!! Form::label('image_presentation', 'Imagen de presentación:', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::file('image_presentation', ['class' => 'form-control', 'accept' => 'image/*',   '@change' => 'inputFile($event, "image_presentation")']) !!}
        <small>Medida recomendada 600px x 400px</small>
    </div>
</div>
<!-- Image Banner Field -->
<div class="form-group row m-b-15">
    {!! Form::label('image_banner', 'Imagen de Banner:', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::file('image_banner', ['class' => 'form-control', 'accept' => 'image/*', '@change' => 'inputFile($event, "image_banner")']) !!}
        <small>Medida recomendada 1500 x 500px</small>
    </div>
</div>

<!-- Annexes -->
<div class="form-group row m-b-15">
    {!! Form::label('url', 'Lista de anexos adjuntos:', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        <input-file :file-name-real="true":value="dataForm" name-field="annexes" :max-files="10"
            :max-filesize="11" file-path="public/container/intranet/news_{{ date('Y') }}"
            message="Arrastre o seleccione los archivos" help-text="Lista de anexos adjuntos."
            :is-update="isUpdate" :key="keyRefresh">
        </input-file>
    </div>
</div>


<!-- Keywords Field -->
<div class="form-group row m-b-15">
    {!! Form::label('keywords', 'Palabras claves:', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::textarea('keywords', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.keywords }", 'v-model' => 'dataForm.keywords']) !!}
        <small>@lang('Enter the') @{{ `@lang('Keywords')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.keywords">
            <p class="m-b-0" v-for="error in dataErrors.keywords">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Featured Field -->
<div class="form-group row m-b-15">
    {!! Form::label('featured', 'Destacado:', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::select('featured', ["Si" => "Si", "No" => "No"], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.directlink }", 'v-model' => 'dataForm.featured']) !!}
        <small>@lang('Enter the') @{{ `@lang('Featured')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.featured">
            <p class="m-b-0" v-for="error in dataErrors.featured">@{{ error }}</p>
        </div>
    </div>
</div>
