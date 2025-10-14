<?php

namespace Modules\ExpedientesElectronicos\Repositories;

use Modules\ExpedientesElectronicos\Models\Expediente;
use App\Repositories\BaseRepository;

class ExpedienteRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'users_id',
        'dependencias_id',
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
        return Expediente::class;
    }
}
