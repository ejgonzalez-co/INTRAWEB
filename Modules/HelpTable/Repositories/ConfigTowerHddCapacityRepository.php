<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\ConfigTowerHddCapacity;
use App\Repositories\BaseRepository;

class ConfigTowerHddCapacityRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'hdd_capacity',
        'status',
        'marca'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return ConfigTowerHddCapacity::class;
    }
}
