<?php

namespace Modules\PQRS\Models;

use Illuminate\Database\Eloquent\Model;

class PQREjeTematico extends Model
{
    public $table = 'pqr_eje_tematico';

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
        'users_id'
    ];

    protected $casts = [
        'codigo' => 'string',
        'nombre' => 'string',
        'descripcion' => 'string',
        'plazo' => 'integer',
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
        'plazo' => 'nullable|integer',
        'plazo_unidad' => 'nullable|string|max:50',
        'temprana' => 'nullable',
        'temprana_unidad' => 'nullable|string|max:50',
        'estado' => 'nullable|string|max:30',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function pqrEjeTematicoHistorial(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\PQRS\Models\PQREjeTematicoHistorial::class, 'pqr_eje_tematico_id')->with("users");
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\User::class, 'users_id');
    }

    public function pqrs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\PQRS\Models\PQR::class, 'pqr_eje_tematico_id');
    }

    public function ejetematicoHasDependencias(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\PQRS\Models\PQREjeTematicoDependencias::class, 'pqr_eje_tematico_id')->with("dependencias");
    }
}
