<!-- evaluation_criteria Field -->
<div class="form-group row m-b-15">
    {!! Form::label(
        'evaluation_criteria_id',
        trans('Criterio de evaluación') . ':',
        ['class' => 'col-form-label col-md-3 required'],
    ) !!}
    <div class="col-md-9">
        <select-check css-class="form-control" name-field="evaluation_criteria_id" reduce-label="criteria_name"
            reduce-key="id" :name-resource="'get-non-compliant-evaluation-criteria/' + initValues.evaluations_id" :value="dataForm"  :enable-search="true">
        </select-check>

        <small>Seleccione el criterio de evaluación</small>
    </div>
</div>

<!-- Source Information Field -->
<div class="form-group row m-b-15">
    {!! Form::label('source_information_id', trans('Source Information') . ':', [
        'class' => 'col-form-label col-md-3 required',
    ]) !!}
    <div class="col-md-9">
        <select-check css-class="form-control" name-field="source_information_id" reduce-label="name" reduce-key="id"
            name-resource="get-active-source-information" :value="dataForm" :enable-search="true">
        </select-check>
        <small>Seleccione la fuente de información.</small>
    </div>
</div>

<!-- Type of improvement opportunity or nonconformity Field -->
<div class="form-group row m-b-15">
    {!! Form::label(
        'type_oportunity_improvements_id',
        trans('Type of improvement opportunity or nonconformity') . ':',
        ['class' => 'col-form-label col-md-3 required'],
    ) !!}
    <div class="col-md-9">
        <select-check css-class="form-control" name-field="type_oportunity_improvements_id" reduce-label="name"
            reduce-key="id" name-resource="get-active-types-opportunities-improvement" :value="dataForm" :enable-search="true">
        </select-check>
        <small>Seleccione el tipo de oportunidad de mejora o no conformidad.</small>
    </div>
</div>

<!-- Name of improvement opportunity or nonconformity Field -->
<div class="form-group row m-b-15">
    {!! Form::label(
        'name_opportunity_improvement',
        trans('Name of improvement opportunity or nonconformity') . ':',
        ['class' => 'col-form-label col-md-3 required'],
    ) !!}
    <div class="col-md-9">
        {!! Form::text('name_opportunity_improvement', null, [
            'class' => 'form-control',
            'v-model' => 'dataForm.name_opportunity_improvement',
            'required' => true,
        ]) !!}
        <small>Ingrese el nombre de la oportunidad de mejora o no conformidad.</small>
    </div>
</div>

<!-- Description of improvement opportunity or nonconformity Field -->
<div class="form-group row m-b-15">
    {!! Form::label(
        'description_opportunity_improvement',
        trans('Description of improvement opportunity or nonconformity') . ':',
        ['class' => 'col-form-label col-md-3 required'],
    ) !!}
    <div class="col-md-9">
        {!! Form::textarea('process', null, [
            'class' => 'form-control',
            'v-model' => 'dataForm.description_opportunity_improvement',
            'required' => true,
        ]) !!}
        <small>Ingrese la descripción de la oportunidad de mejora o no conformidad.</small>
    </div>
</div>

<!-- Unit Responsible Improvement Opportunity Field -->
<div class="form-group row m-b-15">
    {!! Form::label(
        'dependencia_id',
        trans('Unit or process responsible for the opportunity for improvement') . ':',
        ['class' => 'col-form-label col-md-3 required'],
    ) !!}
    <div class="col-md-9">
        <select-check css-class="form-control" name-field="dependencia_id"
            reduce-label="nombre" reduce-key="id" name-resource="get-dependences" :value="dataForm" :enable-search="true">
        </select-check>
        <small>Seleccione la dependencia o proceso responsable de la oportunidad de mejora.</small>
    </div>
</div>

<!-- Official responsible Field -->
<div class="form-group row m-b-15">
    {!! Form::label('official_responsible', trans('Official responsible for the unit') . ':', [
        'class' => 'col-form-label col-md-3 required',
    ]) !!}
    <div class="col-md-9">
        {{-- <autocomplete
        name-prop="name"
        name-field="official_responsible"
        :name-labels-display="['name']"
        :value='dataForm'
        name-resource='get-users-by-name'
        css-class="form-control"
        reduce-key="name"
        :key="keyRefresh"
        :min-text-input="2"
        :is-required="true"
        ref = "placa"
        >
        </autocomplete> --}}

        <select-check css-class="form-control" name-field="official_responsible_id" reduce-label="name" reduce-key="id"
            name-resource="get-users-by-name" :value="dataForm" :enable-search="true">
        </select-check>

        <small>Ingrese el nombre del funcionario responsable de la dependencia.</small>
        {{-- {!! Form::text('responsible', null, [
            'class' => 'form-control',
            'v-model' => 'dataForm.official_responsible',
            'required' => true,
        ]) !!} --}}
    </div>
</div>

<!-- Deadline Submission Field -->
<div class="form-group row m-b-15">
    {!! Form::label('deadline_submission', trans('Deadline for submission of improvement plan') . ':', [
        'class' => 'col-form-label col-md-3 required',
    ]) !!}
    <div class="col-md-9">
        <date-picker :value="dataForm" name-field="deadline_submission">
        </date-picker>
        <small>Seleccione la fecha límite de presentación del plan de mejoramiento.</small>
    </div>
</div>


<!-- Evidence Field -->
<div class="form-group row m-b-15">
    {!! Form::label('evidence', trans('Evidence of opportunity for improvement or nonconformity') . ':', [
        'class' => 'col-form-label col-md-3 required',
    ]) !!}

    <div class="col-md-9" v-if="isUpdate">
        {!! Form::file('evidence', [
            'accept' => '*',
            '@change' => 'inputFile($event, "evidence")',
            'required' => true,
        ]) !!}

        </div>
        <div class="col-md-8" v-else>
            {!! Form::file('evidence', [
                'accept' => '*',
                '@change' => 'inputFile($event, "evidence")',
                'required' => true,
            ]) !!}

        </div>
 
</div>
