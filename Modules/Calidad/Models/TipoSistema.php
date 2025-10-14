<?php

namespace Modules\Calidad\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TipoSistema extends Model
{
    public $table = 'calidad_tipo_sistema';

    public $fillable = [
        'nombre_sistema',
        'descripcion',
        'estado',
        'users_id',
        'usuario_creador'
    ];

    protected $casts = [
        'nombre_sistema' => 'string',
        'descripcion' => 'string',
        'estado' => 'string',
        'usuario_creador' => 'string'
    ];

    public static array $rules = [
        'nombre_sistema' => 'nullable|string|max:255',
        'descripcion' => 'nullable|string|max:255',
        'estado' => 'nullable|string|max:45',
        'usuario_creador' => 'nullable|string|max:100',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    protected $appends = ["expanded"];

    public function getExpandedAttribute() {
        return false;
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

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }

    public function documentos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\Calidad\Models\Documento::class, 'calidad_tipo_sistema_id');
    }

    public function documentoHistorials(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\Calidad\Models\DocumentoHistorial::class, 'calidad_tipo_sistema_id');
    }

    public function documentoTipoDocumentos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\Calidad\Models\DocumentoTipoDocumento::class, 'calidad_tipo_sistema_id');
    }

    public function procesos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\Calidad\Models\Proceso::class, 'calidad_tipo_sistema_id');
    }

    public function procesosArbol(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\Calidad\Models\Proceso::class, 'calidad_tipo_sistema_id')->whereNull('calidad_proceso_id')->with(["subprocesosArbol", "documentosArbol"]);
    }
}
