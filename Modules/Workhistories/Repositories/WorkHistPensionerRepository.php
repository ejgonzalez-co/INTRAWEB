<?php

namespace Modules\Workhistories\Repositories;

use Modules\Workhistories\Models\WorkHistPensioner;
use App\Repositories\BaseRepository;

/**
 * Class WorkHistPensionerRepository
 * @package Modules\Workhistories\Repositories
 * @version December 3, 2020, 5:38 pm -05
*/

class WorkHistPensionerRepository extends BaseRepository
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
        'total_documents',
        'users_name',
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
        return WorkHistPensioner::class;
    }
}
