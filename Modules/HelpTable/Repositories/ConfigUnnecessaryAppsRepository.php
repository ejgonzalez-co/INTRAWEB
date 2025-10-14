<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\ConfigUnnecessaryApps;
use App\Repositories\BaseRepository;

class ConfigUnnecessaryAppsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'unnecessary_app',
        'status'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return ConfigUnnecessaryApps::class;
    }
}
