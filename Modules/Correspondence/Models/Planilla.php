<?php

namespace Modules\Correspondence\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Planilla extends Model
{
    public $table = 'correspondence_planilla';

    public $fillable = [
        'consecutivo',
        'estado',
        'contenido',
        'consulta_sql',
        'tipo_correspondencia',
        'rango_planilla',
        'users_id',
        'nombre_usuario',
        'correspondence_planilla_ruta_id'
    ];

    protected $casts = [
        'estado' => 'string',
        'contenido' => 'string',
        'consulta_sql' => 'string',
        'tipo_correspondencia' => 'string',
        'rango_planilla' => 'string',
        'nombre_usuario' => 'string'
    ];

    public static array $rules = [
        'estado' => 'nullable|string|max:50',
        'contenido' => 'nullable|string',
        'consulta_sql' => 'nullable|string',
        'tipo_correspondencia' => 'required|string|max:150',
        'nombre_usuario' => 'nullable|string|max:90',
        'correspondence_planilla_ruta_id' => 'required',
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

    public function planillaRuta(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Correspondence\Models\PlanillaRuta::class, 'correspondence_planilla_ruta_id');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }
}
