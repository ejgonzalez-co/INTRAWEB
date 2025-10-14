<?php

namespace Modules\PQRS\Models;

use Illuminate\Database\Eloquent\Model;

class SurveySatisfactionPqr extends Model
{
    public $table = 'pqr_encuesta_satisfaccion';

    public $fillable = [
        'users_id',
        'pqr_id',
        'respuesta1',
        'respuesta2',
        'respuesta3',
        'respuesta4'
    ];

    protected $casts = [
        'respuesta1' => 'string',
        'respuesta2' => 'string',
        'respuesta3' => 'string',
        'respuesta4' => 'string'
    ];

    public static array $rules = [
        'respuesta1' => 'nullable|string|max:4',
        'respuesta2' => 'nullable|string|max:4',
        'respuesta3' => 'nullable|string|max:4',
        'respuesta4' => 'nullable|string|max:4',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function pqr(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\PQRS\Models\Pqr::class, 'pqr_id');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\PQRS\Models\User::class, 'users_id');
    }
}
