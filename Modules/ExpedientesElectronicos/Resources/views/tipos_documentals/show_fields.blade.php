<div class="container-fluid bg-light py-4">

<div class="card">
    <div class="card-body">
        <h5 class="mb-3"><strong>Información inicial</strong></h5>
        <div class="row row-cols-1 row-cols-md-1 row-cols-lg-2 g-3 d-flex flex-wrap">

        <div class="col-md-6 mb-2">
            <p class="mb-2"><strong><i class="fas fa-user"></i>  Usuario creador</strong></p>
            <p>@{{ dataShow.user_name ? dataShow.user_name : 'N/A' }}.</p>
        </div>
        <div class="col-md-6 mb-2">
            <p class="mb-2"><strong><i class="fas fa-file"></i> Tipo de documento</strong></p>
            <p>@{{ dataShow.tipo_documento ? dataShow.tipo_documento : 'N/A' }}.</p>
        </div>

        <div class="col-md-6 mb-2">
            <p class="mb-2"><strong><i class="fas fa-info-circle"></i> Estado</strong></p>
            <p>@{{ dataShow.estado ? dataShow.estado : 'N/A' }}.</p>
        </div>
        </div>
    </div>
</div>

</div>


{{-- <div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Información inicial</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <dt class="text-inverse col-sm-3 col-md-3 col-lg-3 ">Tipo de documento:</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.tipo_documento ? dataShow.tipo_documento : 'N/A' }}.</dd>

            <dt class="text-inverse col-sm-3 col-md-3 col-lg-3 ">Estado:</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.estado ? dataShow.estado : 'N/A' }}.</dd>
        </div>
        <div class="row">
            <dt class="text-inverse col-sm-3 col-md-3 col-lg-3 ">Usuario creador:</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.user_name ? dataShow.user_name : 'N/A' }}.</dd>
        </div>
    </div>
</div> --}}