<?php

namespace Modules\Workhistories\Repositories;

use Modules\Workhistories\Models\FamilyPensioner;
use App\Repositories\BaseRepository;

/**
 * Class FamilyPensionerRepository
 * @package Modules\Workhistories\Repositories
 * @version May 9, 2021, 7:44 pm -05
*/

class FamilyPensionerRepository extends BaseRepository
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
        'observation',
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
        return FamilyPensioner::class;
    }
}
