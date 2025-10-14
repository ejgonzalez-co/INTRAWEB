<?php

namespace Modules\NotificacionesIntraweb\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class NotificacionesMailIntraweb extends Model
{
    public $table = 'notificaciones_mail_intraweb';

    public $fillable = [
        'id_mail',
        'id_comunicacion_oficial',
        'user_id',
        'user_name',
        'modulo',
        'consecutivo',
        'estado_comunicacion',
        'correo_destinatario',
        'datos_mail',
        'asunto_notificacion',
        'mensaje_notificacion',
        'estado_notificacion',
        'plantilla_notificacion',
        'respuesta_servidor_notificacion',
        'mensaje_notificacion',
        'estado_notificacion',
        'fecha_hora_leido',
        'leido',
        'mensaje_cliente',
        'ip_leido',
        'agente_navegador',
        'primer_mensaje_novalido',
        'intento'
    ];

        /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $casts = [

        'id_mail' => 'string',
        'user_name' => 'string',
        'modulo' => 'string',
        'consecutivo' => 'string',
        'estado_comunicacion' => 'string',
        'correo_destinatario' => 'string',
        'datos_mail' => 'string',
        'asunto_notificacion' => 'string',
        'mensaje_notificacion' => 'string',
        'estado_notificacion' => 'string',
        'leido' => 'string',
        'mensaje_cliente' => 'string',
        'ip_leido' => 'string',
        'agente_navegador' => 'string',
        'primer_mensaje_novalido' => 'string',

        // 'plantilla_notificacion' => 'string',
        'respuesta_servidor_notificacion' => 'string'
    ];

    public static array $rules = [
        'id_mail' => 'required|string|max:16777215',
        'user_id' => 'required',
        'id_comunicacion_oficial' => 'required',
        'user_name' => 'required|string|max:45',
        'modulo' => 'required|string|max:45',
        'consecutivo' => 'required|string|max:45',
        'asunto_notificacion' => 'required|string',
        'mensaje_notificacion' => 'required|string',
        'estado_comunicacion' => 'required|string|max:45',
        'correo_destinatario' => 'required|string|max:100',
        'datos_mail' => 'required|string',
        'estado_notificacion' => 'required|string',
        // 'plantilla_notificacion' => 'required|string',
        'respuesta_servidor_notificacion' => 'required|string',
        // 'created_at' => 'nullable',
        // 'updated_at' => 'nullable',
        // 'deleted_at' => 'nullable'
    ];

    
}
