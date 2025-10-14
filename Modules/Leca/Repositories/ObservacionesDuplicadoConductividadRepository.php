<?php

namespace Modules\leca\Repositories;

use Modules\leca\Models\ObservacionesDuplicadoConductividad;
use App\Repositories\BaseRepository;

/**
 * Class ObservacionesDuplicadoConductividadRepository
 * @package Modules\leca\Repositories
 * @version February 1, 2023, 12:03 pm -05
*/

class ObservacionesDuplicadoConductividadRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'lc_ensayo_conductividad_id',
        'name_user',
        'observation',
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
        return ObservacionesDuplicadoConductividad::class;
    }
}
