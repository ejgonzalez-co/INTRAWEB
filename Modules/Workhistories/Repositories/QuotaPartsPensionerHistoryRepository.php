<?php

namespace Modules\Workhistories\Repositories;

use Modules\Workhistories\Models\QuotaPartsPensionerHistory;
use App\Repositories\BaseRepository;

/**
 * Class QuotaPartsPensionerHistoryRepository
 * @package Modules\Workhistories\Repositories
 * @version December 10, 2020, 3:48 pm -05
*/

class QuotaPartsPensionerHistoryRepository extends BaseRepository
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
        'time_work',
        'users_id',
        'cp_pensionados_id'
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
        return QuotaPartsPensionerHistory::class;
    }
}
