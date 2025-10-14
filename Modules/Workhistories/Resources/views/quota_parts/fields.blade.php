
<div class="panel" data-sortable-id="ui-general-1">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Información de la cuota parte</strong></h4>
        </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body">
            <div class="row">

                <div class="col-md-12">
                    <div class="form-group row m-b-15">
                    {!! Form::label('name_company', trans('Name Company').':', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-8">
                        {!! Form::text('name_company', null, ['class' => 'form-control', 'v-model' => 'dataForm.name_company', 'required' => true]) !!}
                        <small>Ingrese el nombre de la entidad</small>

                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group row m-b-15">
                    {!! Form::label('time_work', trans('Tiempo de servicio prestado').':', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-8">
                        {!! Form::text('time_work', null, ['class' => 'form-control', 'v-model' => 'dataForm.time_work', 'required' => true]) !!}
                        <small>Ingrese el tiempo de servicio prestado en la entidad</small>

                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group row m-b-15">
                    {!! Form::label('observation', trans('Observation').':', ['class' => 'col-form-label col-md-3']) !!}
                        <div class="col-md-8">
                        {!! Form::textarea('observation', null, ['class' => 'form-control', 'v-model' => 'dataForm.observation', 'required' => false]) !!}
                        </div>
                    </div>
                </div>


                <!-- number_document Field -->
                <div class="col-md-12">
                    <div class="form-group row m-b-15">
                    {!! Form::label('url_document', trans('Attach document').':', ['class' => 'col-form-label col-md-4 required']) !!}
                        <div class="col-md-8" v-if="isUpdate">
                        {!! Form::file('url_document', ['accept' => '*', '@change' => 'inputFile($event, "url_document")']) !!}
                        </div>
                        <div class="col-md-8" v-else>
                        {!! Form::file('url_document', ['accept' => '*', '@change' => 'inputFile($event, "url_document")', 'required' => true]) !!}
                        </div>
                    </div>
                </div>
              
            </div>
        </div>
</div>
