<?php

namespace Modules\PQRS\Repositories;

use Modules\PQRS\Models\PQRTipoSolicitud;
use App\Repositories\BaseRepository;

class PQRTipoSolicitudRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'nombre',
        'descripcion',
        'estado',
        'users_id'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return PQRTipoSolicitud::class;
    }
}
