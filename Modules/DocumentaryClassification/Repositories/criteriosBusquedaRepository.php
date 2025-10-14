<?php

namespace Modules\DocumentaryClassification\Repositories;

use Modules\DocumentaryClassification\Models\criteriosBusqueda;
use App\Repositories\BaseRepository;

class criteriosBusquedaRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'nombre',
        'tipo_campo',
        'texto_ayuda',
        'requerido',
        'opciones'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return criteriosBusqueda::class;
    }
}
