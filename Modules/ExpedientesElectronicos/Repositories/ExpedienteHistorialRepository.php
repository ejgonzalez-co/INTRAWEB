<?php

namespace Modules\ExpedientesElectronicos\Repositories;

use Modules\ExpedientesElectronicos\Models\ExpedienteHistorial;
use App\Repositories\BaseRepository;

class ExpedienteHistorialRepository extends BaseRepository
{
    protected $fieldSearchable = [
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
        'observacion_accion',
        'id_usuario_enviador',
        'nombre_usuario_enviador',
        'tipo_usuario_enviador',
        'hash_caratula_apertura',
        'hash_caratula_cierre',
        'codigo_acceso_caratula_apertura',
        'codigo_acceso_caratula_cierre'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return ExpedienteHistorial::class;
    }
}
