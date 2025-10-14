<?php

namespace Modules\PQRS\Repositories;

use Modules\PQRS\Models\SurveySatisfactionPqr;
use App\Repositories\BaseRepository;

class SurveySatisfactionPqrRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'users_id',
        'pqr_id',
        'respuesta1',
        'respuesta2',
        'respuesta3',
        'respuesta4'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return SurveySatisfactionPqr::class;
    }
}
