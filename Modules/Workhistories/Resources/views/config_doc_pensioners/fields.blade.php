<div class="panel" data-sortable-id="ui-general-1">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Información del documento</strong></h4>
        </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body">
            <div class="row">

                <!-- name Field -->
                <div class="col-md-12">
                    <div class="form-group row m-b-15">
                    {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-8">
                        {!! Form::text('name', null, ['class' => 'form-control', 'v-model' => 'dataForm.name', 'required' => true]) !!}
                        <small>Ingrese el nombre del documento</small>

                        </div>
                    </div>
                </div>

                <!-- description Field -->
                <div class="col-md-12">
                    <div class="form-group row m-b-15">
                    {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-8">
                            {!! Form::textarea('description', null, ['class' => 'form-control', 'v-model' => 'dataForm.description', 'required' => true]) !!}
                            <small>Ingrese una descripción opcional del documento</small>

                        </div>
                    </div>
                </div>

                <!-- estado Field -->
                <div class="col-md-12">
                    <div class="form-group row m-b-15">
                        {!! Form::label('type', trans('State').':', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-8">
                        <select v-model="dataForm.state" name="state" required="required" class="form-control">  
                            <option value="">Seleccione</option>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                        <small>Seleccione el estado del documento</small>
                        </div>
                    </div>
                </div>

            </div>
        </div>
</div>




