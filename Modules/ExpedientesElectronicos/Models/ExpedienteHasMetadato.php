<?php

namespace Modules\ExpedientesElectronicos\Models;

use Illuminate\Database\Eloquent\Model;

class ExpedienteHasMetadato extends Model
{
    public $table = 'ee_expediente_has_metadatos';

    public $fillable = [
        'ee_expediente_id',
        'ee_metadatos_id',
        'ee_documentos_expediente_id',
        'valor'
    ];

    protected $casts = [
        'valor' => 'string'
    ];

    public static array $rules = [
        'ee_expediente_id' => 'required',
        'ee_metadatos_id' => 'required',
        'valor' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function expediente(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\ExpedientesElectronicos\Models\Expediente::class, 'ee_expediente_id');
    }

    public function metadatos(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\DocumentaryClassification\Models\criteriosBusqueda::class, 'ee_metadatos_id');
    }
}
