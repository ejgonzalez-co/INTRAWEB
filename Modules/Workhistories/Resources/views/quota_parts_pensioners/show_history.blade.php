<!-- Panel seguimiento -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Seguimiento</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="table-responsive">
            <table class="table table-hover fix-vertical-table table-bordered">
                <thead>
                    <tr>
                        <th>@lang('Created_at')</th>
                        <th>@lang('Type Document')</th>
                        <th>@lang('Number Document')</th>
                        <th>@lang('Name')</th>
                        <th>@lang('Surname')</th>
                        <th>@lang('Address')</th>
                        <th>@lang('Phone')</th>
                        <th>@lang('Email')</th>
                        <th>@lang('Gender')</th>
                        <th>@lang('Group Ethnic')</th>
                        <th>@lang('Birth Date')</th>
                        <th>@lang('Level study')</th>

                        <th>@lang('Time work')</th>

                
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(history, key) in dataShow.work_histories_cp_pensionados_hes">
                        <td>@{{ history.created_at }}</td>
                        <td>@{{ history.type_document }}</td>
                        <td>@{{ history.number_document }}</td>
                        <td>@{{ history.name }}</td>
                        <td>@{{ history.surname }}</td>
                        <td>@{{ history.address }}</td>
                        <td>@{{ history.phone }}</td>
                        <td>@{{ history.email }}</td>
                        <td v-if="history.gender=='1'">F</td>
                        <td v-if="history.gender=='0'">M</td>
                        <td v-if="history.gender=='2'">No determinado</td>
                        <td>@{{ history.group_ethnic }}</td>
                        <td>@{{ history.birth_date }}</td>
                        <td>@{{ history.level_study }}</td>

                        <td>@{{ history.time_work }}</td>

                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- Panel Historial de documentos adjuntos -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Historial de documentos adjuntos</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="table-responsive">
            <table class="table table-hover fix-vertical-table table-bordered">
                <thead>
                    <tr>
                        <th>@lang('Created_at')</th>
                        <th>@lang('New')</th>
                            
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(new_document, key) in dataShow.work_histories_cp_p_documents_news">
                        <td>@{{ new_document.created_at }}</td>
                        <td>@{{ new_document.new }}</td>
               
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>