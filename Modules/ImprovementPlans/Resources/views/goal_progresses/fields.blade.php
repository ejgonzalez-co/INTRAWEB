<!-- Goal Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('goal_name', trans('Nombre de la meta') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <input type="text" class="form-control" readonly value="{{ $goal->goal_name }}">
        <div class="invalid-feedback" v-if="dataErrors.goal_name">
            <p class="m-b-0" v-for="error in dataErrors.goal_name">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Activity Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('pm_goal_activities_id', trans('Nombre de la actividad') . ':', [
        'class' => 'col-form-label col-md-3 required',
    ]) !!}
    <div class="col-md-9">
        <select-check css-class="form-control" name-field="pm_goal_activities_id" reduce-label="activity_name" reduce-key="id"
            :name-resource="'activities/' + {{ $goal->id }}" :value="dataForm" :is-required="true">
        </select-check>
        <small>Seleccione el nombre de la actividad.</small>
        <div class="invalid-feedback" v-if="dataErrors.pm_goal_activities_id">
            <p class="m-b-0" v-for="error in dataErrors.pm_goal_activities_id">@{{ error }}</p>
        </div>
    </div>
</div>

<div class="form-group row m-b-15">
    {!! Form::label('activity_weigth', trans('Peso de la actividad con respecto a la meta') . ':', [
        'class' => 'col-form-label col-md-3 required',
    ]) !!}
    <div class="col-md-9">
        <div style="display: flex;">
            <input-disabled name-prop="activity_weigth" :name-resource="'activity-weigth/' + dataForm.pm_goal_activities_id" :value="dataForm" name-field="activity_weigth" :key="dataForm.pm_goal_activities_id"></input-disabled>
            <div class="input-group-append">
                <span class="input-group-text">%</span>
            </div>
        </div>
    </div>
</div>
<!-- Activity Weigth Field -->
@if($goal->goal_type == "Cuantitativa")

<div class="form-group row m-b-15">
    {!! Form::label('gap_meet_goal', trans('Brecha para cumplimiento de la meta') . ':', [
        'class' => 'col-form-label col-md-3 required',
    ]) !!}
    <div class="col-md-3">
            <input-disabled name-prop="gap_meet_goal" :name-resource="'activity-weigth/' + dataForm.pm_goal_activities_id" :value="dataForm" name-field="gap_meet_goal" :key="dataForm.pm_goal_activities_id"></input-disabled>
            
        <small>
            Esta es la cantidad que necesitas para alcanzar tu meta.
        </small>
    </div>

    <!-- Progress Weigth Field -->
    {!! Form::label('progress_weigth', trans('Avance') . ':', [
        'class' => 'col-form-label col-md-2 required',
    ]) !!}
    <div class="col-md-4">
        <div class="input-group">
            {!! Form::number('progress_weigth', null, [
                ':class' => "{'form-control':true, 'is-invalid':dataErrors.progress_weigth }",
                'v-model' => 'dataForm.progress_weigth',
                'required' => true,
                ':max'=>"dataForm.gap_meet_goal"

            ]) !!}
            <div class="input-group-append">
                <span class="input-group-text">Cantidad</span>
            </div>
        </div>
        <small>
         ¿Cuánto has logrado para alcanzar tu meta? Ingresa la cantidad para cerrar la brecha. Ejemplo: 10
        </small>
        
    </div>

</div>
@else
<input type="hidden" v-model="dataForm.progress_weigth=100">
@endif


<!-- Evidence Description Field -->
<div class="form-group row m-b-15">
    {!! Form::label('evidence_description', trans('Descripción de la evidencia') . ':', [
        'class' => 'col-form-label col-md-3 required',
    ]) !!}
    <div class="col-md-9">
        {!! Form::textarea('evidence_description', null, [
            ':class' => "{'form-control':true, 'is-invalid':dataErrors.evidence_description }",
            'v-model' => 'dataForm.evidence_description',
            'required' => true,
        ]) !!}
        <small>Por favor, ingrese una descripción detallada de su avance, asegurándose de que tenga más de 20 palabras.</small>
        <div class="invalid-feedback" v-if="dataErrors.evidence_description">
            <p class="m-b-0" v-for="error in dataErrors.evidence_description">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Url Progress Evidence Field -->
<div class="form-group row m-b-15">
    {!! Form::label('url_progress_evidence', trans('Evidencia del avance') . ':', [
        'class' => 'col-form-label col-md-3 required',
    ]) !!}
    <div class="col-md-9">
        {!! Form::file('url_progress_evidence', [
            'accept' => '.png, .jpg, .jpeg,.docx,.pdf,.xlsx',
            '@change' => 'inputFile($event, "url_progress_evidence")',
            'required' => true,
            'class' => 'form-control',
        ]) !!}
        <small style="color: rgb(195, 195, 195);">Seleccione el archivo adjunto que desea relacionar al avance, el cual puede ser en lo siguientes formatos DOCX. PDF,XLSX, PNG y su peso es de máximo 5MB.</small>
        <div class="invalid-feedback" v-if="dataErrors.url_progress_evidence">
            <p class="m-b-0" v-for="error in dataErrors.url_progress_evidence">@{{ error }}</p>
        </div>
    </div>
</div>
