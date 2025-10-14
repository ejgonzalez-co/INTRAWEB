<div class="panel" data-sortable-id="ui-general-1">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Información del documento</strong></h4>
        </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body">
            <div class="row">

                <div class="col-md-12">
                    <div class="form-group row m-b-15">
                    {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-8">
                        {!! Form::text('name', null, ['class' => 'form-control', 'v-model' => 'dataForm.name', 'required' => true]) !!}
                        <small>Ingrese el nombre del documento</small>

                        </div>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="form-group row m-b-15">
                    {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-3']) !!}
                        <div class="col-md-8">
                        {!! Form::textarea('description', null, ['class' => 'form-control', 'v-model' => 'dataForm.description', 'required' => false]) !!}
                            <small>Ingrese una descripción del documento</small>
                        </div>
                    </div>
                </div>

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
