<?php

namespace Modules\ExpedientesElectronicos\Models;

use Illuminate\Database\Eloquent\Model;

class ExpedienteLeido extends Model
{
    public $table = 'ee_expediente_leido';

    public $fillable = [
        'tipo_usuario',
        'nombre_usuario',
        'accesos',
        'vigencia',
        'users_id',
        'ee_expediente_id'
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
        'users_id' => 'required',
        'ee_expediente_id' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function eeDocumentosExpediente(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\ExpedientesElectronicos\Models\documentosExpediente::class, 'ee_expediente_id');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }
}
