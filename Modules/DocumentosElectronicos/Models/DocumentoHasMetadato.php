<?php

namespace Modules\DocumentosElectronicos\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoHasMetadato extends Model
{
    public $table = 'de_documento_has_de_metadato';

    public $fillable = [
        'de_documentos_id',
        'de_metadatos_id',
        'valor'
    ];

    protected $casts = [
        'valor' => 'string'
    ];

    public static array $rules = [
        'de_documentos_id' => 'required',
        'de_metadatos_id' => 'required',
        'valor' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    protected $appends = ["metadato_v_model"];

    public function getMetadatoVModelAttribute() {
        return "metadato_".$this->id;
    }

    public function deDocumentos(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\DocumentosElectronicos\Models\Documento::class, 'de_documentos_id');
    }

    public function deMetadatos(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\DocumentosElectronicos\Models\Metadato::class, 'de_metadatos_id')->orderBy("orden");
    }
}
