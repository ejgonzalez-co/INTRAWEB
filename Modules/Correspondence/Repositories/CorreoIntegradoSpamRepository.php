<?php

namespace Modules\Correspondence\Repositories;

use Modules\Correspondence\Models\CorreoIntegradoSpam;
use App\Repositories\BaseRepository;

class CorreoIntegradoSpamRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'correo_remitente',
        'uid',
        'fecha',
        'asunto',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return CorreoIntegradoSpam::class;
    }
}
