<?php

namespace Modules\leca\Repositories;

use Modules\Leca\Models\ObservacionesDuplicadoPh;
use App\Repositories\BaseRepository;

/**
 * Class ObservacionesDuplicadoPhRepository
 * @package Modules\leca\Repositories
 * @version January 31, 2023, 10:03 am -05
*/

class ObservacionesDuplicadoPhRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name_user',
        'observation',
        'lc_ensayo_ph_id'
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
        return ObservacionesDuplicadoPh::class;
    }
}
