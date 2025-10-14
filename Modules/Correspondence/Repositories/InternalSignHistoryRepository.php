<?php

namespace Modules\Correspondence\Repositories;

use Modules\Correspondence\Models\InternalSignHistory;
use App\Repositories\BaseRepository;

class InternalSignHistoryRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'nombre',
        'hash',
        'ip',
        'id_documento',
        'tipo',
        'consecutivo'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return InternalSignHistory::class;
    }
}
