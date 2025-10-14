<?php

namespace Modules\Workhistories\Repositories;

use Modules\Workhistories\Models\WorkRequest;
use App\Repositories\BaseRepository;

/**
 * Class WorkRequestRepository
 * @package Modules\Workhistories\Repositories
 * @version October 20, 2021, 10:48 am -05
*/

class WorkRequestRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_id',
        'work_histories_id',
        'work_histories_p_id',
        'work_histories_p_users_id',
        'user_id',
        'user_name',
        'consultation_time',
        'answer',
        'reason_consultation',
        'condition',
        'date_start',
        'date_final'
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
        return WorkRequest::class;
    }
}
