<?php

namespace Modules\Calidad\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class DocumentoSolicitudDocumental extends Model
{
    public $table = 'calidad_documento_solicitud_documental';

    public $fillable = [
        'nombre_solicitante',
        'cargo',
        'tipo_solicitud',
        'tipo_documento',
        'codigo',
        'nombre_documento',
        'version',
        'justificacion_solicitud',
        'adjunto',
        'funcionario_responsable',
        'cargo_responsable',
        'estado',
        'observaciones',
        'users_id_solicitante',
        'users_id_responsable',
        'calidad_macroproceso_id',
        'calidad_proceso_id',
        'calidad_documento_id'
    ];

    protected $casts = [
        'nombre_solicitante' => 'string',
        'cargo' => 'string',
        'tipo_solicitud' => 'string',
        'tipo_documento' => 'string',
        'codigo' => 'string',
        'nombre_documento' => 'string',
        'version' => 'string',
        'justificacion_solicitud' => 'string',
        'adjunto' => 'string',
        'funcionario_responsable' => 'string',
        'cargo_responsable' => 'string',
        'estado' => 'string',
        'observaciones' => 'string'
    ];

    public static array $rules = [
        'nombre_solicitante' => 'nullable|string|max:255',
        'cargo' => 'nullable|string|max:255',
        'tipo_solicitud' => 'nullable|string|max:255',
        'tipo_documento' => 'nullable|string|max:255',
        'codigo' => 'nullable|string|max:200',
        'nombre_documento' => 'nullable|string|max:255',
        'version' => 'nullable|string|max:45',
        'justificacion_solicitud' => 'nullable|string',
        'funcionario_responsable' => 'nullable|string|max:255',
        'cargo_responsable' => 'nullable|string|max:255',
        'estado' => 'nullable|string|max:100',
        'observaciones' => 'nullable|string',
        'users_id_solicitante' => 'required',
        'users_id_responsable' => 'required',
        'calidad_macroproceso_id' => 'required',
        'calidad_proceso_id' => 'required',
        'calidad_documento_id' => 'nullable',
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

    public function documento(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Calidad\Models\Documento::class, 'calidad_documento_id');
    }

    public function documentoTipoDocumento(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Calidad\Models\DocumentoTipoDocumento::class, 'tipo_documento');
    }

    public function macroProceso(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Calidad\Models\Proceso::class, 'calidad_macroproceso_id');
    }

    public function proceso(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Calidad\Models\Proceso::class, 'calidad_proceso_id');
    }

    public function usersSolicitante(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id_solicitante');
    }

    public function usersResponsable(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id_responsable');
    }

    public function documentoSolicitudDocumentalHistorials(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\Calidad\Models\DocumentoSolicitudDocumentalHistorial::class, 'calidad_documento_solicitud_documental_id')->with(["proceso", "documentoTipoDocumento"]);
    }
}
