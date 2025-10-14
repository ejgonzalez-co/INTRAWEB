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
                        <th>@lang('User')</th>

                        <th>@lang('Nombre del pensionado fallecido')</th>
                        <th>@lang('Number Document')</th>
                        <th>@lang('State')</th>
                        <th>@lang('Type Document')</th>
                        <th>@lang('Number Document')</th>
                        <th>@lang('Date') @lang('Document')</th>
                        <th>@lang('Name')</th>
                        <th>@lang('Surname')</th>
                        <th>@lang('Birth Date')</th>
                        <th>@lang('Departament')</th>
                        <th>@lang('City')</th>
                        <th>@lang('Address')</th>
                        <th>@lang('Phone')</th>
                        <th>@lang('Email')</th>
                        <th>@lang('Type') @lang('Substitute')</th>
                
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(history, key) in dataShow.substitute_history">
                        <td>@{{ history.created_at }}</td>
                        
                        <td>@{{ history.users_name }}</td>

                        <td>@{{ history.name_pensioner }}</td>
                        <td>@{{ history.document_pensioner }}</td>

                        <td class="w-25" v-if="history.state==1"><p class="bg-green rounded text-center">Pensión activa</p></td>
                        <td class="w-25"  v-else><p class="bg-orange rounded text-center">Pensión inactiva</p></td>
                        <td>@{{ history.type_document }}</td>
                        <td>@{{ history.number_document }}</td>
                        <td>@{{ history.date_document }}</td>

                        <td>@{{ history.name }}</td>
                        <td>@{{ history.surname }}</td>
                        <td>@{{ history.birth_date }}</td>
                        <td>@{{ history.depto }}</td>
                        <td>@{{ history.city }}</td>
                        <td>@{{ history.address }}</td>
                        <td>@{{ history.phone }}</td>
                        <td>@{{ history.email }}</td>
               
                        <td>@{{ history.type_substitute }}</td>
               
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
                    <tr v-for="(new_document, key) in dataShow.documents_news">
                        <td>@{{ new_document.created_at }}</td>
                        <td>@{{ new_document.new }}</td>
               
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>