<?php

namespace Modules\Configuracion\Repositories;

use Modules\Configuracion\Models\versionsUpdate;
use App\Repositories\BaseRepository;

class versionsUpdateRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'numero_version',
        'descripcion',
        'tipo_de_cambio'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return versionsUpdate::class;
    }
}
