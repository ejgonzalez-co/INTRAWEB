<?php

namespace Modules\ExpedientesElectronicos\Models;

use Illuminate\Database\Eloquent\Model;

class PermisoUsuariosExpediente extends Model
{
    public $table = 'ee_permiso_usuarios_expendientes';

    public $fillable = [
        'nombre',
        'correo',
        'pin_acceso',
        'permiso',
        'ee_expedientes_id',
        'dependencia_usuario_id',
        'tipo',
        'tipo_usuario',
        'limitar_descarga_documentos'
    ];

    protected $casts = [
        'nombre' => 'string',
        'correo' => 'string',
        'pin_acceso' => 'string',
        'permiso' => 'string'
    ];

    public static array $rules = [
        'nombre' => 'required|string|max:255',
        'correo' => 'required|string|max:150',
        'pin_acceso' => 'required|string|max:20',
        'permiso' => 'required|string|max:100',
        'limitar_descarga_documentos' => 'integer',
        'ee_expedientes_id' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

 
    public function dependencias_usuarios(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        if($this->tipo == "Dependencia") {
            return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'dependencia_usuario_id');
        } else {
            return $this->belongsTo(\Modules\Intranet\Models\User::class, 'dependencia_usuario_id');
        }
    }

    public function eeExpedientes(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\ExpedientesElectronicos\Models\Expediente::class, 'ee_expedientes_id');
    }
}
