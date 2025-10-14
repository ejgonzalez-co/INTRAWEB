<?php

namespace Modules\Calidad\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoLeido extends Model
{
    public $table = 'calidad_documento_leido';

    public $fillable = [
        'tipo_usuario',
        'nombre_usuario',
        'accesos',
        'vigencia',
        'calidad_documento_id',
        'users_id'
    ];

    protected $casts = [
        'tipo_usuario' => 'string',
        'nombre_usuario' => 'string',
        'accesos' => 'string'
    ];

    public static array $rules = [
        'tipo_usuario' => 'nullable|string|max:100',
        'nombre_usuario' => 'nullable|string|max:255',
        'accesos' => 'nullable|string',
        'vigencia' => 'nullable',
        'calidad_documento_id' => 'required',
        'users_id' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function calidadDocumento(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Calidad\Models\CalidadDocumento::class, 'calidad_documento_id');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Calidad\Models\User::class, 'users_id');
    }
}
