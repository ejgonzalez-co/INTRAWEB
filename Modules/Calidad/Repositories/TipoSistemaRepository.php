<?php

namespace Modules\Calidad\Repositories;

use Modules\Calidad\Models\TipoSistema;
use App\Repositories\BaseRepository;

class TipoSistemaRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'nombre_sistema',
        'descripcion',
        'estado',
        'users_id',
        'usuario_creador'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return TipoSistema::class;
    }
}
