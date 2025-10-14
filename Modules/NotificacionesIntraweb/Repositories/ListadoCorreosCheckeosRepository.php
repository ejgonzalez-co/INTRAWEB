<?php

namespace Modules\NotificacionesIntraweb\Repositories;

use Modules\NotificacionesIntraweb\Models\ListadoCorreosCheckeos;
use App\Repositories\BaseRepository;

class ListadoCorreosCheckeosRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'email',
        'estado',
        'fecha_verificacion',
        'user_id',
        'user_name'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return ListadoCorreosCheckeos::class;
    }
}
