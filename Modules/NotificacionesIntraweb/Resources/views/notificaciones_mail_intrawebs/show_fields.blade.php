<div class="row">
   <!-- Cities Id Field -->
   <dt class="text-inverse text-left col-4 ">Usuario que realizó la notifiación:</dt>
   <dd class="col-7 ">@{{ dataShow.user_name ? dataShow.user_name : 'N/A' }}</dd>
</div>
<br>

<div class="row">
   <!-- Cities Id Field -->
   <dt class="text-inverse text-left col-4 ">Modulo donde se realizó la notificación:</dt>
   <dd class="col-7 ">@{{ dataShow.modulo ? dataShow.modulo : 'N/A' }}</dd>
</div>
<br>

<div class="row">
   <!-- Cities Id Field -->
   <dt class="text-inverse text-left col-4 ">Consecutivo cuando se realizó la notificación:</dt>
   <dd class="col-7 ">@{{ dataShow.consecutivo ? dataShow.consecutivo : 'N/A' }}</dd>
</div>
<br>

<div class="row">
   <!-- Cities Id Field -->
   <dt class="text-inverse text-left col-4 ">Estado cuando se realizó la notificación:</dt>
   <dd class="col-7 ">@{{ dataShow.estado_comunicacion ? dataShow.estado_comunicacion : 'N/A' }}</dd>
</div>
<br>

<div class="row">
   <!-- Cities Id Field -->
   <dt class="text-inverse text-left col-4 ">Correo destinatario:</dt>
   <dd class="col-7 ">@{{ dataShow.correo_destinatario ? dataShow.correo_destinatario : 'N/A' }}</dd>
</div>
<br>

<div class="row">
   <!-- Cities Id Field -->
   <dt class="text-inverse text-left col-4 ">Asunto de la notificación:</dt>
   <dd class="col-7 ">@{{ dataShow.asunto_notificacion ? dataShow.asunto_notificacion : 'N/A' }}</dd>
</div>
<br>

<div class="row">
   <!-- Cities Id Field -->
   <dt class="text-inverse text-left col-4 ">Fecha en que se realizó la notificación:</dt>
   <dd class="col-7 ">@{{formatDate(dataShow.created_at)}}</dd>
</div>
<br>

<div class="row" v-if="dataShow.mensaje_notificacion">
   <!-- Cities Id Field -->
   <dt class="text-inverse text-left col-4 ">Mensaje de la notificación:</dt>
   <dd class="col-7" v-html="dataShow.mensaje_notificacion"></dd>
</div>
<br v-if="dataShow.mensaje_notificacion">

<div class="row" v-if="dataShow.primer_mensaje_novalido == 'No entregado'">
   <!-- Cities Id Field -->
   <dt class="text-inverse text-left col-4 ">Razon por la cual el correo no es válido:</dt>
   <dd class="col-7 ">@{{ dataShow.primer_mensaje_novalido ? dataShow.primer_mensaje_novalido : 'N/A' }}</dd>
</div>
<br>

<div class="row">
   <!-- Cities Id Field -->
   <dt class="text-inverse text-left col-4 ">Estado de la notificaión:</dt>
   <dd class="col-7 ">@{{ dataShow.estado_notificacion ? dataShow.estado_notificacion : 'N/A' }}</dd>
</div>

<br v-if="dataShow.leido">
<div class="row" v-if="dataShow.leido">
   <!-- Cities Id Field -->
   <dt class="text-inverse text-left col-4">El mensaje ya fue leido: </dt>
   <dd class="col-7 ">@{{ dataShow.leido ? dataShow.leido : 'N/A' }}</dd>
</div>

<br v-if="dataShow.fecha_hora_leido">
<div class="row" v-if="dataShow.fecha_hora_leido">
   <!-- Cities Id Field -->
   <dt class="text-inverse text-left col-4">Fecha y hora que se leyó la notificacion: </dt>
   <dd class="col-7 ">@{{ dataShow.fecha_hora_leido ? dataShow.fecha_hora_leido : 'N/A' }}</dd>
</div>


<br v-if="dataShow.ip_leido">
<div class="row" v-if="dataShow.ip_leido">
   <!-- Cities Id Field -->
   <dt class="text-inverse text-left col-4">Dirección IP de apertura: </dt>
   <dd class="col-7 ">@{{ dataShow.ip_leido ? dataShow.ip_leido : 'N/A' }}</dd>
</div>

{{-- <br v-if="dataShow.agente_navegador"> --}}
{{-- <div class="row" v-if="dataShow.agente_navegador">
   <!-- Cities Id Field -->
   <dt class="text-inverse text-left col-4">Navegador donde se leyó la notificación: </dt>
   <dd class="col-7 ">@{{ dataShow.agente_navegador ? dataShow.agente_navegador : 'N/A' }}</dd>
</div> --}}

<br v-if="dataShow.mensaje_cliente">
<div class="row" v-if="dataShow.mensaje_cliente">
   <!-- Cities Id Field -->
   <dt class="text-inverse text-left col-4">Información del envio: </dt>
   <dd class="col-7 ">@{{ dataShow.mensaje_cliente ? dataShow.mensaje_cliente : 'N/A' }}</dd>
</div>

<br v-if="dataShow.respuesta_servidor_notificacion">
<div class="row" v-if="dataShow.respuesta_servidor_notificacion">
   <!-- Cities Id Field -->
   <dt class="text-inverse text-left col-4">Respuesta del envio: </dt>
   <dd class="col-7 ">@{{ dataShow.respuesta_servidor_notificacion ? dataShow.respuesta_servidor_notificacion : 'N/A' }}</dd>
</div>







