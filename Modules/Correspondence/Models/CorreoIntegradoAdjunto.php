<?php

namespace Modules\Correspondence\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class CorreoIntegradoAdjunto extends Model
{
    public $table = 'comunicaciones_por_correo_adjuntos';

    public $fillable = [
        'adjunto',
        'comunicaciones_por_correo_id'
    ];

    protected $casts = [
        'adjunto' => 'string'
    ];

    public static array $rules = [
        'adjunto' => 'nullable|string',
        'comunicaciones_por_correo_id' => 'required',
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

    public function comunicacionesPorCorreo(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Correspondence\Models\ComunicacionesPorCorreo::class, 'comunicaciones_por_correo_id');
    }
}
