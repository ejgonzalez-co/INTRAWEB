<?php

namespace Modules\PQRS\Models;

use Illuminate\Database\Eloquent\Model;

class PQRTipoSolicitud extends Model
{
    public $table = 'pqr_tipo_solicitud';

    public $fillable = [
        'nombre',
        'descripcion',
        'estado',
        'users_id'
    ];

    protected $casts = [
        'nombre' => 'string',
        'descripcion' => 'string',
        'estado' => 'string'
    ];

    public static array $rules = [
        'nombre' => 'nullable|string|max:100',
        'descripcion' => 'nullable|string',
        'estado' => 'nullable|string|max:30',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\User::class, 'users_id');
    }

    public function pqrs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\PQRS\Models\PQR::class, 'pqr_tipo_solicitud_id');
    }
}
