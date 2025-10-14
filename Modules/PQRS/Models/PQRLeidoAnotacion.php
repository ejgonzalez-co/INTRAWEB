<?php

namespace Modules\PQRS\Models;

use Illuminate\Database\Eloquent\Model;

class PQRLeidoAnotacion extends Model
{
    public $table = 'pqr_anotacion_leido';

    public $fillable = [
        'tipo_usuario',
        'nombre_usuario',
        'accesos',
        'vigencia',
        'pqr_anotacion_id',
        'users_id'
    ];

    protected $casts = [
        'tipo_usuario' => 'string',
        'nombre_usuario' => 'string',
        'accesos' => 'string'
    ];

    public static array $rules = [
        'tipo_usuario' => 'nullable|string|max:45',
        'nombre_usuario' => 'nullable|string|max:45',
        'accesos' => 'nullable|string',
        'vigencia' => 'nullable',
        'pqr_anotacion_id' => 'required',
        'users_id' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function pqrAnotacion(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\PQRS\Models\PQRAnotacion::class, 'pqr_anotacion_id');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\User::class, 'users_id');
    }
}
