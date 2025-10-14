@if (Auth::user()->hasRole('Revisor concepto técnico TIC'))
    <div class="panel">
        <div class="panel-body">
            <div class="form-group row m-b-15">
                <label class="col-form-label col-md-2 required" for="">¿Qué desea hacer?</label>
                <div class="col-md-4">
                    {!! Form::select('status',['Devolver al técnico'=>'Devolver','Enviar para aprobación'=>'Enviar para aprobación'] ,null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.status }", 'v-model' => 'dataForm.status']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="panel" v-if="dataForm.status === 'Devolver al técnico'">
        <div class="panel-heading">
            <div class="panel-title"><strong>Observación</strong></div>
        </div>
        <div class="panel-body">
            <div class="form-group row m-b-15">
                <label class="col-form-label col-md-2 required" for="">Observación:</label>
                <div class="col-md-6">
                    <textarea class="form-control" rows="10" v-model="dataForm.observation_return" required></textarea>
                </div>
            </div>
        </div>
    </div>
@endif
