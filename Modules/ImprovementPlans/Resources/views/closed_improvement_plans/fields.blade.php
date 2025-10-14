<!-- Evaluator Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('status_improvement_plan', trans('Estado del plan de mejoramiento').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <select class="form-control" v-model="dataForm.status_improvement_plan" required>
            <option value="Cerrado cumplido">Cerrado cumplido</option>
            <option value="Cerrado no cumplido">Cerrado no cumplido</option>
        </select>
        <small>Seleccione el estado del plan de mejoramiento.</small>
        <div class="invalid-feedback" v-if="dataErrors.status_improvement_plan">
            <p class="m-b-0" v-for="error in dataErrors.status_improvement_plan">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Observation Field -->
<div class="form-group row m-b-15">
    {!! Form::label('objective_evaluation', trans('Observation').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <textarea cols="30" rows="10" class="form-control" v-model="dataForm.observation" required></textarea>
        <small>Ingrese una observación.</small>
        <div class="invalid-feedback" v-if="dataErrors.objective_evaluation">
            <p class="m-b-0" v-for="error in dataErrors.objective_evaluation">@{{ error }}</p>
        </div>
    </div>
</div>

<div class="form-group row m-b-15">
    {!! Form::label('attached', trans('Evidencias') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <input-file :value="dataForm" name-field="evidencias_cierre_plan" :max-files="10"
            :max-filesize="5" file-path="public/container/pqr_{{ date('Y') }}"
            message="Arrastre o seleccione los archivos" help-text="Seleccione el archivo que desea relacionar al cierre del plan, el cual puede ser en lo siguientes formatos DOCX. PDF,XLSX, PNG y su peso es de máximo 5MB por archivo."
            :is-update="isUpdate" :key="keyRefresh" ruta-delete-update="pqrs/p-q-r-s-delete-file" :id-file-delete="dataForm.id">
        </input-file>
    </div>
</div>