<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\ConfigNetworkCard;
use App\Repositories\BaseRepository;

class ConfigNetworkCardRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'network_card',
        'status'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return ConfigNetworkCard::class;
    }
}
