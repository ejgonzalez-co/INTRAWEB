<?php

namespace Modules\Calidad\Repositories;

use Modules\Calidad\Models\MapaProcesos;
use App\Repositories\BaseRepository;

class MapaProcesosRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'nombre_usuario',
        'adjunto',
        'descripcion',
        'vigencia',
        'users_id'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return MapaProcesos::class;
    }
}
