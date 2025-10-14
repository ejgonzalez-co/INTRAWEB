<?php

namespace Modules\Workhistories\Repositories;

use Modules\Workhistories\Models\QuotaPartsPensioner;
use App\Repositories\BaseRepository;

/**
 * Class QuotaPartsPensionerRepository
 * @package Modules\Workhistories\Repositories
 * @version December 10, 2020, 5:07 pm -05
*/

class QuotaPartsPensionerRepository extends BaseRepository
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
        'rh',
        'birth_date',
        'notification',
        'level_study_other',
        'level_study',
        'phone_event',
        'name_event',
        'state',
        'deceased',
        'observation_deceased',
        'total_documents',
        'users_name',
        'time_work',
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
        return QuotaPartsPensioner::class;
    }
}
