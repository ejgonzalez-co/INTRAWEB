<?php

namespace Modules\Workhistories\Repositories;

use Modules\Workhistories\Models\HistoryPensioner;
use App\Repositories\BaseRepository;

/**
 * Class HistoryPensionerRepository
 * @package Modules\Workhistories\Repositories
 * @version December 6, 2020, 8:01 pm -05
*/

class HistoryPensionerRepository extends BaseRepository
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
        'level_study',
        'phone_event',
        'name_event',
        'state',
        'users_name',
        'work_histories_p_id',
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
        return HistoryPensioner::class;
    }
}
