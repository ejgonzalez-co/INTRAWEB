<?php

namespace Modules\Intranet\Repositories;

use Modules\Intranet\Models\Citizen;
use App\Repositories\BaseRepository;

/**
 * Class CitizenRepository
 * @packageModules\Intranet\Repositories
 * @version January 20, 2022, 1:56 am -05
*/

class CitizenRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'states_id',
        'city_id',
        'type_person',
        'type_document',
        'document_number',
        'name',
        'first_name',
        'second_name',
        'first_surname',
        'second_surname',
        'address',
        'phone'
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
        return Citizen::class;
    }
}
