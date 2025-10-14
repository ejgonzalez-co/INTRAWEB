<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\TicTypeTicCategoryHistory;
use App\Repositories\BaseRepository;

class TicTypeTicCategoryHistoryRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
        'id_categories'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return TicTypeTicCategoryHistory::class;
    }
}
