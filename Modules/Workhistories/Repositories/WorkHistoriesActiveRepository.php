<?php

namespace Modules\Workhistories\Repositories;

use Modules\Workhistories\Models\WorkHistoriesActive;
use App\Repositories\BaseRepository;

/**
 * Class WorkHistoriesActiveRepository
 * @package Modules\Workhistories\Repositories
 * @version October 9, 2020, 3:19 pm -05
*/

class WorkHistoriesActiveRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type_document',
        'number_document',
        'name',
        'surname',
        'address',
        'phone',
        'email',
        'gender',
        'group_ethnic',
        'birth_date',
        'notification',
        'level_study'
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
        return WorkHistoriesActive::class;
    }
}
