<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\ConfigTowerSsdCapacity;
use App\Repositories\BaseRepository;

class ConfigTowerSsdCapacityRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'ssd_capacity',
        'status',
        'marca'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return ConfigTowerSsdCapacity::class;
    }
}
