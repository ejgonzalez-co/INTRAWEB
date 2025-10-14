<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\ConfigTowerProcessor;
use App\Repositories\BaseRepository;

class ConfigTowerProcessorRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'processor',
        'status'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return ConfigTowerProcessor::class;
    }
}
