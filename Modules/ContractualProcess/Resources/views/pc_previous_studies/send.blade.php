<div class="panel" data-sortable-id="ui-general-1">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Enviar a revisión de jurídica</strong></h4>
        </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body">
            <div class="row">

                <input name="send" type="hidden" value="Revisión de jurídica">


                <!-- Field -->
                <div class="col-md-12">
                    <div class="form-group row m-b-15">
                    {!! Form::label('observation', trans('Observation').':', ['class' => 'col-form-label col-md-3']) !!}
                        <div class="col-md-8">
                            {!! Form::textarea('observation', null, ['rows' => 5, 'class' => 'form-control', 'v-model' => 'dataForm.observation', 'required' => false]) !!}
                            <small></small>
                        </div>
                    </div>
                </div>


            </div>
        </div>
</div>