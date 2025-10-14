<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\ConfigTowerVideoCard;
use App\Repositories\BaseRepository;

class ConfigTowerVideoCardRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'video_card',
        'status',
        'marca'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return ConfigTowerVideoCard::class;
    }
}
