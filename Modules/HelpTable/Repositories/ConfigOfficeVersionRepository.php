<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\ConfigOfficeVersion;
use App\Repositories\BaseRepository;

class ConfigOfficeVersionRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'office_version',
        'status'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return ConfigOfficeVersion::class;
    }
}
