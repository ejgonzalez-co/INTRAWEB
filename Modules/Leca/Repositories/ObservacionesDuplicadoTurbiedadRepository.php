<?php

namespace Modules\Leca\Repositories;

use Modules\Leca\Models\ObservacionesDuplicadoTurbidez;
use App\Repositories\BaseRepository;

/**
 * Class ObservacionesDuplicadoTurbiedadRepository
 * @package Modules\Leca\Repositories
 * @version August 3, 2022, 2:36 pm -05
*/

class ObservacionesDuplicadoTurbiedadRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_id',
        'name_user',
        'observation',
        'lc_ensayo_turbidez_id'
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
        return ObservacionesDuplicadoTurbidez::class;
    }
}
