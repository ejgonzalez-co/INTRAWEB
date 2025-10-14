<!-- Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.name }", 'v-model' => 'dataForm.name', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Name')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.name">
            <p class="m-b-0" v-for="error in dataErrors.name">@{{ error }}</p>
        </div>
    </div>
</div>


<div class="form-group row m-b-15">
    {!! Form::label('estado', trans('Estado').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::select('estado', ['Activo' => 'Activo', 'Inactivo' => 'Inactivo'], null, [
            'class' => "form-control",
            'v-model' => 'dataForm.estado',
            'required' => true
        ]) !!}
        <small>@lang('Seleccione el') @{{ `@lang('Estado')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.estado">
            <p class="m-b-0" v-for="error in dataErrors.estado">@{{ error }}</p>
        </div>
    </div>
</div>
<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
       <h4 class="panel-title"><strong>Tipos de la categoría</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <dynamic-list label-button-add="Agregar Tipo" :data-list.sync="dataForm.tic_type_assets" :is-remove="true"
            :data-list-options="[
                { label: 'Nombre' ,name: 'name', isShow: true}
            ]"
            :is-required="false"
            class-container="col-md-12" class-table="table table-bordered" :is-remove="false">
            <template #fields="scope">
                <!-- Video url Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('nombre', trans('Nombre') . ':', ['class' => 'col-form-label col-md-3',]) !!}
                    <div class="col-md-9">
                        {!! Form::input('text','nombre', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.nombre }", 'v-model' => 'scope.dataForm.name', 'required' => true]) !!}
                    <small>Ingrese los tipos de la categoría</small>
                        <div class="invalid-feedback" v-if="dataErrors.id">
                            <p class="m-b-0" v-for="error in dataErrors.id">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </template>
        </dynamic-list>
    </div>
</div>

<!-- Description Field -->
<!-- <div class="form-group row m-b-15">
    {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('description', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.description }", 'v-model' => 'dataForm.description', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Description')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.description">
            <p class="m-b-0" v-for="error in dataErrors.description">@{{ error }}</p>
        </div>
    </div>
</div> -->
