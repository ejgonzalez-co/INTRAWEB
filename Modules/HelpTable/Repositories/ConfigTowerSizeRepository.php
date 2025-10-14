<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\ConfigTowerSize;
use App\Repositories\BaseRepository;

class ConfigTowerSizeRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'size',
        'status'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return ConfigTowerSize::class;
    }
}
