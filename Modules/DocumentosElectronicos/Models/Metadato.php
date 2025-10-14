<?php

namespace Modules\DocumentosElectronicos\Models;

use Illuminate\Database\Eloquent\Model;

class Metadato extends Model
{
    public $table = 'de_metadato';

    public $fillable = [
        'nombre_metadato',
        'variable_documento',
        'tipo',
        'texto_ayuda',
        'opciones_listado',
        'requerido',
        'users_id',
        'de_tipos_documentos_id',
        'orden'
    ];

    protected $casts = [
        'nombre_metadato' => 'string',
        'variable_documento' => 'string',
        'tipo' => 'string',
        'texto_ayuda' => 'string',
        'opciones_listado' => 'string',
        'requerido' => 'boolean'
    ];

    public static array $rules = [
        'nombre_metadato' => 'required|string|max:255',
        'variable_documento' => 'nullable|string|max:200',
        'tipo' => 'required|string|max:45',
        'texto_ayuda' => 'nullable|string|max:150',
        'opciones_listado' => 'nullable|string',
        'requerido' => 'nullable|boolean',
        'users_id' => 'required',
        'de_tipos_documentos_id' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    protected $appends = ["metadato_v_model"];

    public function getMetadatoVModelAttribute() {
        return "metadato_".$this->id;
    }

    public function deTiposDocumentos(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\DocumentosElectronicos\Models\DeTipoDocumento::class, 'de_tipos_documentos_id');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\DocumentosElectronicos\Models\User::class, 'users_id');
    }

    public function deDocumentoHasDeMetadatos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\DocumentosElectronicos\Models\DocumentoHasMetadato::class, 'de_metadatos_id');
    }
}
