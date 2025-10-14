<?php

namespace Modules\Intranet\Repositories;

use Modules\Intranet\Models\WorkGroup;
use App\Repositories\BaseRepository;

/**
 * Class WorkGroupRepository
 * @package Modules\Intranet\Repositories
 * @version July 1, 2020, 2:28 pm UTC
*/

class WorkGroupRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'state',
        'url_img_profile',
        'end_date'
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
        return WorkGroup::class;
    }
}
