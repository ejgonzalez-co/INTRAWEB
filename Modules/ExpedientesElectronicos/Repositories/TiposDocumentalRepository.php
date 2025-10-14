<?php

namespace Modules\ExpedientesElectronicos\Repositories;

use Modules\ExpedientesElectronicos\Models\TiposDocumental;
use App\Repositories\BaseRepository;

class TiposDocumentalRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'users_id',
        'user_name',
        'tipo_documento',
        'estado'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return TiposDocumental::class;
    }
}
