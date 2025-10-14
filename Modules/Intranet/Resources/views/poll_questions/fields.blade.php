<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Configuración general de la pregunta</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- Tipo Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('type', trans('Type').':', ['class' => 'col-form-label col-md-4 required']) !!}
                    <div class="col-md-8">

                        <select-check
                            css-class="form-control"
                            name-field="type"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-constants/type_question_poll"
                            :value="dataForm"
                            ref-select-check="typeRef"
                            :is-required="true">
                        </select-check>
                        <small>Seleccione el tipo de pregunta</small>
                    </div>
                </div>
            </div>

            <!-- Question Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('question', trans('Question').':', ['class' => 'col-form-label col-md-4 required']) !!}
                    <div class="col-md-8">
                        {!! Form::textarea('question', null, ['class' => 'form-control', 'v-model' => 'dataForm.question', 'required' => true]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel" data-sortable-id="ui-general-1" v-if="dataForm.type==1 || dataForm.type==2">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>@lang('Configuration') @lang('of') @lang('answers')</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="col-md-12">

            <add-list-option
                :value="dataForm"
                reduce-key="option_question"
                name-field="intranet_polls_questions_options"
                name-key="question2"
                :name-labels-display="['option']"
                name-options-list="question_list"
                help="Ingrese una opción de respuesta y haga clic en el botón 'Agregar a la lista'"
                text-label="Ingrese la opción de respuesta: ">
            </add-list-option>

        </div>

    </div>
</div>

