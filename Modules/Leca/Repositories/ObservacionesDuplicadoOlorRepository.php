<?php

namespace Modules\leca\Repositories;

use Modules\leca\Models\ObservacionesDuplicadoOlor;
use App\Repositories\BaseRepository;

/**
 * Class ObservacionesDuplicadoOlorRepository
 * @package Modules\leca\Repositories
 * @version March 17, 2023, 8:26 am -05
*/

class ObservacionesDuplicadoOlorRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name_user',
        'observation',
        'lc_ensayo_olor_id',
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
        return ObservacionesDuplicadoOlor::class;
    }
}
