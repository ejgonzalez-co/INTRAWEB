<?php

namespace Modules\ExpedientesElectronicos\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class ExpedienteHistorial extends Model
{
    public $table = 'ee_expediente_historial';

    public $fillable = [
        'users_id',
        'ee_expediente_id',
        'dependencias_id',
        'consecutivo',
        'nombre_expediente',
        'user_name',
        'nombre_dependencia',
        'fecha_inicio_expediente',
        'descripcion',
        'classification_serie',
        'classification_subserie',
        'classification_production_office',
        'existe_fisicamente',
        'ubicacion',
        'id_expediente',
        'sede',
        'area_archivo',
        'estante',
        'modulo',
        'entrepano',
        'caja',
        'cuerpo',
        'unidad_conservacion',
        'fecha_archivo',
        'numero_inventario',
        'estado',
        'observacion',
        'vigencia',
        'id_firma_caratula_apertura',
        'id_firma_caratula_cierre',
        'id_responsable',
        'nombre_responsable',
        'consecutivo_order',
        'ip_apertura',
        'ip_cierre',
        'justificacion_cierre',
        'detalle_modificacion',
        'permiso_general_expediente',
        'observacion_accion',
        'id_usuario_enviador',
        'nombre_usuario_enviador',
        'tipo_usuario_enviador',
        'hash_caratula_apertura',
        'hash_caratula_cierre',
        'codigo_acceso_caratula_apertura',
        'codigo_acceso_caratula_cierre'
    ];

    protected $casts = [
        'consecutivo' => 'string',
        'nombre_expediente' => 'string',
        'user_name' => 'string',
        'nombre_dependencia' => 'string',
        'fecha_inicio_expediente' => 'datetime',
        'descripcion' => 'string',
        'existe_fisicamente' => 'string',
        'ubicacion' => 'string',
        'sede' => 'string',
        'area_archivo' => 'string',
        'estante' => 'string',
        'modulo' => 'string',
        'entrepano' => 'string',
        'caja' => 'string',
        'cuerpo' => 'string',
        'unidad_conservacion' => 'string',
        'fecha_archivo' => 'datetime',
        'numero_inventario' => 'string',
        'estado' => 'string',
        'observacion' => 'string'

    ];

    public static array $rules = [
        'users_id' => 'required',
        'ee_expediente_id' => 'required',
        'dependencias_id' => 'required',
        'consecutivo' => 'nullable|string|max:200',
        'nombre_expediente' => 'nullable|string|max:255',
        'user_name' => 'nullable|string|max:255',
        'nombre_dependencia' => 'nullable|string|max:255',
        'fecha_inicio_expediente' => 'nullable',
        'descripcion' => 'nullable|string',
        'classification_serie' => 'nullable',
        'classification_subserie' => 'nullable',
        'classification_production_office' => 'nullable',
        'existe_fisicamente' => 'nullable|string|max:120',
        'ubicacion' => 'nullable|string|max:255',
        'sede' => 'nullable|string|max:255',
        'area_archivo' => 'nullable|string|max:255',
        'estante' => 'nullable|string|max:255',
        'modulo' => 'nullable|string|max:255',
        'entrepano' => 'nullable|string|max:255',
        'caja' => 'nullable|string|max:255',
        'cuerpo' => 'nullable|string|max:255',
        'unidad_conservacion' => 'nullable|string|max:255',
        'fecha_archivo' => 'nullable',
        'numero_inventario' => 'nullable|string|max:255',
        'estado' => 'nullable|string|max:255',
        'observacion' => 'nullable|string',
        'vigencia' => 'required',
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

    public function dependencias(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'dependencias_id');
    }

    public function eeExpediente(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\ExpedientesElectronicos\Models\Expediente::class, 'ee_expediente_id');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'users_id');
    }
}
