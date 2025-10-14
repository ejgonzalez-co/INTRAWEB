<!-- Panel -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Información general del pensionado</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="row">

          <!-- name Field -->
          <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Name'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.name }} @{{ dataShow.surname }}</label>
                </div>
            </div>

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

          

            <!-- gender Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Gender'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.gender }}</label>
                </div>
            </div>

             <!-- rh Field -->
             <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('RH'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.rh }}</label>
                </div>
            </div>

            <!-- group_ethnic Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Group Ethnic'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.group_ethnic }}</label>
                </div>
            </div>

            <!-- state_civil Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('State') civil:</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.state_civil }}</label>
                </div>
            </div>

        

        </div>
    </div>
</div>

<!-- Panel -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Información de contacto del pensionado</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="row">

              <!--phone Field -->
              <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Phone'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.phone }}</label>
                </div>
            </div>

            <!--phone Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Phone') 2:</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.phone2 }}</label>
                </div>
            </div>

            <!-- address Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Address'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.address }}</label>
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


<!-- Panel -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Información de estudios</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="row">

              <!--phone Field -->
              <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Level study'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.level_study }}</label>
                </div>
            </div>

            <!--phone Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Titulo adquirido o grado de escolaridad'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.level_study_other }}</label>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="panel w-100" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Datos Laborales</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Date admission'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.date_admission }}</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Type'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.type_laboral }}</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Level'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.level_laboral }}</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Denomination'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.denomination_laboral }}</label>
                </div>
            </div>            
            
            
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Grade'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.grade_laboral }}</label>
                </div>
            </div>         

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Dependency'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.dependencias_name }}</label>
                </div>
            </div>         

                        
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Número de cuenta bancaria'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.numer_account }}</label>
                </div>
            </div>           

                        
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Salary'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.salary }}</label>
                </div>
            </div>           
              
  
        </div>
    </div>

</div>


<div class="panel w-100" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Seguridad Social Integral y Embargos</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Health'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.health }}</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('ARL'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.arl }}</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Pension'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.pension }}</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Layoffs'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.layoffs }}</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Embargo'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.embargo }}</label>
                </div>
            </div>

            <div class="col-md-6" v-if="dataShow.embargo == 'Si'">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Value'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.embargo_value }}</label>
                </div>
            </div>

            <div class="col-md-6" v-if="dataShow.embargo == 'Si'">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Entity'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.embargo_owner }}</label>
                </div>
            </div>
  
        </div>
    </div>

</div>

<div class="panel w-100" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Notificar en caso de evento</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <!-- name_event Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Name'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.name_event }}</label>
                </div>
            </div>

            <!-- phone_event Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Phone'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.phone_event }}</label>
                </div>
            </div>
  
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Address'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.address_event }}</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Relationship'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.relationship_event }}</label>
                </div>
            </div>
        </div>
    </div>

</div>


<!-- Panel familiar -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Datos Familiares</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>@lang('Type')</th>
                        <th>@lang('Name')</th>
                        <th>@lang('Gender')</th>
                        <th>@lang('Birth Date')</th>
                        <th>@lang('State')</th>

                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(familiar, key) in dataShow.work_historie_families">
                        <td>@{{ familiar.type }}</td>
                        <td>@{{ familiar.name }}</td>
                        <td>@{{ familiar.gender }}</td>
                        <td>@{{ familiar.birth_date }}</td>
                        <td>@{{ familiar.state }}</td>

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
                    <tr v-for="(document, key) in dataShow.work_historie_documents">
                        <td>@{{ document.created_at }}</td>
                        <td>@{{ document.work_histories_config_documents.name }}</td>
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
                    <tr v-for="(new_document, key) in dataShow.documents_news">
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
        <h3 class="panel-title"><strong>Observaciones</strong></h3>
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
                    <tr v-for="(observation, key) in dataShow.work_historie_news">
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