<?php

namespace Modules\Workhistories\Repositories;

use Modules\Workhistories\Models\Family;
use App\Repositories\BaseRepository;

/**
 * Class FamilyRepository
 * @package Modules\Workhistories\Repositories
 * @version May 5, 2021, 2:23 pm -05
*/

class FamilyRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'gender',
        'birth_date',
        'type',
        'state',
        'users_id',
        'work_histories_id'
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
        return Family::class;
    }
}
