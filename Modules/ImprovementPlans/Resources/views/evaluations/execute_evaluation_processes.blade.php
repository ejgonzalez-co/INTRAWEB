<!-- Evaluation Site Field -->
<div class="form-group m-b-15">
    <dynamic-list-select :data-list.sync="dataForm.evaluation_criteria"
        :data-list-options="[
            { label: 'Criterios de evaluación', name: 'criteria_name', isShow: true },
            { label: 'Estado', name: 'status', isShow: true },
            { label: 'Observación', name: 'observations', isShow: true },
        ]"
        class-container="" class-table="table table-bordered" :is-remove="false">
        <template #fields="scope">
                <!-- Name Field -->
                <div class="form-group m-b-15">
                    <label class="col-form-label col-md-4 required">Criterios de evaluación:</label>
                    <div class="row">
                        <select-check css-class="form-control" name-field="criteria_name"
                            reduce-label="name" reduce-key="name" name-resource="active-evaluation-criteria" :value="scope.dataForm">
                        </select-check>
                    </div>
                </div>

            </template>
        </dynamic-list-select>
        
    </div>
    
    <!-- Evaluation Status Field -->
    {{-- <div class="form-group row m-b-15">
        {!! Form::label('status', trans('Estado de la evaluación') . ':', [
            'class' => 'col-form-label col-md-3 required',
            ]) !!}
    <div class="col-md-4">
        <select class="form-control" v-model="dataForm.status" required>
            <option value="Pendiente">Pendiente</option>
            <option value="Cerrada">Cerrada</option>
        </select>
        <small>Seleccione el estado de la evaluación.</small>
    </div>
</div> --}}

<!-- Type Improvement Plans Field -->
<div class="form-group row m-b-15" v-if="dataForm.evaluation_criteria.find(function(criterio) { return criterio.status == 'No conforme'; }) ? true : (dataForm.name_improvement_plan = null)">
    {!! Form::label('status', trans('Tipo del plan de mejoramiento') . ':', [
        'class' => 'col-form-label col-md-3 required',
        ]) !!}
    <div class="col-md-4">
        <select-check css-class="form-control" name-field="name_improvement_plan"
            reduce-label="name" reduce-key="name" name-resource="get-active-types-improvement-plans" :value="dataForm">
        </select-check>
        <small>Seleccion el tipo de plan de mejoramiento</small>
    </div>
</div>

<!-- Name of the improvement plant  Field -->
<div class="form-group row m-b-15" v-if="dataForm.evaluation_criteria.find(function(criterio) { return criterio.status == 'No conforme'; }) ? true : (dataForm.name_improvement_plan = null)">
    {!! Form::label('status', trans('Nombre del plan de mejoramiento') . ':', [
        'class' => 'col-form-label col-md-3 required',
        ]) !!}
    <div class="col-md-4">
        {!! Form::text('name_evaluation', null, [
            'class' => 'form-control',
            'v-model' => 'dataForm.type_name_improvement_plan',
            'required' => true,
        ]) !!}
        <small>Ingrese el nombre del plan de mejoramiento</small>
    </div>
</div>

<!-- Attached Field -->
<div class="form-group row m-b-15">
    {!! Form::label('evaluation_process_attachment', trans('Attached file') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-4">
        {!! Form::file('evaluation_process_attachment', [
            'accept' => '*',
            '@change' => 'inputFile($event, "evaluation_process_attachment")',
            'required' => true,
        ]) !!}

    </div>
</div>

<!-- Type Evaluation Field -->
<div class="form-group row m-b-15">
    {!! Form::label('general_description_evaluation_results', trans('Descripción general de los resultados de la evaluación') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
    <!-- Evaluation Name Field -->
    <div class="col-md-8">
        {!! Form::textarea('general_description_evaluation_results', null, [
            'class' => 'form-control',
            'v-model' => 'dataForm.general_description_evaluation_results',
            'required' => true,
        ]) !!}
        <small>Ingrese la descripción general de los resultados de la evaluación.</small>
    </div>
</div>
