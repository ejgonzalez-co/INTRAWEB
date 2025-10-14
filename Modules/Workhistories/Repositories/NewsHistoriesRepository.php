<?php

namespace Modules\Workhistories\Repositories;

use Modules\Workhistories\Models\NewsHistories;
use App\Repositories\BaseRepository;

/**
 * Class NewsHistoriesRepository
 * @package Modules\Workhistories\Repositories
 * @version November 9, 2020, 3:58 pm -05
*/

class NewsHistoriesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'new',
        'type_document',
        'users_name',
        'work_histories_documents_id',
        'work_histories_id',
        'users_id'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return NewsHistories::class;
    }
}
