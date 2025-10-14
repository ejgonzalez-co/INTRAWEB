<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\ConfigStorageStatus;
use App\Repositories\BaseRepository;

class ConfigStorageStatusRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'storage_status',
        'status'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return ConfigStorageStatus::class;
    }
}
