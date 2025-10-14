<?php

namespace Modules\Calidad\Repositories;

use Modules\Calidad\Models\Proceso;
use App\Repositories\BaseRepository;

class ProcesoRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'nombre',
        'prefijo',
        'estado',
        'orden',
        'id_responsable',
        'tipo_responsable',
        'calidad_tipo_proceso_id',
        'calidad_proceso_id',
        'dependencias_id',
        'calidad_tipo_sistema_id',
        'users_id',
        'usuario_creador'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Proceso::class;
    }
}
