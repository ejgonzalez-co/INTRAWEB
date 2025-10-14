<!-- Panel de documentos adjuntos -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Información general</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

            <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Payment Account Number'):</strong></label>
                            <label class="col-form-label col-md-8">@{{ dataShow.payment_account_number }}</label>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Subscriber Quality'):</strong></label>
                            <label class="col-form-label col-md-8">@{{ dataShow.subscriber_quality }}</label>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Citizen Name'):</strong></label>
                            <label class="col-form-label col-md-8">@{{ dataShow.citizen_name }}</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Document Type'):</strong></label>
                            <label class="col-form-label col-md-8">@{{ dataShow.document_type }}</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Identification'):</strong></label>
                            <label class="col-form-label col-md-8">@{{ dataShow.identification }}</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Gender'):</strong></label>
                            <label class="col-form-label col-md-8">@{{ dataShow.gender }}</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Telephone'):</strong></label>
                            <label class="col-form-label col-md-8">@{{ dataShow.telephone }}</label>
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
                            <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Date Birth'):</strong></label>
                            <label class="col-form-label col-md-8">@{{ dataShow.date_birth }}</label>
                        </div>
                    </div>

                    
         

            </div>
    </div>

</div>






<!-- Panel de documentos adjuntos -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Historial</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>@lang('Created_at')</th>
                        <th>@lang('Payment Account Number')</th>
                        <th>@lang('Subscriber Quality')</th>
                        <th>@lang('Citizen Name')</th>
                        <th>@lang('Document Type')</th>
                        <th>@lang('Identification')</th>
                        <th>@lang('Contact')</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(udcRequest, key) in dataShow.udc_request_histories">
                        <td>@{{ udcRequest.created_at }}</td>
                        <td>@{{ udcRequest.payment_account_number }}</td>
                        <td>@{{ udcRequest.subscriber_quality }}</td>
                        <td>@{{ udcRequest.citizen_name }}</td>
                        <td>@{{ udcRequest.document_type }}</td>
                        <td>@{{ udcRequest.identification }}</td>
                        <td>@{{ udcRequest.telephone }} - @{{ udcRequest.email }}</td>

                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>


