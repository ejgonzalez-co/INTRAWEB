<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\ConfigTowerMemoryRam;
use App\Repositories\BaseRepository;

class ConfigTowerMemoryRamRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'memory_ram',
        'status'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return ConfigTowerMemoryRam::class;
    }
}
