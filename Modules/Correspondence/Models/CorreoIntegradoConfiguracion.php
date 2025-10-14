<?php

namespace Modules\Correspondence\Models;

use Illuminate\Database\Eloquent\Model;

class CorreoIntegradoConfiguracion extends Model
{
    public $table = 'comunicaciones_por_correo_configuracion';

    public $fillable = [
        'notificacion_correspondencia',
        'notificacion_pqr',
        'notificacion_no_oficial',
        'correo_comunicaciones',
        'correo_communicaciones_clave',
        'servidor',
        'puerto',
        'seguridad',
        'obtener_desde',
        'users_id'
    ];

    protected $casts = [
        'notificacion_correspondencia' => 'string',
        'notificacion_pqr' => 'string',
        'notificacion_no_oficial' => 'string',
        'correo_comunicaciones' => 'string',
        'correo_communicaciones_clave' => 'string',
        'servidor' => 'string',
        'seguridad' => 'string'
    ];

    public static array $rules = [
        'notificacion_correspondencia' => 'nullable|string|max:250',
        'notificacion_pqr' => 'nullable|string|max:250',
        'notificacion_no_oficial' => 'nullable|string|max:250',
        'correo_comunicaciones' => 'nullable|string|max:150',
        'correo_communicaciones_clave' => 'nullable|string|max:150',
        'servidor' => 'nullable|string|max:150',
        'seguridad' => 'nullable|string|max:45',
        'users_id' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }
}
