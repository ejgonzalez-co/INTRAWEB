<?php

namespace Modules\PQRS\Repositories;

use Modules\PQRS\Models\PQREjeTematico;
use App\Repositories\BaseRepository;

class PQREjeTematicoRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'codigo',
        'nombre',
        'descripcion',
        'tipo_plazo',
        'plazo',
        'plazo_unidad',
        'temprana',
        'temprana_unidad',
        'estado',
        'users_id'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return PQREjeTematico::class;
    }
}
