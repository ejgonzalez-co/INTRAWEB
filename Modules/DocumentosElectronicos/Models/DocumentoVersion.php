<?php

namespace Modules\DocumentosElectronicos\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class DocumentoVersion extends Model
{
    public $table = 'de_documento_version';

    public $fillable = [
        'numero_version',
        'tipo_usuario',
        'nombre_usuario',
        'correo',
        'estado',
        'observacion',
        'document_pdf_temp',
        'users_id',
        'de_documentos_id'
    ];

    protected $casts = [
        'tipo_usuario' => 'string',
        'nombre_usuario' => 'string',
        'correo' => 'string',
        'estado' => 'string',
        'observacion' => 'string',
        'document_pdf_temp' => 'string'
    ];

    public static array $rules = [
        'numero_version' => 'nullable',
        'tipo_usuario' => 'nullable|string|max:80',
        'nombre_usuario' => 'nullable|string|max:200',
        'correo' => 'nullable|string|max:255',
        'estado' => 'nullable|string|max:90',
        'observacion' => 'nullable|string',
        'document_pdf_temp' => 'nullable|string',
        'users_id' => 'nullable',
        'de_documentos_id' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\User::class, 'users_id');
    }

    public function deDocumentos(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\DocumentosElectronicos\Models\Documento::class, 'de_documentos_id');
    }

    public function deDocumentoFirmars(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\DocumentosElectronicos\Models\DocumentoFirmar::class, 'de_documento_version_id')->with("users");
    }
}
