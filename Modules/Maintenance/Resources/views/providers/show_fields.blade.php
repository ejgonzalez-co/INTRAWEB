<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Información general</strong></h3>
    </div>
    <div class="panel-body">
        <dl class="row">
            <dt class="text-inverse text-left col-3 text-truncate">Tipo de Proveedor:</dt>
            <dd class="col-9 text-truncate">@{{ dataShow.type_provider }}.</dd>

            <dt class="text-inverse text-left col-3 text-truncate">@lang('Type Person'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow.type_person }}.</dd>
            <dt class="text-inverse text-left col-3 text-truncate">@lang('Document Type'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow.document_type }}.</dd>

            <dt class="text-inverse text-left col-3 text-truncate">@lang('Identification'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow.identification }}.</dd>
            <dt class="text-inverse text-left col-3 text-truncate">@lang('Name'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow.name }}.</dd>

            <dt class="text-inverse text-left col-3 text-truncate">@lang('Mail'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow.mail }}.</dd>
            <dt class="text-inverse text-left col-3 text-truncate">@lang('Regime'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow.regime }}.</dd>

            <dt class="text-inverse text-left col-3 text-truncate">@lang('Phone'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow.phone }}.</dd>
            <dt class="text-inverse text-left col-3 text-truncate">@lang('Address'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow.address }}.</dd>

            <dt class="text-inverse text-left col-3 text-truncate">@lang('Municipality'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow.municipality }}.</dd>
            <dt class="text-inverse text-left col-3 text-truncate">@lang('Department'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow.department }}.</dd>

            <dt class="text-inverse text-left col-3 text-truncate">@lang('Types activity'):</dt>
            <dd class="col-9 text-truncate">@{{ dataShow.mant_types_activity ? dataShow.mant_types_activity.name : '' }}.</dd>
        </dl>
    </div>
</div>

<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Información representante legal</strong></h3>
    </div>
    <div class="panel-body">
        <dl class="row">
            <dt class="text-inverse text-left col-3 text-truncate">@lang('Name'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow.name_rep }}.</dd>
            <dt class="text-inverse text-left col-3 text-truncate">@lang('Document Type'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow.document_type_rep }}.</dd>

            <dt class="text-inverse text-left col-3 text-truncate">@lang('Identification'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow.identification_rep }}.</dd>
            <dt class="text-inverse text-left col-3 text-truncate">@lang('Phone'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow.phone_rep }}.</dd>

            <dt class="text-inverse text-left col-3 text-truncate">@lang('Mail'):</dt>
            <dd class="col-9 text-truncate">@{{ dataShow.mail_rep }}.</dd>
        </dl>
    </div>
</div>

<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Correos de contacto opcional</strong></h3>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Observación</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(contact, key) in dataShow.optional_contact_emails">
                        <td>@{{ contact.name }}</td>
                        <td>@{{ contact.mail }}</td>
                        <td>@{{ contact.phone }}</td>
                        <td>@{{ contact.observation }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Otros datos</strong></h3>
    </div>
    <div class="panel-body">
        <dl class="row">
            <div v-if="dataShow.firma_proveedor" class="col-12 row m-b-15">
                <dt class="text-inverse text-left col-3 text-truncate">Firma Proveedor:</dt>
                <dd class="col-9">
                    <a :href="'/storage/' + dataShow.firma_proveedor" target="_blank">Ver Firma</a>
                </dd>
            </div>

            <dt class="text-inverse text-left col-3 text-truncate">@lang('Descripción'):</dt>
            <dd class="col-9" style="white-space: pre-wrap;">@{{ dataShow.description }}.</dd>

            <dt class="text-inverse text-left col-3 text-truncate">@lang('State'):</dt>
            <dd class="col-9 text-truncate">@{{ dataShow.state }}.</dd>
        </dl>
    </div>
</div>

<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Soportes del proveedor</strong></h3>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Adjunto</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(document, key) in dataShow.mant_supports_provider">
                        <td>@{{ document.name }}</td>
                        <td style="white-space: break-spaces;">@{{ document.description }}</td>
                        <td v-if="document.url_document != null">
                            <span v-for="documento in document.url_document.split(',')"><a class="col-3 text-truncate" :href="'{{ asset('storage') }}/'+documento" target="_blank">Ver adjunto</a><br/></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>