<?php

namespace Modules\ExpedientesElectronicos\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class DocExpedienteHistorial extends Model
{
    public $table = 'ee_doc_expediente_historial';

    public $fillable = [
        'ee_documentos_expediente_id',
        'users_id',
        'user_name',
        'nombre_expediente',
        'nombre_tipo_documental',
        'origen_creacion',
        'nombre_documento',
        'fecha_documento',
        'descripcion',
        'pagina_inicio',
        'pagina_fin',
        'adjunto',
        'modulo_intraweb',
        'consecutivo',
        'accion',
        'justificacion',
        'vigencia',
        'modulo_consecutivo',
        'ee_expediente_id',
        'estado_doc',
        'detalle_modificacion',
        'hash_value',
        'hash_algoritmo'
    ];

    protected $casts = [
        'user_name' => 'string',
        'nombre_expediente' => 'string',
        'nombre_tipo_documental' => 'string',
        'origen_creacion' => 'string',
        'nombre_documento' => 'string',
        'fecha_documento' => 'datetime',
        'descripcion' => 'string',
        'adjunto' => 'string',
        'modulo_intraweb' => 'string',
        'consecutivo' => 'string',
        'accion' => 'string',
        'justificacion' => 'string',
        'modulo_consecutivo' => 'string'
    ];

    public static array $rules = [
        'user_name' => 'nullable|string|max:120',
        'nombre_expediente' => 'nullable|string|max:255',
        'nombre_tipo_documental' => 'nullable|string|max:255',
        'origen_creacion' => 'nullable|string|max:255',
        'nombre_documento' => 'nullable|string|max:255',
        'fecha_documento' => 'nullable',
        'descripcion' => 'nullable|string',
        'pagina_inicio' => 'nullable',
        'pagina_fin' => 'nullable',
        'adjunto' => 'nullable|string',
        'modulo_intraweb' => 'nullable|string|max:255',
        'consecutivo' => 'nullable|string|max:255',
        'accion' => 'nullable|string|max:255',
        'justificacion' => 'nullable|string|max:255',
        'vigencia' => 'required',
        'modulo_consecutivo' => 'nullable|string|max:255',
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

    public function eeDocumentosExpediente(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\ExpedientesElectronicos\Models\EeDocumentosExpediente::class, 'ee_documentos_expediente_id');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\ExpedientesElectronicos\Models\User::class, 'users_id');
    }
}
