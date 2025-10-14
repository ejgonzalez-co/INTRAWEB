<!-- Evaluator Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('evaluator_id', trans('Seleccione el tipo de respuesta').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <select class="form-control" v-model="dataForm.status_improvement_plan">
            <option value="Aprobado">Aprobar plan de mejoramiento</option>
            <option value="Devuelto">Devolver plan de mejoramiento</option>
            <option value="Declinado">Declinar plan de mejoramiento</option>
        </select>
        <small>Seleccione que desea hacer con el plan de mejoramiento.</small>
        <div class="invalid-feedback" v-if="dataErrors.evaluator_id">
            <p class="m-b-0" v-for="error in dataErrors.evaluator_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Observation Field -->
<div class="form-group row m-b-15">
    {!! Form::label('objective_evaluation', trans('Observation').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <textarea cols="30" rows="10" class="form-control" required v-model="dataForm.observation"></textarea>
        <small>Agregue una observación sobre el cambio de estado del plan de mejoramiento.</small>
        <div class="invalid-feedback" v-if="dataErrors.objective_evaluation">
            <p class="m-b-0" v-for="error in dataErrors.objective_evaluation">@{{ error }}</p>
        </div>
    </div>
</div>