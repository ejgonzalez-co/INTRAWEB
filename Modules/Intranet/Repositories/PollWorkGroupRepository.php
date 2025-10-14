<?php

namespace Modules\Intranet\Repositories;

use Modules\Intranet\Models\PollWorkGroup;
use App\Repositories\BaseRepository;

/**
 * Class PollWorkGroupRepository
 * @package Modules\Intranet\Repositories
 * @version June 27, 2021, 4:01 pm -05
*/

class PollWorkGroupRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'work_groups_name',
        'work_groups_id',
        'intranet_polls_id'
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
        return PollWorkGroup::class;
    }
}
