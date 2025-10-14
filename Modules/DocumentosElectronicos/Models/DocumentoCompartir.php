<?php

namespace Modules\DocumentosElectronicos\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class DocumentoCompartir extends Model
{
    public $table = 'de_documento_compartir';

    public $fillable = [
        'tipo_usuario',
        'categoria',
        'nombre',
        'correo',
        'users_id',
        'de_documentos_id'
    ];

    protected $casts = [
        'tipo_usuario' => 'string',
        'categoria' => 'string',
        'nombre' => 'string',
        'correo' => 'string'
    ];

    public static array $rules = [
        'tipo_usuario' => 'nullable|string|max:100',
        'categoria' => 'nullable|string|max:90',
        'nombre' => 'nullable|string|max:200',
        'correo' => 'nullable|string|max:255',
        'users_id' => 'required',
        'de_documentos_id' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\DocumentosElectronicos\Models\User::class, 'users_id');
    }

    public function deDocumentos(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\DocumentosElectronicos\Models\DeDocumento::class, 'de_documentos_id');
    }

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
}
