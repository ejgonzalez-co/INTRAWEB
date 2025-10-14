<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\ConfigTowerReference;
use App\Repositories\BaseRepository;

class ConfigTowerReferenceRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'reference',
        'status'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return ConfigTowerReference::class;
    }
}
