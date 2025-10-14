<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Información de la evaluación</strong></div>
    </div>
    <div class="panel-body">
        <!-- Type Evaluation Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('type_evaluation', trans('Type Evaluation') . ':', [
                'class' => 'col-form-label col-md-2 required',
            ]) !!}
            <div class="col-md-4">
                <select-check css-class="form-control" name-field="type_evaluation" reduce-label="name" reduce-key="name"
                    name-resource="active-type-evaluations" :value="dataForm">
                </select-check>
                <small>Seleccione el tipo de evaluación.</small>
            </div>

            <!-- Evaluation Name Field -->
            {!! Form::label('evaluation_name', trans('Evaluation Name') . ':', [
                'class' => 'col-form-label col-md-2 required',
            ]) !!}
            <div class="col-md-4">
                {!! Form::text('evaluation_name', null, [
                    'class' => 'form-control',
                    'v-model' => 'dataForm.evaluation_name',
                    'required' => true,
                ]) !!}
                <small>Ingrese el nombre de la evaluación.</small>
            </div>
        </div>

    </div>
</div>
<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Alcance de la evaluación</strong></div>
    </div>
    <div class="panel-body">
        <div class="form-group row m-b-15">

            <!-- Evaluation Scope Field -->
            {!! Form::label('evaluation_scope', trans('Evaluation Scope') . ':', [
                'class' => 'col-form-label col-md-2 required',
            ]) !!}
            <div class="col-md-10">
                {!! Form::textarea('evaluation_scope', null, [
                    'class' => 'form-control',
                    'v-model' => 'dataForm.evaluation_scope',
                    'required' => true,
                ]) !!}
                <small>Ingrese el alcance de la evaluación.</small>
            </div>
        </div>
    </div>
</div>
<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Objetivo de la evaluación</strong></div>
    </div>
    <div class="panel-body">
        <!-- Objective Evaluation Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('objective_evaluation', trans('Objective Evaluation') . ':', [
                'class' => 'col-form-label col-md-2 required',
            ]) !!}
            <div class="col-md-10">
                {!! Form::textarea('objective_evaluation', null, [
                    'class' => 'form-control',
                    'v-model' => 'dataForm.objective_evaluation',
                    'required' => true,
                ]) !!}
                <small>Ingrese el objetivo de la evaluación.</small>
            </div>
        </div>
    </div>
</div>
<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Horario de la evaluación</strong></div>
    </div>
    <div class="panel-body">
        <!-- Evaluation Site Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('evaluation_site', trans('Evaluation Site') . ':', [
                'class' => 'col-form-label col-md-2 required',
            ]) !!}
            <div class="col-md-4">
                {!! Form::text('evaluation_site', null, [
                    'class' => 'form-control',
                    'v-model' => 'dataForm.evaluation_site',
                    'required' => true,
                ]) !!}
                <small>Ingrese el lugar de evaluación.</small>
            </div>
        </div>

        <!-- Evaluation Start Date Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('evaluation_start_date', trans('Evaluation Start Date') . ':', [
                'class' => 'col-form-label col-md-2 required',
            ]) !!}
            <div class="col-md-4">
                <date-picker :value="dataForm" name-field="evaluation_start_date">
                </date-picker>
                <small>Seleccione la fecha inicial de la evaluación</small>
            </div>
            {!! Form::label('evaluation_start_time', trans('Evaluation Start Time') . ':', [
                'class' => 'col-form-label col-md-2 required',
            ]) !!}
            <div class="col-md-4">
                <input type="time" class="form-control" v-model="dataForm.evaluation_start_time" required />
                <small>Seleccione la hora inicial de la evaluación</small>
            </div>
        </div>

        <!-- Evaluation End Date Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('evaluation_end_date', trans('Evaluation End Date') . ':', [
                'class' => 'col-form-label col-md-2 required',
            ]) !!}
            <div class="col-md-4">
                <date-picker :value="dataForm" name-field="evaluation_end_date">
                </date-picker>
                <small>Seleccione la fecha final de la evaluación</small>
            </div>
            {!! Form::label('evaluation_end_time', trans('Evaluation End Time') . ':', [
                'class' => 'col-form-label col-md-2 required',
            ]) !!}
            <div class="col-md-4">
                <input type="time" class="form-control" v-model="dataForm.evaluation_end_time" required />
                <small>Seleccione la hora final de la evaluación</small>
            </div>
        </div>


    </div>
</div>
<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Información del evaluador</strong></div>
    </div>
    <div class="panel-body">
        <!-- Unit Responsible For Evaluation Field -->
        <div class="form-group row m-b-15">
            {!! Form::label(
                'unit_responsible_for_evaluation',
                trans('Internal unit or external entity responsible for evaluation') . ':',
                [
                    'class' => 'col-form-label col-md-2 required',
                ]
            ) !!}
            <div class="col-md-4">
                {!! Form::text('unit_responsible_for_evaluation', null, [
                    'class' => 'form-control',
                    'v-model' => 'dataForm.unit_responsible_for_evaluation',
                    'required' => true,
                ]) !!}
                <small>Ingrese la dependencia interna o entidad externa responsable de la evaluación.</small>
            </div>
        </div>

        <!-- Evaluation Officer Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('evaluation_officer', trans('Designated official of the evaluating unit or entity') . ':', [
                'class' => 'col-form-label col-md-2 required'
            ]) !!}
            <div class="col-md-4">
                {!! Form::text('evaluation_officer', null, [
                    'class' => 'form-control',
                    'v-model' => 'dataForm.evaluation_officer',
                    'required' => true,
                ]) !!}
                <small>Ingrese el funcionario designado de la dependencia o entidad evaluadora.</small>
            </div>
        </div>
    </div>
</div>
<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Listado de criterios a evaluar</strong></div>
    </div>
    <div class="panel-body">
        <div class="form-group row m-b-15">
            <dynamic-list 
                label-button-add="Agregar criterio de evaluación"
                :data-list.sync="dataForm.evaluation_criteria"
                :data-list-options="[
                    { label: 'Criterios de evaluación', name: 'criteria_name', isShow: true }
                ]"
                class-container="col-md-12" class-table="table table-bordered" :is-remove="false" :is-Update="dataForm.status !== 'Cerrada'">
                <template #fields="scope">
                    <!-- Name Field -->
                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-2 required">Criterios de evaluación:</label>
                        <div class="col-md-5">
                            <select-check css-class="form-control" name-field="criteria_name" reduce-label="name"
                                reduce-key="name" name-resource="active-evaluation-criteria" :value="scope.dataForm" :is-required="true" :enable-search="true">
                            </select-check>
                            <small>Seleccione el criterio de evaluación</small>
                        </div>
                    </div>

                </template>
            </dynamic-list>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Listado de dependencias</strong></div>
    </div>
    <div class="panel-body">
        <!-- Process Field -->
        <div class="form-group row m-b-15">
            <dynamic-list label-button-add="Agregar dependencia" :data-list.sync="dataForm.evaluation_dependences"
                :data-list-options="[
                    { label: 'Dependencia', name: 'dependence_name', isShow: true }
                ]"
                class-container="col-md-12" class-table="table table-bordered" :is-remove="false">
                <template #fields="scope">
                    <!-- Name Field -->
                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-2 required">Dependencia:</label>
                        <div class="col-md-5">
                            <select-check css-class="form-control" name-field="dependence_name" reduce-label="nombre"
                                reduce-key="nombre" name-resource="get-dependences" :value="scope.dataForm" :is-required="true" :enable-search="true">
                            </select-check>
                            <small>Seleccione la dependencia.</small>
                        </div>
                    </div>

                </template>
            </dynamic-list>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Información del evaluado</strong></div>
    </div>
    <div class="panel-body">
        <!-- Evaluated Id Field -->
        <div class="form-group row m-b-15">
            {!! Form::label(
                'evaluated',
                trans('Responsable de la dependencia o proceso evaluado') . ':',
                [
                    'class' => 'col-form-label col-md-2 required',
                ]
            ) !!}
            <div class="col-md-4">
                <select-check css-class="form-control" name-field="evaluated_id" reduce-label="name" reduce-key="id"
                name-resource="get-users-order-name" :value="dataForm" :enable-search="true" :is-required="true">
                </select-check>
                {{-- <autocomplete name-prop="name" name-field="evaluated_id" :name-labels-display="['name']"
                    :value='dataForm' name-resource='get-users-by-name' css-class="form-control" reduce-key="name"
                    :key="keyRefresh" :min-text-input="2" :is-required="true" ref="placa">
                </autocomplete> --}}
                <small>Ingrese el responsable de la dependencia o proceso evaluado.</small>
            </div>
        </div>

    </div>
</div>
<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Información adicional</strong></div>
    </div>
    <div class="panel-body">
        <!-- Process Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('process', trans('Process') . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-10">
                {!! Form::textarea('process', null, [
                    'class' => 'form-control',
                    'v-model' => 'dataForm.process',
                ]) !!}
                <small>Ingrese el proceso.</small>
            </div>
        </div>
    </div>
</div>
<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Documentos</strong></div>
    </div>
    <div class="panel-body">
        <!-- Attached Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('attached', trans('Attached file') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::file('attached', [
                    'accept' => '*',
                    '@change' => 'inputFile($event, "attached")',
                    'required' => true,
                ]) !!}

            </div>
        </div>
    </div>
</div>