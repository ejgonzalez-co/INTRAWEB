<?php

namespace Modules\Correspondence\Repositories;

use Modules\Correspondence\Models\Planilla;
use App\Repositories\BaseRepository;

class PlanillaRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'consecutivo',
        'estado',
        'contenido',
        'consulta_sql_externa',
        'consulta_sql_interna',
        'tipo_correspondencia',
        'rango_planilla',
        'users_id',
        'nombre_usuario',
        'correspondence_planilla_ruta_id'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Planilla::class;
    }
}
