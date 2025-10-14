<?php

namespace Modules\Correspondence\Repositories;

use Modules\Correspondence\Models\PlanillaRuta;
use App\Repositories\BaseRepository;

class PlanillaRutaRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'nombre_ruta',
        'descripcion',
        'users_id',
        'nombre_usuario'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return PlanillaRuta::class;
    }
}
