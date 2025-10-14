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
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Type'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.type }}</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Question'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.question }}</label>
                </div>
            </div>

            <div class="table-responsive" v-if="dataShow.intranet_polls_questions_options ? dataShow.intranet_polls_questions_options.length > 0 : ''">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(option, key) in dataShow.intranet_polls_questions_options">
                            <td>@{{ option.option_question }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
