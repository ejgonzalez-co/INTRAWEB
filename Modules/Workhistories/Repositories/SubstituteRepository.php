<?php

namespace Modules\Workhistories\Repositories;

use Modules\Workhistories\Models\Substitute;
use App\Repositories\BaseRepository;

/**
 * Class SubstituteRepository
 * @package Modules\Workhistories\Repositories
 * @version December 6, 2020, 10:51 pm -05
*/

class SubstituteRepository extends BaseRepository
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
        'city',
        'type_substitute',
        'date_document',
        'birth_date',
        'notification',
        'users_name',
        'users_id',
        'work_histories_p_id'
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
        return Substitute::class;
    }
}
