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

<!-- Attachment Field -->
<div class="form-group row m-b-15">
    {!! Form::label('attachment', trans('Adjunto').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::file('attachment', ['@change' => 'inputFile($event, "attachment")']) !!}
        <br>
        
        <label v-if="dataForm.attachment" style="margin-top: 5px">Fotografia actual: <a class="col-3 text-truncate":href="'{{ asset('storage') }}/'+dataForm.attachment"target="_blank">Ver adjunto</a></label>
        <small v-else>@lang('Select the') un @{{ `@lang('archivo para adjuntar')` | lowercase }}</small> 
        <div class="invalid-feedback" v-if="dataErrors.attachment">
            <p class="m-b-0" v-for="error in dataErrors.attachment">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Status Field -->
<div class="form-group row m-b-15">
    {!! Form::label('status', trans('Estado').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
            {!! Form::select('type_of_request',['Activo'=>'Activo','Inactivo'=>'Inactivo'], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.type_of_request }", 'v-model' => 'dataForm.status', 'required' => true]) !!}  
            
        <small>@lang('Seleccione el') @{{ `@lang('estado')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.status">
            <p class="m-b-0" v-for="error in dataErrors.status">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Comments Field -->
<div class="form-group row m-b-15">
    {!! Form::label('comments', trans('Comentarios').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('comments', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.comments }", 'v-model' => 'dataForm.comments', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('una descripción del adjunto.')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.comments">
            <p class="m-b-0" v-for="error in dataErrors.comments">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Lc Rm Report Management Id Field -->
<!-- Users Id Field -->
<!-- User Name Field -->



