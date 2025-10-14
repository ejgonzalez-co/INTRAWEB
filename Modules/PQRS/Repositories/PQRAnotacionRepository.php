<?php

namespace Modules\PQRS\Repositories;

use Modules\PQRS\Models\PQRAnotacion;
use App\Repositories\BaseRepository;

class PQRAnotacionRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'nombre_usuario',
        'anotacion',
        'vigencia',
        'pqr_id',
        'users_id'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return PQRAnotacion::class;
    }
}
