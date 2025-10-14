@extends('layouts.layoutPublic')

@section('content')
<div style="height:90vh;" class="d-flex align-items-center justify-content-center ">
    <div class="d-flex align-items-center justify-content-center bg-light" style="height:50vh; width: 35rem;">
        <div class="card text-center p-4 shadow-lg border-success" style="width: 30rem;">
            <!-- Icono de check verde y el mensaje -->
            <div class="mb-3" >
                <i class="fas fa-check-circle fa-5x text-success"></i>
            </div>
            <h3 class="text-success">¡Documento marcado como leído!</h3>
            <p class="text-muted">El documento se ha marcado como leído exitosamente.</p>

            <p>Hora: {{$notificacion['fecha_hora_leido']}}</p>
            <p>IP: {{$notificacion['ip_leido']}}</p>

        </div>
    </div>
</div>
  
@endsection

@section('scripts')
    <!-- Incluir Font Awesome (si no lo has incluido previamente) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
@endsection
