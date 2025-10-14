<?php

namespace Modules\Calidad\Repositories;

use Modules\Calidad\Models\TipoProceso;
use App\Repositories\BaseRepository;

class TipoProcesoRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'nombre',
        'prefijo',
        'estado',
        'orden',
        'users_id',
        'usuario_creador'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return TipoProceso::class;
    }
}
