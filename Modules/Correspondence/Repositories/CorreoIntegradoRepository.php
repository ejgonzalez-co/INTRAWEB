<?php

namespace Modules\Correspondence\Repositories;

use Modules\Correspondence\Models\CorreoIntegrado;
use App\Repositories\BaseRepository;

class CorreoIntegradoRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'consecutivo',
        'asunto',
        'contenido',
        'correo_remitente',
        'nombre_remitente',
        'fecha',
        'estado',
        'uid',
        'vigencia',
        'users_id',
        'nombre_usuario'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return CorreoIntegrado::class;
    }
}
