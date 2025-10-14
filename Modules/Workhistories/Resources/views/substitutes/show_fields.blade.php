<!-- Panel -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Información del pensionado fallecido</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="col-md-12 row">

        <div class="col-md-6">
            <div class="form-group row m-b-15">
                <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Name'):</strong></label>
                <label class="col-form-label col-md-8">@{{ dataShow.work_histories_p ? dataShow.work_histories_p.name : '' }}  @{{ dataShow.work_histories_p ? dataShow.work_histories_p.surname : '' }}</label>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row m-b-15">
                <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Number Document'):</strong></label>
                <label class="col-form-label col-md-8">@{{ dataShow.work_histories_p ? dataShow.work_histories_p.number_document : '' }}</label>
            </div>
        </div>

   

        </div>
 
    </div>
</div>


<!-- Panel -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Información del sustituto</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">


        <div class="col-md-6">
            <div class="form-group row m-b-15">
                <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Type Document'):</strong></label>
                <label class="col-form-label col-md-8">@{{ dataShow.type_document }}</label>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row m-b-15">
                <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Number Document'):</strong></label>
                <label class="col-form-label col-md-8">@{{ dataShow.number_document }}</label>
            </div>
        </div>


        <div class="col-md-6">
            <div class="form-group row m-b-15">
                <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Date') @lang('Document'):</strong></label>
                <label class="col-form-label col-md-8">@{{ dataShow.date_document }}</label>
            </div>
        </div>

            
        <div class="col-md-6">
            <div class="form-group row m-b-15">
                <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Name'):</strong></label>
                <label class="col-form-label col-md-8">@{{ dataShow.name }} @{{ dataShow.surname }}</label>
            </div>
        </div>
      
        <div class="col-md-6">
            <div class="form-group row m-b-15">
                <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Birth Date'):</strong></label>
                <label class="col-form-label col-md-8">@{{ dataShow.birth_date }}</label>
            </div>
        </div>
       

        <div class="col-md-6">
            <div class="form-group row m-b-15">
                <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Departament'):</strong></label>
                <label class="col-form-label col-md-8">@{{ dataShow.depto }}</label>
            </div>
        </div>
     
        <div class="col-md-6">
            <div class="form-group row m-b-15">
                <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('City'):</strong></label>
                <label class="col-form-label col-md-8">@{{ dataShow.city }}</label>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row m-b-15">
                <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Address'):</strong></label>
                <label class="col-form-label col-md-8">@{{ dataShow.address }}</label>
            </div>
        </div>
     
           
        <div class="col-md-6">
            <div class="form-group row m-b-15">
                <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Phone'):</strong></label>
                <label class="col-form-label col-md-8">@{{ dataShow.phone }}</label>
            </div>
        </div>
     
    
        <div class="col-md-6">
            <div class="form-group row m-b-15">
                <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Email'):</strong></label>
                <label class="col-form-label col-md-8">@{{ dataShow.email }}</label>
            </div>
        </div>
 

        <div class="col-md-6">
            <div class="form-group row m-b-15">
                <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Type') @lang('Substitute'):</strong></label>
                <label class="col-form-label col-md-8">@{{ dataShow.type_substitute }}</label>
            </div>
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
                    <tr v-for="(document, key) in dataShow.documents_substitute">
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