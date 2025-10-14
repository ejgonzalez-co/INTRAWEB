<?php

namespace Modules\PQRS\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class PQREjeTematicoHistorial extends Model
{
    public $table = 'pqr_eje_tematico_historial';

    public $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'tipo_plazo',
        'plazo',
        'plazo_unidad',
        'temprana',
        'temprana_unidad',
        'estado',
        'users_id',
        'pqr_eje_tematico_id'
    ];

    protected $casts = [
        'codigo' => 'string',
        'nombre' => 'string',
        'descripcion' => 'string',
        'tipo_plazo' => 'string',
        'plazo_unidad' => 'string',
        'temprana_unidad' => 'string',
        'estado' => 'string'
    ];

    public static array $rules = [
        'codigo' => 'nullable|string|max:50',
        'nombre' => 'nullable|string|max:255',
        'descripcion' => 'nullable|string',
        'tipo_plazo' => 'nullable|string|max:50',
        'plazo' => 'nullable',
        'plazo_unidad' => 'nullable|string|max:50',
        'temprana' => 'nullable',
        'temprana_unidad' => 'nullable|string|max:50',
        'estado' => 'nullable|string|max:30',
        'users_id' => 'required',
        'pqr_eje_tematico_id' => 'required',
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

    public function pqrEjeTematico(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\PQRS\Models\PQREjeTematico::class, 'pqr_eje_tematico_id');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\User::class, 'users_id');
    }
}
