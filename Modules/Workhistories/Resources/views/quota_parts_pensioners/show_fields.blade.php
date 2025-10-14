<!-- Panel estructura organizacional -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Información general del pensionado</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="row">
            <!-- type_document Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Type Document'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.type_document }}</label>
                </div>
            </div>
            
            <!-- number_document Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Number Document'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.number_document }}</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Date') de expedición del @lang('Document'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.date_document }}</label>
                </div>
            </div>


            <!-- birth_date Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Birth Date'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.birth_date }}</label>
                </div>
            </div>

            <!-- name Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Name'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.name }} @{{ dataShow.surname }}</label>
                </div>
            </div>

            <!-- gender Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Gender'):</strong></label>
                    <label v-if="dataShow.gender==1" class="col-form-label col-md-8">F</label>
                    <label v-if="dataShow.gender==2" class="col-form-label col-md-8">No determinado</label>
                    {{-- <label v-else class="col-form-label col-md-8">M</label> --}}
                    <label v-if="dataShow.gender==0" class="col-form-label col-md-8">M</label>
                </div>
            </div>

            <!-- group_ethnic Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Group Ethnic'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.group_ethnic }}</label>
                </div>
            </div>

            <!-- rh Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('RH'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.rh }}</label>
                </div>
            </div>

            <!-- level_study Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Level study'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.level_study }}</label>
                    <span v-if="dataShow.level_study=='Otros'" class="col-form-label col-md-8">@{{ dataShow.level_study_other }}</span>

                </div>
            </div>

        </div>
    </div>
</div>

<!-- Panel estructura organizacional -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Información de contacto del pensionado</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="row">

            <!-- address Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Address'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.address }}</label>
                </div>
            </div>

            <!--phone Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Phone'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.phone }}</label>
                </div>
            </div>

            <!--emailField -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Email'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.email }}</label>
                </div>
            </div>
      

        </div>
    </div>
</div>


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
            <table class="table table-hover table-bordered">
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
                        <th>@lang('Name')</th>
                        <th>@lang('Phone')</th>
                
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
                        <td v-if="history.gender==1">F</td>
                        <td v-else>M</td>
                        <td>@{{ history.group_ethnic }}</td>
                        <td>@{{ history.birth_date }}</td>
                        <td>@{{ history.level_study }}</td>
                        <td>@{{ history.name_event }}</td>
                        <td>@{{ history.phone_event }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>




<!-- Panel de documentos adjuntos -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Documentos adjuntos</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>@lang('Created_at')</th>
                        <th>@lang('Type')</th>
                        <th>@lang('Description')</th>
                        <th>@lang('Document')</th>

                        
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(document, key) in dataShow.work_histories_cp_p_documents">
                        <td>@{{ document.created_at }}</td>
                        <td>@{{ document.config_documents.name }}</td>
                        <td>@{{ document.description }}</td>
                        <td><a class="col-9 text-truncate"  v-if="document.url_document"  :href="'{{ asset('storage') }}/'+document.url_document" target="_blank">Ver adjunto</a></td>

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
            <table class="table table-hover table-bordered">
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


<!-- Panel observaciones de documentos adjuntos -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Cuotas partes</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>@lang('Created_at')</th>
                        <th>@lang('Name Company')</th>
                        <th>@lang('Observation')</th>
                        <th>@lang('Document')</th>

                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(observation, key) in dataShow.work_histories_cps">
                        <td>@{{ observation.created_at }}</td>
                        <td>@{{ observation.name_company }}</td>
                        <td>@{{ observation.observation }}</td>
                        <td><a class="col-9 text-truncate"  v-if="observation.url_document"  :href="'{{ asset('storage') }}/'+observation.url_document" target="_blank">Ver adjunto</a></td>

                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>


<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Historial de las cuotas partes</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>@lang('Created_at')</th>
                        <th>@lang('User')</th>
                        <th>@lang('Observation')</th>
                            
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(observation, key) in dataShow.quota_parts_news">
                        <td>@{{ observation.created_at }}</td>
                        <td>@{{ observation.users_name }}</td>
                        <td>@{{ observation.new }}</td>
               
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>


<!-- Panel observaciones de documentos adjuntos -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Información de fallecimiento del pensionado</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

         <div class="col-md-6">
            <div class="form-group row m-b-15">
                <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Deceased'):</strong></label>
                <label class="col-form-label col-md-8" v-if="dataShow.deceased==''">No</label>
                <label class="col-form-label col-md-8" v-else>@{{ dataShow.deceased }}</label>
            </div>
        </div>
      

        <div class="col-md-6" v-if="dataShow.deceased=='Si'">
            <div class="form-group row m-b-15">
                <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Observation'):</strong></label>
                <label class="col-form-label col-md-8">@{{ dataShow.observation_deceased }}</label>
            </div>
        </div>
      
    </div>
</div>