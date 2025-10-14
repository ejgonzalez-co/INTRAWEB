<!-- Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('name', null, ['class' => 'form-control', 'v-model' => 'dataForm.name', 'required' => true]) !!}
        <small class="form-text text-muted">Ingresa el nombre correspondiente.</small>
    </div>
</div>

<!-- Description Field -->
<div class="form-group row m-b-15">
    {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('description', null, ['class' => 'form-control', 'v-model' => 'dataForm.description', 'required' => true]) !!}
        <small class="form-text text-muted">Ingresa una descripción breve.</small>
    </div>
</div>

<!-- State Field -->
<div class="form-group row m-b-15">
    {!! Form::label('state', trans('State').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        <!-- state switcher -->
        <div class="switcher">
            <input type="checkbox" name="state" id="state"  v-model="dataForm.state"  :value="true" :checked="false">
            <label for="state"></label>
        </div>
        <small class="form-text text-muted">Activa o desactiva el estado.</small>
    </div>
</div>

<!-- Url Img Profile Field -->
<div class="form-group row m-b-15">
    {!! Form::label('url_img_profile', trans('Url Img Profile').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::file('url_img_profile', ['class' => 'form-control', 'accept' => 'image/*',  '@change' => 'inputFile($event, "url_img_profile")']) !!}
        <small class="form-text text-muted">Selecciona una imagen de perfil.</small>
    </div>
</div>

<div class="col-md-12">
    <div class="panel">
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Funcionarios</strong></h4>
        </div>
        <div class="alert hljs-wrapper fade show">
            <i class="fa fa-info fa-2x pull-left m-r-10 m-t-5"></i>
            <p class="m-b-0">Funcionarios que pertenecen al grupo de trabajo.</p>
        </div>
        <div class="panel-body">
            <!-- Checks Roles -->
            <select-check
                css-class="custom-control-input"
                name-field="users" reduce-label="fullname"
                name-resource="get-users-order" :value="dataForm"
                :is-check="true" 
                :key="keyRefresh">
            </select-check>
            <small class="form-text text-muted">Selecciona los funcionarios asociados al grupo.</small>
        </div>
    </div>
</div>
