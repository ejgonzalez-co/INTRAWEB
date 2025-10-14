<?php

namespace Modules\Correspondence\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class CorreoIntegradoHistorial extends Model
{
    public $table = 'comunicaciones_por_correo_historial';

    public $fillable = [
        'consecutivo',
        'asunto',
        'contenido',
        'correo_remitente',
        'nombre_remitente',
        'fecha',
        'estado',
        'clasificacion',
        'uid',
        'vigencia',
        'users_id',
        'nombre_usuario',
        'external_received_id',
        'comunicaciones_por_correo_id'
    ];

    protected $casts = [
        'consecutivo' => 'string',
        'asunto' => 'string',
        'contenido' => 'string',
        'correo_remitente' => 'string',
        'nombre_remitente' => 'string',
        'fecha' => 'datetime',
        'estado' => 'string',
        'clasificacion' => 'string',
        'uid' => 'string',
        'nombre_usuario' => 'string'
    ];

    public static array $rules = [
        'consecutivo' => 'nullable|string|max:100',
        'asunto' => 'nullable|string|max:500',
        'contenido' => 'nullable|string',
        'correo_remitente' => 'nullable|string|max:100',
        'nombre_remitente' => 'nullable|string|max:100',
        'fecha' => 'nullable',
        'estado' => 'nullable|string|max:50',
        'clasificacion' => 'nullable|string|max:100',
        'uid' => 'nullable|string|max:50',
        'users_id' => 'nullable',
        'nombre_usuario' => 'nullable|string|max:150',
        'external_received_id' => 'nullable',
        'comunicaciones_por_correo_id' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
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

    public function externalReceived(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Correspondence\Models\ExternalReceived::class, 'external_received_id');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }
}
