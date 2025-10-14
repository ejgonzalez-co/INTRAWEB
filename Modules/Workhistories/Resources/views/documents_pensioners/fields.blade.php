<div class="panel" data-sortable-id="ui-general-1">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Información del documento</strong></h4>
        </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body">
            <div class="row">

                <!-- number_document Field -->
                <div class="col-md-12">
                    <div class="form-group row m-b-15">
                    {!! Form::label('type_document', trans('Type Document').':', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-8">

                        <select-check css-class="form-control" name-field="config_documents_id" reduce-label="name" name-resource="get-conf-doc-pensioner" :value="dataForm" :is-required="true"></select-check>
                            <small>Tipo de documento</small>
                        </div>
                    </div>
                </div>

                <!-- number_document Field -->
                <div class="col-md-12">
                    <div class="form-group row m-b-15">
                    {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-3']) !!}
                        <div class="col-md-8">
                        {!! Form::textarea('description', null, ['class' => 'form-control', 'v-model' => 'dataForm.description', 'required' => false]) !!}
                            <small>Ingrese una descripción del documento</small>
                        </div>
                    </div>
                </div>

                <!-- sheet Field -->
                <div class="col-md-12">
                    <div class="form-group row m-b-15">
                    {!! Form::label('sheet', trans('Sheets').':', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-8">
                        {!! Form::text('sheet', null, ['class' => 'form-control', 'v-model' => 'dataForm.sheet', 'required' => true]) !!}
                        <small>Ingrese el número de folios del documento</small>

                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group row m-b-15">
                    {!! Form::label('document_date', trans('Date').' documento:', ['class' => 'col-form-label col-md-3']) !!}
                        <div class="col-md-8">
                            {!! Form::date('document_date', null, ['class' => 'form-control', 'id' => 'document_date',
                            'placeholder' => 'Select Date', 'v-model' => 'dataForm.document_date']) !!}
                            <small>Seleccione la fecha del documento</small>
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
