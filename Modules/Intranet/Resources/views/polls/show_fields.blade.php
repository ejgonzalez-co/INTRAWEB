<!-- Panel -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Información general de la encuesta</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="row">

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Title'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.title }}</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Description'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.description }}</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Creador'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.creator }}</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Date Open'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.date_open }}</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Date Close'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.date_close }}</label>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Panel -->
<div class="panel col-md-12" data-sortable-id="ui-general-1" v-if="dataShow.work_groups?.length > 0">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Dirigido a grupos</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>@lang('Name')</th>

                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(group, key) in dataShow.work_groups">
                        <td>@{{ group.name }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- Panel -->
<div class="panel col-md-12" data-sortable-id="ui-general-1" v-if="dataShow.users?.length > 0">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Dirigido a funcionarios</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>@lang('Name')</th>

                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(users, key) in dataShow.users">
                        <td>@{{ users.users_name }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Panel -->
<div class="panel col-md-12" data-sortable-id="ui-general-1" v-if="dataShow.total_answered?.length > 0">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Funcionarios que han respondido la encuesta: @{{ dataShow.total_answered?.length }} </strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <b class="text-gray">Importante: </b><span>Para información detallada de respuestas favor ingrese a <a :href="'{!! url('intranet/poll-answers') !!}?poll=' + dataShow.id"  target="_blank">Resultados de la encuesta</a>. También puede accder desde la acción "Respuestas" del listado.</span>
        <br><br>

        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>Funcionario</th>
                        <th>Fecha</th>

                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(person, key) in dataShow.total_answered">
                        <td>@{{ person.users_name }}</td>
                        <td>@{{ person.created_at }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Panel -->
<div class="panel col-md-12" data-sortable-id="ui-general-1" v-if="dataShow.intranet_polls_answers?.length > 0">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Respuestas</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>@lang('Date') @lang('Answer')</th>
                        <th>@lang('Question')</th>
                        <th>@lang('Answer')</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(question, key) in dataShow.intranet_polls_answers">
                        <td>@{{ question.created_at }}</td>
                        <td>@{{ question.intranet_polls_questions.question }}</td>
                        <td>@{{ question.answer }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Panel -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Preguntas</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>@lang('Type')</th>
                        <th>@lang('Question')</th>
                        <th>@lang('Options')</th>

                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(question, key) in dataShow.intranet_polls_questions">
                        <td>@{{ question.type_name }}</td>
                        <td>@{{ question.question }}</td>
                        <td>
                            <ul v-if="question.intranet_polls_questions_options ? question.intranet_polls_questions_options.length > 0 : ''">
                                <li v-for="(option, key) in question.intranet_polls_questions_options">
                                    <p>@{{ option.option_question }}</p>
                                </li>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


