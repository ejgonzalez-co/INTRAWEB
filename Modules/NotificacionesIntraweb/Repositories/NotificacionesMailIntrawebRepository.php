<?php

namespace Modules\NotificacionesIntraweb\Repositories;

use Modules\NotificacionesIntraweb\Models\NotificacionesMailIntraweb;
use App\Repositories\BaseRepository;

class NotificacionesMailIntrawebRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'id_mail',
        'user_id',
        'user_name',
        'modulo',
        'consecutivo',
        'estado',
        'correo_destinatario',
        'datos_mail'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return NotificacionesMailIntraweb::class;
    }
}
