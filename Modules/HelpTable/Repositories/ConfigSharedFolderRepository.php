<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\ConfigSharedFolder;
use App\Repositories\BaseRepository;

class ConfigSharedFolderRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'shared_folder',
        'status'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return ConfigSharedFolder::class;
    }
}
