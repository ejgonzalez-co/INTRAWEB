<?php

namespace Modules\PQRS\Models;

use Illuminate\Database\Eloquent\Model;

class PQRLeido extends Model
{
    public $table = 'pqr_leido';

    public $fillable = [
        'nombre_usuario',
        'tipo_usuario',
        'accesos',
        'destacado',
        'vigencia',
        'pqr_id',
        'users_id'
    ];

    protected $casts = [
        'nombre_usuario' => 'string',
        'tipo_usuario' => 'string',
        'accesos' => 'string'
    ];

    public static array $rules = [
        'nombre_usuario' => 'nullable|string|max:45',
        'tipo_usuario' => 'nullable|string|max:45',
        'accesos' => 'nullable|string',
        'destacado' => 'nullable',
        'vigencia' => 'nullable',
        'pqr_id' => 'required',
        'users_id' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function pqr(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\PQRS\Models\PQR::class, 'pqr_id');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\User::class, 'users_id');
    }
}
