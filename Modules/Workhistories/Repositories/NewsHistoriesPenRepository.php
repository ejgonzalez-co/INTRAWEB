<?php

namespace Modules\Workhistories\Repositories;

use Modules\Workhistories\Models\NewsHistoriesPen;
use App\Repositories\BaseRepository;

/**
 * Class NewsHistoriesPenRepository
 * @package Modules\Workhistories\Repositories
 * @version December 5, 2020, 11:34 am -05
*/

class NewsHistoriesPenRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'new',
        'type_document',
        'users_name',
        'work_histories_p_id',
        'users_id',
        'documents_id'
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
        return NewsHistoriesPen::class;
    }
}
