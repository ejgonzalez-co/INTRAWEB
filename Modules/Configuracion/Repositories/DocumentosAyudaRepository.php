<?php

namespace Modules\Configuracion\Repositories;

use Modules\Configuracion\Models\DocumentosAyuda;
use App\Repositories\BaseRepository;

class DocumentosAyudaRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'nombre',
        'descripcion',
        'documento',
        'videos_url',
        'imagen_previa',
        'users_id'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return DocumentosAyuda::class;
    }
}
